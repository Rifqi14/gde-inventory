<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Calendar;
use App\Models\Menu;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class CalendarController extends Controller
{
    /**
     * Define default access to controller
     */
    public function __construct() {
        $menu_name  = Menu::where('menu_route', 'calendar')->first();
        View::share('menu_name', $menu_name->menu_name);
        View::share('menu_active', url('admin/calendar'));
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.calendar.index');
    }

    /**
     * Get data to display in table list with pagination
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
        $name           = strtoupper($request->name);
        $code           = strtoupper($request->code);
        $description    = strtoupper($request->description);
        $period         = Carbon::parse($request->period);

        // Count Data
        $queryData      = Calendar::with(['exceptions' => function ($q) use ($period) {
            if ($period) {
                $q->whereMonth('date_exception', $period)->whereYear('date_exception', $period);
            }
        }])->GetByName($name)->GetByCode($code);
        if ($description) {
            $queryData->GetByDescription($description);
        }
        $recordsTotal   = $queryData->get()->count();

        // Select Pagination
        $queryData      = Calendar::with(['exceptions' => function ($q) use ($period) {
            if ($period) {
                $q->whereMonth('date_exception', $period)->whereYear('date_exception', $period);
            }
        }])->GetByName($name)->GetByCode($code);
        if ($description) {
            $queryData->GetByDescription($description);
        }
        $queryData->paginate($length);
        $queryData->orderBy($sort, $dir);
        $calendars      = $queryData->get();

        $data           = [];
        foreach ($calendars as $key => $calendar) {
            $calendar->no   = ++$start;
            $calendar->day_month    = Carbon::now()->daysInMonth;
            $calendar->workday      = $calendar->exceptions ? $calendar->day_month - $calendar->exceptions->count() : 0;
            $data[]         = $calendar;
        }
        return response()->json([
            'draw'              => $request->draw,
            'recordsTotal'      => $recordsTotal,
            'recordsFiltered'   => $recordsTotal,
            'data'              => $data
        ], 200);
    }

    /**
     * Gat data to display in combo box
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function select(Request $request)
    {
        $start          = $request->page ? $request->page - 1 : 0;
        $length         = $request->limit;
        $name           = strtoupper($request->name);
        $workday        = $request->workday ? $request->workday : false;

        // Count Data
        $queryData      = Calendar::with(['exceptions' => function ($q) use ($workday) {
            if ($workday) {
                $q->whereMonth('date_exception', Carbon::now())->whereYear('date_exception', Carbon::now());
            }
        }])->GetByName($name);
        $recordsTotal   = $queryData->get()->count();

        // Select Pagination
        $queryData      = Calendar::with(['exceptions' => function ($q) use ($workday) {
            if ($workday) {
                $q->whereMonth('date_exception', Carbon::now())->whereYear('date_exception', Carbon::now());
            }
        }])->GetByName($name);
        $queryData->paginate($length);
        $queryData->orderBy('created_at', 'desc');
        $calendars      = $queryData->get();

        $data           = [];
        foreach ($calendars as $key => $calendar) {
            $calendar->no   = ++$start;
            $calendar->day_month    = Carbon::now()->daysInMonth;
            $calendar->workday      = $calendar->exceptions ? $calendar->day_month - $calendar->exceptions->count() : 0;
            $data[]         = $calendar;
        }
        return response()->json([
            'total'     => $recordsTotal,
            'rows'      => $data,
        ], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.calendar.create');
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
            'code'          => 'required|alpha_dash|unique:calendars',
            'name'          => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        try {
            Calendar::create([
                'code'          => $request->code,
                'name'          => $request->name,
                'description'   => $request->description,
                'is_default'    => $request->is_default ? 'YES' : 'NO'
            ]);
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
            'results'   => route('calendar.index')
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
        try {
            $calendar   = Calendar::with(['exceptions'])->find($id);
            return view('admin.calendar.detail', compact('calendar'));
        } catch (\Illuminate\Database\QueryException $ex) {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $calendar   = Calendar::with(['exceptions'])->find($id);
            return view('admin.calendar.edit', compact('calendar'));
        } catch (\Illuminate\Database\QueryException $ex) {
            abort(404);
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
            'code'          => 'required|alpha_dash',
            'name'          => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        try {
            $calendar               = Calendar::findOrFail($id);
            $calendar->code         = $request->code;
            $calendar->name         = $request->name;
            $calendar->description  = $request->description;
            $calendar->is_default   = $request->is_default ? 'YES' : 'NO';
            $calendar->save();
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => "Error update data {$ex->errorInfo[2]}"
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status'    => true,
            'results'   => route('calendar.index')
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
            $calendar   = Calendar::find($id);
            $calendar->delete();
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json([
                'status'    => false,
                'message'   => 'Error delete data ' . $ex->errorInfo[2]
            ], 400);
        }
        return response()->json([
            'status'    => true,
            'message'   => 'Success delete data'
        ], 200);
    }
}