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

use App\User;
use App\Models\Site;
use App\Models\GrievanceRedress;
use App\Models\GrievanceRedressFocal;
use App\Models\GrievanceRedressHistory;
use App\Models\GrievanceRedressReport;
use App\Models\GrievanceRedressReportBudget;

class GrievanceController extends Controller
{
    public function __construct()
    {
        $menu   = Menu::where('menu_route', 'grievance')->first();
        $parent = Menu::find($menu->parent_id);
        View::share('parent_name', $parent->menu_name);
        View::share('menu_name', $menu->menu_name);
        View::share('menu_active', url('admin/grievance'));
        $this->middleware('accessmenu', ['except' => ['select']]);
    }

    public function index($type="app")
    {
        $userid = Auth::guard('admin')->user()->id;
        return view('admin.grievance.index', compact('userid','type'));
    }

    public function create(Request $request)
    {
        if (in_array('create', $request->actionmenu)) {
            $userid = Auth::guard('admin')->user()->id;
            $code = GrievanceRedress::select("number")->orderBy("id","desc")->first();
		    $number = generateCode("GRM", @$code->number);
            return view('admin.grievance.create', compact('userid','number'));
        } else {
            abort(403);
        }
    }

    public function read(Request $request)
    {
        $start = $request->start;
        $length = $request->length;
        $query = $request->search['value'];
        $sort = $request->columns[$request->order[0]['column']]['data'];
        $dir = $request->order[0]['dir'];

        $query = GrievanceRedress::select('*');

        $row = clone $query;
        $recordsTotal = $row->count();

        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $query->orderBy('created_at', 'desc');
        $grievances = $query->get();

        $data = [];
        foreach($grievances as $grievance){
            $grievance->no = ++$start;
            if($grievance->unit){
                $grievance->unit_name = Site::find($grievance->unit)->name;
            }else{
                $grievance->unit_name = '-';
            }
			$data[] = $grievance;
		}
        return response()->json([
            'draw'=>$request->draw,
			'recordsTotal'=>$recordsTotal,
			'recordsFiltered'=>$recordsTotal,
			'data'=>$data
        ], 200);
    }

    public function reportread(Request $request)
    {
        $start = $request->start;
        $length = $request->length;
        $query = $request->search['value'];
        $sort = $request->columns[$request->order[0]['column']]['data'];
        $dir = $request->order[0]['dir'];

        $query = GrievanceRedressReport::select('*');
        $query->with('grievance_redress');

        $row = clone $query;
        $recordsTotal = $row->count();

        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $query->orderBy('created_at', 'desc');
        $grievances = $query->get();

        $data = [];
        foreach($grievances as $grievance){
            $grievance->no = ++$start;
            if($grievance->grievance_redress->unit){
                $grievance->unit_name = Site::find($grievance->grievance_redress->unit)->name;
            }else{
                $grievance->unit_name = '-';
            }
            $grievance->number = $grievance->grievance_redress->number;
            $grievance->complainant = $grievance->grievance_redress->complainant;
            $grievance->date = date('d/m/Y', strtotime($grievance->grievance_redress->date));
            $grievance->time = $grievance->grievance_redress->time;
            $grievance->approval_status = $grievance->grievance_redress->approval_status;
            $grievance->location = $grievance->grievance_redress->location;
            if($grievance->updated_at){
                // $grievance->updated_at = date('d/m/Y', strtotime($grievance->updated_at));
                $grievance->updated_at = $grievance->updated_at;
            }else{
                $grievance->updated_at = '';
            }
			$data[] = $grievance;
		}
        return response()->json([
            'draw'=>$request->draw,
			'recordsTotal'=>$recordsTotal,
			'recordsFiltered'=>$recordsTotal,
			'data'=>$data
        ], 200);
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        $code = GrievanceRedress::select("number")->orderBy("id","desc")->first();
        $number = generateCode("GRM", @$code->number);
		$content = $request;
		$attach = $request->file('attach');
		$idm_attach = $request->file('idm_attach');
		$creation_user = Auth::guard('admin')->user()->id;
		$reporter = Auth::guard('admin')->user()->name;
		$table = 'grievance_redress';
        $content->date = str_replace("/","-",$content->date);
        $content->date = date("Y-m-d", strtotime($content->date));

        $data = [
            'number' => $number,
            'date' => $content->date,
            'time' => $content->time,
            'media' => (isset($content->media)) ? ((count($content->media) > 0) ? json_encode($content->media):null):null,
            'unit' => $content->unit,
            'complainant' => $content->complainant,
            'gender' => $content->gender,
            'id_number' => $content->id_number,
            'address' => $content->address,
            'phone' => $content->phone?$content->phone:null,
            'fax' => $content->fax?$content->fax:null,
            'email' => $content->email?$content->email:null,
            'affiliation' => (isset($content->affiliation)) ? ((count($content->affiliation) > 0) ? json_encode($content->affiliation):null):null,
            'complaint_type' => (isset($content->affiliation)) ? ((count($content->type) > 0) ? json_encode($content->type):null):null,
            'complaint_type_other' => $content->other_type?$content->other_type:null,
            'location' => $content->location,
            'complaint_desc' => $content->description,
            'status' => $content->status,
			'created_user' => $creation_user,
			'reporter' => $reporter,
		];

        if($content->status == 'waiting'){
			$approv = 'queue';
			$data['queue_date'] = date("Y-m-d");
		} else if($content->status == 'revise'){
			$approv = 'registered';
		} else if($content->status == 'approved'){
			$approv = 'active';
		} else if($content->status == 'declined'){
			$approv = 'declined';
		} else {
			$approv = 'registered';
		}
		$data['approval_status'] = $approv;

        if ($request->hasFile('attach')) {
            $path = 'assets/safeguard/grievance';
			if (!file_exists($path)) {
				mkdir($path, 0777, true);
            }
            $attach->move($path, $request->number.seo($attach->getClientOriginalName()).'.'.$attach->getClientOriginalExtension());
            $filename = $path.$request->number.seo($attach->getClientOriginalName()).'.'.$attach->getClientOriginalExtension();
			$data['attachment'] = $filename;
        }

        if($request->idm_name != ""){
            $data['idm_name'] = $request->idm_name;
            $data['idm_id_number'] = $request->idm_id_number;
            $data['idm_address'] = $request->idm_address;
            $data['idm_phone'] = $request->idm_phone?$request->idm_phone:null;
            $data['idm_fax'] = $request->idm_fax?$request->idm_fax:null;
            $data['idm_email'] = $request->idm_email?$request->idm_email:null;

            if ($request->hasFile('idm_attach')) {
                $path = 'assets/safeguard/grievance';
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $idm_attach->move($path, $request->number.seo($attach->getClientOriginalName()).'.'.$idm_attach->getClientOriginalExtension());
                $filename = $path.$request->number.seo($attach->getClientOriginalName()).'.'.$idm_attach->getClientOriginalExtension();
                $data['idm_attachment'] = $filename;
            }
        }

        $grievance = GrievanceRedress::create($data);

        if ($grievance) {
            $status_type = [
				'grievance_id' => $grievance->id,
				'status' => $approv,
				'created_at' => $grievance->created_at,
			];
            $grievance_redress_history = GrievanceRedressHistory::create($status_type);
            if (!$grievance_redress_history) {
                DB::rollback();
                return response()->json([
                    'success'    => false,
                    'message'   => "Can't insert data grievance"
                ], 400);
			}

            $witness = (isset($request->focal)) ? $request->focal:[];
			for ($i=0; $i < count($witness); $i++) { 
				$data_type = [
					'grievance_id' => $grievance->id,
					'user_id' => $request->focal[$i],
				];
                $grievance_redress_focal = GrievanceRedressFocal::create($data_type);
				if (!$grievance_redress_focal) {
                    DB::rollback();
                    return response()->json([
                        'success'    => false,
                        'message'   => "Can't insert data grievance"
                    ], 400);
				}
			}

            DB::commit();
            return response()->json([
                'success'     => true,
                'message'   => "Data has been saved",
                'results'     => route('grievance.index'),
            ], 200);
        }else{
            DB::rollback();
            return response()->json([
                'success'    => false,
                'message'   => "Can't insert data grievance"
            ], 400);
        }        
    }

    public function edit(Request $request,$id)
    {
        if(in_array('update',$request->actionmenu)){
            $data = GrievanceRedress::find($id);
            $data->affiliation = json_decode($data->affiliation);
            $data->complaint_type = json_decode($data->complaint_type);
            $data->media = json_decode($data->media);
            $focals = GrievanceRedressFocal::where("grievance_id",$id)->get();
            $focal = [];
            foreach($focals as $fc){
                $f = new \stdClass;
                $f->user_id = $fc->user_id;
                $f->realname = User::find($fc->user_id)->first()->name;
                $focal[] = $f;
            }
            $data->focal = $focal;
            $site = Site::find($data->unit);
            $userid = Auth::guard('admin')->user()->id;
            if ($data) {
                return view('admin.grievance.edit', compact('data','userid','site'));
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

        $id = $id;
		$content = $request;
		$attach = $request->file('attach');
		$idm_attach = $request->file('idm_attach');
		$updated_user = Auth::guard('admin')->user()->id;
		$table = 'grievance_redress';
        $content->date = str_replace("/","-",$content->date);
        $content->date = date("Y-m-d", strtotime($content->date));

        $data = [
            'date' => $content->date,
            'time' => $request->time,
			'media' => (isset($request->media)) ? ((count($request->media) > 0) ? json_encode($request->media):null):null,
            'unit' => $request->unit,
            'complainant' => $request->complainant,
            'gender' => $request->gender,
            'id_number' => $request->id_number,
            'address' => $request->address,
            'phone' => $request->phone?$request->phone:null,
            'fax' => $request->fax?$request->fax:null,
            'email' => $request->email?$request->email:null,
			'affiliation' => (isset($request->affiliation)) ? ((count($request->affiliation) > 0) ? json_encode($request->affiliation):null):null,
            'complaint_type' => (isset($request->affiliation)) ? ((count($request->type) > 0) ? json_encode($request->type):null):null,
            'complaint_type_other' => $request->other_type?$request->other_type:null,
            'location' => $request->location,
            'complaint_desc' => $request->description,
            'status' => $request->status,
			'updated_user' => $updated_user,
		];

        if($request->status == 'waiting'){
			$approv = 'queue';
			$data['queue_date'] = date("Y-m-d");
		} else if($request->status == 'revise'){
			$approv = 'registered';
		} else if($request->status == 'approved'){
			$approv = 'active';
		} else if($request->status == 'declined'){
			$approv = 'declined';
		} else {
			$approv = 'registered';
		}
		$data['approval_status'] = $approv;

        $history = GrievanceRedressHistory::where('grievance_id',$id)->where('status',$approv);
        $history->delete();

		$status_type = [
			'grievance_id' => $id,
			'status' => $approv,
		];
		$st = GrievanceRedressHistory::create($status_type);
		if (!$st) {
            DB::rollback();
            return response()->json([
                'success'    => false,
                'message'   => "Can't update data grievance"
            ], 400);
		}

        if ($request->hasFile('attach')) {
            $path = 'assets/safeguard/grievance';
			if (!file_exists($path)) {
				mkdir($path, 0777, true);
            }
            $attach->move($path, $request->number.seo($attach->getClientOriginalName()).'.'.$attach->getClientOriginalExtension());
            $filename = $path.$request->number.seo($attach->getClientOriginalName()).'.'.$attach->getClientOriginalExtension();
			$data['attachment'] = $filename;
        }

        if($request->idm_name != ""){
            $data['idm_name'] = $request->idm_name;
            $data['idm_id_number'] = $request->idm_id_number;
            $data['idm_address'] = $request->idm_address;
            $data['idm_phone'] = $request->idm_phone?$request->idm_phone:null;
            $data['idm_fax'] = $request->idm_fax?$request->idm_fax:null;
            $data['idm_email'] = $request->idm_email?$request->idm_email:null;

            if ($request->hasFile('idm_attach')) {
                $path = 'assets/safeguard/grievance';
                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }
                $idm_attach->move($path, $request->number.seo($attach->getClientOriginalName()).'.'.$idm_attach->getClientOriginalExtension());
                $filename = $path.$request->number.seo($attach->getClientOriginalName()).'.'.$idm_attach->getClientOriginalExtension();
                $data['idm_attachment'] = $filename;
            }
        }

        $grievance = GrievanceRedress::find($id);
        $grievance->update($data);

        if ($grievance) {
            $focal = GrievanceRedressFocal::where('grievance_id',$id);
            $focal->delete();

			$witness = (isset($request->focal)) ? $request->focal:[];
			for ($i=0; $i < count($witness); $i++) { 
				$data_type = [
					'grievance_id' => $grievance->id,
					'user_id' => $request->focal[$i],
				];
                $grievance_redress_focal = GrievanceRedressFocal::create($data_type);
				if (!$grievance_redress_focal) {
                    DB::rollback();
                    return response()->json([
                        'success'    => false,
                        'message'   => "Can't insert data grievance focal"
                    ], 400);
				}
			}

            DB::commit();
            return response()->json([
                'success'     => true,
                'message'   => "Data has been updated",
                'results'     => route('grievance.index'),
            ], 200);
		} else {
			DB::rollback();
            return response()->json([
                'success'    => false,
                'message'   => "Can't update data grievance"
            ], 400);
		}

    }

    public function show(Request $request,$id)
    {
        if(in_array('update',$request->actionmenu)){
            $data = GrievanceRedress::find($id);
            $data->affiliation = json_decode($data->affiliation);
            $data->complaint_type = json_decode($data->complaint_type);
            $data->media = json_decode($data->media);
            $focals = GrievanceRedressFocal::where("grievance_id",$id)->get();
            $focal = [];
            foreach($focals as $fc){
                $f = new \stdClass;
                $f->user_id = $fc->user_id;
                $f->realname = User::find($fc->user_id)->name;
                $focal[] = $f;
            }
            $data->focal = $focal;
            $site = Site::find($data->unit);
            $userid = Auth::guard('admin')->user()->id;
            $cek_spv = Auth::guard('admin')->user()->id;
            $data->spv_id = User::find($data->created_user)->spv_id;
            if ($data) {
                return view('admin.grievance.detail', compact('data','userid','site', 'cek_spv'));
            } else {
                abort(404);
            }
        }else{
            abort(403);
        }
    }

    public function update_status(Request $request)
    {
        DB::beginTransaction();

        $id = $request->id;
		$status = $request->status;
		$comment  = $request->comment;
		$attach = $request->file('attach');
		$updated_user = Auth::guard('admin')->user()->id;
		$table = 'grievance_redress';

        $data = [
			'status' => $status,
			'comment' => $comment,
			'updated_user' => $updated_user,
		];

        if ($request->hasFile('attach')) {
            $path = 'assets/safeguard/grievance/comment';
			if (!file_exists($path)) {
				mkdir($path, 0777, true);
            }
            $attach->move($path, $request->number.seo($attach->getClientOriginalName()).'.'.$attach->getClientOriginalExtension());
            $filename = $path.$request->number.seo($attach->getClientOriginalName()).'.'.$attach->getClientOriginalExtension();
			$data['attachment_comment'] = $filename;
        }

        if($status == 'approved'){
			$data['approval_status'] = 'active';
			$data['active_date'] = date("Y-m-d");
		} else if($status == 'revise'){
			$data['approval_status'] = 'registered';
			$data['updated_at'] = date("Y-m-d");
		} else if($status == 'reject'){
			$data['approval_status'] = 'declined';
			$data['declined_date'] = date("Y-m-d");
		}

        $history = GrievanceRedressHistory::where('grievance_id',$id)->where('status',$data['approval_status']);
        $history->delete();
        $status_type = [
			'grievance_id' => $id,
			'status' => $data['approval_status'],
		];
		$st = GrievanceRedressHistory::create($status_type);
		if (!$st) {
			DB::rollback();
            return response()->json([
                'success'    => false,
                'message'   => "Can't update data grievance"
            ], 400);
		}

        $grievance = GrievanceRedress::find($id);
        $grievance->update($data);

        if ($grievance) {
            if($status == 'approved'){
				$data = [
					'grievance_id' => $id,
					'status' => "draft",
					'created_user' => $updated_user,
				];
				$ok = GrievanceRedressReport::create($data);
				if(!$ok){
					DB::rollback();
                    return response()->json([
                        'success'    => false,
                        'message'   => "Can't insert data grievance report"
                    ], 400);
				}
			}

            DB::commit();
            return response()->json([
                'success'     => true,
                'message'   => "Data has been updated",
                'results'     => route('grievance.index'),
            ], 200);
        }else{
            DB::rollback();
            return response()->json([
                'success'    => false,
                'message'   => "Can't update data grievance"
            ], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $grievance = GrievanceRedress::find($id);
            $grievance->delete();

            $focal = GrievanceRedressFocal::where('grievance_id',$id);
            $focal->delete();

            $history = GrievanceRedressHistory::where('grievance_id',$id);
            $history->delete();
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
