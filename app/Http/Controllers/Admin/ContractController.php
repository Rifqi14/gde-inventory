<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Contract;
use App\Models\ContractJvmember;
use App\Models\ContractPerformanceBond;
use App\Models\ContractAdvanceBond;
use App\Models\ContractRetentionBond;
use App\Models\ContractRetentionMoney;
use App\Models\ContractPenalty;
use App\Models\ContractWarrantyBond;
use App\Models\ContractOwner;
use App\Models\ContractAddendum;
use App\Models\ContractAddendumAttach;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\BatchContract;
use App\Models\BatchContractProduct;
use App\Models\ContractProduct;
use App\Models\Product;
use App\Models\ProductUom;

class ContractController extends Controller
{
    function __construct()
    {
        View::share('menu_active', url('admin/' . 'contract'));
        $this->middleware('accessmenu', ['except' => ['select']]);
    }

    public function index(){
        $userid = Auth::guard('admin')->user()->id;
        $role = Role::where('role_users.user_id', $userid)->join('role_users','roles.id','=','role_users.role_id')->first();
        $group_code = $role->code;
        return view('admin.contract.index', compact('group_code'));
    }

    public function read(Request $request)
    {
        $start = $request->start;
        $length = $request->length;
        $query = $request->search['value'];
        $sort = $request->columns[$request->order[0]['column']]['data'];
        $dir = $request->order[0]['dir'];
        $title = strtoupper($request->title);
        $number = strtoupper($request->number);

        //Count Data
        $query = Contract::select('*');
        $query->join('users','contracts.created_user','=','users.id');
        $query->join('role_users','role_users.user_id','=','users.id');
        $query->join('roles','roles.id','=','role_users.role_id');
        $query->whereRaw("upper(title) like '%$title%'");
        $query->whereRaw("upper(number) like '%$number%'");
        $recordsTotal = $query->count();

        //Select Pagination
        $query = Contract::select('*','contracts.id as contract_id');
        $query->join('users','contracts.created_user','=','users.id');
        $query->join('role_users','role_users.user_id','=','users.id');
        $query->join('roles','roles.id','=','role_users.role_id');
        $query->whereRaw("upper(title) like '%$title%'");
        $query->whereRaw("upper(number) like '%$number%'");
        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $contracts = $query->get();

        $data = [];
        foreach ($contracts as $value) {
            $value->no = ++$start;
            $value->contract_signing_date = date('d/m/Y', strtotime($value->contract_signing_date));
			$value->addendum = count($this->getpieces('contract_addendum',$value->id));
			$value->status_pub = '';
			if($value->status == 'publish'){
				$adden = $this->getaddendum($value->id);
				$start = (isset($adden[0]->expiration_moved))?$adden[0]->expiration_moved:$value->expiration_date;
				$finish = date('Y-m-d');
				$date1 = new \DateTime($start);
				$date2 = new \DateTime($finish);
				$diff = $date1->diff($date2);
				$diff = $diff->days;
				if(strtotime($start) > strtotime($finish) AND $diff >= 730.5 AND $value->progress == 0){
					$value->status_pub = 'More than 2 years';
				}
				if( strtotime($start) > strtotime($finish) AND $diff < 730.5 AND $diff >= 365.25 AND $value->progress == 0){
					$value->status_pub = 'Less than 2 years';
				}
				if(strtotime($start) > strtotime($finish) AND $diff < 365.25 AND $diff >= 182.5 AND $value->progress == 0){
					$value->status_pub = 'Less than 1 years';
				}
				if(strtotime($start) > strtotime($finish) AND $diff < 182.5 AND $diff >= 91.25 AND $value->progress == 0){
					$value->status_pub = 'Less than 6 months';
				}
				if(strtotime($start) > strtotime($finish) AND $diff < 91.25 AND $diff >= 30.42 AND $value->progress == 0){
					$value->status_pub = 'Less than 3 months';
				}
				if(strtotime($start) > strtotime($finish) AND $diff < 30.42 AND $value->progress == 0){
					$value->status_pub = 'Near expiration';
				}
				if(strtotime($start) < strtotime($finish) AND $value->progress == 0){
					$value->status_pub = 'Expired';
				}
				if($value->progress == 1){
					$value->status_pub = 'Completed';
				}
			} else {
				$value->status_pub = '';
			}
			
			if($value->contract_currency == 'yen'){
				$cur = '¥';
			} else if($value->contract_currency == 'dollar'){
				$cur = '$';
			} else if($value->contract_currency == 'euro'){
				$cur = '€';
			} else {
				$cur = 'Rp';
			}
			$value->contract_value = $cur.' '.number_format($value->contract_value,'2',',','.');
            $value->date_created = date("d/m/Y", strtotime($value->created_at));
			$data[] = $value;
        }
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data
        ], 200);
    }

    public function getpieces($db, $contract_id) {
        $read = [];
		if($db == 'contract_addendum'){
			$read = ContractAddendum::where('contract_id',$contract_id)->orderBy('id', 'desc')->get();
		}

		$data = [];
		foreach ($read as $key => $value) {
			if($db == 'contract_addendum'){
				$value->attach = $this->getattach($value->id);
			}
			$data[] = $value;

		}
		return $data;
    }
    
    public function getattach($addendum_id) {
        $read = ContractAddendumAttach::where('addendum_id', $addendum_id)->get();
		$data = [];
		foreach ($read as $key => $value) {
			$data[] = $value;

		}
		return $data;
    }
    
    public function getaddendum($contract_id) {
        $read = ContractAddendum::where('contract_id', $contract_id)->orderBy('id','desc')->limit(1)->get();
		$data = [];
		foreach ($read as $key => $value) {
			$data[] = $value;

		}
		return $data;
	}

    public function create(){
        $roles = Role::all();
        $userid = Auth::guard('admin')->user()->id;
        return view('admin.contract.create', compact('roles','userid'));
    }

    public function store(Request $request){
        $attach = $request->file('attach');
        $content = $request;
        $jv = $request->jv;
        $pb = $request->pb;
        $ab = $request->ab;
        $rb = $request->rb;
        $rm = $request->rm;
        $pen = $request->pen;
        $wb = $request->wb;
        $created_user = Auth::guard('admin')->user()->id;
        $table = 'contract';

        DB::beginTransaction();

        $data = [
            'number' => $request->number,
            'title' => $request->title,
            'scope_of_work' => $request->scope,
            'contractor' => $request->contractor,
            'contract_currency' => $request->contract_currency,
            'contract_value' => (float) str_replace(',','.', str_replace('.','',$request->contract_value)),
            'contract_pic' => $request->contract_pic,
            'remarks' => $request->remarks,
            // 'purchasing_id' => $request->purchasing_id,
            'purchasing_id' => 1,
            'contract_signing_date' => date('Y-m-d', strtotime(str_replace('/','-',$request->contract_signing_date))),
            'contract_start_date' => date('Y-m-d', strtotime(str_replace('/','-',$request->contract_start_date))),
            'work_start_date' => date('Y-m-d', strtotime(str_replace('/','-',$request->work_start_date))),
            'expiration_date' => date('Y-m-d', strtotime(str_replace('/','-',$request->expiration_date))),
            'work_end_date' => date('Y-m-d', strtotime(str_replace('/','-',$request->work_end_date))),
            'insurance' => (isset($request->insurance))?1:null,
            'warning_letter' => (isset($request->warning))?1:null,
            'progress' => (isset($request->progress))?1:0,
			'status' => $request->status,
            'unit' => $request->unit,
            'created_user' => $created_user,
            'contract_type' => $request->contract_type,
            'batch' => $request->batch,
        ];

        if($request->status == 'publish'){
            $start = explode('/', $request->expiration_date);
			$start = $start[2].'-'.$start[1].'-'.$start[0];
			$finish = date('Y-m-d');
			$date1 = new \DateTime($start);
			$date2 = new \DateTime($finish);
			$diff = $date1->diff($date2);
			$diff = $diff->days;
			if(strtotime($start) > strtotime($finish) AND $diff >= 730.5 AND $data['progress'] == 0){
				$data['exp_status'] = 'More than 2 years';
			}
			if( strtotime($start) > strtotime($finish) AND $diff < 730.5 AND $diff >= 365.25 AND $data['progress'] == 0){
				$data['exp_status'] = 'Less than 2 years';
			}
			if(strtotime($start) > strtotime($finish) AND $diff < 365.25 AND $diff >= 182.5 AND $data['progress'] == 0){
				$data['exp_status'] = 'Less than 1 years';
			}
			if(strtotime($start) > strtotime($finish) AND $diff < 182.5 AND $diff >= 91.25 AND $data['progress'] == 0){
				$data['exp_status'] = 'Less than 6 months';
			}
			if(strtotime($start) > strtotime($finish) AND $diff < 91.25 AND $diff >= 30.42 AND $data['progress'] == 0){
				$data['exp_status'] = 'Less than 3 months';
			}
			if(strtotime($start) > strtotime($finish) AND $diff < 30.42 AND $data['progress'] == 0){
				$data['exp_status'] = 'Near expiration';
			}
			if(strtotime($start) < strtotime($finish) AND $data['progress'] == 0){
				$data['exp_status'] = 'Expired';
			}
			if($data['progress'] == 1){
				$data['exp_status'] = 'Completed';
			}
        }

        if ($request->hasFile('attach')) {
            $path = 'assets/procurement/contract';
			if (!file_exists($path)) {
				mkdir($path, 0777, true);
            }
            $attach->move($path, $request->number.'.'.$attach->getClientOriginalExtension());
            $filename = $path.$request->number.'.'.$attach->getClientOriginalExtension();
			$data['attachment'] = $filename;
        }

        $contract = contract::create($data);

        if($contract){
            if($request->jv){
				$jvm = $request->jv_member;
				for ($i=0; $i < count($jvm); $i++) { 
					$data = [
						'contract_id' => $contract->id,
						'name' => $request->jv_member[$i],
                    ];
                    $ok = ContractJvmember::create($data);
					if (!$ok) {
                        DB::rollback();
                        return response()->json([
                            'status' => false,
                            'message'  => "Can't insert data contract JV Member"
                        ], 400);
					}
				}
            }
            
            if($request->pb){
				$data = [
					'contract_id' => $contract->id,
					'issued_by' => $request->issued_pb,
					'validity_period' => ($request->validity_pb)?$request->validity_pb:null,
                ];
                $ok = ContractPerformanceBond::create($data);
				if (!$ok) {                    
                    DB::rollback();
                    return response()->json([
                        'status' => false,
                        'message'  => "Can't insert data contract performance bond"
                    ], 400);
				}	
            }

            if($request->ab){
				$data = [
					'contract_id' => $contract->id,
					'issued_by' => $request->issued_ab,
					'validity_period' => ($request->validity_ab)?$request->validity_ab:null,
					'value' => $request->value_ab,
				];
                $ok = ContractAdvanceBond::create($data);
				if (!$ok) {
					DB::rollback();
                    return response()->json([
                        'status' => false,
                        'message'  => "Can't insert data contract advance bond"
                    ], 400);
				}	
            }
            
            if($request->rb){
				$data = [
					'contract_id' => $contract->id,
					'issued_by' => $request->issued_rb,
					'validity_period' => ($request->validity_rb)?$request->validity_rb:null,
					'value' => $request->value_rb,
				];
                $ok = ContractRetentionBond::create($data);
				if (!$ok) {
					DB::rollback();
                    return response()->json([
                        'status' => false,
                        'message'  => "Can't insert data contract retention bond"
                    ], 400);
				}	
            }
            
            if($request->rm){
				$data = [
					'contract_id' => $contract->id,
					'currency' => $request->currency_rm,
					'value' => (float) str_replace(',','.', str_replace('.','',$request->value_rm)),
				];
                $ok = ContractRetentionMoney::create($data);
				if (!$ok) {
					DB::rollback();
                    return response()->json([
                        'status' => false,
                        'message'  => "Can't insert data Contract Retention Money"
                    ], 400);
				}	
            }
            
            if($request->pen){
				$data = [
					'contract_id' => $contract->id,
					'late_period' => $request->late_pen,
					'value' => $request->value_pen,
				];
                $ok = ContractPenalty::create($data);
				if (!$ok) {
					DB::rollback();
                    return response()->json([
                        'status' => false,
                        'message'  => "Can't insert data Contract Penalty"
                    ], 400);
				}	
            }
            
            if($request->wb){
				$data = [
					'contract_id' => $contract->id,
					'length' => $request->length_wb,
					'bond_value' => $request->value_wb,
					'issued_by' => $request->issued_wb,
				];
                $ok = ContractWarrantyBond::create($data);
				if (!$ok) {
					DB::rollback();
                    return response()->json([
                        'status' => false,
                        'message'  => "Can't insert data Contract Warranty Bond"
                    ], 400);
				}	
            }
            
            $witness = $request->owner;
			for ($i=0; $i < count($witness); $i++) { 
				$data_type = [
					'contract_id' => $contract->id,
					'group_id' => $request->owner[$i],
				];
                $exType = ContractOwner::create($data_type);
				if (!$exType) {
					DB::rollback();
                    return response()->json([
                        'status' => false,
                        'message'  => "Can't insert data Contract Owner"
                    ], 400);
				}
            }

            if($request->contract_type){
                if($request->contract_type == "product"){
                    $total_batch = ($request->batch)?$request->batch:1;
                    for($a=1;$a<=$total_batch;$a++){
                        $data_batch = [
                            'contract_id' => $contract->id,
                            'no' => $a,
                        ];
                        $ok = BatchContract::create($data_batch);
                        if (!$ok) {
                            DB::rollback();
                            return response()->json([
                                'status' => false,
                                'message'  => "Can't insert data Contract Batch"
                            ], 400);
                        }
                    }
                }
            }

            DB::commit();
            return response()->json([
                'status' => true,
                'message'  => "Data has been saved",
                'id' => $contract->id
            ], 200);
        }else {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message'  => "Can't insert data contract"
            ], 400);
		}
    }

    public function edit($id){
        $roles = Role::all();
        // DB::enableQueryLog();
        $contract = Contract::with('jvmember','pb','ab','rb','rm','pen','wb','owner','adden','site','purchasing')->where('id',$id)->first();
        // dd(DB::getQueryLog());
        $owner = [];
        foreach($contract->owner as $owners){
            array_push($owner, $owners->group_id);
        }
        $contract->owner = $owner;
        $userid = Auth::guard('admin')->user()->id;
        $role = Role::where('role_users.user_id', $userid)->join('role_users','roles.id','=','role_users.role_id')->first();
        $group_code = $role->code;

        if(isset($contract->adden[0]->expiration_moved)){
			$start_date = $contract->adden[0]->expiration_moved;
		} else {
			$start_date = $contract->expiration_date;
        }
        $finish_date = date('Y-m-d');
		$date1 = new \DateTime($start_date);
		$date2 = new \DateTime($finish_date);
        $diff = $date1->diff($date2);
        if(strtotime($start_date) >= strtotime($finish_date)){
			$range = $diff->days;
			
		} else {
			$range = $diff->days*-1;
        }
        // dd($contract);
        if ($contract) {
            return view('admin.contract.edit', compact('contract','roles','userid','group_code','range'));
        } else {
            abort(404);
        }
    }

    public function update(Request $request, $id){
        $attach = $request->file('attach');
        $content = $request;
        $jv = $request->jv;
        $pb = $request->pb;
        $ab = $request->ab;
        $rb = $request->rb;
        $rm = $request->rm;
        $pen = $request->pen;
        $wb = $request->wb;
        $created_user = Auth::guard('admin')->user()->id;
        $table = 'contract';

        DB::beginTransaction();

        $data = [
            'number' => $request->number,
            'title' => $request->title,
            'scope_of_work' => $request->scope,
            'contractor' => $request->contractor,
            'contract_currency' => $request->contract_currency,
            'contract_value' => (float) str_replace(',','.', str_replace('.','',$request->contract_value)),
            'contract_pic' => $request->contract_pic,
            'remarks' => $request->remarks,
            // 'purchasing_id' => $request->purchasing_id,
            'purchasing_id' => 1,
            'contract_signing_date' => date('Y-m-d', strtotime(str_replace('/','-',$request->contract_signing_date))),
            'contract_start_date' => date('Y-m-d', strtotime(str_replace('/','-',$request->contract_start_date))),
            'work_start_date' => date('Y-m-d', strtotime(str_replace('/','-',$request->work_start_date))),
            'expiration_date' => date('Y-m-d', strtotime(str_replace('/','-',$request->expiration_date))),
            'work_end_date' => date('Y-m-d', strtotime(str_replace('/','-',$request->work_end_date))),
            'insurance' => (isset($request->insurance))?1:null,
            'warning_letter' => (isset($request->warning))?1:null,
            'progress' => (isset($request->progress))?1:0,
			'status' => $request->status,
            'unit' => $request->unit,
            'created_user' => $created_user,
            'contract_type' => $request->contract_type,
            'batch' => $request->batch,
        ];

        if($request->status == 'publish'){
            $start = explode('/', $request->expiration_date);
			$start = $start[2].'-'.$start[1].'-'.$start[0];
			$finish = date('Y-m-d');
			$date1 = new \DateTime($start);
			$date2 = new \DateTime($finish);
			$diff = $date1->diff($date2);
			$diff = $diff->days;
			if(strtotime($start) > strtotime($finish) AND $diff >= 730.5 AND $data['progress'] == 0){
				$data['exp_status'] = 'More than 2 years';
			}
			if( strtotime($start) > strtotime($finish) AND $diff < 730.5 AND $diff >= 365.25 AND $data['progress'] == 0){
				$data['exp_status'] = 'Less than 2 years';
			}
			if(strtotime($start) > strtotime($finish) AND $diff < 365.25 AND $diff >= 182.5 AND $data['progress'] == 0){
				$data['exp_status'] = 'Less than 1 years';
			}
			if(strtotime($start) > strtotime($finish) AND $diff < 182.5 AND $diff >= 91.25 AND $data['progress'] == 0){
				$data['exp_status'] = 'Less than 6 months';
			}
			if(strtotime($start) > strtotime($finish) AND $diff < 91.25 AND $diff >= 30.42 AND $data['progress'] == 0){
				$data['exp_status'] = 'Less than 3 months';
			}
			if(strtotime($start) > strtotime($finish) AND $diff < 30.42 AND $data['progress'] == 0){
				$data['exp_status'] = 'Near expiration';
			}
			if(strtotime($start) < strtotime($finish) AND $data['progress'] == 0){
				$data['exp_status'] = 'Expired';
			}
			if($data['progress'] == 1){
				$data['exp_status'] = 'Completed';
			}
        }

        if ($request->hasFile('attach')) {
            $path = 'assets/procurement/contract';
			if (!file_exists($path)) {
				mkdir($path, 0777, true);
            }
            $attach->move($path, $request->number.'.'.$attach->getClientOriginalExtension());
            $filename = $path.$request->number.'.'.$attach->getClientOriginalExtension();
			$data['attachment'] = $filename;
        }

        $contract = contract::find($id);
        $update = $contract->update($data);

        if($update){
            $delete_item = ContractJvmember::where('contract_id',$id)->delete();
            if($request->jv){
				$jvm = $request->jv_member;
				for ($i=0; $i < count($jvm); $i++) { 
					$data = [
						'contract_id' => $contract->id,
						'name' => $request->jv_member[$i],
                    ];
                    $ok = ContractJvmember::create($data);
					if (!$ok) {
                        DB::rollback();
                        return response()->json([
                            'status' => false,
                            'message'  => "Can't insert data contract JV Member"
                        ], 400);
					}
				}
            }
            
            $delete_item = ContractPerformanceBond::where('contract_id',$id)->delete();
            if($request->pb){
				$data = [
					'contract_id' => $contract->id,
					'issued_by' => $request->issued_pb,
					'validity_period' => ($request->validity_pb)?$request->validity_pb:null,
                ];
                $ok = ContractPerformanceBond::create($data);
				if (!$ok) {                    
                    DB::rollback();
                    return response()->json([
                        'status' => false,
                        'message'  => "Can't insert data contract performance bond"
                    ], 400);
				}	
            }

            $delete_item = ContractAdvanceBond::where('contract_id',$id)->delete();
            if($request->ab){
				$data = [
					'contract_id' => $contract->id,
					'issued_by' => $request->issued_ab,
					'validity_period' => ($request->validity_ab)?$request->validity_ab:null,
					'value' => $request->value_ab,
				];
                $ok = ContractAdvanceBond::create($data);
				if (!$ok) {
					DB::rollback();
                    return response()->json([
                        'status' => false,
                        'message'  => "Can't insert data contract advance bond"
                    ], 400);
				}	
            }
            
            $delete_item = ContractRetentionBond::where('contract_id',$id)->delete();
            if($request->rb){
				$data = [
					'contract_id' => $contract->id,
					'issued_by' => $request->issued_rb,
					'validity_period' => ($request->validity_rb)?$request->validity_rb:null,
					'value' => $request->value_rb,
				];
                $ok = ContractRetentionBond::create($data);
				if (!$ok) {
					DB::rollback();
                    return response()->json([
                        'status' => false,
                        'message'  => "Can't insert data contract retention bond"
                    ], 400);
				}	
            }
            
            $delete_item = ContractRetentionMoney::where('contract_id',$id)->delete();
            if($request->rm){
				$data = [
					'contract_id' => $contract->id,
					'currency' => $request->currency_rm,
					'value' => (float) str_replace(',','.', str_replace('.','',$request->value_rm)),
				];
                $ok = ContractRetentionMoney::create($data);
				if (!$ok) {
					DB::rollback();
                    return response()->json([
                        'status' => false,
                        'message'  => "Can't insert data Contract Retention Money"
                    ], 400);
				}	
            }
            
            $delete_item = ContractPenalty::where('contract_id',$id)->delete();
            if($request->pen){
				$data = [
					'contract_id' => $contract->id,
					'late_period' => $request->late_pen,
					'value' => $request->value_pen,
				];
                $ok = ContractPenalty::create($data);
				if (!$ok) {
					DB::rollback();
                    return response()->json([
                        'status' => false,
                        'message'  => "Can't insert data Contract Penalty"
                    ], 400);
				}	
            }
            
            $delete_item = ContractWarrantyBond::where('contract_id',$id)->delete();
            if($request->wb){
				$data = [
					'contract_id' => $contract->id,
					'length' => $request->length_wb,
					'bond_value' => $request->value_wb,
					'issued_by' => $request->issued_wb,
				];
                $ok = ContractWarrantyBond::create($data);
				if (!$ok) {
					DB::rollback();
                    return response()->json([
                        'status' => false,
                        'message'  => "Can't insert data Contract Warranty Bond"
                    ], 400);
				}	
            }
            
            $delete_item = ContractOwner::where('contract_id',$id)->delete();
            $witness = $request->owner;
			for ($i=0; $i < count($witness); $i++) { 
				$data_type = [
					'contract_id' => $contract->id,
					'group_id' => $request->owner[$i],
				];
                $exType = ContractOwner::create($data_type);
				if (!$exType) {
					DB::rollback();
                    return response()->json([
                        'status' => false,
                        'message'  => "Can't insert data Contract Owner"
                    ], 400);
				}
            }

            if($request->contract_type){
                if($request->contract_type == "product"){
                    $delete_item = BatchContract::where('contract_id',$id)->delete();
                    $total_batch = ($request->batch)?$request->batch:1;
                    for($a=1;$a<=$total_batch;$a++){
                        $data_batch = [
                            'contract_id' => $contract->id,
                            'no' => $a,
                        ];
                        $ok = BatchContract::create($data_batch);
                        if (!$ok) {
                            DB::rollback();
                            return response()->json([
                                'status' => false,
                                'message'  => "Can't insert data Contract Batch"
                            ], 400);
                        }
                    }
                }
            }

            DB::commit();
            return response()->json([
                'status' => true,
                'message'  => "Data has been updated",
                'id' => $contract->id
            ], 200);
        }else {
            DB::rollback();
            return response()->json([
                'status' => false,
                'message'  => "Can't update data contract"
            ], 400);
		}
    }

    public function destroy($id)
    {
        try {
            $contract = contract::find($id);
            $contract->delete();
        } catch (QueryException $th) {
            return response()->json([
                'status'    => false,
                'message'   => 'Error delete data ' . $th->errorInfo[2]
            ], 400);
        }
        return response()->json([
            'status'    => true,
            'message'   => 'Success delete data'
        ], 200);
    }

    public function show(Request $request,$id){
        $roles = Role::all();
        $contract = Contract::where('id',$id)->first();
        if($contract){
            $contract->with('jvmember','pb','ab','rb','rm','pen','wb','owner','adden','site','purchasing')->first();
            $owner = [];
            foreach($contract->owner as $owners){
                array_push($owner, $owners->group_id);
            }
            $contract->owner = $owner;
            $userid = Auth::guard('admin')->user()->id;
            $role = Role::where('role_users.user_id', $userid)->join('role_users','roles.id','=','role_users.role_id')->first();
            $group_code = $role->code;

            if(isset($contract->adden[0]->expiration_moved)){
                $start_date = $contract->adden[0]->expiration_moved;
            } else {
                $start_date = $contract->expiration_date;
            }
            $finish_date = date('Y-m-d');
            $date1 = new \DateTime($start_date);
            $date2 = new \DateTime($finish_date);
            $diff = $date1->diff($date2);
            if(strtotime($start_date) >= strtotime($finish_date)){
                $range = $diff->days;
                
            } else {
                $range = $diff->days*-1;
            }
        }
        if ($contract) {
            return view('admin.contract.detail', compact('contract','roles','userid','group_code','range'));
        } else {
            abort(404);
        }
    }

    public function addendum(){
        
    }

    public function product(Request $request){
        $start = $request->start;
        $length = $request->length;
        $query = $request->search['value'];
        $sort = $request->columns[$request->order[0]['column']]['data'];
        $dir = $request->order[0]['dir'];
        // $name = strtoupper($request->name);

        //Count Data
        $query = ContractProduct::select('*')->with('product','uom','product.uoms','product.uoms.uom');
        $query->where('contract_id',$request->id);
        // $query->whereRaw("upper(name) like '%$name%'");

        $row = clone $query;
        $recordsTotal = $row->count();

        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $products = $query->get();

        $data = [];
        foreach ($products as $product) {
            $product->no = ++$start;
            $data[] = $product;
        }
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data
        ], 200);
    }

    public function selectproduct(Request $request)
    {
        $start = $request->page ? $request->page - 1 : 0;
        $length = $request->limit;
        $name = strtoupper($request->name);

        //Count Data
        $query = Product::select('*')->with('uoms','uoms.uom');
        $query->whereRaw("upper(name) like '%$name%'");

        $row = clone $query;
        $recordsTotal = $row->count();

        $query->offset($start);
        $query->limit($length);
        $products = $query->get();

        $data = [];
        foreach ($products as $product) {
            $product->no = ++$start;
            $data[] = $product;
        }
        return response()->json([
            'total' => $recordsTotal,
            'rows' => $data
        ], 200);
    }

    public function storeproduct(Request $request){
        $validator = Validator::make($request->all(), [
            'contract_id'      => 'required',
            'product_id'       => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }

        $productuom = ProductUom::where('product_id',$request->product_id)->first();

        $contractproduct = ContractProduct::create([
            'contract_id' => $request->contract_id,
            'product_id' => $request->product_id,
            'qty' => 0,
            'uom_id' => $productuom->uom_id,
        ]);

        if (!$contractproduct) {
            return response()->json([
                'status'    => false,
                'message'   => "failed Insert contract Product"
            ], 400);
        }

        return response()->json([
            'status'     => true,
            'results'     => route('contract.index'),
        ], 200);
    }

    public function updateproduct(Request $request){
        $validator = Validator::make($request->all(), [
            'id'       => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }

        $contractproduct = ContractProduct::find($request->id);
        if($request->uom_id){
            $contractproduct->uom_id = $request->uom_id;
        }
        if($request->qty){
            $contractproduct->qty = $request->qty;
        }

        if($contractproduct->save()){
            return response()->json([
                'status'     => true,
                'message'     => 'Success update contract Product',
            ], 200);
        }else{
            return response()->json([
                'status'    => false,
                'message'   => "failed update contract Product"
            ], 400);
        }
    }

    public function deleteproduct(Request $request){
        try {
            $contractproduct = ContractProduct::find($request->id);
            $contractproduct->delete();
        } catch (QueryException $th) {
            return response()->json([
                'status'    => false,
                'message'   => 'Error delete data ' . $th->errorInfo[2]
            ], 400);
        }
        return response()->json([
            'status'    => true,
            'message'   => 'Success delete data'
        ], 200);
    }

    public function batch(Request $request,$id){
        $roles = Role::all();
        $contract = Contract::where('id',$id)->first();
        if($contract){
            $contract->with('jvmember','pb','ab','rb','rm','pen','wb','owner','adden','site','purchasing')->first();
            $owner = [];
            foreach($contract->owner as $owners){
                array_push($owner, $owners->group_id);
            }
            $contract->owner = $owner;
            $userid = Auth::guard('admin')->user()->id;
            $role = Role::where('role_users.user_id', $userid)->join('role_users','roles.id','=','role_users.role_id')->first();
            $group_code = $role->code;

            if(isset($contract->adden[0]->expiration_moved)){
                $start_date = $contract->adden[0]->expiration_moved;
            } else {
                $start_date = $contract->expiration_date;
            }
            $finish_date = date('Y-m-d');
            $date1 = new \DateTime($start_date);
            $date2 = new \DateTime($finish_date);
            $diff = $date1->diff($date2);
            if(strtotime($start_date) >= strtotime($finish_date)){
                $range = $diff->days;
                
            } else {
                $range = $diff->days*-1;
            }
        }
        if ($contract) {
            return view('admin.contract.batch', compact('contract','roles','userid','group_code','range'));
        } else {
            abort(404);
        }
    }

    public function batchread(Request $request)
    {
        $start = $request->start;
        $length = $request->length;
        $query = $request->search['value'];
        $sort = $request->columns[$request->order[0]['column']]['data'];
        $dir = $request->order[0]['dir'];
        // $name = strtoupper($request->name);

        //Count Data
        $query = BatchContract::select('*')->withCount("batchproduct");
        $query->where('contract_id',$request->id);
        // $query->whereRaw("upper(name) like '%$name%'");

        $row = clone $query;
        $recordsTotal = $row->count();

        $query->offset($start);
        $query->limit($length);
        $query->orderBy("no", "asc");
        $batchs = $query->get();

        $data = [];
        foreach ($batchs as $batch) {
            $batch->number = ++$start;
            $batch->start_date = date("d F Y", strtotime($batch->start_batch));
            $batch->end_date = date("d F Y", strtotime($batch->end_batch));
            $batch->total_sku = $this->batchGetTotalSKU($batch->id);
            $data[] = $batch;
        }
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data
        ], 200);
    }

    public function batchadd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contract_id'       => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();

        $batchcontract = BatchContract::where("contract_id",$request->contract_id)->count();

        $data_batch = [
            'contract_id' => $request->contract_id,
            'no' => $batchcontract+1,
        ];
        $ok = BatchContract::create($data_batch);

        if($ok){
            $contract = Contract::find($request->contract_id);
            $contract->batch = $batchcontract+1;

            if(!$contract->save()){
                DB::rollback();
                return response()->json([
                    'status'    => false,
                    'message'   => "Failed update contract",
                    'error'     => $contract,
                ], 400);
            }

            DB::commit();
            return response()->json([
                'status'     => true,
                'message'   => "Success add batch"
            ], 200);
        }else{
            DB::rollback();
            return response()->json([
                'status'    => false,
                'message'   => "Failed add batch"
            ], 400);
        }

    }

    public function batchdelete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contract_id' => 'required',
            'id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        
        try {
            $batch = BatchContract::find($request->id);
            if($batch->delete()){
                $totalbatch = BatchContract::where("contract_id",$request->contract_id)->count();

                // update contract
                $contract = Contract::find($request->contract_id);
                $contract->batch = $totalbatch;

                if(!$contract->save()){
                    DB::rollback();
                    return response()->json([
                        'status'    => false,
                        'message'   => "Failed update contract",
                        'error'     => $contract,
                    ], 400);
                }

                // reorder batch
                $batchcontracts = BatchContract::where("contract_id",$request->contract_id)->orderBy("id","asc")->get();
                $n = 0;
                foreach($batchcontracts as $batchcontract){
                    $n++;
                    $batchcontract->no = $n;
                    if(!$batchcontract->save()){
                        DB::rollback();
                        return response()->json([
                            'status'    => false,
                            'message'   => "Failed update contract",
                            'error'     => $batchcontract,
                        ], 400);
                    }
                }

                DB::commit();
                return response()->json([
                    'status'     => true,
                    'message'   => "Success update batch"
                ], 200);
            }
        } catch (QueryException $th) {
            DB::rollback();
            return response()->json([
                'status'    => false,
                'message'   => 'Error delete data ' . $th->errorInfo[2]
            ], 400);
        }

    }

    public function batchedit(Request $request)
    {
        $batchcontract = BatchContract::where("id",$request->id)->first();
        if($batchcontract->start_batch){
            $batchcontract->start_batch = date("d/m/Y", strtotime($batchcontract->start_batch));
        }
        if($batchcontract->end_batch){
            $batchcontract->end_batch = date("d/m/Y", strtotime($batchcontract->end_batch));
        }
        if($batchcontract){
            return response()->json([
                'status'     => true,
                'message'   => "Success",
                'data' => $batchcontract,
            ], 200);
        }else{
            return response()->json([
                'status'    => false,
                'message'   => "Batch Not Found",
                'error'     => $batchcontract,
            ], 400);
        }
    }

    public function batchshow(Request $request)
    {
        $batchcontract = BatchContract::where("id",$request->id)->withCount("batchproduct")->first();
        if($batchcontract->start_batch){
            $batchcontract->start_batch = date("d/m/Y", strtotime($batchcontract->start_batch));
        }
        if($batchcontract->end_batch){
            $batchcontract->end_batch = date("d/m/Y", strtotime($batchcontract->end_batch));
        }
        $batchcontract->total_sku = $this->batchGetTotalSKU($batchcontract->id);
        if($batchcontract){
            return response()->json([
                'status'     => true,
                'message'   => "Success",
                'data' => $batchcontract,
            ], 200);
        }else{
            return response()->json([
                'status'    => false,
                'message'   => "Batch Not Found",
                'error'     => $batchcontract,
            ], 400);
        }
    }

    public function batchGetTotalSKU($id){
        // DB::enableQueryLog();
        $products = BatchContractProduct::selectRaw("count(*) as total")->where("batch_contract_id",$id);
        $products->join("contract_products","contract_products.id","=","batch_contract_products.contract_product_id");
        $products->groupBy("contract_products.product_id");
        $totals = $products->get();
        $total = 0;
        foreach($totals as $row){
            $total++;
        }
        // dd(DB::getQueryLog());
        // echo json_encode($totals);
        // die();
        return $total;
    }

    public function batchupdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'batch_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();

        $start_batch = explode("/", $request->start_batch);
        $end_batch = explode("/", $request->end_batch);

        $batchcontract = BatchContract::find($request->batch_id);
        $batchcontract->start_batch = $start_batch[2]."-".$start_batch[1]."-".$start_batch[0];
        $batchcontract->end_batch = $end_batch[2]."-".$end_batch[1]."-".$end_batch[0];

        if($batchcontract->save()){
            DB::commit();
            return response()->json([
                'status'     => true,
                'message'   => "Success update batch",
                'data' => $batchcontract,
            ], 200);
        }else{
            DB::rollback();
            return response()->json([
                'status'    => false,
                'message'   => "Failed update batch",
                'error'     => $batchcontract,
            ], 400);
        }
    }

    public function batchproductread(Request $request)
    {
        $start = $request->start;
        $length = $request->length;
        $query = $request->search['value'];
        $sort = $request->columns[$request->order[0]['column']]['data'];
        $dir = $request->order[0]['dir'];
        // $name = strtoupper($request->name);

        //Count Data
        $query = BatchContractProduct::select('*')->with("contractproduct","contractproduct.product","contractproduct.uom");
        $query->where('batch_contract_id',$request->id);
        // $query->whereRaw("upper(name) like '%$name%'");

        $row = clone $query;
        $recordsTotal = $row->count();

        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $batchs = $query->get();

        $data = [];
        foreach ($batchs as $batch) {
            $batch->number = ++$start;
            $batch->start_date = date("d F Y", strtotime($batch->start_batch));
            $batch->end_date = date("d F Y", strtotime($batch->end_batch));
            $data[] = $batch;
        }
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data
        ], 200);
    }

    public function selectbatch(Request $request)
    {
        $start = $request->page ? $request->page - 1 : 0;
        $length = $request->limit;
        $name = strtoupper($request->name);

        //Count Data
        $query = ContractProduct::select('*');
        $query->where("contract_id",$request->contract_id);
        // $query->whereRaw("upper(products.name) like '%$name%'");
        
        $row = clone $query;
        $recordsTotal = $row->count();
        
        $query->offset($start);
        $query->limit($length);
        $query->with('product','uom');
        $products = $query->get();

        $data = [];
        foreach ($products as $product) {
            $product->no = ++$start;
            $data[] = $product;
        }
        return response()->json([
            'total' => $recordsTotal,
            'rows' => $data
        ], 200);
    }

    public function batchproductadd(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'batch_contract_id'       => 'required',
            'contract_product'       => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();

        $contractproduct = ContractProduct::where("id",$request->contract_product)->first();

        $data = [
            'contract_product_id' => $request->contract_product,
            'batch_contract_id' => $request->batch_contract_id,
            'qty' => $contractproduct->qty,
        ];
        $ok = BatchContractProduct::create($data);

        if($ok){
            DB::commit();
            return response()->json([
                'status'     => true,
                'message'   => "Success add product"
            ], 200);
        }else{
            DB::rollback();
            return response()->json([
                'status'    => false,
                'message'   => "Failed add product"
            ], 400);
        }
    }

    public function batchproductdelete(Request $request)
    {
        try {
            $batchcontractproduct = BatchContractProduct::find($request->id);
            $batchcontractproduct->delete();
        } catch (QueryException $th) {
            return response()->json([
                'status'    => false,
                'message'   => 'Error delete data ' . $th->errorInfo[2]
            ], 400);
        }
        return response()->json([
            'status'    => true,
            'message'   => 'Success delete data'
        ], 200);
    }
}
