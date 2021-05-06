<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Models\Site;
use App\Models\Budget;
use App\Models\BudgetDetail;
use App\Models\BusinessTrip;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BusinessTripController extends Controller
{
    function __construct(){
        $menu   = Menu::where('menu_route', 'businesstrip')->first();
        $parent = Menu::find($menu->parent_id);
        View::share('parent_name', $parent->menu_name);
        View::share('menu_name', $menu->menu_name);
        View::share('menu_active', url('admin/'.'businesstrip'));
        $this->middleware('accessmenu', ['except' => ['select']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.business_trip.index',);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
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
            return view('admin.businesstrip.detail', compact('user'));
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
            return view('admin.businesstrip.edit', compact('user'));
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
        $draw    = $request->draw;
        $start   = $request->start;
        $length  = $request->length;
        $query   = $request->search['value'];
        $sort    = $request->columns[$request->order[0]['column']]['data'];
        $dir     = $request->order[0]['dir'];
        $status = $request->status;

        //Count Data
        $query = BusinessTrip::query();    
        $query->selectRaw("business_trips.*");
        if($status){
            $query->where('business_trips.status',$status);
        }else{
            $query->where('business_trips.status','<>',3);
        }

        $rows  = clone $query;
        $total = $rows->count();

        //Select Pagination
        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $businesstrips = $query->get();

        $data = [];
        foreach ($businesstrips as $key => $row) {
            $row->no = ++$start;                      
            $row->actvity = 0;
            $row->budget = '1.000';
            $data[] = $row;
        }
        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $data
        ], 200);
    }

}