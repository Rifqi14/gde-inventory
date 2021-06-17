<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\AttendanceRequest;
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
        $queryData      = AttendanceRequest::with(['attendances'])->getByAttendanceId($attendance_id);

        $row            = clone $queryData;
        $recordsTotal   = $row->count();

        $queryData->offset($start);
        $queryData->limit($length);
        $queryData->orderBy($sort, $dir);
        $attendanceRequests = $queryData->get();

        $data       = [];
        foreach ($attendanceRequests as $key => $attendanceRequest) {
            $attendanceRequest->no          = ++$start;
            $attendanceRequest->short_reason= (strlen(strip_tags($attendanceRequest->request_reason)) > 13) ? substr(strip_tags($attendanceRequest->request_reason), 0, 10) . '...' : $attendanceRequest->request_reason;
            $data[]                         = $attendanceRequest;
        }
        return response()->json([
            'draw'              => $request->draw,
            'recordsTotal'      => $recordsTotal,
            'recordsFiltered'   => $recordsTotal,
            'data'              => $data,
        ], 200);
    }
}