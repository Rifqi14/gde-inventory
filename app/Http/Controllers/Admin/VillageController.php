<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

use App\Models\Village;

class VillageController extends Controller
{
    function __construct()
    {
        View::share('menu_active', url('admin/' . 'village'));
        $this->middleware('accessmenu', ['except' => ['select']]);
    }

    public function select(Request $request)
    {
        $start = $request->page ? $request->page - 1 : 0;
        $length = $request->limit;
        $name = strtoupper($request->name);
        $district_id = $request->district_id;

        //Count Data
        $query = Village::select('*');
        $query->whereRaw("upper(name) like '%$name%'");
        if ($district_id) {
            $query->where('district_id', $district_id);
        }

        $row = clone $query;
        $recordsTotal = $row->count();

        $query->offset($start);
        $query->limit($length);
        $villages = $query->get();

        $data = [];
        foreach ($villages as $village) {
            $village->no = ++$start;
            $data[] = $village;
        }
        return response()->json([
            'total' => $recordsTotal,
            'rows' => $data
        ], 200);
    }
}