<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\Role;
use App\Models\MenuRole;
use Illuminate\Database\Eloquent\Builder;

class RoleController extends Controller
{
    function __construct()
    {
        View::share('menu_active', url('admin/' . 'role'));
        $this->middleware('accessmenu', ['except' => ['select','set','selectitle']]);
    }

    public function select(Request $request)
    {
        $start = $request->page ? $request->page - 1 : 0;
        $length = $request->limit;
        $name = strtoupper($request->name);
        $eliminate = $request->eliminate;
        $ownership = $request->ownership;

        //Count Data
        // $query = DB::table('roles');
        // $query->select('roles.*');
        // $query->whereRaw("upper(name) like '%$name%'");
        $query = Role::when($ownership, function(\Illuminate\Database\Eloquent\Builder $q) {
            $q->whereHas('categoryContractors.contractor', function(\Illuminate\Database\Eloquent\Builder $que){
                $que->where('ownership', false);
                $que->where('ownership', true);
            });
        })->whereRaw("upper(name) like '%$name%'");
        if ($eliminate == 'true') {
            $query->doesnthave('categoryContractors');
        }

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

    public function index(){
        return view('admin.role.index');
    }

    public function read(Request $request)
    {
        $start = $request->start;
        $length = $request->length;
        $query = $request->search['value'];
        $sort = $request->columns[$request->order[0]['column']]['data'];
        $dir = $request->order[0]['dir'];
        $name = strtoupper($request->name);
        $code = strtoupper($request->code);

        //Count Data
        $query = DB::table('roles');
        $query->select('roles.*');
        $query->whereRaw("upper(name) like '%$name%'");
        $query->whereRaw("upper(code) like '%$code%'");
        $recordsTotal = $query->count();

        //Select Pagination
        $query = DB::table('roles');
        $query->select('roles.*');
        $query->whereRaw("upper(name) like '%$name%'");
        $query->whereRaw("upper(code) like '%$code%'");
        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $roles = $query->get();

        $data = [];
        foreach ($roles as $role) {
            $role->no = ++$start;
            $data[] = $role;
        }
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data
        ], 200);
    }

    public function create(){
        return view('admin.role.create');
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
            'name'         => 'required|unique:roles',
            'code'     => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }

        $role = Role::create([
            'code'     => $request->code,
            'name'     => $request->name,
            'data_manager'     => $request->data_manager?1:0,
            'guest'     => $request->guest?1:0,
        ]);
        if($request->guest){
            Role::where('id','<>',$role->id)->update([
                'guest'=>0
            ]);
        }
        if (!$role) {
            return response()->json([
                'status' => false,
                'message'     => $role
            ], 400);
        }
        return response()->json([
            'status'     => true,
            'results'     => route('role.index'),
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $role = Role::find($id);
        if ($role) {
            return view('admin.role.edit', compact('role'));
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
            'name'         => 'required|unique:roles,name,' . $id,
            'code'     => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }

        $role = Role::find($id);
        $role->name = $request->name;
        $role->code = $request->code;
        $role->data_manager = $request->data_manager?1:0;
        $role->guest = $request->guest?1:0;
        $role->save();
        if (!$role) {
            return response()->json([
                'status' => false,
                'message'     => $role
            ], 400);
        }
        if($request->guest){
            Role::where('id','<>',$role->id)->update([
                'guest'=>0
            ]);
        }
        return response()->json([
            'status'     => true,
            'results'     => route('role.index'),
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
        $role = Role::find($id);
        if ($role) {
            $query = DB::table('menus');
            $query->select('menus.*');
            $query->orderBy('menus.menu_sort', 'asc');
            $menus = $query->get();
            foreach ($menus as $menu) {
                $rolemenu = MenuRole::where('menu_id', $menu->id)
                    ->where('role_id', $id)
                    ->get()->first();
                if (!$rolemenu) {
                    MenuRole::create([
                        'role_id' => $id,
                        'menu_id' => $menu->id,
                        'role_access' => 0,
                        'create' => 0,
                        'read' => 0,
                        'update' => 0,
                        'delete' => 0,
                        'import' => 0,
                        'export' => 0,
                        'print' => 0,
                        'approval' => 0,
                    ]);
                }
            }
            $rolemenus = MenuRole::select('menu_roles.*', 'menus.menu_name', 'menus.parent_id')
                ->where('role_id', $id)
                ->leftJoin('menus', 'menus.id', '=', 'menu_roles.menu_id')
                ->orderBy('menus.menu_sort', 'asc')
                ->get();
            return view('admin.role.detail', compact('role', 'rolemenus'));
        } else {
            abort(404);
        }
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
            $role = Role::find($id);
            $role->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status'     => false,
                'message'     => 'Data has been used to another page'
            ], 400);
        }
        return response()->json([
            'status'     => true,
            'message' => 'Success delete data'
        ], 200);
    }

    public function set(Request $request)
    {
        $request->session()->put('role_id', $request->id);
        return redirect()->back();
    }
}
