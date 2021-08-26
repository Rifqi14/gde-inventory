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

class BinController extends Controller
{
    function __construct()
    {
        View::share('menu_active', url('admin/' . 'bin'));
        $this->middleware('accessmenu', ['except' => ['select','store','edit','update','destroy','read']]);
    }

    public function read(Request $request)
    {
        $start = $request->start;
        $length = $request->length;
        $query = $request->search['value'];
        $sort = $request->columns[$request->order[0]['column']]['data'];
        $dir = $request->order[0]['dir'];
        $warehouse_id = $request->warehouse_id;
        $name = strtoupper($request->name);
        $rack_id = $request->rack_id;

        //Count Data
        $query = BinWarehouse::where("rack_warehouses.warehouse_id", $warehouse_id)->select('bin_warehouses.*');
        $query->join("rack_warehouses","rack_warehouses.id","=","bin_warehouses.rack_id");
        $query->whereRaw("upper(bin_warehouses.name) like '%$name%'");
        if($rack_id){
            $query->where("rack_id",$rack_id);
        }
        $recordsTotal = $query->count();

        //Select Pagination
        $query = BinWarehouse::where("rack_warehouses.warehouse_id", $warehouse_id)->select('bin_warehouses.*','rack_warehouses.name as rack_name');
        $query->join("rack_warehouses","rack_warehouses.id","=","bin_warehouses.rack_id");
        $query->whereRaw("upper(bin_warehouses.name) like '%$name%'");
        if($rack_id){
            $query->where("rack_id",$rack_id);
        }
        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $bins = $query->get();

        $data = [];
        foreach ($bins as $bin) {
            $bin->no = ++$start;
            $data[] = $bin;
        }
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data
        ], 200);
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'rack_id'              => 'required',
            'name'              => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }

        if($request->id){
            $rack = BinWarehouse::find($request->id);
            $rack->rack_id = $request->rack_id;
            $rack->name = $request->name;
            $rack->save();
        }else{
            $rack = BinWarehouse::create([
                'rack_id'          => $request->rack_id,
                'name'             => $request->name
            ]);
        }
        
        if (!$rack) {
            return response()->json([
                'status'    => false,
                'message'   => "Can't insert data bin"
            ], 400);
        }

        return response()->json([
            'status'     => true
        ], 200);
    }

    public function destroy($id)
    {
        try {
            $bin = BinWarehouse::find($id);
            $bin->delete();
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
}
