<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

use App\Models\Region;

class RegionController extends Controller
{
    function __construct()
    {
        View::share('menu_active', url('admin/' . 'region'));
        $this->middleware('accessmenu', ['except' => ['select']]);
    }

    public function select(Request $request)
    {
        $start = $request->page ? $request->page - 1 : 0;
        $length = $request->limit;
        $name = strtoupper($request->name);
        $province_id = $request->province_id;

        //Count Data
        $query = Region::with('province');        
        if ($name) {
            $query->whereRaw("upper(name) like '%$name%'");
        }
        if ($province_id) {
            $query->where('province_id',$province_id);
        }

        $row = clone $query;
        $recordsTotal = $row->count();

        $query->offset($start);
        $query->limit($length);
        $regions = $query->get();

        $data = [];
        foreach ($regions as $region) {
            $region->no = ++$start;
            $data[] = $region;
        }
        return response()->json([
            'total' => $recordsTotal,
            'rows' => $data
        ], 200);
    }
}
