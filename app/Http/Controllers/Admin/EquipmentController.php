<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Equipment;
use App\Models\Menu;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class EquipmentController extends Controller
{
    function __construct() {
        $menu               = Menu::GetByRoute('equipment')->first();
        $parent             = Menu::parent($menu->parent_id)->first();
        View::share('menu_name', $menu->menu_name);
        View::share('parent_name', $parent->menu_name);
        View::share('menu_active', url('admin/equipment'));
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
        if (in_array('read', $request->actionmenu)) {
            return view('admin.equipment.index');
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
            return view('admin.equipment.create');
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
            'area_id'       => 'required',
            'equipment_name'=> 'required',
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
                'equipment_name'    => $request->equipment_name,
                'area_id'           => $request->area_id,
                'remark'            => $request->remark,
            ];
            $area       = Equipment::create($data);
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
            'results'   => route('equipment.index'),
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
            $equipment  = Equipment::with(['area', 'area.site'])->findOrFail($id);
            if ($equipment) {
                return view('admin.equipment.edit', compact('equipment'));
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
            'area_id'       => 'required',
            'equipment_name'=> 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        try {
            $equipment                  = Equipment::findOrFail($id);
            $equipment->equipment_name  = $request->equipment_name;
            $equipment->area_id         = $request->area_id;
            $equipment->remark          = $request->remark;
            $equipment->save();
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
            'results'   => route('equipment.index'),
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
            $equipment  = Equipment::destroy($id);
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
     * The function to get data to show in datatable index
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
        $equipment      = strtoupper($request->asset);
        $area_id        = $request->area_id;
        $site_id        = $request->site_id;

        $queryData      = Equipment::with(['area', 'area.site'])->whereRaw("upper(equipment_name) like '%$equipment%'");
        if ($area_id) {
            $queryData->where('area_id', $area_id);
        }
        if ($site_id) {
            $queryData->whereHas('area', function (Builder $q) use ($site_id) {
                $q->where('site_id', $site_id);
            });
        }

        $row            = clone $queryData;
        $recordsTotal   = $row->count();

        $queryData->offset($start);
        $queryData->limit($length);
        $queryData->orderBy($sort, $dir);
        $equipments     = $queryData->get();

        $data           = [];
        foreach ($equipments as $key => $equipment) {
            $equipment->no  = ++$start;
            $data[]         = $equipment;
        }
        return response()->json([
            'draw'              => $request->draw,
            'recordsTotal'      => $recordsTotal,
            'recordsFiltered'   => $recordsTotal,
            'data'              => $data,
        ], 200);
    }

    /**
     * The function to get data to select2
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function select(Request $request)
    {
        # code...
    }
}
