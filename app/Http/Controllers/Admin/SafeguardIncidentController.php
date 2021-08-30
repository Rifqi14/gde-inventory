<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Menu;

use App\Models\Site;
use App\Models\SafeguardCategory;
use App\Models\SafeguardIncident;
use App\Models\SafeguardIncidentAttachment;
use App\Models\SafeguardIncidentWitness;

class SafeguardIncidentController extends Controller
{
    public function __construct()
    {
        $menu   = Menu::where('menu_route', 'hseincident')->first();
        $parent = Menu::find($menu->parent_id);
        View::share('parent_name', $parent->menu_name);
        View::share('menu_name', $menu->menu_name);
        View::share('menu_active', url('admin/hseincident'));
        $this->middleware('accessmenu', ['except' => ['select']]);
    }

    public function index()
    {
        $userid = Auth::guard('admin')->user()->id;
        return view('admin.incident.index', compact('userid'));
    }

    public function read(Request $request)
    {
        $start = $request->start;
        $length = $request->length;
        $query = $request->search['value'];
        $sort = $request->columns[$request->order[0]['column']]['data'];
        $dir = $request->order[0]['dir'];

        $query = SafeguardIncident::select('*');

        $row = clone $query;
        $recordsTotal = $row->count();

        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $query->orderBy('created_at', 'desc');
        $incidents = $query->get();

        $data = [];
        foreach($incidents as $incident){
            $incident->no = ++$start;
            $incident->type = ucfirst($incident->type);
			$incident->date = date('d/m/Y', strtotime($incident->date));
			$data[] = $incident;
		}
        return response()->json([
            'draw'=>$request->draw,
			'recordsTotal'=>$recordsTotal,
			'recordsFiltered'=>$recordsTotal,
			'data'=>$data
        ], 200);
    }

    public function create(Request $request)
    {
        if (in_array('create', $request->actionmenu)) {
            $userid = Auth::guard('admin')->user()->id;
            $realname = Auth::guard('admin')->user()->name;
            $code = SafeguardIncident::select("number")->orderBy("id","desc")->first();
		    $number = generateCode("INC", @$code->number);
            return view('admin.incident.create', compact('userid','realname','number'));
        } else {
            abort(403);
        }
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        $code = SafeguardIncident::select("number")->orderBy("id","desc")->first();
        $number = generateCode("INC", @$code->number);
		$reporter  = $request->reporter;
		$type  = $request->type;
		$subject  = $request->subject;
		$loss_time  = $request->loss_time;
		$unit  = $request->unit;
		$date  = $request->date;
		$time  = $request->time;
		$area_id  = $request->area_id;
		$content  = $request;
        // $attachment = $request->file('attachment');
		$creation_user = Auth::guard('admin')->user()->id;
		$remarks  = $request->remarks;
		$status  = $request->status;
		$table = 'safeguard_incident';
        $content->date = str_replace("/","-",$content->date);
        $content->date = date("Y-m-d", strtotime($content->date));

        $rs = $request->attachment;

        $data = [
			'number' => $number,
            'reporter' => $reporter,
            'subject' => $subject,
            'type' => $type,
			'loss_time' => $loss_time,
			'unit' => $unit,
			'date' => $content->date,
			'time' => $time,
			'area_id' => $area_id,
			'status' => $status,
			'created_user' => $creation_user,
			'remarks' => $remarks,
		];

        $ok = SafeguardIncident::create($data);
        if ($ok) {
            $files = $request->attachment;
            foreach($files as $key => $tmp_name){
                $attachment = $request->file('attachment')[$key];
                if ($attachment->isValid()) {
                    $path = 'assets/safeguard/incident/';
                    if (!file_exists($path)) {
                        mkdir($path, 0777, true);
                    }
                    $attachment->move($path, $request->number.seo($attachment->getClientOriginalName()).'.'.$attachment->getClientOriginalExtension());
                    $filename = $path.$request->number.seo($attachment->getClientOriginalName()).'.'.$attachment->getClientOriginalExtension();
                    // $data['attachment'] = $filename;

                    $data = [
						'incident_id'=>$ok->id,
						'attachment'=>$filename,
					];
                    $attch = SafeguardIncidentAttachment::create($data);
					if(!$attch){
                        DB::rollback();
                        return response()->json([
                            'success'    => false,
                            'message'   => "Can't insert data incident atachment"
                        ], 400);
					}
                }
			}

            $witness = $request->witness;
			for ($i=0; $i < count($witness); $i++) { 
				$data_type = [
					'incident_id' => $ok->id,
					'user_id' => $request->witness[$i],
				];
				$exType = SafeguardIncidentWitness::create($data_type);
				if (!$exType) {
                    DB::rollback();
                    return response()->json([
                        'success'    => false,
                        'message'   => "Can't insert data incident witness"
                    ], 400);
				}
			}

            DB::commit();
            return response()->json([
                'success'     => true,
                'message'   => "Data has been saved",
                'message'   => $ok->id,
                'results'     => route('hseincident.index'),
            ], 200);
        }else{
            DB::rollback();
            return response()->json([
                'success'    => false,
                'message'   => "Can't insert data incident"
            ], 400);
        }
    }

    public function edit(Request $request,$id)
    {
        if(in_array('update',$request->actionmenu)){
            $data = SafeguardIncident::where("id", $id)->with('witness','attachment','area')->with(array('witness.user' => function($query) {
                $query->select('id','name');
            }))->first();
            $data->date = date("d/m/Y", strtotime($data->date));
            $type = 'edit';
            $userid = Auth::guard('admin')->user()->id;
            $site = Site::find($data->unit);
            if ($data) {
                return view('admin.incident.edit', compact('data','type','userid','site'));
            } else {
                abort(404);
            }
        }else{
            abort(403);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        $id    = $request->id;	
		$reporter  = $request->reporter;
		$type  = $request->type;
		$subject  = $request->subject;
		$loss_time  = $request->loss_time;
		$unit  = $request->unit;
		$date  = $request->date;
		$time  = $request->time;
		$area_id  = $request->area_id;
		$content  = $request;
        // $attachment = $request->file('attachment');
		$updated_user = Auth::guard('admin')->user()->id;
		$remarks  = $request->remarks;
		$status  = $request->status;
		$table = 'safeguard_incident';
        $content->date = str_replace("/","-",$content->date);
        $content->date = date("Y-m-d", strtotime($content->date));

        $data = [
            'subject' => $subject,
            'type' => $type,
			'loss_time' => $loss_time,
			'unit' => $unit,
			'date' => $content->date,
			'time' => $time,
			'area_id' => $area_id,
			'status' => $status,
			'updated_user' => $updated_user,
			'remarks' => $remarks,
		];

        $ok = SafeguardIncident::find($id);
        $ok->update($data);
        if ($ok) {
            $files = $request->attachment;
            if($files){
                function test_odd($var)
                {
                    return($var);
                }
                $files2 = array_filter($files,"test_odd");
                if(count($files2)>0){
                    $attach = SafeguardIncidentAttachment::where('incident_id',$id);
                    $attach->delete();
                }

                foreach($files as $key => $tmp_name){
                    $attachment = $request->file('attachment')[$key];
                    if ($attachment->isValid()) {
                        $path = 'assets/safeguard/incident/';
                        if (!file_exists($path)) {
                            mkdir($path, 0777, true);
                        }
                        $attachment->move($path, $request->number.seo($attachment->getClientOriginalName()).'.'.$attachment->getClientOriginalExtension());
                        $filename = $path.$request->number.seo($attachment->getClientOriginalName()).'.'.$attachment->getClientOriginalExtension();

                        $data = [
                            'incident_id'=>$ok->id,
                            'attachment'=>$filename,
                        ];
                        $attch = SafeguardIncidentAttachment::create($data);
                        if(!$attch){
                            DB::rollback();
                            return response()->json([
                                'success'    => false,
                                'message'   => "Can't insert data incident atachment"
                            ], 400);
                        }
                    }
                }
            }

            $witness = SafeguardIncidentWitness::where('incident_id',$id);
            $witness->delete();
            $witness = $request->witness;
			for ($i=0; $i < count($witness); $i++) { 
				$data_type = [
					'incident_id' => $ok->id,
					'user_id' => $request->witness[$i],
				];
				$exType = SafeguardIncidentWitness::create($data_type);
				if (!$exType) {
                    DB::rollback();
                    return response()->json([
                        'success'    => false,
                        'message'   => "Can't insert data incident witness"
                    ], 400);
				}
			}

            DB::commit();
            return response()->json([
                'success'     => true,
                'message'   => "Data has been updated",
                'message'   => $ok->id,
                'results'     => route('hseincident.index'),
            ], 200);
        }else{
            DB::rollback();
            return response()->json([
                'success'    => false,
                'message'   => "Can't update data incident"
            ], 400);
        }
    }

    public function show(Request $request,$id)
    {
        if(in_array('update',$request->actionmenu)){
            $data = SafeguardIncident::where("id", $id)->with('witness','attachments','area')->with(array('witness.user' => function($query) {
                $query->select('id','name');
            }))->first();
            $data->date = date("d/m/Y", strtotime($data->date));
            $type = 'view';
            $userid = Auth::guard('admin')->user()->id;
            $cek_spv = Auth::guard('admin')->user()->id;
            $realname = Auth::guard('admin')->user()->name;
            $site = Site::find($data->unit);
            if ($data) {
                return view('admin.incident.edit', compact('data','type','userid','site','realname','cek_spv'));
            } else {
                abort(404);
            }
        }else{
            abort(403);
        }
    }

    public function approved(Request $request){
        DB::beginTransaction();

        $id    = $request->id;
		$status  = $request->status;
		$comment  = $request->comment;
		$attachment = $request->file('attachment');
		$updated_user = Auth::guard('admin')->user()->id;
		$table = 'safeguard_incident';

        $data = [
			'status' => $status,
			'comment' => $comment,
			'updated_user' => $updated_user,
		];

        if ($request->hasFile('attachment')) {
            $path = 'assets/safeguard/incident/comment/';
			if (!file_exists($path)) {
				mkdir($path, 0777, true);
            }
            $attachment->move($path, $request->number.seo($attachment->getClientOriginalName()).'.'.$attachment->getClientOriginalExtension());
            $filename = $path.$request->number.seo($attachment->getClientOriginalName()).'.'.$attachment->getClientOriginalExtension();
			$data['attachment'] = $filename;
        }

        $ok = SafeguardIncident::find($id);
        $ok->update($data);
        if ($ok) {
            $inc = SafeguardIncident::find($id);
			if($status == 'approved'){
				// if($inc->type == 'fatality' or $inc->type == 'major'){
				// 	$data = [
				// 		'timer' => date('Y-m-d H:i:s'),
				// 	];
				// 	$this->db->where('id', 1);
				// 	$ok = $this->db->update('incident_timer', $data);
				// 	if(!$ok){
				// 		$result['success'] = false;
				// 		$result['message'] = "Can't insert data incident";
				// 		$this->db->trans_rollback();
				// 		return json_encode($result);		
				// 	}
				// }
			}

            DB::commit();
            return response()->json([
                'success'     => true,
                'message'   => "Data has been updated",
                'message'   => $ok->id,
                'results'     => route('hseincident.index'),
            ], 200);
        }else{
            DB::rollback();
            return response()->json([
                'success'    => false,
                'message'   => "Can't update data incident"
            ], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $incident = SafeguardIncident::find($id);
            $incident->delete();
        } catch (QueryException $th) {
            return response()->json([
                'success'    => false,
                'message'   => 'Error delete data ' . $th->errorInfo[2]
            ], 400);
        }
        return response()->json([
            'success'    => true,
            'message'   => 'Success delete data'
        ], 200);
    }
}
