<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceLog;
use App\Models\Employee;
use App\Models\Menu;
use App\Models\WorkingShift;
use Carbon\Carbon;
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
        $date           = $request->date;
        $employee       = $request->employee;
        $status         = $request->status;

        // Query Data
        $queryData      = Attendance::with(['employee', 'shift']);
        if ($status) {
            $queryData->status($status);
        }
        if ($date) {
            $queryData->date($date);
        }
        if ($employee) {
            $queryData->employee($employee);
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
            $attendance->diff_in    = $this->getDiffWithShift($attendance->shift, $attendance->attendance_in);
            $attendance->diff_out   = $this->getDiffWithShift($attendance->shift, $attendance->attendance_out);
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
        return view('admin.attendance.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (in_array('create', $request->actionmenu)) {
            $employee_id    = Auth::guard('admin')->user();
            $employee       = Employee::find($employee_id->employee_id);
            if ($employee) {
                return view('admin.attendance.create', compact('employee'));
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
            'shift' => 'required',
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
            $sameAttendance = Attendance::date($time->toDateTime())->employee($request->employee_id)->first();
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
                'working_shift_id'  => $request->shift,
                'day'               => date('D'),
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
        try {
            $time           = Carbon::now();
            $attendance     = Attendance::find($id);
            $shift          = WorkingShift::find($attendance->working_shift_id);
            $attendance->attendance_out = $time->toDateTime();
            $attendance->working_time   = $this->countWorkingTime($attendance->attendance_in, $attendance->attendance_out);
            $attendance->over_time      = $this->countOvertime($attendance->working_time, $attendance->working_shift_id);
            $attendance->remarks        = $request->description;
            $attendance->day_work       = ($attendance->working_time >= $shift->total_working_time) ? 1 : 0.5;
            if ($request->status == 'approved') {
                $attendance->status     = 'APPROVED';
            }
            $attendance->save();

            if ($attendance) {
                $log        = AttendanceLog::create([
                    'attendance_id'     => $attendance->id,
                    'employee_id'       => $request->employee_id,
                    'attendance'        => $time->toDateTime(),
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
            $shifts         = Carbon::parse($shift->time_out);
        }
        $diffInMinutes      = $attendanceHour->diff($shifts, false);
        $data       = [
            'diff_type'     => $attendanceHour->diffInSeconds($shifts, false) < 0 ? 'late' : 'early',
            'diff_format'   => $attendanceHour->diffInSeconds($shifts, false) < 0 ? $diffInMinutes->format('- %H:%I:%S') : $diffInMinutes->format('+ %H:%I:%S'),
        ];
        return $attendance ? $data : false;
    }
}