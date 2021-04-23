<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Region;
use App\Http\Controllers\Controller;

class RegionController extends Controller
{
    public function select(Request $request)
    {
        $start = $request->page?$request->page - 1:0;
        $limit = $request->limit;
        $city  = strtoupper($request->city);
        $province_id = $request->province_id;

        $query = Region::with('province');
        if ($city) {
            $query->whereRaw("upper(name) like '%$city%'");
        }
        if ($province_id) {
            $query->where('province_id',$province_id);
        }

        $rows  = clone $query;
        $total = $rows->count();

        $query->offset($start);
        $query->limit($limit);

        $district = $query->get();

        $data = [];
        foreach ($district as $key => $row) {
            $data[] = $row;
        }

        return response()->json([
            'total' => $total,
            'rows'  => $data
        ],200);
    }
}
