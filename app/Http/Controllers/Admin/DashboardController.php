<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;

class DashboardController extends Controller
{
    function __construct(){
        View::share('menu_active', url('admin/'.'dashboard'));
        $this->middleware('accessmenu', ['except' => 'index']);
    }
    public function index(){
        return view('admin.dashboard.index');
    }
}
