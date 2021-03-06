<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Country;

class CountryController extends Controller
{
    public function select(Request $request)
    {
        $start          = $request->page ? ($request->page - 1) * $request->limit : 0;
        $length         = $request->limit;
        $name           = strtoupper($request->name);

        // Count Data
        $query          = Country::whereRaw("upper(country) like '%$name%'");
        
        $row            = clone $query;
        $recordsTotal   = $row->count();

        $query->offset($start);
        $query->limit($length);
        $countries  = $query->get();

        $data       = [];
        foreach ($countries as $key => $country) {
            $country->no    = ++$start;
            $data[]         = $country;
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
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
}