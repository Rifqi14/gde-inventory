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

use App\Models\DcCategory;

class DcCategoryController extends Controller
{
    function __construct()
    {
        View::share('menu_active', url('admin/' . 'documentcategory'));
        $this->middleware('accessmenu', ['except' => ['select']]);
    }

    public function index()
    {
        return view('admin.dccategory.index');
    }

    public function read(Request $request){
        $start = $request->start;
        $length = $request->length;
        $query = $request->search['value'];
        $sort = $request->columns[$request->order[0]['column']]['data'];
        $dir = $request->order[0]['dir'];
        $name = strtoupper($request->name);
        $type = strtoupper($request->type);

        //Count Data
        $query = DcCategory::select('*');
        $query->whereRaw("upper(name) like '%$name%'");
        $query->whereRaw("upper(type) like '%$type%'");

        $row = clone $query;
        $recordsTotal = $row->count();

        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $dccategorys = $query->get();

        $data = [];
        foreach ($dccategorys as $dccategory) {
            $dccategory->no = ++$start;
            $data[] = $dccategory;
        }
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data
        ], 200);
    }

    public function create(Request $request)
    {
        if(in_array('create',$request->actionmenu)){
            return view('admin.dccategory.create');
        }else{
            abort(403);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type'              => 'required',
            'name'              => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }

        $dccategory = DcCategory::create([
            'type'                  => $request->type,
            'name'                  => $request->name
        ]);
        if (!$dccategory) {
            return response()->json([
                'status'    => false,
                'message'   => "failed Insert Document Category"
            ], 400);
        }
        return response()->json([
            'status'     => true,
            'results'     => route('documentcategory.index'),
        ], 200);
    }

    public function edit(Request $request,$id)
    {
        if(in_array('update',$request->actionmenu)){
            $dccategory = DcCategory::find($id);
            if ($dccategory) {
                return view('admin.dccategory.edit', compact('dccategory'));
            } else {
                abort(404);
            }
        }else{
            abort(403);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'type'              => 'required',
            'name'              => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }

        $dccategory = DcCategory::find($id);
        $dccategory->type = $request->type;
        $dccategory->name = $request->name;
        $dccategory->save();
        if (!$dccategory) {
            return response()->json([
                'status' => false,
                'message'     => "Failed Update Document Category"
            ], 400);
        }

        return response()->json([
            'status'     => true,
            'results'     => route('documentcategory.index'),
        ], 200);
    }

    public function destroy($id)
    {
        try {
            $dccategory = DcCategory::find($id);
            $dccategory->delete();
        } catch (QueryException $th) {
            return response()->json([
                'status'    => false,
                'message'   => 'Error delete data ' . $th->errorInfo[2]
            ], 400);
        }
        return response()->json([
            'status'    => true,
            'message'   => 'Failed delete data'
        ], 200);
    }

    public function show(Request $request,$id)
    {
        if(in_array('read',$request->actionmenu)){
            $dccategory = DcCategory::find($id);
            if ($dccategory) {
                return view('admin.dccategory.detail', compact('dccategory'));
            } else {
                abort(404);
            }
        }else{
            abort(403);
        }
    }
}
