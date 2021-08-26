<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceLog;
use App\Models\AttendanceRequest;
use App\Models\WorkingShift;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AttendanceRequestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $validator      = Validator::make($request->all(), [
            'type_request'      => 'required',
            'request_reason'    => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        try {
            $revisionNumber     = $this->getLastRevisionNumber($request);
            $attendance         = Attendance::find($request->attendance_id);
            $type               = $request->type_request;
            $data               = [
                'attendance_id'     => $request->attendance_id,
                'request_reason'    => $request->request_reason,
                'type'              => $request->type_request,
                'revision_number'   => $revisionNumber,
                'status'            => 'WAITING',
            ];
            switch ($type) {
                case 'shift':
                    $data['working_shift_id']   = $request->shift_request;
                    $data['value_before']       = $attendance->working_shift_id;
                    break;
                case 'checkin':
                    $data['request_date']       = $request->attendance_in;
                    $data['value_before']       = $attendance->attendance_in;
                    break;
                case 'checkout':
                    $data['request_date']       = $request->attendance_out;
                    $data['value_before']       = $attendance->attendance_out;
                    break;
                
                default:
                    return false;
                    break;
            }
            AttendanceRequest::create($data);
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => "Error create data {$ex->errorInfo[2]}",
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status'        => true,
            'message'       => "Success create request data",
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
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $request    = AttendanceRequest::with(['requestshift'])->find($id);
        if ($request) {
            return response()->json([
                'status'    => true,
                'data'      => $request,
            ], 200);
        } else {
            return response()->json([
                'status'    => false,
                'data'      => "Sorry, you're not allowed to edit this data or this data has been not found",
            ], 400);
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
            'type_request'      => 'required',
            'request_reason'    => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        try {
            $attendanceRequest  = AttendanceRequest::find($id);
            $attendance         = Attendance::find($request->attendance_id);
            $type               = $request->type_request;
            $attendanceRequest->attendance_id   = $request->attendance_id;
            $attendanceRequest->request_reason  = $request->request_reason;
            $attendanceRequest->type            = $request->type_request;
            switch ($type) {
                case 'shift':
                    $attendanceRequest->working_shift_id   = $request->shift_request;
                    $attendanceRequest->value_before       = $attendance->working_shift_id;
                    break;
                case 'checkin':
                    $attendanceRequest->request_date       = $request->attendance_in;
                    $attendanceRequest->value_before       = $attendance->attendance_in;
                    break;
                case 'checkout':
                    $attendanceRequest->request_date       = $request->attendance_out;
                    $attendanceRequest->value_before       = $attendance->attendance_out;
                    break;
                
                default:
                    return false;
                    break;
            }
            $attendanceRequest->save();
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => "Error create data {$ex->errorInfo[2]}",
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status'        => true,
            'message'       => "Success create request data",
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
        //
    }

    /**
     * Function to get latest revision number and add + 1
     *
     * @param \Illuminate\Http\Request $request
     * @return int
     */
    public function getLastRevisionNumber(Request $request)
    {
        $attendanceId       = $request->attendance_id;

        $attendanceRequest  = AttendanceRequest::getByAttendanceId($attendanceId)->first();

        return $attendanceRequest ? $attendanceRequest->max('revision_number') + 1 : 1;
    }

    /**
     * Undocumented function
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function read(Request $request)
    {
        $start          = $request->start;
        $length         = $request->length;
        $query          = $request->search['value'];
        $sort           = $request->columns[$request->order[0]['column']]['data'];
        $dir            = $request->order[0]['dir'];
        $attendance_id  = $request->attendance_id;

        // Query Data
        $queryData      = AttendanceRequest::with(['attendances', 'attendances.shift', 'requestshift'])->getByAttendanceId($attendance_id);

        $row            = clone $queryData;
        $recordsTotal   = $row->count();

        $queryData->offset($start);
        $queryData->limit($length);
        $queryData->orderBy($sort, $dir);
        $attendanceRequests = $queryData->get();

        $data       = [];
        foreach ($attendanceRequests as $key => $attendanceRequest) {
            $attendanceRequest->no          = ++$start;
            $attendanceRequest->short_reason= (strlen(strip_tags($attendanceRequest->request_reason)) > 13) ? substr(strip_tags($attendanceRequest->request_reason), 0, 10) . '...' : strip_tags($attendanceRequest->request_reason);
            $data[]                         = $attendanceRequest;
        }
        return response()->json([
            'draw'              => $request->draw,
            'recordsTotal'      => $recordsTotal,
            'recordsFiltered'   => $recordsTotal,
            'data'              => $data,
        ], 200);
    }
    
    public function approve(Request $request)
    {
        $validator      = Validator::make($request->all(), [
            'type'  => 'required',
            'id'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        try {
            $attendanceRequest  = AttendanceRequest::find($request->id);
            $attendances        = Attendance::find($attendanceRequest->attendance_id);

            if ($request->type == 'APPROVED') {
                $attendanceRequest->status  = $request->type;
                
                switch ($attendanceRequest->type) {
                    case 'checkin':
                        $attendances->attendance_in = $attendanceRequest->request_date;
                        $attendances->working_time  = $this->countWorkingTime($attendances->attendance_in, $attendances->attendance_out);
                        $attendances->over_time     = $this->countOvertime($attendances->working_time, $attendances->working_shift_id);
                        $type       = $attendanceRequest->type;
                        $value      = $attendanceRequest->request_date;
                        AttendanceLog::create([
                            'attendance_id'     => $attendances->id,
                            'employee_id'       => $attendances->employee_id,
                            'attendance'        => $attendanceRequest->request_date,
                            'type'              => 'IN',
                        ]);
                        break;
                    case 'checkout':
                        $attendances->attendance_out = $attendanceRequest->request_date ;
                        $attendances->working_time  = $this->countWorkingTime($attendances->attendance_in, $attendances->attendance_out);
                        $attendances->over_time     = $this->countOvertime($attendances->working_time, $attendances->working_shift_id);
                        $type       = $attendanceRequest->type;
                        $value      = $attendanceRequest->request_date;
                        AttendanceLog::create([
                            'attendance_id'     => $attendances->id,
                            'employee_id'       => $attendances->employee_id,
                            'attendance'        => $attendanceRequest->request_date,
                            'type'              => 'OUT',
                        ]);
                        break;
                    case 'shift':
                        $attendances->working_shift_id = $attendanceRequest->working_shift_id;
                        $attendances->working_time  = $this->countWorkingTime($attendances->attendance_in, $attendances->attendance_out);
                        $attendances->over_time     = $this->countOvertime($attendances->working_time, $attendances->working_shift_id);
                        $shift              = WorkingShift::find($attendances->working_shift_id);
                        $type       = $attendanceRequest->type;
                        $value      = [
                            'id'        => $shift->id,
                            'text'      => $shift->shift_name,
                            'time_in'   => $shift->time_in,
                            'time_out'  => $shift->time_out,
                        ];
                        break;
                    
                    default:
                        DB::rollBack();
                        return response()->json([
                            'status'    => false,
                            'message'   => "Type request not defined",
                        ], 400);
                        break;
                }
                $attendances->save();
            } else {
                $attendanceRequest->status  = $request->type;
            }
            $attendanceRequest->save();
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $request->type == 'APPROVED' ? "Error approved data {$ex->errorInfo[2]}" : "Error rejected data {$ex->errorInfo[2]}",
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status'    => true,
            'message'   => $request->type == 'APPROVED' ? "Success approved data" : "Success rejected data",
            'type'      => $type,
            'value'     => $value,
        ], 200);
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
}