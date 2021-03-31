<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Role;
use App\Models\RoleUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $url    = route('user.store');
        return view('admin.user.create', compact('url'));
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
            'group_id' => 'required',
            'realname' => 'required',
            'username' => 'required|unique:users',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 200);
        }

        $user = User::create([
            'name' => $request->realname,
            'email' => $request->email,
            'username' => $request->username,
            'password' => Hash::make($request->password),
            'is_active' => $request->is_active ? 1 : 0,
            'spv_id' => $request->spv_id ? $request->spv_id : null,
        ]);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message'     => 'Cant create user'
            ], 200);
        }

        $group = RoleUser::create([
            'role_id' => $request->group_id,
            'user_id' => $user->id
        ]);

        if (!$group) {
            return response()->json([
                'status' => false,
                'message'     => "Cant create user"
            ], 200);
        }

        // $images = $request->file('images');
        // $name   = strtolower(str_replace(" ", "-", $user->name));
        // $path   = 'assets/users/';

        // if ($images) {
        //     $images->move($path, $name . '.' . $images->getClientOriginalExtension());
        //     $filename = $path . $name . '.' . $images->getClientOriginalExtension();
        //     $user->images = $filename ? $filename : '';
        //     $user->save();
        // } else {
        //     $user->images = $path . "placeholder.jpg";
        //     $user->save();
        // }

        // $role = Role::find($request->role_id);
        // $user->attachRole($role);
        return response()->json([
            'status'     => true,
            'message'     => 'Created success',
            'results'     => route('user.index'),
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
        $query = DB::table('users');
        $query->select('users.*', 'roles.name as group_description', 'role_users.role_id', 'spv.name as spv_name');
        $query->leftJoin('users as spv', 'spv.id', '=', 'users.spv_id');
        $query->leftJoin('role_users', 'role_users.user_id', '=', 'users.id');
        $query->leftJoin('roles', 'role_users.role_id', '=', 'roles.id');
        $query->where('users.id', '=', $id);
        $user = $query->get()->first();
        if ($user) {
            return view('admin.user.detail', compact('user'));
        } else {
            abort(404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $query = DB::table('users');
        $query->select('users.*', 'roles.name as group_description', 'role_users.role_id', 'spv.name as spv_name');
        $query->leftJoin('users as spv', 'spv.id', '=', 'users.spv_id');
        $query->leftJoin('role_users', 'role_users.user_id', '=', 'users.id');
        $query->leftJoin('roles', 'role_users.role_id', '=', 'roles.id');
        $query->where('users.id', '=', $id);
        $user = $query->get()->first();

        $url    = route('user.update', ['id' => $user->id]);
        if ($user) {
            return view('admin.user.edit', compact('user', 'url'));
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
            'group_id'     => 'required',
            'realname' => 'required',
            'username' => 'required|unique:users,username,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }

        $user = User::find($id);
        $user->name = $request->realname;
        $user->username = $request->username;
        $user->is_active = $request->is_active ? 1 : 0;
        $user->spv_id = $request->spv_id ? $request->spv_id : null;
        if ($request->password) {
            $user->password    = Hash::make($request->password);
        }
        $user->save();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message'     => $user
            ], 400);
        }

        return response()->json([
            'status'     => true,
            'results'     => route('user.index'),
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
            $user = User::find($id);
            $user->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status'     => false,
                'message'     => 'Error delete data'
            ], 400);
        }
        return response()->json([
            'status'     => true,
            'message' => 'Success delete data'
        ], 200);
    }

    public function select(Request $request)
    {
        $start = $request->page ? $request->page - 1 : 0;
        $length = $request->limit;
        $name = strtoupper($request->name);

        //Count Data
        $query = DB::table('users');
        $query->select('users.*');
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

    public function read(Request $request)
    {
        $start = $request->start;
        $length = $request->length;
        $query = $request->search['value'];
        $sort = $request->columns[$request->order[0]['column']]['data'];
        $dir = $request->order[0]['dir'];
        $display_name = strtoupper($request->display_name);
        $name = strtoupper($request->realname);
        $username = strtoupper($request->username);
        $email = strtoupper($request->email);
        $status = strtoupper($request->status);

        //Count Data
        $query = DB::table('users');
        $query->select('users.*', 'roles.name as group_description');
        $query->leftJoin('role_users', 'role_users.user_id', '=', 'users.id');
        $query->leftJoin('roles', 'role_users.role_id', '=', 'roles.id');
        $query->whereRaw("upper(roles.name) like '%$display_name%'");
        $query->whereRaw("upper(users.name) like '%$name%'");
        $query->whereRaw("upper(users.username) like '%$username%'");
        $query->whereRaw("upper(email) like '%$email%'");
        if ($request->status != '') {
            $query->where('status', '=', $request->status);
        }

        $row = clone $query;
        $recordsTotal = $row->count();

        //Select Pagination
        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $users = $query->get();

        $data = [];
        foreach ($users as $user) {
            $user->no = ++$start;
            $data[] = $user;
        }
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data
        ], 200);
    }

    public function supervisor_read(Request $request)
    {
        $start = $request->page ? $request->page - 1 : 0;
        $length = $request->limit;
        $name = strtoupper($request->name);
        // $creation_user = Auth::guard('admin')->user()->id;

        //Count Data
        $query = DB::table('users');
        $query->select('users.*');
        $query->whereRaw("upper(name) like '%$name%'");
        // $query->where('id', '!=', $creation_user);

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
