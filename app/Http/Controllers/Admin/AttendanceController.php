<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\Menu;
use App\Models\WorkingShift;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class AttendanceController extends Controller
{
    /**
     * Define default method when access this controller
     */
    function __construct() {
        $menu       = Menu::GetByRoute('attendance')->first();
        $parent     = Menu::parent($menu->parent_id)->first();
        View::share('menu_name', $menu->menu_name);
        View::share('parent_name', $parent->menu_name);
        View::share('menu_active', url('admin/attendance'));
        $this->middleware('accessmenu', ['except' => ['select']]);
    }

    /**
     * Define method to get data and show in datatable
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function read(Request $request)
    {
        $start          = $request->start;
        $length         = $request->length;
        $query          = $request->search['value'];
        $sort           = $request->columns[$request->order[0]['column']]['data'];
        $dir            = $request->order[0]['dir'];
        $date           = Carbon::parse($request->date);
        $employee       = $request->employee;
        $status         = $request->status;

        // Query Data
        $queryData      = Attendance::with(['employee', 'shift']);
        if ($status) {
            $queryData->status($status);
        }
        if ($date) {
            $queryData->whereMonth('attendance_date', $date);
            $queryData->whereYear('attendance_date', $date);
        }
        if ($employee) {
            $queryData->getByEmployee($employee);
        }

        $row            = clone $queryData;
        $recordsTotal   = $row->count();

        $queryData->offset($start);
        $queryData->limit($length);
        $queryData->orderBy($sort, $dir);
        $attendances    = $queryData->get();

        $data           = [];
        foreach ($attendances as $key => $attendance) {
            $attendance->no     = ++$start;
            $attendance->diff_in    = $attendance->shift ? $this->getDiffWithShift($attendance->shift, $attendance->attendance_in) : null;
            $attendance->diff_out   = $attendance->shift ? $this->getDiffWithShift($attendance->shift, $attendance->attendance_out, 'OUT') : null;
            $data[]             = $attendance;
        }
        return response()->json([
            'draw'              => $request->draw,
            'recordsTotal'      => $recordsTotal,
            'recordsFiltered'   => $recordsTotal,
            'data'              => $data
        ], 200);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $employee_id        = Auth::guard('admin')->user();
        $attendanceToday    = Attendance::getByEmployee($employee_id->employee_id)->date(date('Y-m-d'))->first();
        return view('admin.attendance.index', compact('attendanceToday', 'employee_id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param string $backdate
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request, string $backdate = 'NO')
    {
        if (in_array('create', $request->actionmenu)) {
            $employee_id    = Auth::guard('admin')->user();
            $employee       = Employee::with(['workingshift'])->find($employee_id->employee_id);
            if ($employee) {
                return view('admin.attendance.create', compact('employee', 'backdate'));
            } else {
                abort(403);
            }
        } else {
            abort(403);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator      = Validator::make($request->all(), [
            'type'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        try {
            $time   = Carbon::now();
            $sameAttendance = Attendance::date($time->toDateTime())->getByEmployee($request->employee_id)->first();
            if ($sameAttendance) {
                return response()->json([
                    'status'        => false,
                    'message'       => 'You already fill attendance form today. Please update the existing data.'
                ], 400);
            }
            $create     = Attendance::create([
                'employee_id'       => $request->employee_id,
                'attendance_date'   => date('Y-m-d'),
                'attendance_in'     => $request->type == 'in' ? $time->toDateTime() : null,
                'attendance_out'    => $request->type == 'out' ? $time->toDateTime() : null,
                'status'            => 'WAITING',
                'remarks'           => $request->description,
                'working_shift_id'  => $request->working_shift_id ? $request->working_shift_id : $request->shift,
                'day'               => date('D'),
                'day_work'          => Employee::find($request->employee_id)->shift_type == 'hourly' ? 1 : null,
            ]);

            if ($create) {
                $log    = AttendanceLog::create([
                    'attendance_id'     => $create->id,
                    'employee_id'       => $request->employee_id,
                    'attendance'        => $time->toDateTime(),
                    'type'              => $request->type == 'in' ? 'IN' : 'OUT',
                ]);
                if (!$log) {
                    DB::rollBack();
                    return response()->json([
                        'status'    => false,
                        'message'   => "Error create data $log"
                    ], 400);
                }
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => "Error create data {$ex->errorInfo[2]}"
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status'    => true,
            'results'   => route('attendance.index'),
        ], 200);
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (in_array('update', $request->actionmenu)) {
            $attendance     = Attendance::with(['employee'])->find($id);
            // dd((Auth::guard('admin')->user()->id) && in_array('approval', $request->actionmenu));
            if ($attendance) {
                $countDayDiff   = Carbon::parse($attendance->attendance_date)->diffInDays(Carbon::now());
                return view('admin.attendance.edit', compact('attendance', 'countDayDiff'));
            } else {
                abort(404);
            }
        } else {
            abort(403);
        }
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
        $validator      = Validator::make($request->all(), [
            'employee_id'       => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        if ($request->status) {
            try {
                $attendance     = Attendance::find($id);
                $shift          = WorkingShift::find($request->shift);
                $dayWork        = 0;

                $attendance->attendance_in      = $request->check_in;
                $attendance->attendance_out     = $request->check_out;
                $attendance->working_time       = $this->countWorkingTime($attendance->attendance_in, $attendance->attendance_out) >= 8 ? 8 : $this->countWorkingTime($attendance->attendance_in, $attendance->attendance_out);
                $attendance->over_time          = ($this->countWorkingTime($attendance->attendance_in, $attendance->attendance_out) - 8) >= 0 ? $this->countWorkingTime($attendance->attendance_in, $attendance->attendance_out) - 8 : 0;
                $attendance->working_shift_id   = $attendance->employee->shift_type == 'hourly' ? null : $attendance->working_shift_id;
                $attendance->remarks            = $request->description;
                if (0 < $attendance->working_time && $attendance->working_time < 8) {
                    $dayWork        = 0.5;
                } elseif ($attendance->working_time >= 8) {
                    $dayWork        = 1;
                }
                if (($attendance->attendance_in && !$attendance->attendance_out) || (!$attendance->attendance_in && $attendance->attendance_out)) {
                    $dayWork    = null;
                }
                $attendance->day_work           = $dayWork;
                $attendance->save();

                if ($attendance) {
                    $attendanceInExist  = AttendanceLog::attendanceId($attendance->id)->employeeId($request->employee_id)->attendanceTime($request->check_in)->type('IN')->first();
                    $attendanceOutExist  = AttendanceLog::attendanceId($attendance->id)->employeeId($request->employee_id)->attendanceTime($request->check_out)->type('OUT')->first();
                    if ($request->check_in && !$attendanceInExist) {
                        $logIn      = AttendanceLog::create([
                            'attendance_id'     => $attendance->id,
                            'employee_id'       => $request->employee_id,
                            'attendance'        => $request->check_in,
                            'type'              => 'IN',
                        ]);
                    }

                    if ($request->check_out && !$attendanceOutExist) {
                        $logOut     = AttendanceLog::create([
                            'attendance_id'     => $attendance->id,
                            'employee_id'       => $request->employee_id,
                            'attendance'        => $request->check_out,
                            'type'              => 'OUT',
                        ]);
                    }
                }
            } catch (\Illuminate\Database\QueryException $ex) {
                DB::rollBack();
                return response()->json([
                    'status'    => false,
                    'message'   => "Error create data {$ex->errorInfo[2]}"
                ], 400);
            }
        } else {
            try {
                $time           = Carbon::now();
                $attendance     = Attendance::find($id);
                $shift          = WorkingShift::find($attendance->working_shift_id);
                $dayWork        = 0;
                
                $attendance->attendance_out = $time->toDateTime();
                $attendance->working_time   = $this->countWorkingTime($attendance->attendance_in, $attendance->attendance_out) >= 8 ? 8 : $this->countWorkingTime($attendance->attendance_in, $attendance->attendance_out);
                $attendance->over_time      = ($this->countWorkingTime($attendance->attendance_in, $attendance->attendance_out) - 8) >= 0 ? $this->countWorkingTime($attendance->attendance_in, $attendance->attendance_out) - 8 : 0;
                $attendance->working_shift_id   = $attendance->employee->shift_type == 'hourly' ? null : $attendance->working_shift_id;
                $attendance->remarks        = $request->description;
                $attendance->status         = 'APPROVED';
                if ($attendance->working_time >= 8) {
                    $dayWork        = 1;
                } elseif (0 < $attendance->working_time && $attendance->working_time < 8) {
                    $dayWork        = 0.5;
                }
                $attendance->day_work       = $dayWork;
                $attendance->save();
    
                if ($attendance) {
                    $log        = AttendanceLog::create([
                        'attendance_id'     => $attendance->id,
                        'employee_id'       => $request->employee_id,
                        'attendance'        => $attendance->attendance_out,
                        'type'              => 'OUT',
                    ]);
                }
            } catch (\Illuminate\Database\QueryException $ex) {
                DB::rollBack();
                return response()->json([
                    'status'    => false,
                    'message'   => "Error create data {$ex->errorInfo[2]}"
                ], 400);
            }
        }
        DB::commit();
        return response()->json([
            'status'    => true,
            'results'   => route('attendance.index'),
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $attendance = Attendance::find($id);
            $attendance->delete();
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json([
                'status'    => false,
                'message'   => "Error delete data {$ex->errorInfo[2]}",
            ], 400);
        }
        return response()->json([
            'status'    => true,
            'message'   => "Success delete data"
        ], 200);
    }

    /**
     * Check exist attendance header by date and employee id
     *
     * @param object $date
     * @param integer $employee_id
     * @return boolean
     */
    public function checkAttendanceByDate(object $date, int $employee_id)
    {
        $attendance = Attendance::date($date->toDate())->employee($employee_id)->first();

        if ($attendance) {
            return true;
        } else {
            return false;
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

    /**
     * Count Overtime function
     *
     * @param $workingTime
     * @param $shiftId
     * @return integer
     */
    public function countOvertime($workingTime, $shiftId)
    {
        if ($shiftId) {
            $totalShiftHour = WorkingShift::find($shiftId);
            if ($workingTime - $totalShiftHour->total_working_time > 0) {
                return $workingTime - $totalShiftHour->total_working_time;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function getDiffWithShift($shift, $attendance, $type = 'IN')
    {
        $attendanceHour     = Carbon::parse($attendance);
        $attendanceDate     = $attendanceHour->toDateString();
        $shifts             = '';

        if ($type == 'IN') {
            $shifts         = Carbon::parse($attendanceDate . ' ' . $shift->time_in);
        } else {
            $shifts         = Carbon::parse($attendanceDate . ' ' . $shift->time_out);
        }
        $diffInMinutes      = $attendanceHour->diff($shifts, false);
        $data       = [
            'shifts'        => $shifts->toDateTimeString(),
            'attendance_hour'   => $attendanceHour->toDateTimeString(),
            'diff_type'     => $attendanceHour->diffInSeconds($shifts, false) < 0 ? 'late' : 'early',
            'diff_format'   => $attendanceHour->diffInSeconds($shifts, false) < 0 ? $diffInMinutes->format('- %H:%I:%S') : $diffInMinutes->format('+ %H:%I:%S'),
        ];
        return $attendance ? $data : false;
    }

    public function generateHeaderWhenNotAttend()
    {
        $employees  = Employee::payrollYes()->whereDoesntHave('attendances', function(\Illuminate\Database\Eloquent\Builder $query) {
            $query->where('attendance_date', '=', date('Y-m-d'));
        })->get();

        DB::beginTransaction();
        foreach ($employees as $key => $employee) {
            try {
                Attendance::create([
                    'employee_id'       => $employee->id,
                    'attendance_date'   => date('Y-m-d'),
                    'status'            => 'APPROVED',
                    'remarks'           => 'Tidak Hadir',
                    'working_shift_id'  => $employee->working_shift_id ? $employee->working_shift_id : null,
                    'day'               => date('D'),
                    'day_work'          => 0,
                ]);
            } catch (\Illuminate\Database\QueryException $ex) {
                DB::rollBack();
                return response()->json([
                    'status'    => false,
                    'message'   => "Error delete data{$ex->errorInfo[2]}"
                ], 400);
            }
        }
                
        DB::commit();
        return response()->json([
            'status'    => true,
            'message'   => "Success create data",
        ], 200);
    }

    public function generateAttendanceAMonth($month, $year)
    {
        $from       = Carbon::createFromDate($year, $month, 01);
        $to         = Carbon::createFromDate($year, $month)->endOfMonth();
        $data = [];
        $date = $from;

        $employee_id        = Auth::guard('admin')->user()->employees;
        if (!$employee_id) {
            return response()->json([
                'status'    => false,
                'Message'   => "Error to generate attendance in month because employee for this user not found",
            ], 400);
        }
        
        while ($date < $to) {
            $checkAttendance    = Attendance::where('employee_id', $employee_id->id)->where('attendance_date', $date->format('Y-m-d'))->first();
            $data[]     = $checkAttendance;

            $in         = Carbon::create($date->year, $date->month, $date->day, rand(7, 10), rand(0, 59), rand(0, 59))->toDateTimeString();
            $out        = Carbon::create($date->year, $date->month, $date->day, rand(15, 17), rand(0, 59), rand(0, 59))->toDateTimeString();

            if (!$checkAttendance) {
                $dayWork   = 0;
                $attendance = Attendance::create([
                    'employee_id'       => $employee_id->id,
                    'attendance_date'   => $date->format('Y-m-d'),
                    'attendance_in'     => $in,
                    'attendance_out'    => $out,
                    'status'            => 'APPROVED',
                    'working_time'      => $this->countWorkingTime($in, $out),
                    'over_time'         => $this->countOvertime($this->countWorkingTime($in, $out), $employee_id->working_shift_id),
                    'working_shift_id'  => $employee_id->workingshift->id,
                    'day'               => $date->shortEnglishDayOfWeek,
                ]);
                if ($attendance->working_time >= $employee_id->workingshift->total_working_time) {
                    $dayWork        = 1;
                } else {
                    $dayWork        = 0.5;
                }
                $attendance->day_work   = $dayWork;
                $attendance->save();
            }
            $date->addDays(1);
        }

        return response()->json([
            'status'    => true,
            'message'   => "Attendance success been generate",
        ], 200);
    }
}