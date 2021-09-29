<?php

namespace App\Http\Controllers\Admin\Transmittal\TransmittalProperties;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;

class TransmittalPropertiesController extends Controller
{
    function __construct() {
        $menu   = Menu::with(['parentMenu'])->GetByRoute('transmittalproperties')->first();
        view()->share('menu_name', $menu->menu_name);
        view()->share('parent_name', $menu->parentMenu->menu_name);
        view()->share('menu_active', url("admin/transmittalproperties"));
        $this->middleware('accessmenu', ['except' => ['select']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // dd($request->all());
        if (in_array('read', $request->actionmenu)) {
            return view('admin.transmittal.index');
        }
        abort(403);
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
