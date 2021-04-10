<?php

namespace App\Http\Controllers\Admin;

use App\Models\Site;
use App\Models\Budget;
use App\Models\BudgetDetail;
use App\Models\BusinessTrip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class BusinessTripController extends Controller
{
    function __construct(){
        View::share('menu_active', url('admin/'.'business-trip'));
        $this->middleware('accessmenu', ['except' => ['select']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = ($request->unit) ? $request->unit : 'dieng';
        return view('admin.business_trip.index', compact('type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.business_trip.create');
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
            'name' => 'required|unique:budgets',
            'budget_description' => 'required',
            'budget_amount' => 'required',
            'currency' => 'required',
            'site_id' => 'required',
        ]);

        $site = Site::find($request->site_id);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'type'     => $site->code,
                'message' => $validator->errors()->first()
            ], 200);
        }


        $user = Budget::create([
            'name' => $request->name,
            'description' => $request->budget_description,
            'amount' => (float) str_replace(',', '.', str_replace('.', '', $request->budget_amount)),
            'currency' => $request->currency,
            'site_id' => $request->site_id,
            'created_user' => 1,
        ]);

        if (!$user) {
            return response()->json([
                'status' => false,
                'type'     => $site->code,
                'message'     => 'Cant create budget'
            ], 200);
        }

        $type = $request->type;
        $weight = $request->weight;
        $total = $request->total;
        for ($i = 0; $i < count($type); $i++) {
            $budget = BudgetDetail::create([
                'budget_id' => $user->id,
                'type' => $type[$i],
                'weight' => $weight[$i],
                'total' => (float) str_replace(',', '.', str_replace('.', '', $total[$i])),
            ]);
            if (!$budget) {
                return response()->json([
                    'status' => false,
                    'type'     => $site->code,
                    'message'     => 'Cant create budget'
                ], 200);
            }
        }

        return response()->json([
            'status'     => true,
            'message'     => 'Created success',
            'type'     => $site->code,
            'results'     => route('budgetary.index'),
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
        $budget = Budget::with('detail')->find($id);
        $site = Site::find($budget->site_id);
        $budget->origin_amount = $budget->amount;
        $budget->amount = number_format($budget->amount, '2', '.', '');
        $budget->site_name = $site->name;
        $user = $budget;
        if ($user) {
            return view('admin.business_trip.detail', compact('user'));
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
        $budget = Budget::with('detail')->find($id);
        $site = Site::find($budget->site_id);
        $budget->origin_amount = $budget->amount;
        $budget->amount = number_format($budget->amount, '2', '.', '');
        $budget->site_name = $site->name;
        $user = $budget;
        if ($user) {
            return view('admin.business_trip.edit', compact('user'));
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
            'name' => 'required|unique:budgets,name,' . $id,
            'budget_description' => 'required',
            'budget_amount' => 'required',
            'currency' => 'required',
            'site_id' => 'required',
        ]);

        $site = Site::find($request->site_id);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'type'     => $site->code,
                'message'     => $validator->errors()->first()
            ], 200);
        }

        $user = Budget::find($id);
        $user->name = $request->name;
        $user->description = $request->budget_description;
        $user->amount = (float) str_replace(',', '.', str_replace('.', '', $request->budget_amount));
        $user->currency = $request->currency;
        $user->site_id = $request->site_id;
        $user->updated_user = 1;
        $user->save();

        if (!$user) {
            return response()->json([
                'status' => false,
                'type'     => $site->code,
                'message'     => "Cant update budget"
            ], 200);
        }

        $type = $request->type;
        $weight = $request->weight;
        $total = $request->total;
        $del = BudgetDetail::where('budget_id', $id);
        if ($del->delete()) {
            for ($i = 0; $i < count($type); $i++) {
                $budget = BudgetDetail::create([
                    'budget_id' => $user->id,
                    'type' => $type[$i],
                    'weight' => $weight[$i],
                    'total' => (float) str_replace(',', '.', str_replace('.', '', $total[$i])),
                ]);
                if (!$budget) {
                    return response()->json([
                        'status' => false,
                        'type'     => $site->code,
                        'message'     => 'Cant create budget'
                    ], 200);
                }
            }
        }

        return response()->json([
            'status'     => true,
            'type'     => $site->code,
            'results'     => route('budgetary.index'),
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
            $user = Budget::find($id);
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

}
