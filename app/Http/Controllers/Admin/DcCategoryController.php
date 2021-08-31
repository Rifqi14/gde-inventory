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
        $menu_id            = $request->menu_id;
        $document_type_id   = $request->document_type_id;

        //Count Data
        $query = DcCategory::with(['menu', 'doctype']);
        if ($menu_id) {
            $query->where('menu_id', $menu_id);
        }
        if ($document_type_id) {
            $query->where('document_type_id', $document_type_id);
        }

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
            'menu_id'           => 'required',
            'document_type_id'  => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        try {
            foreach ($request->menu_id as $key => $menu) {
                $exists = DcCategory::where([['menu_id', '=', $menu], ['document_type_id', '=', $request->document_type_id]])->first();
                if (!$exists) {
                    $dccategory = DcCategory::create([
                        'menu_id'           => $menu,
                        'document_type_id'  => $request->document_type_id,
                    ]);
                }
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => "Error create data: {$ex->errorInfo[2]}",
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status'    => true,
            'results'   => route('doccenterproperties.index'),
        ], 200);
    }

    public function edit(Request $request,$id)
    {
        if(in_array('update',$request->actionmenu)){
            $dccategory = DcCategory::with(['menu', 'doctype'])->find($id);
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
            'menu_id'           => 'required',
            'document_type_id'  => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        try {
            $exists     = DcCategory::where([['menu_id', '=', $request->menu_id], ['document_type_id', '=', $request->document_type_id], ['id', '<>', $id]])->first();
            if ($exists) {
                DB::rollBack();
                return response()->json([
                    'status'    => false,
                    'message'   => "This data already exists",
                ], 400);
            }
            $dccategory = DcCategory::find($id);
            $dccategory->menu_id            = $request->menu_id;
            $dccategory->document_type_id   = $request->document_type_id;
            $dccategory->save();
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => "Error update data: {$ex->errorInfo[2]}",
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status'     => true,
            'results'     => route('doccenterproperties.index'),
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
            'message'   => 'Success delete data'
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
