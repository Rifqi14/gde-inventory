<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

use App\Models\Purchasing;
use App\Models\PurchasingUser;
use App\Models\PurchasingSchedule;
use App\Models\PurchasingScheduleNote;
use App\Models\PurchasingBudget;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\AdbSchedule;
use App\Models\Contract;
use App\Models\Budget;

class PurchasingController extends Controller
{
    function __construct()
    {
        View::share('menu_active', url('admin/' . 'purchasing'));
        $this->middleware('accessmenu', ['except' => ['select','test']]);
    }

    public function index(){
        return view('admin.purchasing.index');
    }

    public function select(Request $request)
    {
        $start = $request->page ? $request->page - 1 : 0;
        $length = $request->limit;
        $number = strtoupper($request->name);

        // search available purchasing from contract
        $limit = Contract::all();
		$arr = [];
		foreach($limit as $get){
			array_push($arr, $get->purchasing_id);
		}

        //Count Data
        $query = Purchasing::select('*');
        $query->whereNotIn('id',$arr);
        $query->whereRaw("upper(number) like '%$number%'");

        $row = clone $query;
        $recordsTotal = $row->count();

        $query->offset($start);
        $query->limit($length);
        $purchasings = $query->get();

        $data = [];
        foreach ($purchasings as $purchasing) {
            $purchasing->no = ++$start;
            $data[] = $purchasing;
        }
        return response()->json([
            'total' => $recordsTotal,
            'rows' => $data
        ], 200);
    }

    public function read(Request $request)
    {
        $start = $request->start;
        $length = $request->length;
        $query = $request->search['value'];
        $sort = $request->columns[$request->order[0]['column']]['data'];
        $dir = $request->order[0]['dir'];
        // $code = strtoupper($request->code);
        // $name = strtoupper($request->name);

        //Count Data
        $query = Purchasing::select('*');
        // $query->whereRaw("upper(code) like '%$code%'");
        // $query->whereRaw("upper(name) like '%$name%'");
        $recordsTotal = $query->count();

        //Select Pagination
        $query = Purchasing::select('*');
        // $query->whereRaw("upper(code) like '%$code%'");
        // $query->whereRaw("upper(name) like '%$name%'");
        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $purchasings = $query->get();
        // dd($purchasings);
        $data = [];
        foreach ($purchasings as $purchasing) {
            $purchasing->no = ++$start;
            $purchasing->created_ats = date('d/m/Y', strtotime($purchasing->created_at));
			if($purchasing->est_currency == 'yen'){
				$cur = '¥';
			} else if($purchasing->est_currency == 'dollar'){
				$cur = '$';
			} else if($purchasing->est_currency == 'euro'){
				$cur = '€';
			} else {
				$cur = 'Rp';
			}
			$purchasing->est_value = $cur.' '.number_format($purchasing->est_value,'2',',','.');
            $data[] = $purchasing;
        }
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data
        ], 200);
    }

    public function create()
    {
        $roles = Role::all();
        $userid = Auth::guard('admin')->user()->id;
        return view('admin.purchasing.create', compact('roles','userid'));
    }

    public function getgdeperiod(Request $request){
        $date  = $request->date;
		$new = sortDateArray($date);
		// print_r($start);
		// exit();
		$start_date = $new[0];
		$finish_date = end($new);
		$range = getDatesFromRange($start_date, $finish_date, 'Y-m-d', '1 days');

        return response()->json([
            'count' => count($range)
        ], 200);
    }

    public function getadb(Request $request){
        $type 	= $request->type;

        $adb_schedule = AdbSchedule::select('*');
        $adb_schedule->whereRaw('upper(adb_schedules.type) = '."'".strtoupper($type)."'");
        $adb_schedule->orderBy('id', 'asc');
        $adb = $adb_schedule->get();

        $data = [];
		foreach ($adb as $key => $value) {
			$data[] = $value;
		}
        return response()->json($data, 200);
    }

    public function getperiod(Request $request){
        $array  = $request;

        $type = $request->type;
		$date = $request->date;
		$date = explode('/', $date);
		$date = $date[2]."-".$date[1]."-".$date[0];
		$date = date('Y-m-d', strtotime($date));

        $adb_schedule = AdbSchedule::select('*');
        $adb_schedule->whereRaw("upper(adb_schedules.type) = '".strtoupper($type)."'");
        $adb_schedule->orderBy('adb_schedules.id', 'asc');
        $adb = $adb_schedule->get();

		$data = [];
		$range = [];
		$ndate = $date;
		foreach ($adb as $key => $value) {
			$period = ' + '.$value->period.' days';
			$fdate = date('Y-m-d', strtotime($ndate. $period));
			$value->date = date('d/m/Y', strtotime($fdate)); 
			$data[] = $value;
			array_push($range, $fdate);
			$ndate = $fdate;

		}

		$start_date = $range[0];
		$finish_date = end($range);
		$range = getDatesFromRange($start_date, $finish_date, 'Y-m-d', '1 days');

        return response()->json([
            'data' => $data,
            'count' => count($range)
        ], 200);
    }

    public function store(Request $request)
    {
		$number = $request->number;
		$subject = $request->subject;
		$rule = $request->rule;
		$est_currency = $request->est_currency;
		$est_value = $request->est_value;
		$technical = $request->technical;
		$financial = $request->financial;
		$tor = $request->file('tor');
		$duration = $request->duration;
		$content = $request;
		$created_user = Auth::guard('admin')->user()->id;
		$choose_adb = $request->choose_adb;
		$table = 'purchasing';

        DB::beginTransaction();

        $data = array(
			'number'=>$number,
			'subject'=>$subject,
			'rule'=>$rule,
			'est_currency'=>$est_currency,
			'est_value'=> (int) str_replace('.','',$est_value),
			'technical'=>(int) $technical,
			'financial'=>(int) $financial,
			'duration'=>(int) $duration,
			'created_user' => $created_user,
			'adb' => $choose_adb?$choose_adb:null,
		);

        if ($request->hasFile('tor')) {
            $path = 'assets/procurement/purchasing';
			if (!file_exists($path)) {
				mkdir($path, 0777, true);
            }
            $tor->move($path, $number.'.'.$tor->getClientOriginalExtension());
            $filename = $path.$number.'.'.$tor->getClientOriginalExtension();
			$data['tor'] = $filename;
        }

        $purchasing = Purchasing::create($data);

        if($purchasing){
            if($rule == 'adb'){
				$adb_ids = $request->adb_id;
				$adb_date = $request->adb_date;
				foreach ($adb_ids as $key => $adb_id) {
					$data = [
						'purchasing_id' => $purchasing->id,
						'adb_id' => $adb_id,
						'date' => $adb_date[$key],
					];
                    $purchasing_schedule = PurchasingSchedule::create($data);
					if(!$purchasing_schedule){
                        DB::rollback();
                        return response()->json([
                            'status' => false,
                            'message'  => "Can't insert data purchasing schedule"
                        ], 400);
					}
				}
			} else {
				for ($i=1; $i <= 11; $i++) { 
					if($request->{"gde_date_".$i}){
						$data = [
							'purchasing_id' => $purchasing->id,
							'schedule' => $request->{'schedule_name_'.$i},
							'date' => $request->{'gde_date_'.$i},
						];
                        $purchasing_schedule = PurchasingSchedule::create($data);
						if(!$purchasing_schedule){
							DB::rollback();
                            return response()->json([
                                'status' => false,
                                'message'  => "Can't insert data purchasing schedule"
                            ], 400);
						}
					}
				}
			}

            $users = $request->user;
            foreach ($users as $key => $user) {
                $data = [
                    'purchasing_id' => $purchasing->id,
                    'group_id' => $user,
                ];
                $purchasing_user = PurchasingUser::create($data);
                if(!$purchasing_user){
                    DB::rollback();
                    return response()->json([
                        'status' => false,
                        'message'  => "Can't insert data purchasing user"
                    ], 400);
                }
			}

            $budget_ids = $request->budget_id;
            $budget_val = $request->budget_val;
            foreach ($budget_ids as $key => $budget) {
                $data = [
                    'purchasing_id' => $purchasing->id,
                    'budget_id' => $budget,
                    'value' => str_replace('.','',$budget_val[$key]),
                ];
                $purchasing_budget = PurchasingBudget::create($data);
                if(!$purchasing_budget){
                    DB::rollback();
                    return response()->json([
                        'status' => false,
                        'message'  => "Can't insert data purchasing budget"
                    ], 400);
                }
            }

            DB::commit();
            return response()->json([
                'status' => true,
                'message'  => "Data has been saved",
                'id' => $purchasing->id,
                'results' => route('purchasing.index'),
            ], 200);

        }else{
            DB::rollback();
            return response()->json([
                'status' => false,
                'message'  => "Can't insert data purchasing"
            ], 400);
        }
    }

    public function edit(Request $request,$id)
    {
        $roles = Role::all();
        $userid = Auth::guard('admin')->user()->id;
        $purchasing = Purchasing::find($id)->with('puser')->first();
        $users = [];
        foreach($purchasing->puser as $user){
            array_push($users, $user->group_id);
        }
        $purchasing->puser = $users;
        $purchasing->step = $this->getschedule($purchasing->id);
        $purchasing->budget = $this->getbudget($purchasing->id);
        $budgets = Budget::orderBy('site_id','asc')->orderBy('name','asc')->get();
        // dd($purchasing->step);
        if ($purchasing) {
            return view('admin.purchasing.edit', compact('purchasing','roles','userid','budgets'));
        } else {
            abort(404);
        }
    }

    public function getschedule($purchasing_id) {
        // DB::enableQueryLog();
        $purchasing_schedule = PurchasingSchedule::select('purchasing_schedules.*','adb_schedules.schedule_name');
        $purchasing_schedule->leftJoin('adb_schedules','adb_schedules.id','=','purchasing_schedules.adb_id');
        $purchasing_schedule->where('purchasing_schedules.purchasing_id', $purchasing_id);
        $purchasing_schedule->orderBy('date', 'asc');
        $read = $purchasing_schedule->get();
        // dd(DB::getQueryLog());

		$data = [];
		$prev_delay = 0;
		foreach ($read as $key => $value) {
			$value->fulldate = date('d F Y', strtotime($value->date));
			$value->notes = $this->getnotes($value->id);
			$value->delay = 0;
			if($value->status){
				$delay = getDatesFromRange($value->date, $value->updated, 'Y-m-d', '1 days');
				if(strtotime($value->updated)>strtotime($value->date)){
					$value->delay = (count($delay)-1) - $prev_delay;
					if($value->delay > 0){
						$value->stat = "Actual Finish Date (".date('d/m/Y', strtotime($value->updated)).") - Delayed ".$value->delay." Days";
					} else {
						$value->stat = "Finished on Target";	
					}
				} else {
					$value->stat = "Finished on Target";
				}
			}
			$data[] = $value;
			$prev_delay = $prev_delay + $value->delay;
		}
		return $data;
	}

    public function getnotes($schedule_id) {
        $PurchasingScheduleNote = PurchasingScheduleNote::select('*');
        $PurchasingScheduleNote->where('schedule_id', $schedule_id);
        $read = $PurchasingScheduleNote->get();

		$data = [];
		foreach ($read as $key => $value) {
			$value->date = date('d/m/Y', strtotime($value->date));
			$data[] = $value;

		}
		return $data;
	}

    public function getbudget($purchasing_id) {
        $purchasingbudget = PurchasingBudget::select('purchasing_budgets.*','budgets.name');
        $purchasingbudget->leftJoin('budgets','budgets.id','=','purchasing_budgets.budget_id');
        $purchasingbudget->where('purchasing_budgets.purchasing_id', $purchasing_id);
        $read = $purchasingbudget->get();

		$data = [];
		foreach ($read as $key => $value) {
			$data[] = $value;

		}
		return $data;
	}

    public function update(Request $request, $id)
    {
		$number = $request->number;
		$subject = $request->subject;
		$est_currency = $request->est_currency;
		$est_value = $request->est_value;
		$technical = $request->technical;
		$financial = $request->financial;
		$tor = $request->file('tor');
		$content = $request;
		$updated_user = Auth::guard('admin')->user()->id;
		$table = 'purchasing';
        
        DB::beginTransaction();
		$data = array(
			'number'=>$number,
			'subject'=>$subject,
			'est_currency'=>$est_currency,
			'est_value'=> (int) str_replace('.','',$est_value),
			'technical'=>(int) $technical,
			'financial'=>(int) $financial,
			'updated_user' => $updated_user,
		);

        if ($request->hasFile('tor')) {
            $path = 'assets/procurement/purchasing';
			if (!file_exists($path)) {
				mkdir($path, 0777, true);
            }
            $tor->move($path, $number.'.'.$tor->getClientOriginalExtension());
            $filename = $path.$number.'.'.$tor->getClientOriginalExtension();
			$data['tor'] = $filename;
        }

        $purchasing = Purchasing::find($id);
        $update = $purchasing->update($data);
        
		if($update){
            $delete_item = PurchasingUser::where('purchasing_id',$id)->delete();
			$users = $request->user;
            foreach ($users as $key => $user) {
                $data = [
                    'purchasing_id' => $id,
                    'group_id' => $user,
                ];
                $ok = PurchasingUser::create($data);
                if(!$ok){
                    DB::rollback();
                    return response()->json([
                        'status' => false,
                        'message'  => "Can't insert data purchasing user"
                    ], 400);
                }
			}
			
            $delete_item = PurchasingBudget::where('purchasing_id',$id)->delete();
			$budget_ids = $request->budget_id;
            $budget_val = $request->budget_val;
            foreach ($budget_ids as $key => $budget) {
                $data = [
                    'purchasing_id' => $id,
                    'budget_id' => $budget,
                    'value' => str_replace('.','',$budget_val[$key]),
                ];
                $ok = PurchasingBudget::create($data);
                if(!$ok){
                    DB::rollback();
                    return response()->json([
                        'status' => false,
                        'message'  => "Can't insert data purchasing budget"
                    ], 400);
                }
            }

			DB::commit();
            return response()->json([
                'status' => true,
                'message'  => "Data has been updated",
                'id' => $id,
                'results'=>url('admin/' . 'purchasing')
            ], 200);
		}else{
			DB::rollback();
            return response()->json([
                'status' => false,
                'message'  => "Can't update data purchasing"
            ], 400);
		}
    }

    public function show(Request $request,$id)
    {
        $roles = Role::all();
        $userid = Auth::guard('admin')->user()->id;
        $purchasing = Purchasing::find($id)->with('puser')->first();
        $users = [];
        foreach($purchasing->puser as $user){
            array_push($users, $user->group_id);
        }
        $purchasing->puser = $users;
        $purchasing->step = $this->getschedule($purchasing->id);
        $purchasing->budget = $this->getbudget($purchasing->id);
        $purchasing->total_step = count($this->getschedule($purchasing->id));
        $purchasing->group = $this->getgroup($purchasing->id);
        $budgets = Budget::orderBy('site_id','asc')->orderBy('name','asc')->get();
        $role = Role::where('role_users.user_id', $userid)->join('role_users','roles.id','=','role_users.role_id')->first();
        $group_code = $role->code;
        // dd($purchasing->group);
        if ($purchasing) {
            return view('admin.purchasing.detail', compact('purchasing','roles','userid','budgets','group_code'));
        } else {
            abort(404);
        }
    }
    
    public function getgroup($purchasing_id) {
        $purchasinguser = PurchasingUser::select('purchasing_users.*','roles.name');
        $purchasinguser->leftJoin("roles","roles.id","=","purchasing_users.group_id");
        $purchasinguser->where('purchasing_id', $purchasing_id);
        $read = $purchasinguser->get();

		$data = [];
		foreach ($read as $key => $value) {
			$data[] = $value;

		}
		return $data;
	}

    public function addnotes(Request $request){
        $sch_id = $request->schedule_id;
		$schedule_id = $sch_id;
		$notes = $request->notes;
		$notes_date = $request->notes_date;
		$attach = $request->file('attach');
		$table = 'purchasing_schedule_notes';

        DB::beginTransaction();
		$date = $notes_date;
		$files = $attach;
		foreach ($notes as $key => $note) {
			$data = [
				'schedule_id' => $schedule_id,
				'notes' => $note,
				'date' => $date[$key],
			];

            if ($request->hasFile('attach')) {
                $path = 'assets/procurement/purchasing/notes';
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $files[$key]->move($path, $id_notes.'.'.$files->getClientOriginalExtension());
                $filename = $path.$id_notes.'.'.$files->getClientOriginalExtension();
                $data['file'] = $filename;
            }

            $ok = PurchasingScheduleNote::create($data);
			if(!$ok){
                DB::rollback();
                return response()->json([
                    'status' => false,
                    'message'  => "Can't insert data notes"
                ], 400);
			}
		}

        $get = PurchasingSchedule::where('id',$schedule_id)->first();
        $cek = PurchasingSchedule::where('purchasing_id', $get->purchasing_id)->where('date <', $get->date)->whereNull('status')->get();
		if(count($cek) > 0){
            DB::rollback();
            return response()->json([
                'status' => false,
                'message'  => "Previous step must be proceed first !"
            ], 400);
		}

		$data = array(
			'status' => 'proceed'
		);
        $purchasing_schedule = PurchasingSchedule::find($schedule_id);
        $update = $purchasing_schedule->update($data);
		if(!$update){
            DB::rollback();
            return response()->json([
                'status' => false,
                'message'  => "Can't insert data notes"
            ], 400);
		}

        DB::commit();
        return response()->json([
            'status' => true,
            'message'  => "Data has been saved",
            'id' => $get->purchasing_id,
        ], 200);
    }

    public function destroy($id){
        try {
            $purchasing = Purchasing::find($id);
            $purchasing->delete();
        } catch (QueryException $th) {
            return response()->json([
                'status'    => false,
                'message'   => 'Error delete data ' . $th->errorInfo[2]
            ], 400);
        }
        return response()->json([
            'status'    => true,
            'message'   => 'Failed delete data'
        ], 200);
    }

    public function test(){
        $data = AdbSchedule::all();
        echo json_encode($data);
    }
}
