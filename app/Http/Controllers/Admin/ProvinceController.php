<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

use App\Models\Province;

class ProvinceController extends Controller
{
    function __construct()
    {
        View::share('menu_active', url('admin/' . 'province'));
        $this->middleware('accessmenu', ['except' => ['select']]);
    }

    public function select(Request $request)
    {
        $start = $request->page ? $request->page - 1 : 0;
        $length = $request->limit;
        $name = strtoupper($request->name);

        //Count Data
        $query = Province::select('*');
        $query->whereRaw("upper(name) like '%$name%'");

        $row = clone $query;
        $recordsTotal = $row->count();

        $query->offset($start);
        $query->limit($length);
        $provinces = $query->get();

        $data = [];
        foreach ($provinces as $province) {
            $province->no = ++$start;
            $data[] = $province;
        }
        return response()->json([
            'total' => $recordsTotal,
            'rows' => $data
        ], 200);
    }
}
