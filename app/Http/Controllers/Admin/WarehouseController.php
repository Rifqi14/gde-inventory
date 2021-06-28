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
use App\Models\Site;
use App\Models\Menu;

class WarehouseController extends Controller
{
    function __construct()
    {        

        $menu   = Menu::where('menu_route', 'warehouse')->first();
        $parent = Menu::find($menu->parent_id);
        View::share('parent_name', $parent->menu_name);
        View::share('menu_name', $menu->menu_name);
        View::share('menu_active', url('admin/warehouse'));
        $this->middleware('accessmenu', ['except' => ['select']]);
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

    public function edit(Request $request,$id)
    {
        if(in_array('update',$request->actionmenu)){
            $warehouse = Warehouse::find($id);
            return view('admin.warehouse.edit', compact('warehouse'));
        }else{
            abort(403);
        }
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

    public function select(Request $request)
    {
        $start     = $request->page ? $request->page - 1 : 0;
        $length    = $request->limit;
        $name      = strtoupper($request->name);
        $site_id   = $request->site_id;
        $except_id = $request->exception_id;

        //Count Data
        $query = Warehouse::selectRaw("
            warehouses.*,
            sites.name as site
        ");
        $query->leftJoin('sites','sites.id','=','warehouses.site_id');
        $query->whereRaw("upper(warehouses.name) like '%$name%'");
        if($site_id){
            $query->where('site_id',$site_id);
        }
        if($except_id){
            $query->where('warehouses.id','<>',$except_id);
        }

        $row = clone $query;
        $recordsTotal = $row->count();

        $query->offset($start);
        $query->limit($length);
        $warehouses = $query->get();

        $data = [];
        foreach ($warehouses as $warehouse) {
            $warehouse->no = ++$start;
            $data[] = $warehouse;
        }
        return response()->json([
            'total' => $recordsTotal,
            'rows' => $data
        ], 200);
    }

    public function selectrack(Request $request)
    {
        $start       = $request->page ? $request->page - 1 : 0;
        $length      = $request->limit;
        $name        = strtoupper($request->name);        
        $warehouseid = $request->warehouse_id;

        $query = RackWarehouse::selectRaw("
            rack_warehouses.*,
            warehouses.name as warehouse,
            warehouses.site_id,
            sites.name as site
        ");
        $query->leftJoin('warehouses','warehouses.id','=','rack_warehouses.warehouse_id');
        $query->leftJoin('sites','sites.id','=','warehouses.site_id');
        if($warehouseid){
            $query->where('rack_warehouses.warehouse_id',$warehouseid);
        }
        if($name){
            $query->whereRaw("upper(rack_warehouses.name) like '%$name%'");
        }        

        $row   = clone $query;
        $total = $row->count();

        $query->offset($start);
        $query->limit($length);
        $racks = $query->get();

        $data = [];
        foreach ($racks as $key => $row) {
            $data[] = $row;
        }

        return response()->json([
            'total' => $total,
            'rows'  => $data
        ], 200);

    }

    public function selectbin(Request $request)
    {
        $start       = $request->page ? $request->page - 1 : 0;
        $length      = $request->limit;
        $name        = strtoupper($request->name);        
        $warehouseid = $request->warehouse_id;
        $rackid      = $request->rack_id;

        $query = BinWarehouse::selectRaw("
            bin_warehouses.*,            
            rack_warehouses.warehouse_id,
            rack_warehouses.name as rack,
            warehouses.name as warehouse,
            warehouses.site_id,
            sites.name as site
        ");
        $query->leftJoin('rack_warehouses','rack_warehouses.id','=','bin_warehouses.rack_id');
        $query->leftJoin('warehouses','warehouses.id','=','rack_warehouses.warehouse_id');
        $query->leftJoin('sites','sites.id','=','warehouses.site_id');
        if($rackid){
            $query->where('bin_warehouses.rack_id',$rackid);
        }
        if($warehouseid){
            $query->where('rack_warehouses.warehouse_id',$warehouseid);
        }
        if($name){
            $query->whereRaw("upper(bin_warehouses.name) like '%$name%'");
        }
        
        $row   = clone $query;
        $total = $row->count();

        $query->offset($start);
        $query->limit($length);
        $bins = $query->get();

        $data = [];
        foreach ($bins as $key => $row) {
            $data[] = $row;
        }

        return response()->json([
            'total' => $total,
            'rows'  => $data
        ], 200);
    }

    public function read(Request $request)
    {
        $start = $request->start;
        $length = $request->length;
        $query = $request->search['value'];
        $sort = $request->columns[$request->order[0]['column']]['data'];
        $dir = $request->order[0]['dir'];
        $name = strtoupper($request->name);
        $status = $request->status;

        //Count Data
        $query = Warehouse::selectRaw('*')->withCount('rack');
        $query->whereRaw("upper(name) like '%$name%'");
        $query->where('type','<>','virtual'); 
        if($status){
            $query->where('status', $status);
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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'              => 'required|unique:warehouses',
            'name'              => 'required',
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
            'type'                  => 'internal',
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
        
        if ($warehouse) {
            $this->generateWarehouseVirtual($warehouse->site_id);

            return response()->json([
                'status'     => true,
                'results'     => route('warehouse.index'),
            ], 200);               
        }else{
            return response()->json([
                'status'    => false,
                'message'   => "Can't insert data warehouse"
            ], 400);
        }        
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'code'              => 'required|unique:warehouses,code,' . $id,
            'name'              => 'required',            
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
        $warehouse->code            = $request->code;
        $warehouse->name            = $request->name;        
        $warehouse->site_id         = $request->site_id;
        $warehouse->province_id     = $request->province_id;
        $warehouse->region_id       = $request->region_id;
        $warehouse->district_id     = $request->district_id;
        $warehouse->subdistrict_id  = $request->subdistrict_id;
        $warehouse->postal_code     = $request->postal_code;
        $warehouse->address         = $request->address;
        $warehouse->description     = $request->description;
        $warehouse->status          = $request->status;
        $warehouse->save();

        if ($warehouse) {
            $this->generateWarehouseVirtual($warehouse->site_id);

            return response()->json([
                'status'     => true,
                'results'     => route('warehouse.index'),
            ], 200);            
        }else{
            return response()->json([
                'status' => false,
                'message'     => "Can't update data warehouse"
            ], 400);
        }

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

    public function getBinTotal($warehouse_id){
        $rackwarehouses = RackWarehouse::where('warehouse_id',$warehouse_id)->get();
        $racks = [];
        foreach($rackwarehouses as $rack){
            array_push($racks,$rack->id);
        }
        $total = BinWarehouse::whereIn('rack_id',$racks)->count();
        return $total;
    }

    public function generateWarehouseVirtual($site_id)
    {
        $query = Warehouse::where([
            ['site_id','=',$site_id],
            ['type','=','virtual']
        ])->first();

        if(!$query){
            $site     = Site::find($site_id);
            $sitecode = strtoupper($site->code);
            $sitename = $site->name;

            $query = Warehouse::create([
                'code'          => "$sitecode - WV",
                'name'          => "$sitename - Warehouse Virtual",
                'type'          => 'virtual',
                'site_id'       => $site_id,
                'description'   => "Warehouse virtual of Unit or Site $sitename",
                'postal_code'   => 0,
                'address'       => '---',
                'status'        => 'active'
            ]);

            if($query){
                return response()->json([
                    'status'    => false,
                    'message'   => 'Failed to create warehouse virtual.'
                ],400);
            }
        }

    }
}