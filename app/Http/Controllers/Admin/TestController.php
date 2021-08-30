<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function test()
    {
        $query = DB::table('villages')->get();
        echo json_encode($query);
    }
}
