<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\UnitCode;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class UnitCodeController extends Controller
{
    function __construct() {
        $menu       = Menu::GetByRoute('unitcode')->first();
        $parent     = Menu::parent($menu->parent_id)->first();
        View::share('menu_name', $menu->menu_name);
        View::share('parent_name', $parent->menu_name);
        View::share('menu_active', url('admin/unitcode'));
        $this->middleware('accessmenu', ['except' => ['select']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (in_array('create', $request->actionmenu)) {
            return view('admin.unitcode.index');
        }
        abort(403);
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
            return view('admin.unitcode.create');
        }
        abort(403);
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
            'code'  => 'required',
            'name'  => 'required',
            'organization_id'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        try {
            $data   = [
                'code'  => $request->code,
                'name'  => $request->name,
                'organization_code_id'  => $request->organization_id,
            ];
            $unitcode   = UnitCode::create($data);
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => "Error create data: {$ex->errorInfo[2]}",
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status'    => true,
            'message'   => "Success create data",
            'results'   => route('unitcode.index'),
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
     * @param \Illuminate\Http\Request $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (in_array('create', $request->actionmenu)) {
            $unitcode   = UnitCode::find($id);
            if ($unitcode) {
                return view('admin.unitcode.edit', compact('unitcode'));
            }
            abort(404);
        }
        abort(403);
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
            'code'  => 'required',
            'name'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        try {
            $unitcode   = UnitCode::findOrFail($id);
            $unitcode->code     = $request->code;
            $unitcode->name     = $request->name;
            $unitcode->organization_code_id     = $request->organization_id;
            $unitcode->save();
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => "Error update data: {$ex->errorInfo[2]}",
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status'    => true,
            'message'   => "Success update data",
            'results'   => route('unitcode.index'),
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
            $unitcode   = UnitCode::destroy($id);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json([
                'status'    => false,
                'message'   => "Error delete data: {$ex->errorInfo[2]}"
            ], 400);
        }
        return response()->json([
            'status'    => true,
            'message'   => "Success delete data"
        ], 200);
    }

    /**
     * Method to get data to show in database
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
        $code           = strtoupper($request->code);
        $name           = strtoupper($request->name);
        $organization_id= $request->organization_id;

        // Query Data
        $queryData      = UnitCode::with(['organization'])->whereRaw("upper(code) like '%$code%'")->whereRaw("upper(name) like '%$name%'");
        if ($organization_id) {
            $queryData->where('organization_code_id', $organization_id);
        }

        $row            = clone $queryData;
        $recordsTotal   = $row->count();

        $queryData->offset($start);
        $queryData->limit($length);
        $queryData->orderBy($sort, $dir);
        $units      = $queryData->get();

        $data           = [];
        foreach ($units as $key => $unit) {
            $unit->no   = ++$start;
            $data[]         = $unit;
        }
        return response()->json([
            'draw'              => $request->draw,
            'recordsTotal'      => $recordsTotal,
            'recordsFiltered'   => $recordsTotal,
            'data'              => $data,
        ], 200);
    }

    /**
     * Method to get data to show in select2
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function select(Request $request)
    {
        $start  = $request->page ? $request->page - 1 : 0;
        $length = $request->limit;
        $name   = strtoupper($request->name);

        // Count Data
        $query  = UnitCode::whereRaw("upper(name) like '%$name%'");

        $row    = clone $query;
        $recordsTotal   = $row->count();

        $query->offset($start);
        $query->limit($length);
        $organizations  = $query->get();

        $data = [];
        foreach ($organizations as $organization) {
            $organization->no   = ++$start;
            $data[]         = $organization;
        }
        return response()->json([
            'total'     => $recordsTotal,
            'rows'      => $data,
        ], 200);
    }
}
