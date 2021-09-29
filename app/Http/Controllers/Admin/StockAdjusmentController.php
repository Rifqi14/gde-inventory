<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Jobs\MovementProcess;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Database\QueryException;
use Illuminate\Support\Carbon;

use App\Models\Menu;
use App\Models\Site;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\ProductSerial;
use App\Models\StockAdjustment;
use App\Models\StockAdjustmentProduct;
use App\Models\StockAdjustmentLog;
use App\Models\StockMovement;
use App\Models\StockWarehouse;

class StockAdjusmentController extends Controller
{
    public function __construct()
    {
        View::share('menu_active', url('admin/' . 'stockadjustment'));        

        $menu   = Menu::where('menu_route', 'stockadjustment')->first();
        $parent = Menu::find($menu->parent_id);
        View::share('parent_name', $parent->menu_name);
        View::share('menu_name', $menu->menu_name);
        View::share('menu_active', url('admin/stockadjustment'));
        $this->middleware('accessmenu', ['except' => ['select']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.stockadjustment.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if(in_array('create',$request->actionmenu)){
            return view('admin.stockadjustment.create');
        }else{
            abort(403);
        }
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        if(in_array('update',$request->actionmenu)){

            $query = StockAdjustment::with([
                'product' => function($product){
                    $product->selectRaw("
                        stock_adjustment_products.*,                                                
                        products.name as product,                   
                        products.sku,
                        products.is_serial,                        
                        product_categories.name as category,
                        uoms.name as uom,
                        sum(stock_warehouses.stock) as stock_warehouse,
                        (case 
                            when sum(stock_warehouses.stock) > 0 then products.last_serial 
                            else 0 
                        end) as key_serial
                    ");
                    $product->leftJoin('products','products.id','=','stock_adjustment_products.product_id');
                    $product->leftJoin('product_categories','product_categories.id','=','products.product_category_id');
                    $product->leftJoin('uoms','uoms.id','=','stock_adjustment_products.uom_id');                                        
                    $product->leftJoin('stock_warehouses','stock_warehouses.product_id','=','stock_adjustment_products.product_id');
                    $product->groupBy(
                        'stock_adjustment_products.id',
                        'products.name',
                        'products.sku',
                        'uoms.name',                     
                        'products.is_serial',
                        'product_categories.name',
                        'products.last_serial'
                    );
                }
            ]);
            $query->selectRaw("
                stock_adjustments.*,
                TO_CHAR(stock_adjustments.adjustment_date,'DD/MM/YYYY') as date_adjustment,
                sites.name as site,
                warehouses.name as warehouse,
                (case
                    when employees.name is not null then employees.name
                    else users.name
                end) as issuedby
            ");
            $query->leftJoin('users','users.id','=','stock_adjustments.issued_by');
            $query->leftJoin('employees','employees.id','=','users.employee_id');
            $query->leftJoin('sites','sites.id','=','stock_adjustments.site_id');
            $query->leftJoin('warehouses','warehouses.id','=','stock_adjustments.warehouse_id');
            $data = $query->find($id);

            // echo json_encode($data);
            // return;

            if($data){
                return view('admin.stockadjustment.edit',compact('data'));
            }else{
                abort(404);
            }

        }else{
            abort(403);
        }
    }    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $query = StockAdjustment::with([
            'product' => function($product){
                $product->selectRaw("
                    stock_adjustment_products.*,                                                
                    products.name as product,                   
                    products.is_serial,                         
                    product_categories.name as category,
                    uoms.name as uom 
                ");
                $product->leftJoin('products','products.id','=','stock_adjustment_products.product_id');
                $product->leftJoin('product_categories','product_categories.id','=','products.product_category_id');
                $product->leftJoin('uoms','uoms.id','=','stock_adjustment_products.uom_id');                                        
            }
        ]);
        $query->selectRaw("
            stock_adjustments.*,
            TO_CHAR(stock_adjustments.adjustment_date,'DD/MM/YYYY') as date_adjustment,
            sites.name as site,
            warehouses.name as warehouse,
            (case
                when employees.name is not null then employees.name
                else users.name
            end) as issuedby
        ");
        $query->leftJoin('users','users.id','=','stock_adjustments.issued_by');
        $query->leftJoin('employees','employees.id','=','users.employee_id');
        $query->leftJoin('sites','sites.id','=','stock_adjustments.site_id');
        $query->leftJoin('warehouses','warehouses.id','=','stock_adjustments.warehouse_id');
        $data = $query->find($id);        

        if($data){
            return view('admin.stockadjustment.detail',compact('data'));
        }else{
            abort(404);
        }
    }

    public function read(Request $request)
    {        

        $draw       = $request->draw;
        $start      = $request->start;
        $length     = $request->length;
        $query      = $request->search['value'];
        $sort       = $request->columns[$request->order[0]['column']]['data'];
        $dir        = $request->order[0]['dir'];
        $startDate  = $request->startdate;
        $endDate    = $request->enddate;
        $number     = strtoupper($request->number);
        $status     = strtolower($request->status);
        $products   = $request->products;

        $query  = StockAdjustment::selectRaw("
            stock_adjustments.*,
            TO_CHAR(stock_adjustments.adjustment_date,'DD/MM/YYYY') as date_adjustment,
            warehouses.name as warehouse,
            count(stock_adjustment_products.product_id) as products
        ");
        $query->leftJoin('warehouses','warehouses.id','=','stock_adjustments.warehouse_id');
        $query->leftJoin('stock_adjustment_products','stock_adjustment_products.stock_adjustment_id','=','stock_adjustments.id');                
        if($startDate && $endDate){
            $query->whereBetween('stock_adjustments.adjustment_date', [$startDate, $endDate]);
        }
        if($number){
            $query->whereRaw("stock_adjustments.adjustment_number like '%$number%'");
        }
        if($status){
            $query->where('stock_adjustments.status','=',$status);
        }
        $query->orderBy('stock_adjustments.created_at','desc');
        $query->groupBy('stock_adjustments.id','warehouses.name');

        $rows  = clone $query;
        $total = $rows->count();

        $rows  = clone $query;
        $total = $rows->count();

        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);

        $queries = $query->get();

        $data = [];
        foreach ($queries as $key => $row) {
            if (isset($products) && $row->products == $products) {
                $row->no = ++$start;
                $data[]  = $row;
            } else if (!isset($products)) {
                $row->no = ++$start;
                $data[]  = $row;
            }
        }

        return response()->json([
            'draw'              => $draw,
            'recordsTotal'      => $total,
            'recordsFiltered'   => $total,
            'data'              => $data
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {                
        $validator = Validator::make($request->all(),[
            'adjustmentdate' => 'required',
            'site'           => 'required',
            'warehouse'      => 'required',
            'issuedby'       => 'required',
            'status'         => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ],400);            
        }

        $adjustmentdate = $request->adjustmentdate;
        $site           = $request->site;
        $warehouse      = $request->warehouse;        
        $issuedby       = $request->issuedby;
        $description    = $request->description;
        $status         = $request->status;

        $query = StockAdjustment::create([
            'adjustment_date' => $adjustmentdate,
            'site_id'         => $site,
            'warehouse_id'    => $warehouse,
            'issued_by'       => $issuedby,
            'status'          => $status,
            'description'     => $description
        ]);

        if($query){
            $now           = date('Y-m-d H:i:s');
            $adjustment_id = $query->id;

            $this->adjustmentNumber($adjustment_id,$query->key_number,$query->created_at);

            $getProducts = $request->products;
            $products    = [];

            if($getProducts){
                foreach (json_decode($getProducts) as $key => $row) {
                    $products[] = [
                        'stock_adjustment_id' => $adjustment_id,
                        'product_id'          => $row->product_id,
                        'uom_id'              => $row->uom_id,
                        'product_serial'      => $row->serial,
                        'qty_before'          => $row->qty_before?$row->qty_before:0,
                        'qty_after'           => $row->qty_after?$row->qty_after:0,
                        'created_at'          => $now,
                        'updated_at'          => $now
                    ];
                }

                if(count($products) > 0){
                    $query = StockAdjustmentProduct::insert($products);
                    if(!$query){
                        return response()->json([
                            'status'    => false,
                            'message'   => 'Failed to create detail products.'
                        ],400);
                    }
                }
            }

            $log = StockAdjustmentLog::create([
                'stock_adjustment_id'   => $adjustment_id,
                'issued_by'             => $issuedby,
                'log_description'       => 'Stock Adjusment has been created'
            ]);

            $result = [
                'status'    => true,
                'message'   => 'Data has been saved.',
                'point'     => 200
            ];
        }else{
            $result = [
                'status'    => false,
                'message'   => 'Failed to create data.',
                'point'     => 400
            ];                     
        }        

        return response()->json([
            'status'    => $result['status'],
            'message'   => $result['message']
        ],$result['point']);            
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
        $validator = Validator::make($request->all(),[
            'adjustmentdate' => 'required',
            'site'           => 'required',
            'warehouse'      => 'required',
            'issuedby'       => 'required',
            'status'         => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ],400);            
        }

        $adjustmentdate = $request->adjustmentdate;
        $site           = $request->site;
        $warehouse      = $request->warehouse;        
        $issuedby       = $request->issuedby;
        $description    = $request->description;
        $status         = $request->status;

        $query = StockAdjustment::find($id);
        $query->adjustment_date = $adjustmentdate;
        $query->site_id         = $site;
        $query->warehouse_id    = $warehouse;
        $query->description     = $description;
        $query->status          = $status;
        $query->save();

        if($query){            
            $getProducts = $request->products;            

            StockAdjustmentProduct::where('stock_adjustment_id',$id)->delete();

            if($getProducts){
                $adjustID  = $query->id;
                $adjustNum = $query->adjustment_number;

                $this->approval($getProducts,$adjustID,$adjustNum,$site, $warehouse, $status, $issuedby);                                             
            }           

            StockAdjustmentLog::create([
                'stock_adjustment_id'   => $id,
                'issued_by'             => $issuedby,
                'log_description'       => 'Stock Adjusment has been updated.'
            ]);

            $result = [
                'status'    => true,
                'message'   => 'Data has been saved.',
                'point'     => 200
            ];
        }else{
            $result = [
                'status'    => false,
                'message'   => 'Failed to update data.',
                'point'     => 400
            ];
        }
        return response()->json([
            'status'    => $result['status'],
            'message'   => $result['message']
        ],$result['point']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request,$id)
    {
        if(in_array('delete',$request->actionmenu)){
            try {
                $query = StockAdjustment::find($id)->delete();

                if($query){
                    return response()->json([
                        'status'    => true,
                        'message'   => 'Data has been removed.'
                    ],200);    
                }
            } catch (QueryException $th) {
                return response()->json([
                    'status'    => false,
                    'message'   => 'Failed to delete data.'
                ],400);
            }
        }else{
            abort(403);
        }
    }

    public function approval($getProducts,$adjustID,$adjustNum,$siteID,$warehouseID,$status, $issuedBy)
    {   
        $virtual = Warehouse::select('id')
                   ->where([
                       ['site_id','=',$siteID],
                       ['type','=','virtual']
                   ])->first();

        if(!$virtual){
            $site     = Site::find($siteID);
            $sitecode = strtoupper($site->code);
            $sitename = $site->name;

            $virtual = Warehouse::create([
                'code'          => "$sitecode - WV",
                'name'          => "$sitename - Warehouse Virtual",
                'type'          => 'virtual',
                'site_id'       => $siteID,
                'description'   => "Warehouse virtual of Unit or Site $sitename",
                'postal_code'   => 0,
                'address'       => '---',
                'status'        => 'active'
            ]);
        }

        foreach (json_decode($getProducts) as $key => $row) {  
            $qtyBefore = $row->qty_before?$row->qty_before:0;
            $qtyAfter  = $row->qty_after?$row->qty_after:0;                  

            $product = StockAdjustmentProduct::create([
                'stock_adjustment_id' => $adjustID,
                'product_id'          => $row->product_id,
                'uom_id'              => $row->uom_id,
                'product_serial'      => $row->serial,
                'qty_before'          => $qtyBefore,
                'qty_after'           => $qtyAfter
            ]);            

            if(!$product){
                return response()->json([
                    'status'    => false,
                    'message'   => 'Failed to adjust stock.'
                ], 400);
            }

           if($product && $status == 'approved'){
               if(isset($row->serial)){
                    $lastkey = 0;
                    $serials = [];
    
                    foreach(json_decode($row->serial) as $index => $bar){
                        $serials[] = [
                            'product_id'    => $row->product_id,
                            'warehouse_id'  => $warehouseID,
                            'serial_number' => $bar->number,               
                            'status'        => 1,
                            'created_at'    => Carbon::now(),
                            'updated_at'    => Carbon::now()
                        ];
                        
                        $lastkey = $bar->key;
                    }    
                            
                    $serial = ProductSerial::insert($serials);

                    if($serial){
                        $query = Product::find($row->product_id);
                        $query->last_serial = $lastkey;
                        $query->save();
                    }else{
                        return response()->json([
                            'status'    => false,
                            'message'   => 'Failed to create some product serials.'
                        ], 400);
                    }
               }
            
               $movement = StockMovement::create([
                'reference'      => $adjustNum,
                'description'    => 'Adjustment',
                'uom_id'         => $row->uom_id,                    
                'qty'            => $product->qty_after,
                'source_id'      => $virtual->id,
                'destination_id' => $warehouseID,
                'site_id'        => $siteID,
                'date'           => Carbon::now(),                    
                'proceed'        => 0,
                'type'           => 'adjustment',
                'creation_user'  => $issuedBy,
                'product_id'     => $row->product_id
               ]);

               if($movement){
                MovementProcess::dispatchNow($movement, $movement->type);        
               }
            }
        }                
    }

    public function adjustmentNumber($id, $key_number, $created_at)
    {
        $key   = preg_split("/[\s-]+/", "$key_number");
        $code  = $key[0];
        $year  = $key[1];
        $index = $key[2];
        $month = date('m', strtotime($created_at));

        $number = "$code-$year-$month-$index";

        $query = StockAdjustment::find($id);
        $query->adjustment_number = $number;
        $query->save();
    }

    public function calculateStock($warehouse_id,$products)
    {
        foreach (json_decode($products) as $key => $row) {
            $product_id = $row->product_id;

            $stock = StockWarehouse::where([
                ['warehouse_id','=',$warehouse_id],
                ['product_id','=',$product_id]
            ])->first();

            if($stock){
                $stock->stock = $row->qty_after;
                $stock->save();

                if(!$stock){
                    return response()->json([
                        'status'    => false,
                        'message'   => 'Failed to calculate stock product.'
                    ],400);
                }
            }else{
                $query = StockWarehouse::create([
                    'product_id'    => $product_id,
                    'warehouse_id'  => $warehouse_id,
                    'stock'         => $row->qty_after
                ]);

                if(!$query){
                    return response()->json([
                        'status'    => false,
                        'message'   => 'Failed to calculate stock product.'
                    ],400);
                }
            }
        }
    }  
    
    public function productserial(Request $request)
    {           
        $id         = $request->id;                
        $query = StockAdjustmentProduct::selectRaw("
                stock_adjustment_products.product_id,            
                stock_adjustment_products.product_serial,
                products.sku,
                uoms.name as uom,
                sum(stock_warehouses.stock) as stock_warehouse,
                (case 
                    when sum(stock_warehouses.stock) > 0 then products.last_serial 
                    else 0 
                end) as key_serial
            ");
            $query->leftJoin('products','products.id','=','stock_adjustment_products.product_id');
            $query->leftJoin('uoms','uoms.id','=','stock_adjustment_products.uom_id');
            $query->leftJoin('stock_warehouses','stock_warehouses.product_id','=','stock_adjustment_products.product_id');
            $query->groupBy(
                'stock_adjustment_products.id',
                'products.last_serial',
                'products.sku',
                'uoms.name'
            );
            $data = $query->find($id);           

        return response()->json([            
            'status'    => true,
            'data'   => $data
        ],200);
    }
}
