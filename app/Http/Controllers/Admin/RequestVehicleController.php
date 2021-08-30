<?php

namespace App\Http\Controllers\Admin;

use App\Models\RequestVehicle;
use App\Models\BorrowerRequestVehicle;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\LogRevise;
use App\Models\Menu;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;

class RequestVehicleController extends Controller
{
    private $menu_route;
    public function __construct()
    {
        $menu             = Menu::getByRoute('requestvehicle')->first();
        $parent           = Menu::find($menu->parent_id);
        $this->menu_route = $menu->menu_route;
        View::share('parent_name', $parent->menu_name);
        View::share('menu_name', $menu->menu_name);
        View::share('menu_route', $menu->menu_route);
        View::share('menu_active', url('admin' . '/requestvehicle'));
        $this->middleware('accessmenu', ['except' => ['select']]);
    }

    function getRouteName()
    {
        return $this->menu_route;
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
        // $query = RequestVehicle::selectRaw("
        //         request_vehicles.*,
        //         vehicles.vehicle_name,
        //         vehicles.police_number,
        //         employees.name as employee_name,
        //         users.name as user_name
        // ");
        // $query->leftJoin('vehicles','vehicles.id','=','request_vehicles.vehicle_id');
        // $query->leftJoin('users','users.id','=','request_vehicles.issued_by');
        // $query->leftJoin('employees','employees.id','=','users.employee_id');
        // $query->where('request_vehicles.id',$id);
        // $query = $query->first();

        $query  = RequestVehicle::with(['vehiclerequest', 'issuedbyrequest', 'issuedbyrequest.employees'])->find($id);
            
        if ($query) {            

            if($query->start_request){
                $query->start_request = date('d/m/Y', strtotime($query->start_request));
            }else{
                $query->start_request = '';
            }

            if($query->finish_request){
                $query->finish_request = date('d/m/Y', strtotime($query->finish_request));
            }else{
                $query->finish_request = '';
            }
            
            $data = $query;                   
                    
            if ($query->status == 'APPROVED' || $query->status == 'REJECTED') {
                abort(403);
            } else {
                $status = config('enums.global_status')[$query->status];
                return view('admin.requestvehicle.edit',compact('data', 'status'));
            }
        }else{
            abort(404);
        }        
    }

    public function read(Request $request)
    {
        $draw          = $request->draw;
        $start         = $request->start;
        $length        = $request->length;
        $query         = $request->search['value'];
        $sort          = $request->columns[$request->order[0]['column']]['data'];
        $dir           = $request->order[0]['dir'];
        $vehicle       = $request->vehicle;        
        $status        = $request->status;             
        $startrequest  = $request->startrequest;
        $finishrequest = $request->finishrequest;       
        $borrower      = $request->borrower;        

        $query = RequestVehicle::selectRaw("
            distinct(request_vehicles.id),
            request_vehicles.start_request,
            request_vehicles.finish_request,
            request_vehicles.status,
            vehicles.vehicle_name,
            vehicles.police_number,
            (case when employees.name is not null then employees.name else users.name end) as issued_name
        ");
        $query->leftJoin('vehicles', 'vehicles.id', '=', 'request_vehicles.vehicle_id');        
        $query->leftJoin('users','users.id','=','request_vehicles.issued_by');
        $query->leftJoin('employees','employees.id','=','users.employee_id');
        if ($vehicle) {
            $query->where('request_vehicles.vehicle_id',$vehicle);
        }
        if($borrower)       {
            $query->where('employees.id',$borrower);
        }
        if ($status) {
            $query->where('request_vehicles.status',$status);
        }
        if ($startrequest || $finishrequest) {
            $query->where('request_vehicles.start_request','>=',$startrequest);            
            $query->Where('request_vehicles.finish_request','<=',$finishrequest);
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
        $start      = $request->page ? $request->page - 1 : 0;
        $length     = $request->limit;
        $search     = strtoupper($request->search);   
        $employeeid = $request->employee_id;
        $data       = [];
        $total      = 0;

        if($employeeid){
            $query = RequestVehicle::query();
            $query->selectRaw('
                request_vehicles.id,
                request_vehicles.start_request,
                request_vehicles.finish_request,
                request_vehicles.remarks,
                vehicles.vehicle_name,
                employees.name as employee_name
            ');
            $query->leftJoin('vehicles','vehicles.id','=','request_vehicles.vehicle_id');
            // $query->join('borrower_request_vehicles',function($j) use ($employeeid){
            //     $j->on('borrower_request_vehicles.request_vehicle_id','=','request_vehicles.id');            
            // });
            $query->leftJoin('users', 'users.id', '=', 'request_vehicles.issued_by');
            $query->join('employees','employees.id','=','users.employee_id');
            $query->where([
                ['request_vehicles.issued_by','=',$employeeid],
                ['request_vehicles.status','=','APPROVED']
            ]);
            if($search){
                $query->whereRaw("upper(vehicles.vehicle_name) like '%$search%'");
            }

            $total = $query->count();

            $query->offset($start);
            $query->limit($length);
            $query->whereDoesntHave('business_trip.businesstrip', function (Builder $q) {
                $q->where('status', 'approved');
            });
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
        $issued_by  = $request->issued_by;        

        $reqvehicle = RequestVehicle::create([
            'vehicle_id'     => $vehicle,
            'start_request'  => $startdate,
            'finish_request' => $finishdate,
            'remarks'        => $notes,
            'status'         => $status,
            'issued_by'      => $issued_by
        ]);

        if ($reqvehicle) {
            // foreach (json_decode($borrowers) as $key => $row) {
            //     BorrowerRequestVehicle::create([
            //         'request_vehicle_id' => $reqvehicle->id,
            //         'employee_id'        => $row->employee_id
            //     ]);
            // }

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
            'startdate'  => 'required_unless:status,APPROVED,REJECTED',
            'finishdate' => 'required_unless:status,APPROVED,REJECTED'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 400);
        }

        $vehicle    = $request->vehicle;
        $vehicle_id = $request->vehicle_id;
        $startdate  = $request->startdate;        
        $finishdate = $request->finishdate;
        $borrowers  = $request->borrowers;
        $notes      = $request->notes;
        $status     = $request->status;
        $issued_by  = $request->issued_by;

        $reqvehicle = RequestVehicle::find($id);
        $vehicle_id = $reqvehicle->revise_status == 'NO' ? $vehicle_id : $vehicle;
        $reqvehicle->vehicle_id     = $vehicle_id ? $vehicle_id : $reqvehicle->vehicle_id; 
        $reqvehicle->start_request  = $startdate ? $startdate : $reqvehicle->start_request;
        $reqvehicle->finish_request = $finishdate ? $finishdate : $reqvehicle->finish_request;        
        $reqvehicle->remarks        = $notes ? $notes : $reqvehicle->remarks;
        $reqvehicle->status         = $status;
        $reqvehicle->issued_by      = $issued_by ? $issued_by : $reqvehicle->issued_by;
        $reqvehicle->revise_status  = 'NO';
        $reqvehicle->save();
        
        if ($reqvehicle) {
            // BorrowerRequestVehicle::where('request_vehicle_id',$reqvehicle->id)->delete();

            // foreach (json_decode($borrowers) as $key => $row) {
            //     BorrowerRequestVehicle::create([
            //         'request_vehicle_id' => $reqvehicle->id,
            //         'employee_id'        => $row->employee_id
            //     ]);
            // }

            if ($request->file('reason_attachment')) {
                $name       = ucwords(str_replace(' ', '-', $request->attachment_name));
                $status     = ucwords($reqvehicle->status);
                $now        = time();
                $filename   = "$status-Attachment-$name-$now.".$request->file('reason_attachment')->getClientOriginalExtension();
                if (file_exists($reqvehicle->reason_attachment)) {
                    File::delete($reqvehicle->reason_attachment);
                }
                
                $moveFile   = $this->reasonAttachment($request->file('reason_attachment'), $filename, 'requestvehicle', $reqvehicle->id);
                if ($moveFile) {
                    $reqvehicle->reason             = $request->reason;
                    $reqvehicle->attachment_name    = $request->attachment_name;
                    $reqvehicle->reason_attachment  = "assets/requestvehicle/$reqvehicle->id/$filename";
                    $reqvehicle->save();
                } else {
                    return response()->json([
                        'status'    => false,
                        'message'   => "Error upload attachment",
                    ], 400);
                }
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

    public function show($id)
    {   
        $query = RequestVehicle::selectRaw("
                request_vehicles.*,
                vehicles.vehicle_name,
                vehicles.police_number,
                employees.name as employee_name,
                users.name as user_name
        ");
        $query->leftJoin('vehicles','vehicles.id','=','request_vehicles.vehicle_id');
        $query->leftJoin('users','users.id','=','request_vehicles.issued_by');
        $query->leftJoin('employees','employees.id','=','users.employee_id');
        $query->where('request_vehicles.id',$id);
        $query = $query->first();
            
        if ($query) {            

            if($query->start_request){
                $query->start_request = date('d/m/Y', strtotime($query->start_request));
            }else{
                $query->start_request = '';
            }

            if($query->finish_request){
                $query->finish_request = date('d/m/Y', strtotime($query->finish_request));
            }else{
                $query->finish_request = '';
            }
            
            $data = $query;                   
                    

            return view('admin.requestvehicle.detail',compact('data'));
        }else{
            abort(404);
        }        
    }

    public function destroy($id)
    {
        try {
            $this->destroyLogRevise('requestvehicle', $id);
            $reqvehicle = RequestVehicle::destroy($id);

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

    public function revise(Request $request)
    {
        $validator  = Validator::make($request->all(), [
            'request_vehicle_id'        => 'required',
            'revise_number'             => 'required',
            'revise_reason'             => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'        => false,
                'message'       => $validator->errors()->first()
            ], 200);
        }

        DB::beginTransaction();
        $revise     = RequestVehicle::find($request->request_vehicle_id);
        $revise->status             = 'REVISED';
        $revise->revise_status      = 'YES';
        $revise->revise_number      += 1;
        $revise->revise_reason      = $request->revise_reason;
        $revise->save();

        if (!$revise) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => "Cant create revise"
            ], 400);
        }

        $log    = LogRevise::create([
            'route_menu'        => $this->getRouteName(),
            'data_id'           => $revise->id,
            'revise_number'     => $revise->revise_number,
            'revise_reason'     => $revise->revise_reason,
        ]);

        if (!$log) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => "Cant create revise"
            ], 400);
        }

        if ($request->file('revise_attachment')) {
            $name       = ucwords(str_replace(' ', '-', $request->revise_attachment_name));
            $filename   = "Revise-Attachment-Revision-{$revise->revise_number}-{$name}.".$request->file('revise_attachment')->getClientOriginalExtension();
            
            $moveFile   = $this->reasonAttachment($request->file('revise_attachment'), $filename, 'requestvehicle', $revise->id);
            if ($moveFile) {
                $revise->revise_attachment_name = $request->revise_attachment_name;
                $revise->revise_attachment      = "assets/requestvehicle/$revise->id/$filename";
                $revise->save();

                $log->attachment_name           = $request->revise_attachment_name;
                $log->revise_attachment         = "assets/requestvehicle/$revise->id/$filename";
                $log->save();
            } else {
                DB::rollBack();
                return response()->json([
                    'status'    => false,
                    'message'   => "Error upload attachment",
                ], 400);
            }
            
        }

        DB::commit();
        return response()->json([
            'status'    => true,
            'message'   => "Data has been revised",
        ], 200);
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