<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BusinessTrip;
use App\Models\BusinessTripDeclaration;
use App\Models\BusinessTripLodging;
use App\Models\BusinessTripOther;
use App\Models\BusinessTripTransportation;
use App\Models\BusinessTripVehicle;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class BusinessTripDeclarationController extends Controller
{
    function __construct() {
        $menu       = Menu::getByRoute('businesstrip')->first();
        $parent     = Menu::find($menu->parent_id);
        View::share('parent_name', $parent->menu_name);
        View::share('menu_name', $menu->menu_name);
        View::share('menu_active', url('admin/businesstrip'));
        // $this->middleware('accessmenu', ['except' => ['select']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
            return view('admin.declaration.create');
        } else {
            abort(403);
        }
    }

    /**
     * Define read data in index declaration
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function read(Request $request)
    {
        $draw               = $request->draw;
        $start              = $request->start;
        $length             = $request->length;
        $search             = $request->search['value'];
        $sort               = $request->columns[$request->order[0]['column']]['data'];
        $dir                = $request->order[0]['dir'];
        $businessTripNumber = strtoupper($request->businesstripnumber);
        $startdate          = $request->startdate;
        $enddate            = $request->enddate;
        $totalCost          = str_replace('.','',$request->total_cost);

        // Count Data
        $query      = BusinessTripDeclaration::with(['declarationbt']);
        if ($businessTripNumber) {
            $query->where('declaration_number', $businessTripNumber);
        }

        $rows       = clone $query;
        $total      = $rows->count();

        // Select Pagination
        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $declarations   = $query->get();

        $data       = [];
        foreach ($declarations as $key => $declaration) {
            $declaration->no        = ++$start;
            $declaration->rate      = $declaration->declarationbt->total_cost;
            $declaration->schedule  = date('d/m/Y',strtotime($declaration->declarationbt->departure_date)).' - '.date('d/m/Y',strtotime($declaration->declarationbt->arrived_date));
            $data[]                 = $declaration;
        }
        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data
        ], 200);
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
            'departure_date'       => 'required',
            'arrived_date'         => 'required',
            'purpose'              => 'required',
            'location'             => 'required',
            'rate'                 => 'required',
            'total_cost'           => 'required',
            'business_trip_request'=> 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        $declaration    = BusinessTripDeclaration::create([
            'business_trip_id'      => $request->business_trip_request,
            'declaration_by'        => $request->issued,
        ]);
        $explodeIndex   = explode('-', $declaration->index_number);
        $month          = date('m');
        $declarationNo  = "$explodeIndex[0]-$explodeIndex[1]-$month-$explodeIndex[2]";
        $declaration->declaration_number    = $declarationNo;
        $declaration->save();

        $businesstrip   = BusinessTrip::find($declaration->business_trip_id);
        $businesstrip->issued_by        = $request->user_id;
        $businesstrip->departure_date   = $request->departure_date;
        $businesstrip->arrived_date     = $request->arrived_date;
        $businesstrip->purpose          = $request->purpose;
        $businesstrip->location         = $request->location;
        $businesstrip->rate             = str_replace('.', '', $request->rate);
        $businesstrip->total_cost       = str_replace('.', '', $request->total_cost);
        $businesstrip->update();

        if ($businesstrip) {
            $now         = date('Y-m-d H:i:s');
            $business_id = $businesstrip->id; 
            $departures  = $request->departure;
            $vehicles    = $request->vehicle;
            $returns     = $request->returning;
            $lodgings    = $request->lodging;
            $others      = $request->others;

            if($departures || $returns){                
                $cleared = BusinessTripTransportation::where('business_trip_id', $business_id);
                $cleared->delete();                

                $transportation = [];
                foreach (json_decode($departures) as $key => $row) {
                    $transportation[] = [
                        'business_trip_id'    => $business_id,
                        'transportation_type' => 'depart',
                        'type'                => $row->type,
                        'description'         => $row->description,
                        'price'               => str_replace('.','',$row->price),
                        'created_at'          => $now,
                        'updated_at'          => $now
                    ];
                }                               

                foreach (json_decode($returns) as $key => $row) {
                    $transportation[] = [
                        'business_trip_id'    => $business_id,
                        'transportation_type' => 'return',
                        'type'                => $row->type,
                        'description'         => $row->description,
                        'price'               => str_replace('.','',$row->price),
                        'created_at'          => $now,
                        'updated_at'          => $now
                    ];
                }

                $query = BusinessTripTransportation::insert($transportation);
                if(!$query){
                    return response()->json([
                        'status'  => false,
                        'message' => 'Failed to create detail transportation.'
                    ],400);
                }
            }

            if($vehicles){
                $cleared = BusinessTripVehicle::where('business_trip_id', $business_id);
                $cleared->delete(); 

                $vehicle = [];
                foreach(json_decode($vehicles) as $key => $row){
                    $vehicle[] = [
                        'business_trip_id'   => $business_id,
                        'request_vehicle_id' => $row->request_id,
                        'description'        => '',
                        'created_at'         => $now,
                        'updated_at'         => $now
                    ];
                }

                $query = BusinessTripVehicle::insert($vehicle);
                if(!$query){
                    if(!$query){
                        return response()->json([
                            'status'  => false,
                            'message' => 'Failed to create detail request vehicle.'
                        ],400);
                    }
                }
            }

            if($lodgings){
                $cleared = BusinessTripLodging::where('business_trip_id', $business_id);
                $cleared->delete();                

                $lodging = [];
                foreach (json_decode($lodgings) as $key => $row) {
                    $lodging[] = [
                        'business_trip_id' => $business_id,
                        'place'            => $row->place,
                        'price'            => str_replace('.','',$row->price),
                        'night'            => $row->days,
                        'created_at'       => $now,
                        'updated_at'       => $now
                    ];
                }

                $query = BusinessTripLodging::insert($lodging);
                if(!$query){
                    return response()->json([
                        'status'  => false,
                        'message' => 'Failed to create detail lodging.'
                    ],400);
                }
            }

            if($others){
                $cleared = BusinessTripOther::where('business_trip_id', $business_id);
                $cleared->delete();

                $other  = [];
                foreach (json_decode($others) as $key => $row) {
                    $other[] = [
                        'business_trip_id' => $business_id,
                        'description'      => $row->description,
                        'price'            => str_replace('.','',$row->price),
                        'qty'              => $row->qty,
                        'created_at'       => $now,
                        'updated_at'       => $now
                    ];
                }

                $query = BusinessTripOther::insert($other);
                if(!$query){
                    return response()->json([
                        'status' => false,
                        'message' => 'Failed to create others detail.'
                    ],400);
                }
            }

            $result = [
                'status'    => true,
                'message'   => 'Data has been updated.',
                'point'     => 200
            ];
        } else {
            DB::rollBack();
            $result = [
                'status'    => false,
                'message'   => 'Failed to update data.',
                'point'     => 400
            ];
        }

        DB::commit();
        return response()->json([
            'status'    => $result['status'],
            'message'   => $result['message']
        ],$result['point']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if (in_array('read', $request->actionmenu)) {
            $declaration        = BusinessTripDeclaration::with(['declarationbt'])->find($id);
            if ($declaration) {
                return view('admin.declaration.detail', compact('declaration'));
            } else {
                abort(404);
            }
        } else {
            abort(403);
        }
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
            $declaration        = BusinessTripDeclaration::with(['declarationbt'])->find($id);
            if ($declaration) {
                return view('admin.declaration.edit', compact('declaration'));
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::beginTransaction();
        try {
            $query      = BusinessTripDeclaration::find($id);
            $query->delete();

            DB::commit();
            return response()->json([
                'status'        => true,
                'message'       => "Data has been removed"
            ], 200);
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => $ex->errorInfo[2],
            ]. 400);
        }
    }
}