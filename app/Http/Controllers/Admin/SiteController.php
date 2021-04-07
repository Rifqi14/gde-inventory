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

use App\Models\Site;

class SiteController extends Controller
{
    function __construct()
    {
        View::share('menu_active', url('admin/' . 'site'));
        $this->middleware('accessmenu', ['except' => ['select', 'set']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function read(Request $request)
    {
        $start = $request->start;
        $length = $request->length;
        $query = $request->search['value'];
        $sort = $request->columns[$request->order[0]['column']]['data'];
        $dir = $request->order[0]['dir'];
        $code = strtoupper($request->code);
        $name = strtoupper($request->name);
        $category = $request->category;

        //Count Data
        $query = Site::select('sites.*');
        $query->select('sites.*');
        $query->whereRaw("upper(code) like '%$code%'");
        $query->whereRaw("upper(name) like '%$name%'");
        if ($category) {
            $query->onlyTrashed();
        } 
        $recordsTotal = $query->count();

        //Select Pagination
        $query = Site::select('sites.*');
        $query->whereRaw("upper(code) like '%$code%'");
        $query->whereRaw("upper(name) like '%$name%'");
        if ($category) {
            $query->onlyTrashed();
        } 
        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $sites = $query->get();

        $data = [];
        foreach ($sites as $site) {
            $site->no = ++$start;
            $data[] = $site;
        }
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data
        ], 200);
    }

    public function index()
    {
        return view('admin.site.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        // if(in_array('create',$request->actionmenu)){
            return view('admin.site.create');
        // }else{
            // abort(403);
        // }
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
            'code'              => 'required|unique:sites',
            'name'              => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }

        $site = Site::create([
            'code'                  => $request->code,
            'name'                  => $request->name
        ]);
        if (!$site) {
            return response()->json([
                'status'    => false,
                'message'   => $site
            ], 400);
        }
        $logo = $request->file('logo');
        if ($logo) {
            $path = 'assets/site/';
            $logo->move($path, $site->code . '.' . $logo->getClientOriginalExtension());
            $filename = $path . $site->code . '.' . $logo->getClientOriginalExtension();
            $site->logo = $filename ? $filename : '';
            $site->save();
        }
        return response()->json([
            'status'     => true,
            'results'     => route('site.index'),
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        // if(in_array('update',$request->actionmenu)){
            // $site = Site::withTrashed()->find($id);
            $site = Site::find($id);
            // if ($site) {
                return view('admin.site.edit', compact('site'));
            // } else {
                // abort(404);
            // }
        // }else{
            // abort(403);
        // }
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
            'code'              => 'required|unique:sites,code,' . $id,
            'name'              => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }

        $site = Site::find($id);
        $site->code = $request->code;
        $site->name = $request->name;
        $site->save();
        if (!$site) {
            return response()->json([
                'status' => false,
                'message'     => $site
            ], 400);
        }
        $logo = $request->file('logo');
        if ($logo) {
            if (file_exists($site->logo)) {
                unlink($site->logo);
            }
            $path = 'assets/site/';
            $logo->move($path, $site->code . '.' . $logo->getClientOriginalExtension());
            $filename = $path . $site->code . '.' . $logo->getClientOriginalExtension();
            $site->logo = $filename ? $filename : '';
            $site->save();
        }

        return response()->json([
            'status'     => true,
            'results'     => route('site.index'),
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
            $site = Site::find($id);
            $site->delete();
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
        // if(in_array('read',$request->actionmenu)){
            // $site = Site::withTrashed()->find($id);
            $site = Site::find($id);
            // if ($site) {
                return view('admin.site.detail', compact('site'));
            // } else {
                // abort(404);
            // }
        // }else{
            // abort(403);
        // }
    }
    
    public function select(Request $request)
    {
        $start = $request->page ? $request->page - 1 : 0;
        $length = $request->limit;
        $name = strtoupper($request->name);

        //Count Data
        $query = DB::table('sites');
        $query->select('sites.*');
        $query->whereRaw("upper(name) like '%$name%'");

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
}
