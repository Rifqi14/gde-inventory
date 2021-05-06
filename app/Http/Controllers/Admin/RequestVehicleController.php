<?php

namespace App\Http\Controllers\Admin;

use App\Models\RequestVehicle;
use App\Models\BorrowerRequestVehicle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;

class RequestVehicleController extends Controller
{
    public function __construct()
    {
        View::share('menu_active', url('admin' . 'requestvehicle'));
    }

    public function index()
    {
        return view('admin.requestvehicle.index');
    }

    public function create()
    {
        $url = route('requestvehicle.store');
        return view('admin.requestvehicle.create', compact('url'));
    }

    public function edit(Request $request,$id)
    {        
        if ($id) {
            $reqvehicle = RequestVehicle::with([
                'borrowers' => function ($q)
                {
                    $q->selectRaw('
                        borrower_request_vehicles.request_vehicle_id,
                        borrower_request_vehicles.employee_id,
                        employees.name
                    ');
                    $q->leftJoin('employees','employees.id','=','borrower_request_vehicles.employee_id');
                }
            ]);
            $reqvehicle->selectRaw("
                request_vehicles.*,
                vehicles.vehicle_name,
                vehicles.police_number
            ");
            $reqvehicle->leftJoin('vehicles','vehicles.id','=','request_vehicles.vehicle_id');
            $reqvehicle->where('request_vehicles.id',$id);
            $reqvehicle = $reqvehicle->first();

            if($reqvehicle->start_request){
                $reqvehicle->start_request = date('d/m/Y', strtotime($reqvehicle->start_request));
            }else{
                $reqvehicle->start_request = '';
            }

            if($reqvehicle->finish_request){
                $reqvehicle->finish_request = date('d/m/Y', strtotime($reqvehicle->finish_request));
            }else{
                $reqvehicle->finish_request = '';
            }
            
            $data = $reqvehicle;                    

            return view('admin.requestvehicle.edit',compact('data'));
        }else{
            abort(404);
        }        
    }

    public function read(Request $request)
    {
        $draw    = $request->draw;
        $start   = $request->start;
        $length  = $request->length;
        $query   = $request->search['value'];
        $sort    = $request->columns[$request->order[0]['column']]['data'];
        $dir     = $request->order[0]['dir'];
        $vehicle = strtoupper($request->vehicle);
        $plate   = strtoupper($request->plate);        
        $status  = $request->status;
        $borrowers     = [];        
        $startrequest  = $request->startrequest;
        $finishrequest = $request->finishrequest;        

        if($request->borrowers){
            foreach (json_decode($request->borrowers) as $key => $row) {
                array_push($borrowers,intval($row->employee_id));
            }
        }

        $query = RequestVehicle::with([
            'borrowers' => function ($q) {
                $q->selectRaw("
                    borrower_request_vehicles.id,
                    borrower_request_vehicles.request_vehicle_id,                    
                    employees.name
                ");
                $q->leftJoin('employees', 'employees.id', '=', 'borrower_request_vehicles.employee_id');                                                        
            }
        ]);                
        $query->selectRaw("
            distinct(request_vehicles.id),
            request_vehicles.start_request,
            request_vehicles.finish_request,
            request_vehicles.status,
            vehicles.vehicle_name,
            vehicles.police_number
        ");
        $query->join('vehicles', 'vehicles.id', '=', 'request_vehicles.vehicle_id');        
        if(count($borrowers) > 0){
            $query->join('borrower_request_vehicles','borrower_request_vehicles.request_vehicle_id','=','request_vehicles.id');
            $query->whereIn('borrower_request_vehicles.employee_id', $borrowers);
        }
        if ($vehicle) {
            $query->whereRaw("upper(vehicles.vehicle_name) like '%$vehicle%'");
        }
        if ($plate) {
            $query->whereRaw("upper(vehicles.police_number) like '%$plate%'");
        }
        if ($status) {
            $query->where('request_vehicles.status',$status);
        }
        if ($startrequest) {
            $query->where('request_vehicles.start_request','<=',"'$startrequest'");            
            $query->orWhere('request_vehicles.start_request','>=',"'$finishrequest'");
        }
        if ($finishrequest) {
            $query->where('request_vehicles.finish_request','<=',"'$startrequest'");            
            $query->orWhere('request_vehicles.finish_request','>=',"'$finishrequest'");
        }

        $rows  = clone $query;
        $total = $rows->count();

        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $reqvehicle = $query->get();

        $data = [];
        foreach ($reqvehicle as $key => $row) {
            $row->no = ++$start;
            $row->date_request = date('d/m/Y',strtotime($row->start_request)) . ' - ' .date('d/m/Y', strtotime($row->finish_request));
            $data[]  = $row;
        }

        return response()->json([
            'draw'            => $draw,
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,                   
            'data'            => $data
        ], 200);
    }

    public function select(Request $request)
    {
        $start   = $request->page ? $request->page - 1 : 0;
        $length  = $request->limit;
        $search = strtoupper($request->search);   
        $employeeid = $request->employee_id;
        $data  = [];
        $total = 0;

        if($employeeid){
            $query = RequestVehicle::query();
            $query->selectRaw('
                request_vehicles.id,
                request_vehicles.start_request,
                request_vehicles.finish_request,
                request_vehicles.remarks,
                vehicles.vehicle_name,
                borrower_request_vehicles.employee_id,
                employees.name as employee_name
            ');
            $query->leftJoin('vehicles','vehicles.id','=','request_vehicles.vehicle_id');
            $query->join('borrower_request_vehicles',function($j) use ($employeeid){
                $j->on('borrower_request_vehicles.request_vehicle_id','=','request_vehicles.id');            
            }); 
            $query->join('employees','employees.id','=','borrower_request_vehicles.employee_id');
            $query->where([
                ['borrower_request_vehicles.employee_id','=',$employeeid],
                ['request_vehicles  .status','=',2]
            ]);

            $total = $query->count();

            $query->offset($start);
            $query->limit($length);
            $reqvehicle = $query->get();
            
            foreach($reqvehicle as $key => $row){
                $row->date_request = date('d/m/Y',strtotime($row->start_request)).' - '.date('d/m/Y',strtotime($row->finish_request));
                $data[] = $row;
            }
        }       
        
        return response()->json([
            'total' => $total,
            'rows'  => $data
        ]);


    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'vehicle'    => 'required',
            'startdate'  => 'required',
            'finishdate' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $vehicle    = $request->vehicle;
        $startdate  = $request->startdate;
        $borrowers  = $request->borrowers;
        $finishdate = $request->finishdate;
        $notes      = $request->notes;
        $status     = $request->status;

        $reqvehicle = RequestVehicle::create([
            'vehicle_id'     => $vehicle,
            'start_request'  => $startdate,
            'finish_request' => $finishdate,
            'remarks'        => $notes,
            'status'         => $status,
        ]);

        if ($reqvehicle) {
            foreach (json_decode($borrowers) as $key => $row) {
                BorrowerRequestVehicle::create([
                    'request_vehicle_id' => $reqvehicle->id,
                    'employee_id'        => $row->employee_id
                ]);
            }

            $result = [
                'status'  => true,
                'message' => 'Successfully insert data.',
                'point'   => 200
            ];
        } else {
            $result = [
                'status'  => false,
                'message' => 'Failed to create data.',
                'point'   => 400
            ];
        }

        return response()->json([
            'status'  => $result['status'],
            'message' => $result['message'],
        ], $result['point']);
    }

    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(), [
            'vehicle'    => 'required',
            'startdate'  => 'required',
            'finishdate' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $vehicle    = $request->vehicle;
        $startdate  = $request->startdate;        
        $finishdate = $request->finishdate;
        $borrowers  = $request->borrowers;
        $notes      = $request->notes;
        $status     = $request->status;

        $reqvehicle = RequestVehicle::find($id);   
        $reqvehicle->vehicle_id     = $vehicle; 
        $reqvehicle->start_request  = $startdate;
        $reqvehicle->finish_request = $finishdate;        
        $reqvehicle->remarks        = $notes;
        $reqvehicle->status         = $status;
        $reqvehicle->save();
        
        if ($reqvehicle) {
            BorrowerRequestVehicle::where('request_vehicle_id',$reqvehicle->id)->delete();

            foreach (json_decode($borrowers) as $key => $row) {
                BorrowerRequestVehicle::create([
                    'request_vehicle_id' => $reqvehicle->id,
                    'employee_id'        => $row->employee_id
                ]);
            }

            $result = [
                'status'  => true,
                'message' => 'Successfully updated data.',
                'point'   => 200
            ];
        }else{
            $result = [
                'status'  => false,
                'message' => 'Failed to update data.',
                'point'   => 400
            ];
        }

        return response()->json([
            'status'  => $result['status'],
            'message' => $result['message'],
        ], $result['point']);
    }

    public function delete($id)
    {
        try {
            $reqvehicle = RequestVehicle::find($id);
            $reqvehicle->delete();

            if ($reqvehicle) {
                $result = [
                    'status'  => true,
                    'message' => 'Data hase been removed.',
                    'point'   => 200
                ];
            } else {
                $result = [
                    'status'  => false,
                    'message' => 'Data hase been removed.',
                    'point'   => 400
                ];
            }

            return response()->json([
                'status'  => $result['status'],
                'message' => $result['message']
            ], $result['point']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => false
            ],400);
        }
    }

    public function daterequest(Request $request)
    {
        $request_id    = $request->request_id?intval($request->request_id):0;
        $vehicle_id    = $request->vehicle_id;
        $startrequest  = $request->start_request;
        $finishrequest = $request->finish_request;

        $dates = RequestVehicle::query();                                
        $dates->where(function($w) use ($request_id,$vehicle_id){
            if($request_id){
                $w->where('id','<>',$request_id);
            }
            $w->where([
                ['vehicle_id','=',$vehicle_id],
                ['status','<>',3]
            ]);            
        });        
        $dates->where(function($w) use ($startrequest,$finishrequest){
            $w->where([
                ['start_request','>=',"'$startrequest'"],
                ['start_request','<=',"'$finishrequest'"]
            ]);
            $w->orWhere([
                ['finish_request','>=',"'$startrequest'"],
                ['finish_request','<=',"'$finishrequest'"]
            ]);
        });

        $count = $dates->count();

        if($count > 0){
            $result = [
                'status'  => false,
                'message' => 'Some date already taken.'                
            ];
        }else{
            $result = [
                'status'  => true,
                'message' => 'Allowed to pick date.'
            ];
        }

        return response()->json([
            'status'  => $result['status'],
            'message' => $result['message'],
            'dates'   => $dates->get(),            
            'request_id' => $request_id
        ]);
    }
}
