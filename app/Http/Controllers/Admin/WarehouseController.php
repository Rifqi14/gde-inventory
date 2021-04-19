<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Warehouse;
use App\Models\RackWarehouse;
use App\Models\BinWarehouse;

class WarehouseController extends Controller
{
    function __construct()
    {
        View::share('menu_active', url('admin/' . 'warehouse'));
        $this->middleware('accessmenu', ['except' => ['select']]);
    }

    public function read(Request $request)
    {
        $start = $request->start;
        $length = $request->length;
        $query = $request->search['value'];
        $sort = $request->columns[$request->order[0]['column']]['data'];
        $dir = $request->order[0]['dir'];
        $name = strtoupper($request->name);
        $type = $request->type;

        //Count Data
        $query = Warehouse::select('*')->withCount('rack');
        $query->whereRaw("upper(name) like '%$name%'");
        if($type){
            $query->where("type", $type);
        }

        $row = clone $query;
        $recordsTotal = $row->count();

        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $warehouses = $query->get();

        $data = [];
        foreach ($warehouses as $warehouse) {
            $warehouse->no = ++$start;
            $text_status = '<span class="badge bg-success color-platte text-sm">'.config('enums.warehouse_status')[$warehouse->status].'</span>';
            if($warehouse->status !== 'active'){
                $text_status = '<span class="badge bg-red color-platte text-sm">'.config('enums.warehouse_status')[$warehouse->status].'</span>';
            }
            $warehouse->type = config('enums.warehouse_type')[$warehouse->type];
            $warehouse->text_status = $text_status;
            $warehouse->bin_count = $this->getBinTotal($warehouse->id);
            $data[] = $warehouse;
        }
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data
        ], 200);
    }

    public function getBinTotal($warehouse_id){
        $rackwarehouses = RackWarehouse::where('warehouse_id',$warehouse_id)->get();
        $rack = [];
        foreach($rackwarehouses as $rack){
            array_push($rack->id, $rack);
        }
        $total = BinWarehouse::whereIn('rack_id',$rack)->count();
        return $total;
    }

    public function index()
    {
        return view('admin.warehouse.index');
    }

    public function create(Request $request)
    {
        if(in_array('create',$request->actionmenu)){
            return view('admin.warehouse.create');
        }else{
            abort(403);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'              => 'required|unique:warehouses',
            'name'              => 'required',
            'type'              => 'required',
            'site_id'           => 'required',
            'province_id'       => 'required',
            'region_id'         => 'required',
            'district_id'       => 'required',
            'subdistrict_id'    => 'required',
            'postal_code'       => 'required',
            'address'           => 'required',
            'status'            => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }

        $warehouse = Warehouse::create([
            'code'                  => $request->code,
            'name'                  => $request->name,
            'type'                  => $request->type,
            'site_id'               => $request->site_id,
            'province_id'           => $request->province_id,
            'region_id'             => $request->region_id,
            'district_id'           => $request->district_id,
            'subdistrict_id'        => $request->subdistrict_id,
            'postal_code'           => $request->postal_code,
            'address'               => $request->address,
            'description'           => $request->description,
            'status'                => $request->status
        ]);
        
        if (!$warehouse) {
            return response()->json([
                'status'    => false,
                'message'   => "Can't insert data warehouse"
            ], 400);
        }

        return response()->json([
            'status'     => true,
            'results'     => route('warehouse.index'),
        ], 200);
    }

    public function edit(Request $request,$id)
    {
        if(in_array('update',$request->actionmenu)){
            $warehouse = Warehouse::find($id);
            return view('admin.warehouse.edit', compact('warehouse'));
        }else{
            abort(403);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'code'              => 'required|unique:warehouses,code,' . $id,
            'name'              => 'required',
            'type'              => 'required',
            'site_id'           => 'required',
            'province_id'       => 'required',
            'region_id'         => 'required',
            'district_id'       => 'required',
            'subdistrict_id'    => 'required',
            'postal_code'       => 'required',
            'address'           => 'required',
            'status'            => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }

        $warehouse = Warehouse::find($id);
        $warehouse->code = $request->code;
        $warehouse->name = $request->name;
        $warehouse->type = $request->type;
        $warehouse->site_id = $request->site_id;
        $warehouse->province_id = $request->province_id;
        $warehouse->region_id = $request->region_id;
        $warehouse->district_id = $request->district_id;
        $warehouse->subdistrict_id = $request->subdistrict_id;
        $warehouse->postal_code = $request->postal_code;
        $warehouse->address = $request->address;
        $warehouse->description = $request->description;
        $warehouse->status = $request->status;
        $warehouse->save();
        if (!$warehouse) {
            return response()->json([
                'status' => false,
                'message'     => "Can't update data warehouse"
            ], 400);
        }

        return response()->json([
            'status'     => true,
            'results'     => route('warehouse.index'),
        ], 200);
    }

    public function destroy($id)
    {
        try {
            $warehouse = Warehouse::find($id);
            $warehouse->delete();
        } catch (QueryException $th) {
            return response()->json([
                'status'    => false,
                'message'   => 'Error delete data ' . $th->errorInfo[2]
            ], 400);
        }
        return response()->json([
            'status'    => true,
            'message'   => 'Success delete data'
        ], 200);
    }

    public function show(Request $request,$id)
    {
        if(in_array('read',$request->actionmenu)){
            $warehouse = Warehouse::find($id);
            if ($warehouse) {
                return view('admin.warehouse.detail', compact('warehouse'));
            } else {
                abort(404);
            }
        }else{
            abort(403);
        }
    }
}
