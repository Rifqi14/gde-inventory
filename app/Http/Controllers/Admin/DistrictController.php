<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

use App\Models\District;

class DistrictController extends Controller
{
    function __construct()
    {
        View::share('menu_active', url('admin/' . 'district'));
        $this->middleware('accessmenu', ['except' => ['select']]);
    }

    public function select(Request $request)
    {
        $start = $request->page ? $request->page - 1 : 0;
        $length = $request->limit;
        $name = strtoupper($request->name);

        //Count Data
        $query = District::select('*');
        $query->whereRaw("upper(name) like '%$name%'");

        $row = clone $query;
        $recordsTotal = $row->count();

        $query->offset($start);
        $query->limit($length);
        $districts = $query->get();

        $data = [];
        foreach ($districts as $district) {
            $district->no = ++$start;
            $data[] = $district;
        }
        return response()->json([
            'total' => $recordsTotal,
            'rows' => $data
        ], 200);
    }
}
