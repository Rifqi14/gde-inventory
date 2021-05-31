<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\ProductConsumable;
use Illuminate\Support\Facades\View;

class ProductConsumableController extends Controller
{
    /**
     * Default method when access this controller
     *
     */
    function __construct() {
        $menu       = Menu::GetByRoute('consumable')->first();
        $parent     = Menu::find($menu->parent_id);
        View::share('menu_name', $menu->menu_name);
        View::share('menu_parent', $parent->menu_name);
        View::share('menu_active', url('admin/consumable'));
        $this->middleware('accessmenu', ['except'   => ['select']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (in_array('read', $request->actionmenu)) {
            return view('admin.consumable.index');
        } else {
            abort(403);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (in_array('create', $request->actionmenu)) {
            return view('admin.consumable.create');
        } else {
            abort(403);
        }
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (in_array('update', $request->actionmenu)) {
            $consumable     = ProductConsumable::with(['detail', 'document', 'log'])->find($id);
            if ($consumable) {
                return view('admin.consumable.edit', compact('consumable'));
            } else {
                abort(404);
            }
        } else {
            abort(403);
        }
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
        try {
            $consumable     = ProductConsumable::find($id);
            $consumable->delete();
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json([
                'status'    => false,
                'message'   => "Error delete data {$ex->errorInfo[2]}",
            ], 400);
        }
        return response()->json([
            'status'    => true,
            'message'   => "Success delete data"
        ], 200);
    }

    /**
     * Get data to show in index page
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function read(Request $request)
    {
        # code...
    }

    /**
     * Get data to show in select2
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function select(Request $request)
    {
        # code...
    }
}