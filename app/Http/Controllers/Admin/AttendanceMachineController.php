<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AttendanceMachine;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class AttendanceMachineController extends Controller
{
    /**
     * Define default method when access this controller
     */
    public function __construct() {
        $menu   = Menu::where('menu_route', 'attendancemachine')->first();
        View::share('menu_name', $menu->menu_name);
        View::share('menu_active', url('admin/attendancemachine'));
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
        $start      = $request->start;
        $length     = $request->length;
        $query      = $request->search['value'];
        $sort       = $request->columns[$request->order[0]['column']]['data'];
        $dir        = $request->order[0]['dir'];
        $name       = strtoupper($request->machine_name);
        $type       = $request->type;

        // Query Data
        $queryData  = AttendanceMachine::GetByName($name);
        if ($type) {
            $queryData->GetByType($type);
        }

        $row    = clone $queryData;
        $recordsTotal   = $row->count();

        $queryData->offset($start);
        $queryData->limit($length);
        $queryData->orderBy($sort, $dir);
        $machines   = $queryData->get();

        $data   = [];
        foreach ($machines as $key => $machine) {
            $machine->no    = ++$start;
            $data[]         = $machine;
        }
        return response()->json([
            'draw'              => $request->draw,
            'recordsTotal'      => $recordsTotal,
            'recordsFiltered'   => $recordsTotal,
            'data'              => $data
        ], 200);
    }

    /**
     * Define method to get data and show in dropdown select2
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function select(Request $request)
    {
        $start          = $request->page ? $request->page - 1 : 0;
        $length         = $request->limit;
        $name           = strtoupper($request->name);

        // Query
        $query          = AttendanceMachine::GetByName($name);

        $row            = clone $query;
        $recordsTotal   = $row->count();

        $query->offset($start);
        $query->limit($length);
        $machines       = $query->get();

        $data           = [];
        foreach ($machines as $key => $machine) {
            $machine->no    = ++$start;
            $data[]         = $machine;
        }
        return response()->json([
            'total'     => $recordsTotal,
            'rows'      => $data,
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.attendancemachine.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Show the form for creating a new resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (in_array('create', $request->actionmenu)) {
            return view('admin.attendancemachine.create');
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
        $validator  = Validator::make($request->all(), [
            'machine_name'      => 'required',
            'type'              => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        try {
            $machine    = AttendanceMachine::create([
                'machine_name'      => $request->machine_name,
                'type'              => $request->type,
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
            'results'   => route('attendancemachine.index'),
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
     * @param Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (in_array('update', $request->actionmenu)) {
            $machine    = AttendanceMachine::find($id);
            if ($machine) {
                return view('admin.attendancemachine.edit', compact('machine'));
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
        $validator  = Validator::make($request->all(), [
            'machine_name'      => 'required',
            'type'              => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }
        
        DB::beginTransaction();
        try {
            $machine    = AttendanceMachine::find($id);
            $machine->machine_name  = $request->machine_name;
            $machine->type          = $request->type;
            $machine->save();
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => "Error create data {$ex->errorInfo[2]}",
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status'    => true,
            'results'   => route('attendancemachine.index'),
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
            $machine    = AttendanceMachine::find($id);
            $machine->delete();
        } catch (\illuminate\Database\QueryException $ex) {
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
}