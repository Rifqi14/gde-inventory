<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Area;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class AreaController extends Controller
{
    protected $actionmenu;
    function __construct() {
        $menu               = Menu::GetByRoute('area')->first();
        $parent             = Menu::parent($menu->parent_id)->first();
        View::share('menu_name', $menu->menu_name);
        View::share('parent_name', $parent->menu_name);
        View::share('menu_active', url('admin/area'));
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
        $this->actionmenu   = $request->actionmenu;
        if (in_array('read', $request->actionmenu)) {
            return view('admin.area.index');
        } else {
            abort(403);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (in_array('create', $request->actionmenu)) {
            return view('admin.area.create');
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
            'site_id'   => 'required',
            'area'      => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        try {
            $data       = [
                'name'      => $request->area,
                'site_id'   => $request->site_id,
                'remark'    => $request->remark,
            ];
            $area       = Area::create($data);
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
            'results'   => route('area.index'),
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
    public function edit(Request $request, $id)
    {
        if (in_array('update', $request->actionmenu)) {
            $area       = Area::find($id);
            if ($area) {
                return view('admin.area.edit', compact('area'));
            }
            abort(404);
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
            'site_id'   => 'required',
            'area'      => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        try {
            $area           = Area::findOrFail($id);
            $area->name     = $request->area;
            $area->site_id  = $request->site_id;
            $area->remark   = $request->remark;
            $area->save();
        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => "Error update data: {$th->errorInfo[2]}",
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status'    => true,
            'message'   => "Success update data",
            'results'   => route('area.index'),
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
            $area   = Area::destroy($id);
        } catch (\Illuminate\Database\QueryException $th) {
            return response()->json([
                'status'    => false,
                'message'   => "Error delete data {$th->errorInfo[2]}",
            ], 400);
        }
        return response()->json([
            'status'    => true,
            'message'   => "Success delete data"
        ], 200);
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
        $area           = strtoupper($request->area);
        $site_id        = $request->site_id;

        // Query Data
        $queryData      = Area::with(['site'])->whereRaw("upper(name) like '%$area%'");
        if ($site_id) {
            $queryData->where('site_id', $site_id);
        }

        $row            = clone $queryData;
        $recordsTotal   = $row->count();

        $queryData->offset($start);
        $queryData->limit($length);
        $queryData->orderBy($sort, $dir);
        $areas          = $queryData->get();

        $data           = [];
        foreach ($areas as $key => $area) {
            $area->no       = ++$start;
            $data[]         = $area;
        }
        return response()->json([
            'draw'              => $request->draw,
            'recordsTotal'      => $recordsTotal,
            'recordsFiltered'   => $recordsTotal,
            'data'              => $data,
        ], 200);
    }

    /**
     * Define method to get data and show in select2
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function select(Request $request)
    {
        $start  = $request->page ? $request->page - 1 : 0;
        $length = $request->limit;
        $name   = strtoupper($request->name);
        $site_id= $request->site_id;

        // Count Data
        $query  = Area::with(['site'])->whereRaw("upper(name) like '%$name%'");
        if ($site_id) {
            $query->where('site_id', $site_id);
        }

        $row    = clone $query;
        $recordsTotal   = $row->count();

        $query->offset($start);
        $query->limit($length);
        $areas  = $query->get();

        $data = [];
        foreach ($areas as $area) {
            $area->no = ++$start;
            $data[] = $area;
        }
        return response()->json([
            'total'     => $recordsTotal,
            'rows'      => $data,
        ], 200);
    }
}
