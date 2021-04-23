<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Province;
use App\Http\Controllers\Controller;

class ProvinceController extends Controller
{
    public function select(Request $request)
    {
        $start     = $request->page?$request->page - 1:0;
        $limit     = $request->limit;
        $province  = strtoupper($request->province);

        $query = Province::query();
        if ($province) {
            $query->whereRaw("upper(name) like '%$province%'");
        }

        $rows  = clone $query;
        $total = $rows->count();

        $query->offset($start);
        $query->limit($limit);

        $provinces = $query->get();

        $data = [];
        foreach ($provinces as $key => $row) {
            $data[] = $row;
        }

        return response()->json([
            'total' => $total,
            'rows'  => $data
        ],200);
    }
}
