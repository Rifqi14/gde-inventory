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
        $contract = Contract::find($id)->with('jvmember','pb','ab','rb','rm','pen','wb','owner','adden','site')->first();
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

    public function show(Request $request,$id){
        $roles = Role::all();
        $contract = Contract::find($id);
        if($contract){
            $contract->with('jvmember','pb','ab','rb','rm','pen','wb','owner','adden','site')->first();
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
}
