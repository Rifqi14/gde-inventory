<?php

namespace App\Http\Controllers\Admin;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class VehicleController extends Controller
{
    function __construct(){
        View::share('menu_active', url('admin/'.'vehicle'));
        $this->middleware('accessmenu', ['except' => ['select']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.vehicle.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.vehicle.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'site_id' => 'required',
            'police_number' => 'required|unique:vehicles',
            'vehicle_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 200);
        }

        $user = Vehicle::create([
            'police_number' => $request->police_number,
            'vehicle_name' => $request->vehicle_name,
            'site_id' => $request->site_id,
            'remarks' => $request->remarks,
            'status' => $request->status,
        ]);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message'     => 'Cant create vehicle'
            ], 200);
        }

        return response()->json([
            'status'     => true,
            'message'     => 'Created success',
            'results'     => route('vehicle.index'),
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
        $query = DB::table('vehicles');
        $query->select('vehicles.*', 'sites.name as site_name');
        $query->leftJoin('sites', 'vehicles.site_id', '=', 'sites.id');
        $query->where('vehicles.id', '=', $id);
        $user = $query->get()->first();
        if ($user) {
            return view('admin.vehicle.detail', compact('user'));
        } else {
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
        $query = DB::table('vehicles');
        $query->select('vehicles.*', 'sites.name as site_name');
        $query->leftJoin('sites', 'vehicles.site_id', '=', 'sites.id');
        $query->where('vehicles.id', '=', $id);
        $user = $query->get()->first();
        if ($user) {
            return view('admin.vehicle.edit', compact('user'));
        } else {
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
        $validator = Validator::make($request->all(), [
            // 'site_id'     => 'required',
            'police_number' => 'required|unique:vehicles,police_number,' . $id,
            'vehicle_name' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 200);
        }

        $user = Vehicle::find($id);
        $user->police_number = $request->police_number;
        $user->vehicle_name = $request->vehicle_name;
        $user->site_id = $request->site_id;
        $user->status = $request->status;
        $user->remarks = $request->remarks;
        $user->save();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message'     => "Cant update vehicles"
            ], 200);
        }

        return response()->json([
            'status'     => true,
            'results'     => route('vehicle.index'),
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
            $user = Vehicle::find($id);
            $user->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status'     => false,
                'message'     => 'Error delete data'
            ], 400);
        }
        return response()->json([
            'status'     => true,
            'message' => 'Success delete data'
        ], 200);
    }

    public function select(Request $request)
    {
        $start = $request->page ? $request->page - 1 : 0;
        $length = $request->limit;
        $name = strtoupper($request->name);

        //Count Data
        $query = DB::table('vehicles');
        $query->select('vehicles.*');
        $query->whereRaw("upper(vehicle_name) like '%$name%'");
        $query->orWhereRaw("upper(police_number) like '%$name%'");

        $row = clone $query;
        $recordsTotal = $row->count();

        $query->offset($start);
        $query->limit($length);
        $roles = $query->get();

        $data = [];
        foreach ($roles as $role) {
            $role->no = ++$start;
            $data[] = $role;
        }
        return response()->json([
            'total' => $recordsTotal,
            'rows' => $data
        ], 200);
    }

    public function read(Request $request)
    {
        $start = $request->start;
        $length = $request->length;
        $query = $request->search['value'];
        $sort = $request->columns[$request->order[0]['column']]['data'];
        $dir = $request->order[0]['dir'];
        $police_number = strtoupper($request->police_number);
        $vehicle_name = strtoupper($request->vehicle_name);
        $site_id = $request->site_id;
        $status = $request->status;

        //Count Data
        $query = DB::table('vehicles');
        $query->select('vehicles.*', 'sites.name as site_name');
        $query->leftJoin('sites', 'vehicles.site_id', '=', 'sites.id');
        $query->whereRaw("upper(vehicles.police_number) like '%$police_number%'");
        $query->whereRaw("upper(vehicles.vehicle_name) like '%$vehicle_name%'");
        if ($request->site_id) {
            $query->where("vehicles.site_id", $site_id);
        }
        if ($request->status) {
            $query->where("vehicles.status", $status);
        }

        $row = clone $query;
        $recordsTotal = $row->count();

        //Select Pagination
        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $users = $query->get();

        $data = [];
        foreach ($users as $user) {
            $user->no = ++$start;
            $data[] = $user;
        }
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data
        ], 200);
    }
}
