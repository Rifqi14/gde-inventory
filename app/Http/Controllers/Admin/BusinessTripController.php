<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\BusinessTrip;
use App\Models\BusinessTripTransportation;
use App\Models\BusinessTripLodging;
use App\Models\BusinessTripVehicle;
use App\Models\BusinessTripOther;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BusinessTripController extends Controller
{
    function __construct(){
        // $menu   = Menu::where('menu_route', 'businesstrip')->first();
        // $parent = Menu::find($menu->parent_id);
        // View::share('parent_name', $parent->menu_name);
        // View::share('menu_name', $menu->menu_name);
        View::share('menu_active', url('admin/'.'businesstrip'));
        // $this->middleware('accessmenu', ['except' => ['select']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.business_trip.index',);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {        
        return view('admin.business_trip.create');
    }
    
     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {        
        $businesstrip = BusinessTrip::with([
            'departs',
            'returns',
            'vehicles' => function($q){
                $q->selectRaw("
                    business_trip_vehicles.*,                    
                    TO_CHAR(request_vehicles.start_request,'DD/MM/YYYY') as start_request,
                    TO_CHAR(request_vehicles.finish_request,'DD/MM/YYYY') as finish_request,
                    vehicles.vehicle_name,
                    request_vehicles.remarks
                ");
                $q->leftJoin('request_vehicles','request_vehicles.id','=','business_trip_vehicles.request_vehicle_id');
                $q->leftJoin('vehicles','vehicles.id','=','request_vehicles.vehicle_id');
            },
            'lodgings',
            'others'
        ]);
        $businesstrip->selectRaw("
            business_trips.*,
            users.name as issued_name
        ");
        $businesstrip->leftJoin('users','users.id','=','business_trips.issued_by');
        $businesstrip = $businesstrip->find($id);       
        
        if ($businesstrip) {
            $data = $businesstrip;
            return view('admin.business_trip.edit', compact('data'));
        } else {
            abort(404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $businesstrip = BusinessTrip::with([
            'departs',
            'returns',
            'vehicles' => function($q){
                $q->selectRaw("
                    business_trip_vehicles.*,                    
                    TO_CHAR(request_vehicles.start_request,'DD/MM/YYYY') as start_request,
                    TO_CHAR(request_vehicles.finish_request,'DD/MM/YYYY') as finish_request,
                    vehicles.vehicle_name,
                    request_vehicles.remarks
                ");
                $q->leftJoin('request_vehicles','request_vehicles.id','=','business_trip_vehicles.request_vehicle_id');
                $q->leftJoin('vehicles','vehicles.id','=','request_vehicles.vehicle_id');
            },
            'lodgings',
            'others'
        ]);
        $businesstrip->selectRaw("
            business_trips.*,
            users.name as issued_name
        ");
        $businesstrip->leftJoin('users','users.id','=','business_trips.issued_by');
        $businesstrip = $businesstrip->find($id);
        if ($businesstrip) {
            $data = $businesstrip;
            return view('admin.business_trip.detail', compact('data'));
        } else {
            abort(404);
        }
    }       

    public function select(Request $request)
    {
        $start = $request->page ? $request->page - 1 : 0;
        $length = $request->limit;
        $name = strtoupper($request->name);

        //Count Data
        $query = DB::table('working_shifts');
        $query->whereRaw("upper(shift_name) like '%$name%'");

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
        $draw               = $request->draw;
        $start              = $request->start;
        $length             = $request->length;
        $search             = $request->search['value'];
        $sort               = $request->columns[$request->order[0]['column']]['data'];
        $dir                = $request->order[0]['dir'];
        $status             = $request->status;
        $businessTripNumber = $request->businesstripnumber;
        $startdate          = $request->startdate;
        $enddate            = $request->enddate;
        $total_cost         = str_replace('.','',$request->total_cost);

        //Count Data
        $query = BusinessTrip::query();    
        $query->selectRaw("business_trips.*");        
        if($businessTripNumber){
            $query->whereRaw("upper(business_trip_number) like '%$businessTripNumber%'");
        }
        $query->where(function($w) use($status,$startdate,$enddate,$total_cost){
            $w->where([
                ['departure_date','>=',$startdate],
                ['departure_date','<=',$enddate]
            ]);
            if($total_cost){
                $w->where('total_cost',$total_cost);
            }        
            if($status){
                $w->where('status',$status);
            }else{
                $w->where('status','<>','approved');
            }            
        });
        $query->orWhere(function($w) use($status,$startdate,$enddate,$total_cost){
            $w->where([
                ['arrived_date','>=',$startdate],
                ['arrived_date','<=',$enddate]
            ]);
            if($total_cost){
                $w->where('total_cost',$total_cost);
            }  
            if($status){
                $w->where('status',$status);
            }else{
                $w->where('status','<>','approved');
            }               
        });
        

        $rows  = clone $query;
        $total = $rows->count();

        //Select Pagination
        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $businesstrips = $query->get();

        $data = [];
        foreach ($businesstrips as $key => $row) {
            $row->no = ++$start;                      
            $row->schedule = date('d/m/Y',strtotime($row->departure_date)).' - '.date('d/m/Y',strtotime($row->arrived_date));            
            $data[] = $row;
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
        $validator = Validator::make($request->all(),[            
            'departure_date'       => 'required',
            'arrived_date'         => 'required',
            'purpose'              => 'required',
            'location'             => 'required',
            'rate'                 => 'required',
            'total_cost'           => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ],400);
        }

        $issued_by   = $request->issued;
        $status      = $request->status;
        $purpose     = $request->purpose;
        $location    = $request->location;
        $departdate  = $request->departure_date;
        $arriveddate = $request->arrived_date;
        $rate        = str_replace('.','',$request->rate);
        $total_cost  = str_replace('.','',$request->total_cost);

        $query = BusinessTrip::create([            
            'issued_by'            => $issued_by,
            'departure_date'       => $departdate,
            'arrived_date'         => $arriveddate,
            'purpose'              => $purpose,
            'location'             => $location,
            'status'               => $status,
            'rate'                 => $rate,
            'total_cost'           => $total_cost
        ]);

        if($query){
            $now = date('Y-m-d H:i:s');
            $business_id = $query->id; 
            $departures  = $request->departure;
            $returns     = $request->returning;
            $vehicles    = $request->vehicle;
            $lodgings    = $request->lodging;
            $others      = $request->others;

            if($departures || $returns){                                            
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
                $lodging = [];
                foreach (json_decode($lodgings) as $key => $row) {
                    $lodging[] = [
                        'business_trip_id' => $business_id,
                        'place'            => $row->place,
                        'price'            => intval(str_replace('.','',$row->price)),
                        'night'            => intval($row->days),
                        'created_at'       => $now,
                        'updated_at'       => $now
                    ];
                }

                $query = BusinessTripLodging::insert($lodging);
                if(!$query){
                    return response()->json([
                        'status'  => false,
                        'message' => 'Failed to cretae detail lodging.'
                    ],400);
                }
            }

            if($others){
                $other  = [];
                foreach (json_decode($others) as $key => $row) {
                    $other[] = [
                        'business_trip_id' => $business_id,
                        'description'      => $row->description,
                        'price'            => intval(str_replace('.','',$row->price)),
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
                'status'  => true,
                'message' => 'Data has been saved.',
                'point'   => 200
            ];
        }else{
            $result = [
                'status'  => false,
                'message' => 'Failed to create data.',
                'point'   => 400
            ];
        }

        return response()->json([
            'status'  => $result['status'],
            'message' => $result['message']
        ],$result['point']);
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
        $validator = Validator::make($request->all(),[       
            'business_trip_number' => 'required|unique:business_trips,business_trip_number,'.$id,
            'departure_date'       => 'required',
            'arrived_date'         => 'required',
            'purpose'              => 'required',
            'location'             => 'required',
            'rate'                 => 'required',
            'total_cost'           => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ],400);
        }

        $number      = $request->business_trip_number;
        $issued_by   = $request->issued;
        $status      = $request->status;
        $purpose     = $request->purpose;
        $location    = $request->location;
        $departdate  = $request->departure_date;
        $arriveddate = $request->arrived_date;
        $rate        = str_replace('.','',$request->rate);
        $total_cost  = str_replace('.','',$request->total_cost);

        $query = BusinessTrip::find($id);
        $query->issued_by      = $issued_by;
        $query->departure_date = $departdate;
        $query->arrived_date   = $arriveddate;
        $query->purpose        = $purpose;
        $query->location       = $location;
        $query->rate           = $rate;
        $query->status         = $status;
        $query->total_cost     = $total_cost;
        $query->update();

        if($query){
            $now         = date('Y-m-d H:i:s');
            $business_id = $query->id; 
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
        }else{
            $result = [
                'status'    => false,
                'message'   => 'Failed to update data.',
                'point'     => 400
            ];
        }

        return response()->json([
            'status'    => $result['status'],
            'message'   => $result['message']
        ],$result['point']);
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
            $query = BusinessTrip::find($id);
            $query->delete();

            if ($query) {
                $result = [
                    'status'  => true,
                    'message' => 'Data hase been removed.',
                    'point'   => 200
                ];
            }else{
                $result = [
                    'status'  => false,
                    'message' => 'Data hase been removed.',
                    'point'   => 400
                ];
            }

            return response()->json([
                'status'  => $result['status'],
                'message' => $result['message']
            ],$result['point']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => false                
            ], 400);
        }
    }    

}