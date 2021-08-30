<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

use App\Models\StockAdjustment;
use App\Models\StockAdjustmentAsset;
use App\Models\StockAdjustmentLog;
use App\Models\StockAdjustmentProduct;
use App\Models\StockAdjustmentProductAsset;
use App\Models\StockMovement;
use App\Models\Product;
use App\Models\ProductSerial;
use App\Models\ProductSerialAsset;
use App\Models\ProductUom;

class StockAdjustmentController extends Controller
{
    function __construct()
    {
        View::share('menu_active', url('admin/' . 'stockadjustment'));
        $this->middleware('accessmenu', ['except' => ['select','selectproduct']]);
    }

    public function index()
    {
        return view('admin.stockadjustment.index');
    }

    public function read(Request $request)
    {
        $start = $request->start;
        $length = $request->length;
        $query = $request->search['value'];
        $sort = $request->columns[$request->order[0]['column']]['data'];
        $dir = $request->order[0]['dir'];
        $name = strtoupper($request->name);
        $type = $request->type;

        //Count Data
        $query = StockAdjustment::select('*')->with("warehouse");
        // $query->whereRaw("upper(name) like '%$name%'");
        // if($type){
        //     $query->where("type", $type);
        // }

        $row = clone $query;
        $recordsTotal = $row->count();

        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $adjustments = $query->get();

        $data = [];
        foreach ($adjustments as $adjustment) {
            $adjustment->no = ++$start;
            $adjustment->total_items = $this->getTotalItems($adjustment->id);
            $data[] = $adjustment;
        }
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data
        ], 200);
    }

    public function getTotalItems($id){
        $saps = StockAdjustmentProduct::where("stock_adjustment_id",$id)->count();
        return $saps;
    }

    public function selectproduct(Request $request)
    {
        $start = $request->page ? $request->page - 1 : 0;
        $length = $request->limit;
        $name = strtoupper($request->name);
        $warehouse_id = $request->warehouse_id;
        $category = $request->category;

        //Count Data
        // $query = Product::select('*')->with('uoms','uoms.uom');
        // $query->where('product_category_id', $category);
        // $query->whereRaw("upper(name) like '%$name%'");

        // $row = clone $query;
        // $recordsTotal = $row->count();

        // $query->offset($start);
        // $query->limit($length);
        // $products = $query->get();

        $query = Product::with('uoms','uoms.uom');
        $query->select('products.id','products.name','products.sku',
                        'products.is_serial as is_serial',
                        'min_max_products.minimum as minimum_qty',
                        'stock_warehouses.stock');
        $query->leftJoin(DB::raw("(
            select stock_warehouses.*,warehouses.name warehouse_name, warehouses.site_id warehouse_site_id from stock_warehouses
            left join warehouses on warehouses.id = stock_warehouses.warehouse_id
            where warehouses.type = 'internal' and warehouses.id = $warehouse_id
            ) as stock_warehouses"), 'stock_warehouses.product_id', '=' ,'products.id');
        $query->leftJoin('min_max_products', function ($join) {
            $join->on('min_max_products.product_id', '=', 'products.id');
            $join->on('min_max_products.site_id', '=', 'stock_warehouses.warehouse_site_id');
        });
        $query->whereRaw("upper(name) like '%$name%'");
        $query->where('product_category_id', $category);
        if($request->selected_product_id){
            $query->whereNotIn('products.id', $request->selected_product_id);
        }
        $query->with('uoms','uoms.uom');

        $row = clone $query;
        $recordsTotal = $row->count();

        $query->offset($start);
        $query->limit($length);
        $products = $query->get();

        $data = [];
        foreach ($products as $product) {
            $product->no = ++$start;
            $product->qty = 0;
            $product->items = [];
            if($product->is_serial){
                $product->items = $this->getItems($product->id, $warehouse_id);
                $product->qty = count($product->items);
            }
            $product->uom_id = "";
            $product->uom_name = "";
            foreach($product->uoms as $uom){
                if($uom->uom->type == "REFERENCE"){
                    $product->uom_id = $uom->uom_id;
                    $product->uom_name = $uom->uom->name;
                }
            }
            if(!$product->stock){
                $product->stock = 0;
            }
            $data[] = $product;
        }
        return response()->json([
            'total' => $recordsTotal,
            'rows' => $data
        ], 200);
    }

    public function getItems($product_id, $warehouse_id){
        $items = ProductSerial::where("product_id",$product_id)->where("warehouse_id",$warehouse_id)->get();

        return $items;
    }

    public function create(Request $request)
    {
        if(in_array('create',$request->actionmenu)){
            return view('admin.stockadjustment.create');
        }else{
            abort(403);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'site_id'              => 'required',
            'warehouse_id'              => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }

        $userid = Auth::guard('admin')->user()->id;

        $adjustment = StockAdjustment::create([
            'adjustment_date'   => $request->adjustment_date,
            'site_id'           => $request->site_id,
            'warehouse_id'      => $request->warehouse_id,
            'status'            => $request->status,
            'description'       => $request->description,
            'issued_by'         => $userid,
        ]);

        $this->updateNumber($adjustment->id, $adjustment->key_number, $adjustment->created_at);

        if ($adjustment) {
            $request->adjustment_item = json_decode($request->adjustment_item);
            foreach($request->adjustment_item as $row){
                $adjustment_product = StockAdjustmentProduct::create([
                    'stock_adjustment_id'   => $adjustment->id,
                    'product_id'            => $row->product_id,
                    'qty_before'            => $row->qty_before,
                    'qty_after'             => $row->qty_after,
                    'updated_items'         => json_encode($row->items),
                    'added_items'           => json_encode($row->added_serial),
                    'deleted_items'         => json_encode($row->deleted_serial),
                ]);
            }

            $adjustment_log = StockAdjustmentLog::create([
                'stock_adjustment_id'   => $adjustment->id,
                'issued_by'   => $userid,
                'log_description'   => "Create Adjustment",
            ]);
            
            return response()->json([
                'status'     => true,
                'results'     => route('stockadjustment.index'),
            ], 200);   
        }else{
            return response()->json([
                'status'    => false,
                'message'   => $adjustment
            ], 400);
        }        
    }

    public function getitemserial(Request $request){
        $product_id = $request->product_id;
        $warehouse_id = $request->warehouse_id;

        $stocks = ProductSerial::where("product_id",$product_id)->where("warehouse_id",$warehouse_id)->get();

        $data = [];
        foreach($stocks as $row){   
            $data[] = $row;
        }

        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => "Success Get Product Serial"
        ], 200);   
    }

    public function getuomproduct(Request $request){
        $start = $request->page ? $request->page - 1 : 0;
        $length = $request->limit;
        $name = strtoupper($request->name);
        $product_id = $request->product_id;

        $query = ProductUom::where("product_id", $product_id)->with("uom");

        $row = clone $query;
        $recordsTotal = $row->count();

        $query->offset($start);
        $query->limit($length);
        $uoms = $query->get();

        $data = [];
        foreach($uoms as $uom){
            $data[] = $uom;
        }

        return response()->json([
            'total' => $recordsTotal,
            'rows' => $data
        ], 200);
    }

    public function getdetailserial(Request $request){
        $product_serial_id = $request->product_serial_id;

        $product_serial = ProductSerial::where("id", $product_serial_id)->first();

        $data;
        $serial = new \stdClass;
        $serial->id = "";
        $serial->product_id = "";
        $serial->serial_number = "";
        $serial->uom_id = "";
        $serial->uom = [];
        $serial->description = "";
        $serial->photo = [];
        $serial->document = [];
        if($product_serial){
            $serial->id = $product_serial->id;
            $serial->product_id = $product_serial->product_id;
            $serial->serial_number = $product_serial->serial_number;
            $serial->uom_id = $product_serial->uom_id;
            $serial->uom = $product_serial->uom;
            $serial->description = $product_serial->description;
            $serial->photo = [];
            $serial->document = [];
        }
        $data = $serial;

        return response()->json([
            'status' => true,
            'data' => $data,
            'message' => "Success"
        ], 200);
    }

    public function updateNumber($id, $key_number, $created_at)
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
}
