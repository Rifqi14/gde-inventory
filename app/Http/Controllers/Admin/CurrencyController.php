<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Menu;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class CurrencyController extends Controller
{
    function __construct() {
        $menu       = Menu::getByRoute('currency')->first();
        $parent     = Menu::find($menu->parent_id);
        View::share('parent_name', $parent->menu_name);
        View::share('menu_name', $menu->menu_name);
        View::share('menu_active', url('admin/currency'));
        $this->middleware('accessmenu', ['except' => ['select']]);
    }

    public function read(Request $request)
    {
        $start          = $request->start;
        $length         = $request->length;
        $query          = $request->search['value'];
        $sort           = $request->columns[$request->order[0]['column']]['data'];
        $dir            = $request->order[0]['dir'];
        $country_id     = $request->country_id;
        $currency       = $request->currency;

        // Count Data
        $query          = Currency::with(['country']);
        if ($country_id) {
            $query->countryId($country_id);
        }
        if ($currency) {
            $query->currencyData($currency);
        }

        $row            = clone $query;
        $recordsTotal   = $row->count();

        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $currencies    = $query->get();

        $data           = [];
        foreach ($currencies as $key => $currency) {
            $currency->no     = ++$start;
            $data[]             = $currency;
        }
        return response()->json([
            'draw'              => $request->draw,
            'recordsTotal'      => $recordsTotal,
            'recordsFiltered'   => $recordsTotal,
            'data'              => $data
        ], 200);
    }

    public function select(Request $request)
    {
        $start          = $request->page ? ($request->page - 1) * $request->limit : 0;
        $length         = $request->limit;
        $name           = strtoupper($request->name);

        // Count Data
        $query          = Currency::with(['country'])->whereRaw("upper(currency) like '%$name%'");
        
        $row            = clone $query;
        $recordsTotal   = $row->count();

        $query->offset($start);
        $query->limit($length);
        $currencies  = $query->get();

        $data       = [];
        foreach ($currencies as $key => $currency) {
            $currency->no    = ++$start;
            $data[]         = $currency;
        }
        return response()->json([
            'total'     => $recordsTotal,
            'rows'      => $data,
        ], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.currency.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (in_array('create', $request->actionmenu)) {
            return view('admin.currency.create');
        } else {
            abort(403);
        }
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
            'code'          => 'required',
            'currency'      => 'required',
            'countries_id'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 200);
        }

        DB::beginTransaction();
        try {
            Currency::create([
                'countries_id'  => $request->countries_id,
                'symbol'        => $request->symbol,
                'currency'      => $request->currency,
                'code'          => $request->code,
            ]);
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => "Error create currency: {$ex->errorInfo[2]}"
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status'    => true,
            'results'   => route('currency.index')
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (in_array('update', $request->actionmenu)) {
            $currency       = Currency::with(['country'])->find($id);
            if ($currency) {
                return view('admin.currency.edit', compact('currency'));
            } else {
                abort(404);
            }
        } else {
            abort(403);
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
            'code'          => 'required',
            'currency'      => 'required',
            'countries_id'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first()
            ], 200);
        }

        DB::beginTransaction();
        try {
            $currency   = Currency::find($id);
            $currency->code         = $request->code;
            $currency->countries_id = $request->countries_id;
            $currency->currency     = $request->currency;
            $currency->symbol       = $request->symbol;
            $currency->save();
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => "Error update data: {$ex->errorInfo[2]}"
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status'    => true,
            'results'   => route('currency.index')
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
            $currency   = Currency::find($id);
            $currency->delete();
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json([
                'status'    => false,
                'message'   => "Error delete data {$ex->errorInfo[2]}",
            ], 400);
        }
        return response()->json([
            'status'    => true,
            'message'   => "Success delete data"
        ], 200);
    }
}