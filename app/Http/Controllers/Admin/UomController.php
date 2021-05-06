<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Uom;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class UomController extends Controller
{
    /**
     * Define default method when access this controller
     */
    function __construct() {
        $menu   = Menu::GetByRoute('uomcategory')->first();
        View::share('menu_name', $menu->menu_name);
        View::share('menu_active', url('admin/uomcategory'));
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
        $name       = $request->name;
        $category   = $request->category;
        $type       = $request->type;

        $queryData  = Uom::with(['category'])->GetByName($name);
        if ($category) {
            $queryData->GetByCategory($category);
        }
        if ($type) {
            $queryData->GetByType($type);
        }

        $row        = clone $queryData;
        $recordsTotal   = $row->count();

        $queryData->orderBy($sort, $dir);
        $queryData->paginate($length);
        $uoms       = $queryData->get();

        $data       = [];
        $type       = config('enums.uom_type');
        foreach ($uoms as $key => $uom) {
            $uom->no    = ++$start;
            $uom->type  = $type[$uom->type];
            $data[]     = $uom;
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
        $query          = Uom::GetByName($name);

        $row            = clone $query;
        $recordsTotal   = $row->count();

        $query->paginate($length);
        $uoms       = $query->get();

        $data           = [];
        foreach ($uoms as $key => $uom) {
            $uom->no    = ++$start;
            $data[]     = $uom;
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
        return view('admin.uom.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $name
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (in_array('create', $request->actionmenu)) {
            return view('admin.uom.create');
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
            'name'          => 'required',
            'category'      => 'required',
            'type'          => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        try {
            Uom::create([
                'name'              => $request->name,
                'uom_category_id'   => $request->category,
                'type'              => $request->type,
                'ratio'             => $request->ratio
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
            'results'   => route('uom.index'),
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
     * @param \Illuminate\Http\Request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (in_array('update', $request->actionmenu)) {
            $uom    = Uom::with(['category'])->find($id);
            if ($uom) {
                return view('admin.uom.edit', compact('uom'));
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
            'name'              => 'required',
            'category'          => 'required',
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
            $uom    = Uom::find($id);
            $uom->name              = $request->name;
            $uom->uom_category_id   = $request->category;
            $uom->type              = $request->type;
            $uom->save();
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
            'results'   => route('uom.index'),
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
            $uom    = Uom::find($id);
            $uom->delete();
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
}