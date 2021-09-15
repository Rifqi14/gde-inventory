<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Http\Response;

use App\Models\Menu;
use App\Models\StockMovement;
class StockMovementController extends Controller
{

    public function __construct()
    {
        View::share('menu_active', url('admin/' . 'stockmovement'));        

        $menu   = Menu::where('menu_route', 'stockmovement')->first();
        $parent = Menu::find($menu->parent_id);
        View::share('parent_name', $parent->menu_name);
        View::share('menu_name', $menu->menu_name);
        View::share('menu_active', url('admin/stockmovement'));
        $this->middleware('accessmenu', ['except' => ['select']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.stockmovement.index');
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
        if($id){
            $query = StockMovement::selectRaw("
                stock_movements.*,
                products.name as product,
                users.name as issued_by,
                TO_CHAR(stock_movements.created_at, 'DD/MM/YYYY HH24:MI:SS') as movement_date
            ");
            $query->join('products','products.id','=','stock_movements.product_id');
            $query->join('users','users.id','=','stock_movements.creation_user');
            $data = $query->find($id);
            
            if(!$data){
                abort(404);
            }

            return view('admin.stockmovement.detail',compact('data'));

        }else{
            abort(404);
        }
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

    public function read(Request $request)
    {
        $start  = $request->start;
        $length = $request->length;
        $draw   = $request->draw;
        $sort   = $request->columns[$request->order[0]['column']]['data'];
        $dir    = $request->order[0]['dir'];

        $query = StockMovement::selectRaw("
            stock_movements.id,
            stock_movements.reference,
            stock_movements.description,
            stock_movements.type,
            stock_movements.qty,
            stock_movements.status,
            TO_CHAR(stock_movements.created_at, 'DD/MM/YYYY HH24:MI') as movement_date,
            products.name as product,
            users.name as issued_by
        ");
        $query->join('products','products.id','=','stock_movements.product_id');
        $query->join('users','users.id','=','stock_movements.creation_user');
        $query->orderBy('stock_movements.date', 'desc');

        $rows  = clone $query;
        $total = $rows->count();

        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);

        $queries = $query->get();

        $data = [];
        $no   = 0;
        foreach ($queries as $key => $row) {
            $row->no = ++$no;
            $data[]  = $row;
        }
        return response()->json([
            'draw'              => $draw,
            'recordsTotal'      => $total,
            'recordsFiltered'   => $total,
            'data'              => $data
        ], Response::HTTP_OK);
    }
}
