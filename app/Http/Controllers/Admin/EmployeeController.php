<?php

namespace App\Http\Controllers\Admin;

use App\User;
use App\Models\Employee;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\RoleUser;
use App\Models\SiteUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $menu   = Menu::where('menu_route', 'employee')->first();
        $parent = Menu::find($menu->parent_id);
        View::share('parent_name', $parent->menu_name);
        View::share('menu_name', $menu->menu_name);
        View::share('menu_active', url('admin' . 'employee'));
        $this->middleware('accessmenu', ['except' => ['select']]);
    }

    public function index()
    {
        return view('admin.employee.index');
    }

    public function create()
    {
        $url = route('employee.store');
        return view('admin.employee.create', compact('url'));
    }

    public function edit($id)
    {
        $employee = Employee::with([
            'user',
            'user.sites',
            'user.roles',
            'user.spv',
            'region' => function ($q) {
                $q->selectRaw('regions.id,regions.name,regions.province_id');
            },
            'province' => function ($q) {
                $q->selectRaw('provinces.id,provinces.name');
            },
            'workingshift' => function ($q) {
                $q->selectRaw('working_shifts.id,working_shifts.shift_name');
            }
        ])->find($id);
        if ($employee) {
            $data = $employee;
            return view('admin.employee.edit', compact('data'));
        } else {
            abort(404);
        }
    }

    public function detail($id)
    {
        $employee = Employee::with([
            'user',
            'region' => function ($q) {
                $q->selectRaw('regions.id,regions.name,regions.province_id');
            },
            'province' => function ($q) {
                $q->selectRaw('provinces.id,provinces.name');
            },
            'workingshift' => function ($q) {
                $q->selectRaw('working_shifts.id,working_shifts.shift_name');
            }
        ])->find($id);
        if ($employee) {
            $data = $employee;
            return view('admin.employee.detail', compact('data'));
        } else {
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
        $nik     = $request->employee_nik;
        $name    = strtoupper($request->employee_name);
        $address = strtoupper($request->employee_address);

        $employee = Employee::query();
        $employee->selectRaw('id,nik,name,address');
        if ($name) {
            $employee->whereRaw("upper(name) like '%$name%'");
        }
        if ($address) {
            $employee->whereRaw("upper(address) like '%$address%'");
        }
        if ($nik) {
            $employee->whereRaw("nik like '%$nik%'");
        }

        $rows  = clone $employee;
        $total = $employee->count();

        $employee->offset($start);
        $employee->limit($length);
        $employee->orderBy($sort, $dir);
        $employee = $employee->get();

        $data = [];
        foreach ($employee as $key => $row) {
            $row->no = ++$start;
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
        $start = $request->page ? $request->page - 1 : 0;
        $length = $request->limit;
        $name = strtoupper($request->name);

        $query = Employee::query();
        if($name){
            $query->whereRaw("upper(name) like '%$name%'");
        }
        $query->where('status',1);
        $query->orderBy('id');

        $rows = clone $query;
        $total = $rows->count();

        $query->offset($start);
        $query->limit($length);
        $employees = $query->get();

        $data = [];
        foreach($employees as $key => $row){
            $data[] = $row;
        }

        return response()->json([
            'total' => $total,
            'rows'  => $data
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_name'  => 'required',
            'email'          => 'required|unique:employees,email',
            'nid'            => 'required|unique:employees,nid',
            'nik'            => 'required|unique:employees,nik',
            'address'        => 'required',
            'city'           => 'required',
            'province'       => 'required',
            'shift_type'     => 'required',
            'join_date'      => 'required',
            'salary'         => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        $name          = $request->employee_name;
        $email         = $request->email;
        $nid           = $request->nid;
        $phone         = $request->phone_number;
        $nik           = $request->nik;
        $npwp          = $request->npwp;
        $address       = $request->address;
        $city          = $request->city;
        $province      = $request->province;
        $accountbank   = $request->account_bank;
        $accountnumber = $request->account_number;
        $accountname   = $request->account_name;
        $shifttype     = $request->shift_type;
        $workingshift  = $request->shift;
        $joindate      = $request->date_join;
        $resigndate    = $request->date_resign;
        $salary        = str_replace('.', '', $request->salary);
        $as_user       = $request->user;
        $photo         = $request->file('photo');
        $payroll       = $request->payroll;

        $employee = Employee::create([
            'name'             => $name,
            'email'            => $email,
            'nid'              => $nid,
            'phone'            => $phone,
            'nik'              => $nik,
            'npwp'             => $npwp,
            'address'          => $address,
            'region_id'        => $city,
            'province_id'      => $province,
            'account_bank'     => $accountbank,
            'account_number'   => $accountnumber,
            'account_name'     => $accountname,
            'shift_type'       => $shifttype,
            'working_shift_id' => $workingshift,
            'status'           => $request->is_active ? 1 : 0,
            'join_date'        => $joindate,
            'resign_date'      => $resigndate,
            'salary'           => $salary,
            'payroll_type'     => $payroll,
            'position'         => $request->position,
        ]);

        $user = ['status' => false, 'message' => 'Employee without user account.'];

        if ($as_user) {
            $account = User::create([
                'name'        => $employee->name,
                'email'       => $employee->email,
                'username'    => $request->username,
                'password'    => Hash::make('123456'),
                'employee_id' => $employee->id,
                'is_active'   => 1,
                'spv_id'      => $request->spv ? $request->spv : null,
            ]);

            if ($request->site) {
                $site_user  = SiteUser::where('user_id', $account->id)->first();
                if ($site_user) {
                    $site_user->site_id     = $request->site;
                    $site_user->save();
                } else {
                    $create_site_user   = SiteUser::create([
                        'site_id'   => $request->site,
                        'user_id'   => $account->id,
                    ]);
                }
            }

            if ($account) {
                $user = ['status' => true, 'message' => 'User account has been created.'];
            } else {
                $user = ['status' => false, 'message' => 'Failed to create user account.'];
            }
            
            $group_user = RoleUser::where('user_id', $account->id)->first();
            if ($group_user) {
                $group_user->role_id    = $request->role;
                $group_user->save();
            } else {
                try {
                    $group  = RoleUser::create([
                        'role_id'       => $request->role,
                        'user_id'       => $account->id,
                    ]);
                } catch (\Illuminate\Database\QueryException $ex) {
                    return response()->json([
                        'status'    => false,
                        'message'   => "Cant create role user {$ex->errorInfo[2]}"
                    ], 400);
                }
            }
        }
        
        

        if ($employee) {
            if ($photo) {
                $filename = 'foto.' . $request->photo->getClientOriginalExtension();
                if (file_exists($employee->photo)) {
                    unlink($employee->photo);
                }

                $src = 'assets/employee/' . $employee->id;
                if (!file_exists($src)) {
                    mkdir($src, 0777, true);
                }
                $photo->move($src, $filename);
                $employee->photo = $src . '/' . $filename;
                $employee->save();
            }
            $result = [
                'status'  => true,
                'message' => 'Successfully insert data.',
                'account' => $user,
                'point'   => 200
            ];
        } else {
            $result = [
                'status'  => false,
                'message' => 'Failed to insert data.',
                'point'   => 400
            ];
        }

        return response()->json([
            'status'    => $result['status'],
            'message'   => $result['message'],
        ], $result['point']);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'employee_name'  => 'required',
            'email'          => 'required|unique:employees,email,' . $id,
            'nid'            => 'required|unique:employees,nid,' . $id,
            'nik'            => 'required|unique:employees,nik,' . $id,
            'address'        => 'required',
            'city'           => 'required',
            'province'       => 'required',
            'shift_type'     => 'required',
            'join_date'      => 'required',
            'salary'         => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        $name          = $request->employee_name;
        $email         = $request->email;
        $nid           = $request->nid;
        $phone         = $request->phone_number;
        $nik           = $request->nik;
        $npwp          = $request->npwp;
        $address       = $request->address;
        $city          = $request->city;
        $province      = $request->province;
        $accountbank   = $request->account_bank;
        $accountnumber = $request->account_number;
        $accountname   = $request->account_name;
        $shifttype     = $request->shift_type;
        $workingshift  = $request->shift;
        $status        = $request->status ? 1 : 0;
        $joindate      = $request->date_join;
        $resigndate    = $request->date_resign;
        $salary        = str_replace('.', '', $request->salary);
        $as_user       = $request->user;
        $photo         = $request->file('photo');
        $has_photo     = $request->has_photo;
        $payroll       = $request->payroll;

        $employee = Employee::find($id);
        $employee->name             = $name;
        $employee->email            = $email;
        $employee->nid              = $nid;
        $employee->phone            = $phone;
        $employee->nik              = $nik;
        $employee->npwp             = $npwp;
        $employee->address          = $address;
        $employee->region_id        = $city;
        $employee->province_id      = $province;
        $employee->account_bank     = $accountbank;
        $employee->account_number   = $accountnumber;
        $employee->account_name     = $accountname;
        $employee->shift_type       = $shifttype;
        $employee->working_shift_id = $workingshift;
        $employee->status           = $status;
        $employee->join_date        = $joindate;
        $employee->resign_date      = $resigndate;
        $employee->salary           = $salary;
        $employee->payroll_type     = $payroll;
        $employee->position         = $request->position;
        $employee->save();

        DB::beginTransaction();
        if ($as_user) {
            $account = User::where('employee_id', $employee->id)->first();
            if (!$account) {
                $account = User::create([
                    'employee_id' => $employee->id,
                    'name'        => $employee->name,
                    'email'       => $employee->email,
                    'username'    => $request->username,
                    'password'    => Hash::make('123456'),
                    'is_active'   => 1,
                    'spv_id'      => $request->spv ? $request->spv : null,
                ]);

                if ($request->site) {
                    $site_user  = SiteUser::where('user_id', $account->id)->first();
                    if ($site_user) {
                        $site_user->site_id     = $request->site;
                        $site_user->save();
                    } else {
                        $create_site_user   = SiteUser::create([
                            'site_id'   => $request->site,
                            'user_id'   => $account->id,
                        ]);
                    }
                }

                if ($account) {
                    $user = ['status' => true, 'message' => 'User account has been created.'];
                } else {
                    $user = ['status' => false, 'message' => 'Failed to create user account.'];
                }
            } else {
                $account->name      = $employee->name;
                $account->email     = $employee->email;
                $account->username  = $request->username;
                $account->spv_id    = $request->spv ? $request->spv : null;
                $account->save();

                if ($request->site) {
                    $site_user  = SiteUser::where('user_id', $account->id)->first();
                    if ($site_user) {
                        $site_user->site_id     = $request->site;
                        $site_user->save();
                    } else {
                        $create_site_user   = SiteUser::create([
                            'site_id'   => $request->site,
                            'user_id'   => $account->id,
                        ]);
                    }
                }
                $user = ['status' => true, 'message' => 'Employee already have an account.'];
            }

            $group_user = RoleUser::where('user_id', $account->id)->first();
            if ($group_user) {
                $group_user->role_id    = $request->role;
                $group_user->save();
            } else {
                try {
                    $group  = RoleUser::create([
                        'role_id'       => $request->role,
                        'user_id'       => $account->id,
                    ]);
                } catch (\Illuminate\Database\QueryException $ex) {
                    return response()->json([
                        'status'    => false,
                        'message'   => "Cant create role user {$ex->errorInfo[2]}"
                    ], 400);
                }
            }
        } else {
            $account = User::where('employee_id', $employee->id);
            $account->delete();

            if ($account) {
                $user = ['status' => true, 'message' => 'User account has been removed.'];
            } else {
                $user = ['status' => false, 'message' => 'Failed to remove user account.'];
            }
        }

        if ($employee) {
            if ($photo) {
                $filename = 'foto.' . $request->photo->getClientOriginalExtension();
                if (file_exists($employee->photo)) {
                    unlink($employee->photo);
                }

                $src = 'assets/employee/' . $employee->id;
                if (!file_exists($src)) {
                    mkdir($src, 0777, true);
                }
                $photo->move($src, $filename);
                $employee->photo = $src . '/' . $filename;
                $employee->save();
            }
            if ($has_photo == 0) {
                $employee->photo = '';
                $employee->save();
            }
            if (!$employee) {
                $result = [
                    'status'    => false,
                    'message'   => 'Successfully update data.',
                    'photo'     => $photo,
                    'point'     => 400
                ];
            } else {
                $result = [
                    'status'    => true,
                    'message'   => 'Successfully update data.',
                    'account'   => $user,
                    'point'     => 200
                ];
            }
        } else {
            $result = [
                'status' => false,
                'message' => 'text',
                'point' => 400
            ];
        }

        DB::commit();
        return response()->json([
            'status' => $result['status']
        ], $result['point']);
    }

    public function destroy($id)
    {
        try {
            $employee = Employee::find($id);
            $employee->delete();

            if ($employee) {
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
                'status' => false,
                'message'=> "Error delete data {$e->errorInfo[2]}",
            ], 400);
        }
    }
}