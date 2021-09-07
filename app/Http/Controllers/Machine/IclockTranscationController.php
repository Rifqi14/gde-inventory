<?php

namespace App\Http\Controllers\Machine;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\Machine\AttPayloadpairing;
use App\Models\Machine\IclockTransaction;
use App\Models\Machine\PersonnelDepartment;
use App\Models\WorkingShift;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class IclockTranscationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data   = json_decode(json_encode($this->read($request)));
        
        foreach ($data as $key => $attendance) {
            $employee   = Employee::where('nid', $attendance->emp_code)->first();
            if ($employee) {
                $sameAttendance = Attendance::date($attendance->att_date)->getByEmployee($employee->id)->first();
                if (!$sameAttendance) {
                    $create     = Attendance::create([
                        'employee_id'       => $employee->id,
                        'attendance_date'   => $attendance->att_date,
                        'attendance_in'     => $attendance->check_in,
                        'attendance_out'    => $attendance->check_out,
                        'status'            => $attendance->check_out ? 'APPROVED' : 'WAITING',
                        'day'               => $attendance->weekday,
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
                        if ($attendance->check_in) {
                            $logIn    = AttendanceLog::create([
                                'attendance_id'     => $create->id,
                                'employee_id'       => $employee->id,
                                'attendance'        => $attendance->check_in,
                                'type'              => 'IN',
                            ]);
                        }
                        if ($attendance->check_out) {
                            $logOut    = AttendanceLog::create([
                                'attendance_id'     => $create->id,
                                'employee_id'       => $employee->id,
                                'attendance'        => $attendance->check_out,
                                'type'              => 'OUT',
                            ]);
                        }
                    }
                } else {
                    $sameAttendance->attendance_in  = $attendance->check_in;
                    $sameAttendance->attendance_out = $attendance->check_out;
                    $sameAttendance->working_time   = $this->countWorkingTime($sameAttendance->attendance_in, $sameAttendance->attendance_out) >= 8 ? 8 : $this->countWorkingTime($sameAttendance->attendance_in, $sameAttendance->attendance_out);
                    $sameAttendance->over_time      = ($this->countWorkingTime($sameAttendance->attendance_in, $sameAttendance->attendance_out) - 8) >= 0 ? $this->countWorkingTime($sameAttendance->attendance_in, $sameAttendance->attendance_out) - 8 : 0;
                    if ($sameAttendance->working_time >= 8 || ($employee->shift_type == 'hourly' && $sameAttendance->working_time > 0)) {
                        $dayWork        = 1;
                    } else {
                        $dayWork        = 0.5;
                    }
                    $sameAttendance->day_work       = $dayWork;
                    $sameAttendance->save();

                    $checkLogIn = AttendanceLog::where([['type', 'IN'], ['attendance_id', $sameAttendance->id], ['attendance', $attendance->check_in]])->first();
                    $checkLogOut= AttendanceLog::where([['type', 'OUT'], ['attendance_id', $sameAttendance->id], ['attendance', $attendance->check_out]])->first();
                    if (!$checkLogIn && $attendance->check_in) {
                        $logIn    = AttendanceLog::create([
                            'attendance_id'     => $sameAttendance->id,
                            'employee_id'       => $employee->id,
                            'attendance'        => $attendance->check_in,
                            'type'              => 'IN',
                        ]);
                    }
                    if (!$checkLogOut && $attendance->check_out) {
                        $logOut    = AttendanceLog::create([
                            'attendance_id'     => $sameAttendance->id,
                            'employee_id'       => $employee->id,
                            'attendance'        => $attendance->check_out,
                            'type'              => 'OUT',
                        ]);
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

    public function firstInLastOutReport(Request $request)
    {
        # code...
    }

    public function getAllDepartment()
    {
        return PersonnelDepartment::pluck('id')->implode(',');
    }

    public function read(Request $request)
    {
        $start_date     = $request->start_date ? Carbon::parse($request->start_date) : Carbon::now()->subDays(2);
        $end_date       = $request->end_date ? Carbon::parse($request->end_date) : Carbon::now();
        $departments    = $this->getAllDepartment();
        $areas          = -1;
        $groups         = -1;
        $employees      = -1;

        // Query Data
        $queryData      = AttPayloadpairing::with(['employee.department'])->whereBetween('att_date', [$start_date, $end_date]);

        $row            = clone $queryData;
        $recordsTotal   = $row->count();

        $transactions   = $queryData->get();

        $data           = [];
        foreach ($transactions as $key => $transaction) {
            $data[]             = [
                'emp_code'      => $transaction->employee->emp_code,
                'first_name'    => $transaction->employee->first_name,
                'last_name'     => $transaction->employee->last_name,
                'nick_name'     => $transaction->employee->nickname,
                'gender'        => $transaction->employee->gender,
                'dept_code'     => $transaction->employee->department->id,
                'dept_name'     => $transaction->employee->department->name,
                'att_date'      => $transaction->att_date,
                'weekday'       => date('D', strtotime($transaction->att_date)),
                'check_in'      => $transaction->clock_in,
                'check_out'     => $transaction->clock_out,
                'total_time'    => $transaction->worked_duration,
            ];
        }

        return $data;
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
