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

class RackController extends Controller
{
    function __construct()
    {
        View::share('menu_active', url('admin/' . 'rack'));
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

        //Count Data
        $query = RackWarehouse::where("warehouse_id", $warehouse_id)->select('*');
        $query->whereRaw("upper(name) like '%$name%'");
        $recordsTotal = $query->count();

        //Select Pagination
        $query = RackWarehouse::where("warehouse_id", $warehouse_id)->select('*');
        $query->whereRaw("upper(name) like '%$name%'");
        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $racks = $query->get();

        $data = [];
        foreach ($racks as $rack) {
            $rack->no = ++$start;
            $data[] = $rack;
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
            'warehouse_id'      => 'required',
            'name'              => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }

        if($request->id){
            $rack = RackWarehouse::find($request->id);
            $rack->name = $request->name;
            $rack->save();
        }else{
            $rack = RackWarehouse::create([
                'warehouse_id'          => $request->warehouse_id,
                'name'                  => $request->name
            ]);
        }
        
        if (!$rack) {
            return response()->json([
                'status'    => false,
                'message'   => "Can't insert data rack"
            ], 400);
        }

        return response()->json([
            'status'     => true
        ], 200);
    }

    public function destroy($id)
    {
        try {
            $rack = RackWarehouse::find($id);
            $rack->delete();
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

    public function select(Request $request)
    {
        $start = $request->page ? $request->page - 1 : 0;
        $length = $request->limit;
        $name = strtoupper($request->name);

        //Count Data
        $query = RackWarehouse::select('*');
        $query->whereRaw("upper(name) like '%$name%'");
        if($request->warehouse_id){
            $query->where("warehouse_id", $request->warehouse_id);
        }

        $row = clone $query;
        $recordsTotal = $row->count();

        $query->offset($start);
        $query->limit($length);
        $racks = $query->get();

        $data = [];
        foreach ($racks as $rack) {
            $rack->no = ++$start;
            $data[] = $rack;
        }
        return response()->json([
            'total' => $recordsTotal,
            'rows' => $data
        ], 200);
    }
}
