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

class PurchasingController extends Controller
{
    function __construct()
    {
        View::share('menu_active', url('admin/' . 'purchasing'));
        $this->middleware('accessmenu', ['except' => ['select']]);
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
            $purchasing->created_ats = date('d/m/Y', strtotime('2021-04-16 06:29:23'));
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
        $array  = $this->input->post();
		$read =  $this->procurement_m->getperiod($array);

        $type = $request->type;
		$date = $request->date;
		$date = explode('/', $date);
		$date = $date[2]."-".$date[1]."-".$date[0];
		$date = date('Y-m-d', strtotime($date));

        $adb_schedule = AdbSchedule::select('*');
        $adb_schedule->where('upper(type)', strtoupper($type));
        $adb_schedule->orderBy('id', 'asc');
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

        if (isset($tor->filename)) {
            $path = 'assets/procurement/purchasing';
			if (!file_exists($path)) {
				mkdir($src, 0777, true);
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
}
