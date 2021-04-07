<?php

namespace App\Http\Controllers\Admin;

use App\Models\Budget;
use App\Models\BudgetDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class BudgetController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.budget.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.budget.create');
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
            'shift_name' => 'required',
            'time_in' => 'required',
            'time_out' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 200);
        }

        $user = WorkingShift::create([
            'shift_type' => $request->shift_type,
            'shift_name' => $request->shift_name,
            'time_in' => $request->time_in,
            'time_out' => $request->time_out,
            'status' => $request->status,
        ]);

        if (!$user) {
            return response()->json([
                'status' => false,
                'message'     => 'Cant create shift'
            ], 200);
        }

        return response()->json([
            'status'     => true,
            'message'     => 'Created success',
            'results'     => route('workingshift.index'),
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
        $query = DB::table('working_shifts');
        $query->where('id', '=', $id);
        $user = $query->get()->first();
        if ($user) {
            return view('admin.working_shift.detail', compact('user'));
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
        $query = DB::table('working_shifts');
        $query->where('id', '=', $id);
        $user = $query->get()->first();
        if ($user) {
            return view('admin.working_shift.edit', compact('user'));
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
            'shift_name' => 'required',
            'time_in' => 'required',
            'time_out' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 200);
        }

        $user = WorkingShift::find($id);
        $user->shift_type = $request->shift_type;
        $user->shift_name = $request->shift_name;
        $user->time_in = $request->time_in;
        $user->time_out = $request->time_out;
        $user->status = $request->status;
        $user->save();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message'     => "Cant update shift"
            ], 200);
        }

        return response()->json([
            'status'     => true,
            'results'     => route('workingshift.index'),
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
            $user = WorkingShift::find($id);
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
        $query = DB::table('working_shifts');
        $query->whereRaw("upper(shift_name) like '%$name%'");

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
        $name = strtoupper($request->name);
        $desc = strtoupper($request->desc);
        $type = strtoupper($request->type);

        //Count Data
        $query = DB::table('budgets');
        $query->select('budgets.*', 'role_users.role_id');
        $query->join('role_users', 'budgets.created_user', '=', 'role_users.user_id');
        $query->join('sites', 'budgets.site_id', '=', 'sites.id');
        $query->whereRaw("upper(budgets.name) like '%$name%'");
        $query->WhereRaw("upper(budgets.description) like '%$desc%'");
        if ($type) {
            $query->whereRaw("upper(sites.code) = '$type'");
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
            $user->detail = $this->detail($user->id);
            $data[] = $user;
        }
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data
        ], 200);
    }

    function detail($budget_id)
    {
        $data = [];
        $query = DB::table('budget_details');
        $query->where('budget_id', $budget_id);
        $read = $query->get();
        foreach ($read as $value) {
            $value->total = number_format($value->total, '2', '.', '');
            $data[] = $value;
        }

        return $data;
    }

    function stack_chart(Request $request)
    {
        $types = ['adb', 'ctf', 'pmn', 'equity', 'unsigned'];
        $query = DB::table('budgets');
        $query->selectRaw("budgets.*, coalesce(expenses.actual_total, 0) as actual_total, coalesce(expenses.commit_total, 0) as commit_total");
        $query->leftJoin('expenses', 'budgets.id', '=', 'expenses.budget_id');
        $query->join('sites', 'budgets.site_id', '=', 'sites.id');
        $query->whereRaw("sites.code = '$request->unit'");
        $query->orderBy('budgets.name', 'asc');
        $read = $query->get();
        $cat = [];
        $cet = [];
        foreach ($read as $val) {
            array_push($cat, $val->name);
            $cet[$val->name] = $val->description;
        }

        $series = [];
        foreach ($types as $type) {
            $data = [];
            foreach ($read as $val) {
                $point = 0;
                $detail = $this->detail_stack($val->id, $type);
                foreach ($detail as $value) {
                    $point = (int) $value->total;
                }
                $new = $point - ($val->actual_total + $val->commit_total);
                array_push($data, $new);
            }

            if ($type == 'kasinternal') {
                $name = 'Kas Internal';
            } elseif ($type == 'budget') {
                $name = 'Budget';
            } else {
                $name = strtoupper($type);
            }
            $series[] = [
                'name' => $name,
                'data' => $data
            ];
        }

        $result['categories']     = $cat;
        $result['cet']     = $cet;
        $result['series'] = $series;
        return response()->json([
            'status' => true,
            'series' => $result
        ], 200);
    }

    function detail_stack($budget_id, $type)
    {
        $data = [];
        $type = strtolower($type);
        $query = DB::table('budget_details');
        $query->where('budget_id', $budget_id);
        $query->whereRaw("lower(type) = '$type'");
        $read = $query->get();
        foreach ($read as $value) {
            $data[] = $value;
        }

        return $data;
    }
}
