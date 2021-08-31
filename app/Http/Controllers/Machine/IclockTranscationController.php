<?php

namespace App\Http\Controllers\Machine;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\Machine\IclockTransaction;
use App\Models\Machine\PersonnelDepartment;
use App\Models\WorkingShift;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class IclockTranscationController extends Controller
{
    public $url = 'http://127.0.0.1:8081';
    public $token;
    function __construct() {
        $authData   = [
            'username'  => 'rifqi14',
            'password'  => 'rf280616',
        ];

        $curl       = curl_init("$this->url/jwt-api-token-auth/");

        // 1. Set the CURLOPT_RETURNTRANSFER option to true
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // 2. Set the CURLOPT_POST option to true for POST request
        curl_setopt($curl, CURLOPT_POST, true);
        // 3. Set the request data as JSON using json_encode function
        curl_setopt($curl, CURLOPT_POSTFIELDS,  json_encode($authData));
        // 4. Set custom headers for RapidAPI Auth and Content-Type header
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
        ]);
        $token   = json_decode(curl_exec($curl));
        curl_close($curl);
        $this->setToken("JWT $token->token");
    }
    function setToken($token)
    {
        $this->token = $token;
    }

    function getToken() 
    {
        return $this->token;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $deparments = PersonnelDepartment::pluck('id')->implode(',');
        $token      = $this->getToken();
        $authData   = [
            'page'          => 1,
            'page_size'     => 20,
            'start_date'    => "2021-08-01",
            'end_date'      => "2021-09-01",
            "departments"   => $deparments,
            // areas:-1
            // groups:-1
            // employees:-1
        ];

        $curl       = curl_init("$this->url/att/api/firstInLastOutReport/?" . http_build_query($authData));

        // 1. Set the CURLOPT_RETURNTRANSFER option to true
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        // 4. Set custom headers for RapidAPI Auth and Content-Type header
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        "Authorization: $token",
        ]);
        $response   = json_decode(curl_exec($curl));
        curl_close($curl);
        
        foreach ($response->data as $key => $attendance) {
            $time   = Carbon::parse($attendance->att_date);
            $employee   = Employee::where('nid', $attendance->emp_code)->first();
            if ($employee) {
                $sameAttendance = Attendance::date($time)->getByEmployee($employee->id)->first();
                if (!$sameAttendance) {
                    $create     = Attendance::create([
                        'employee_id'       => $employee->id,
                        'attendance_date'   => $time,
                        'attendance_in'     => $attendance->check_in ? date('Y-m-d H:i:s', strtotime($time->toDateString() . $attendance->check_in)) : null,
                        'attendance_out'    => $attendance->check_out ? date('Y-m-d H:i:s', strtotime($time->toDateString() . $attendance->check_out)) : null,
                        'status'            => 'APPROVED',
                        'day'               => date('D', strtotime($time)),
                    ]);
                    $create->working_time   = $this->countWorkingTime($create->attendance_in, $create->attendance_out) >= 8 ? 8 : $this->countWorkingTime($create->attendance_in, $create->attendance_out);
                    $create->over_time      = ($this->countWorkingTime($create->attendance_in, $create->attendance_out) - 8) >= 0 ? $this->countWorkingTime($create->attendance_in, $create->attendance_out) - 8 : 0;
                    if ($create->working_time >= 8 || ($employee->shift_type == 'hourly' && $create->working_time > 0)) {
                        $dayWork        = 1;
                    } else {
                        $dayWork        = 0.5;
                    }
                    $create->day_work       = $dayWork;
                    $create->save();
        
                    if ($create) {
                        $logIn    = AttendanceLog::create([
                            'attendance_id'     => $create->id,
                            'employee_id'       => $employee->id,
                            'attendance'        => date('Y-m-d H:i:s', strtotime($time->toDateString() . $attendance->check_in)),
                            'type'              => 'IN',
                        ]);
                        $logOut    = AttendanceLog::create([
                            'attendance_id'     => $create->id,
                            'employee_id'       => $employee->id,
                            'attendance'        => date('Y-m-d H:i:s', strtotime($time->toDateString() . $attendance->check_out)),
                            'type'              => 'IN',
                        ]);
                        if (!$logIn || !$logOut) {
                            return response()->json([
                                'status'    => false,
                                'message'   => "Error create data"
                            ], 400);
                        }
                    }
                }
            }
        }
    }    

    /**
     * Count working time function. 
     *
     * @param $attendanceIn
     * @param $attendanceOut
     * @return integer
     */
    public function countWorkingTime($attendanceIn, $attendanceOut)
    {
        if ($attendanceIn && $attendanceOut) {
            $count      = Carbon::parse($attendanceIn)->diffInHours($attendanceOut);
            return $count;
        } else {
            return 0;
        }
    }

    public function read(Request $request)
    {
        $start          = $request->start;
        $length         = $request->length;
        $query          = $request->search['value'];
        $sort           = $request->columns[$request->order[0]['column']]['data'];
        $dir            = $request->order[0]['dir'];
        $emp_code       = $request->employee_nid;
        $start_time     = Carbon::parse($request->start_time)->startOfDay()->toDateTimeString();
        $end_time       = Carbon::parse($request->end_time)->endOfDay()->toDateTimeString();
        $punch_state    = $request->punch_state;

        // Query Data
        $queryData      = IclockTransaction::whereBetween('punch_time', [$start_time, $end_time]);
        if ($emp_code) {
            $queryData->where('emp_code', $emp_code);
        }
        if ($punch_state) {
            $queryData->where('punch_state', $punch_state);
        }

        $row            = clone $queryData;
        $recordsTotal   = $row->count();

        $queryData->offset($start);
        $queryData->limit($length);
        $queryData->orderBy($sort, $dir);
        $transactions   = $queryData->get();

        $data           = [];
        foreach ($transactions as $key => $transaction) {
            $transaction->no    = ++$start;
            $data[]             = $transaction;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
