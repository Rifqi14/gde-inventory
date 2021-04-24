<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\CalendarException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class CalendarExceptionController extends Controller
{
    /**
     * Define default function who is access first when call this controller
     */
    public function __construct() {
        View::share('menu_active', url('admin/calendarexception'));
    }

    public function read(Request $request)
    {
        $start          = $request->start;
        $length         = $request->length;
        $query          = $request->search['value'];
        $sort           = $request->columns[$request->order[0]['column']]['data'];
        $dir            = $request->order[0]['dir'];
        $calendar_id    = $request->calendar_id;
        $description    = strtoupper($request->description);
        $date           = $request->date;

        // Count Data
        $queryData      = CalendarException::where('calendar_id', $calendar_id)->whereRaw("upper(description) like '%$description%'");
        $recordsTotal   = $queryData->get()->count();

        // Select Pagination
        $queryData      = CalendarException::where('calendar_id', $calendar_id)->whereRaw("upper(description) like '%$description%'");
        $queryData->offset($start);
        $queryData->limit($length);
        $queryData->orderBy('date_exception', 'asc');
        $calendarExceptions = $queryData->get();

        $data = [];
        foreach ($calendarExceptions as $key => $calendarException) {
            $calendarException->no = ++$start;
            $calendarException->date_exception = changeDateFormat('d-m-Y', $calendarException->date_exception);
            $data[] = $calendarException;
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
     * Function to change date format to DB format
     *
     * @param date $date
     * @return Date
     */
    public function dbDate($date)
    {
        $date   = str_replace('/', '-', $date);
        return date('Y-m-d', strtotime($date));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $format = 'Y-m-d';
        $validator  = Validator::make($request->all(), [
            'description'   => 'required',
            'reccurence_day'=> 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        $this->validateCheckedDay($request);
        $this->checkExceedFinishDate($request);

        DB::beginTransaction();
        $start_range    = dbDate($request->start_range);
        $finish_range   = dbDate($request->finish_range);
        if ($request->reccurence_day == 'reccurence_day' && !isset($request->day)) {
            $dateRanges = getDatesFromRange($start_range, $finish_range, $format, '1 day');
            foreach ($dateRanges as $key => $dateRange) {
                $exception = CalendarException::create([
                    'calendar_id'       => $request->calendar_id,
                    'date_exception'    => $dateRange,
                    'description'       => $request->description,
                    'label_color'       => $request->label_color,
                    'text_color'        => $request->text_color,
                    'day'               => changeDateFormat('D', $dateRange),
                ]);
                if (!$exception) {
                    DB::rollBack();
                    $results['status']  = false;
                    $results['message'] = 'Error add data';
                } else {
                    $results['status']  = false;
                    $results['message'] = 'Error add data';
                }
            }
            if ($results['status'] == true) {
                DB::commit();
                return response()->json($results, 200);
            } else {
                return response()->json($results, 400);
            }
        }

        if ($request->reccurence_day == 'reccurence_day' && isset($request->day)) {
            $dateRanges = getDatesFromRange($start_range, $finish_range, $format, '1 weeks');
            foreach ($dateRanges as $key => $week) {
                $weekName   = changeDateFormat('w', $week);

                $days       = $request->day;
                foreach ($days as $key => $day) {
                    if ($day == 'sunday') {
                        $date = date('Y-m-d', strtotime(0 - $weekName. " day", strtotime($week))); //Sunday
                    }
                    if ($day == 'monday') {
                        $date = date('Y-m-d', strtotime(1 - $weekName. " day", strtotime($week))); //Monday
                    }
                    if ($day == 'tuesday') {
                        $date = date('Y-m-d', strtotime(2 - $weekName. " day", strtotime($week))); //Thursday
                    }
                    if ($day == 'wednesday') {
                        $date = date('Y-m-d', strtotime(3 - $weekName. " day", strtotime($week))); //Wednesday
                    }
                    if ($day == 'thursday') {
                        $date = date('Y-m-d', strtotime(4 - $weekName. " day", strtotime($week))); //Tuesday
                    }
                    if ($day == 'friday') {
                        $date = date('Y-m-d', strtotime(5 - $weekName. " day", strtotime($week))); //Friday
                    }
                    if ($day == 'saturday') {
                        $date = date('Y-m-d', strtotime(6 - $weekName. " day", strtotime($week))); //Saturday
                    }

                    if ($date >= $start_range && $date <= $finish_range) {
                        $exception  = CalendarException::create([
                            'calendar_id'       => $request->calendar_id,
                            'date_exception'    => $date,
                            'description'       => $request->description,
                            'label_color'       => $request->label_color,
                            'text_color'        => $request->text_color,
                            'day'               => changeDateFormat('D', $date),
                        ]);

                        if (!$exception) {
                            DB::rollBack();
                            $results['status']  = false;
                            $results['message'] = "Error add data";
                        } else {
                            $results['status']  = true;
                            $results['message'] = "Success add data";
                        }
                    }
                }
            }
            if ($results['status'] == true) {
                DB::commit();
                return response()->json($results, 200);
            } else {
                return response()->json($results, 400);
            }
        }

        if ($request->reccurence_day == 'specific_day') {
            $exc_date = date('m-d', strtotime(dbDate($request->specific_date)));
            $start_year = date('Y', strtotime(dbDate($request->start_specific)));

            $start = date("Y-m-d", strtotime($start_year . '-' . $exc_date));
            $end   = date("Y-m-d", strtotime(dbDate($request->finish_specific)));
            $period = getDatesFromRange($start, $end, $format, "1 years");

            foreach ($period as $key => $year) {
                $exception = CalendarException::create([
                    'calendar_id'   => $request->calendar_id,
                    'date_exception'=> $year,
                    'description'   => $request->description,
                    'label_color'   => $request->label_color,
                    'text_color'    => $request->text_color,
                    'day'           => date('D', strtotime($year))
                ]);

                if (!$exception) {
                    DB::rollBack();
                    $results['status'] = false;
                    $results['message'] = 'Wrong';
                } else {
                    $results['status'] = true;
                    $results['message'] = 'Success add data';
                };
            }

            if ($results['status'] == true) {
                DB::commit();
                return response()->json($results, 200);
            } elseif ($results['status'] == false) {
                return response()->json($results, 400);
            }
        }
    }

    /**
     * To validated checked day when recurrence day selected
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function validateCheckedDay(Request $request)
    {
        if (!isset($request->reccurence_day) && isset($request->day)) {
            return response()->json([
                'status'    => false,
                'message'   => 'Please check recurrence if you want to create exceptions by days'
            ], 400);
        }
    }

    /**
     * To check exceed finish date
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function checkExceedFinishDate(Request $request)
    {
        if ($this->dbDate($request->start_range) > $this->dbDate($request->finish_range) || $this->dbDate($request->start_specific) > $this->dbDate($request->finish_specific)) {
            return response()->json([
                'status'    => false,
                'message'   => "Start date exceeds finish date"
            ], 400);
        }
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
        $calendarExc = CalendarException::with(['calendar'])->find($id);
        return response()->json([
            'status'    => true,
            'data'      => $calendarExc
        ], 200);
    }

    public function calendar($id)
    {
        $calendars  = CalendarException::select('calendar_exceptions.description as description', 'calendar_exceptions.date_exception as start', 'calendar_exceptions.label_color as color', 'calendar_exceptions.text_color as textColor')->where('calendar_id', $id)->get();

        $data   = [];
        foreach ($calendars as $key => $calendar) {
            $calendar->title    = $calendar->description;
            $data[]             = $calendar;
        }
        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function addcalendar(Request $request)
    {
        $validator  = Validator::make($request->all(), [
            'calendar_desc_add'     => 'required',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        try {
            $calendar   = CalendarException::create([
                'calendar_id'       => $request->id_calendar,
                'date_exception'    => dbDate($request->calendar_date),
                'description'       => $request->calendar_desc_add,
                'day'               => changeDateFormat('D', $request->calendar_date),
                'label_color'       => $request->calendar_label,
                'text_color'        => $request->calendar_text,
            ]);
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => "Error add data {$ex->errorInfo[2]}"
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status'    => true,
            'message'   => "Success add data"
        ], 200);
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
        $validator  = Validator::make($request->all(), [
            'exception_date'    => 'required',
            'exception_desc'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        try {
            $exception  = CalendarException::find($id);
            $exception->date_exception      = dbDate($request->exception_date);
            $exception->description         = $request->exception_desc;
            $exception->label_color         = $request->exception_label;
            $exception->text_color          = $request->exception_text;
            $exception->save();
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => "Error edit data {$ex->errorInfo[2]}"
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status'    => true,
            'message'   => "Success edit data"
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
            $calendar = CalendarException::find($id);
            $calendar->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status'    => false,
                'message'   => 'Data has been used to another page'
            ], 400);
        }
        return response()->json([
            'status'    => true,
            'message'   => 'Success delete data'
        ], 200);
    }
}