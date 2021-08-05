<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\BusinessTrip;
use App\Models\Currency;
use App\Models\Menu;
use Illuminate\Support\Facades\View;

class ReimbursementController extends Controller
{
    function __construct(){
        $menu   = Menu::where('menu_route', 'reimbursement')->first();
        $parent = Menu::find($menu->parent_id);
        View::share('parent_name', $parent->menu_name);
        View::share('menu_name', $menu->menu_name);
        View::share('menu_active', url('admin/'.'reimbursement'));
        $this->middleware('accessmenu', ['except' => ['select']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.reimbursement.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $currencies = Currency::all();
        return view('admin.reimbursement.create', compact('currencies'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function rateprocess()
    {           
        $query = BusinessTrip::with([
            'departs' => function($w){
                $w->selectRaw(" 
                    business_trip_id,
                    price
                ");
            },
            'returns'=> function($w){
                $w->selectRaw(" 
                    business_trip_id,
                    price
                ");
            },            
            'lodgings' => function($w){
                $w->selectRaw(" 
                    business_trip_id,
                    price,
                    night,
                    (price * night) as subtotal
                ");
            },
            'others' => function($w){
                $w->selectRaw(" 
                    business_trip_id,
                    price,
                    qty,
                    (price * qty) as subtotal
                ");
            }
        ]);
        $query->selectRaw("
            business_trips.id,                                     
            business_trips.status,
            business_trips.total_cost,
            (case when employees.rate_business_trip is not null then employees.rate_business_trip else 0 end) as rate,
            (DATE_PART('day', CURRENT_DATE::timestamp - departure_date::timestamp)::INTEGER) as date_part
        ");
        $query->leftJoin('users','users.id','=','business_trips.issued_by');
        $query->leftJoin('employees','employees.id','=','users.employee_id');
        $query->where('business_trips.status','<>','approved');
        $businessTrips = $query->get();        
        
        $data = [];
        foreach ($businessTrips as $key => $row) {                        
            $datePart   = $row->date_part;            
            $departs    = 0;
            $returns    = 0;
            $lodgings   = 0;
            $others     = 0;
            $rate       = $row->rate;
            $onupdate   = false;

            if($datePart >= 8 && $datePart <= 14){
                $rate     = $rate * 80/100;
                $onupdate = true;
            }else if($datePart >= 15){
                $rate     = $rate * 0;
                $onupdate = true;
            }            

            foreach ($row->departs as $key => $depart) {
                $departs = $departs + $depart->subtotal;
            }

            foreach ($row->returns as $key => $return) {
                $returns = $returns + $return->subtotal;
            }

            foreach($row->lodgings as $key => $lodging){
                $lodgings = $lodgings + $lodging->subtotal;
            }

            foreach($row->others as $key => $other){
                $others = $others + $other->subtotal;
            }

            $total = $departs + $returns + $lodgings + $others + $rate;
            
            if($onupdate){
                $query = BusinessTrip::find($row->id);
                $query->rate       = $rate;
                $query->total_cost = $total;
                $query->save();
            }
        }
    }       

    public function select(Request $request)
    {
        $start      = $request->page ? $request->page - 1 : 0;
        $length     = $request->limit;
        $number     = $request->request_number;
        $purpose    = strtoupper($request->purpose);
        $approved   = $request->approved;

        //Count Data
        $query = BusinessTrip::with(['transportation', 'departs', 'returns', 'vehicles', 'lodgings', 'others', 'issuedby.employees'])->doesntHave('btdeclaration');
        if ($number) {
            $query->whereRaw("upper(business_trip_number) like '%$number%'");
        }
        if ($purpose) {
            $query->whereRaw("upper(purpose) like '%$purpose%'");
        }
        if ($approved) {
            $query->where('status', 'approved');
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

    public function read(Request $request)
    {
        $draw               = $request->draw;
        $start              = $request->start;
        $length             = $request->length;
        $search             = $request->search['value'];
        $sort               = $request->columns[$request->order[0]['column']]['data'];
        $dir                = $request->order[0]['dir'];
        $status             = $request->status;
        $businessTripNumber = $request->businesstripnumber;
        $startdate          = $request->startdate;
        $enddate            = $request->enddate;
        $total_cost         = str_replace('.','',$request->total_cost);

        //Count Data
        $query = BusinessTrip::with([
            'departs.transportCurrency',
            'returns.transportCurrency',
            'lodgings.lodgingCurrency',
            'others.othersCurrency',
            'issuedby.employees',
        ]);      
        if($businessTripNumber){
            $query->whereRaw("upper(business_trip_number) like '%$businessTripNumber%'");
        }
        $query->where(function($w) use($status,$startdate,$enddate,$total_cost){
            $w->where([
                ['departure_date','>=',$startdate],
                ['departure_date','<=',$enddate]
            ]);
            if($total_cost){
                $w->where('total_cost',$total_cost);
            }        
            if($status){
                $w->where('status',$status);
            }else{
                $w->where('status','<>','approved');
            }            
        });
        $query->orWhere(function($w) use($status,$startdate,$enddate,$total_cost){
            $w->where([
                ['arrived_date','>=',$startdate],
                ['arrived_date','<=',$enddate]
            ]);
            if($total_cost){
                $w->where('total_cost',$total_cost);
            }  
            if($status){
                $w->where('status',$status);
            }else{
                $w->where('status','<>','approved');
            }               
        });
        

        $rows  = clone $query;
        $recordsTotal = $rows->count();

        //Select Pagination
        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $businesstrips = $query->get();

        $data = [];
        foreach ($businesstrips as $key => $row) {
            $currencies = Currency::all();
            $total  = [];
            foreach ($currencies as $key => $currency) {
                $total[$currency->id]['price']  = 0;
                $total[$currency->id]['symbol']  = $currency->symbol;
                foreach ($row->departs as $key => $depart) {
                    if ($currency->id == $depart->currency_id) {
                        $total[$currency->id]['price']    += $depart->price;
                    }
                }
                foreach ($row->returns as $key => $depart) {
                    if ($currency->id == $depart->currency_id) {
                        $total[$currency->id]['price']    += $depart->price;
                    }
                }
                foreach ($row->lodgings as $key => $depart) {
                    if ($currency->id == $depart->currency_id) {
                        $total[$currency->id]['price']    += $depart->price;
                    }
                }
                foreach ($row->others as $key => $depart) {
                    if ($currency->id == $depart->currency_id) {
                        $total[$currency->id]['price']    += $depart->price;
                    }
                }
                if ($row->issuedby->employees) {
                    if ($currency->id == $row->issuedby->employees->rate_currency_id) {
                        $total[$currency->id]['price']    += $row->issuedby->employees->rate_business_trip;
                    }
                }
            }
            $row->no = ++$start;                      
            $row->schedule = date('d/m/Y',strtotime($row->departure_date)).' - '.date('d/m/Y',strtotime($row->arrived_date));
            $row->total  = $total;
            $data[] = $row;
        }
        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data
        ], 200);
    }
}
