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

use App\Models\Product;
use App\Models\MinMaxProduct;
use App\Models\ProductUom;
use App\Models\Site;
use App\Models\ProductCategory;

class ProductController extends Controller
{
    function __construct()
    {
        View::share('menu_active', url('admin/' . 'product'));
        $this->middleware('accessmenu', ['except' => ['select']]);
    }

    public function index(){
        return view('admin.product.index');
    }
    
    public function read(Request $request){
        $start = $request->start;
        $length = $request->length;
        $query = $request->search['value'];
        $sort = $request->columns[$request->order[0]['column']]['data'];
        $dir = $request->order[0]['dir'];
        $name = strtoupper($request->name);
        $product_category_id = $request->product_category_id;

        //Count Data
        $query = Product::select('*')->with('category');
        $query->whereRaw("upper(name) like '%$name%'");
        if($product_category_id){
            $query->where('product_category_id',$product_category_id);
        }

        $row = clone $query;
        $recordsTotal = $row->count();

        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $products = $query->get();

        $data = [];
        foreach ($products as $product) {
            $product->no = ++$start;
            if($product->category->parent_id != 0){
                $product->category->name = $this->getParent($product->category->parent_id, $product->category->name);
            }else{
                $product->category->name = $product->category->name;
            }
            $product->stock = 0;
            $data[] = $product;
        }
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data
        ], 200);
    }

    public function create(Request $request)
    {
        if(in_array('create',$request->actionmenu)){
            $sites = Site::all();
            return view('admin.product.create', compact('sites'));
        }else{
            abort(403);
        }
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name'                  => 'required',
            'product_category_id'   => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();

        $data = [
            'name'                   => $request->name,
            'description'            => $request->description,
            'product_category_id'    => $request->product_category_id,
            'merek'                  => $request->merek,
            'sku'                    => $request->sku,
            'is_serial'              => $request->is_serial ? '1' : '0',
        ];

        $attach = $request->file('image');
        if ($request->hasFile('image')) {
            $path = 'assets/product/';
			if (!file_exists($path)) {
				mkdir($path, 0777, true);
            }
            $product_name = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower($request->name));
            $attach->move($path, $product_name.'.'.$attach->getClientOriginalExtension());
            $filename = $path.$product_name.'.'.$attach->getClientOriginalExtension();
			$data['image'] = $filename;
        }

        $product = Product::create($data);
        
        if($product) {
            if($request->uom_id){
                foreach($request->uom_id as $key => $uom){
                    $uom = ProductUom::create([
                        'product_id' => $product->id,
                        'uom_id' => $uom,
                        'ratio' => $request->ratio[$key],
                        'is_show' => $request->show[$key],
                    ]);
                    if(!$uom){
                        DB::rollback();
                        return response()->json([
                            'status' => false,
                            'message'  => "Can't insert data UOM"
                        ], 400);
                    }
                }
            }

            if($request->minmax_site){
                foreach($request->minmax_site as $key => $site){
                    $minmax = MinMaxProduct::create([
                        'site_id' => $site,
                        'minimum' => $request->minimum[$key],
                        'maximum' => $request->maximum[$key],
                        'product_id' => $product->id,
                    ]); 
                    if(!$minmax){
                        DB::rollback();
                        return response()->json([
                            'status' => false,
                            'message'  => "Can't insert data minimum & maximum"
                        ], 400);
                    }
                }
            }

            DB::commit();
            return response()->json([
                'status' => true,
                'message'  => "Data has been saved",
                'results'  => route("product.index"),
                'id' => $product->id
            ], 200);
        }else{
            DB::rollback();
            return response()->json([
                'status'    => false,
                'message'   => 'Failed Insert Product.'
            ], 400);
        }
    }

    public function edit(Request $request,$id)
    {
        if(in_array('update',$request->actionmenu)){
            $product = Product::with('uoms','minmax')->find($id);
            if ($product) {
                if($product->category->parent_id != 0){
                    $product->category_name = $this->getParent($product->category->parent_id, $product->category->name);
                }else{
                    $product->category_name = $product->category->name;
                }
                return view('admin.product.edit', compact('product'));
            } else {
                abort(404);
            }
        }else{
            abort(403);
        }
    }

    public function getParent($id, $name){
        $categorys = ProductCategory::select('*')->where("id",$id)->get();
        
        foreach ($categorys as $category) {
            $name = $category->name.' &nbsp;&nbsp;<span style="font-size: 23px;position:relative;top: 2px;line-height: 0.1;">&rsaquo;</span>&nbsp;&nbsp;  '.$name;
            if($category->parent_id != 0){
                $name = $this->getParent($category->parent_id, $name);
            }
        }

        return $name;
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'                  => 'required',
            'product_category_id'   => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();

        $product = Product::find($id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->product_category_id = $request->product_category_id;
        $product->merek = $request->merek;
        $product->sku   = $request->sku;
        $product->is_serial = $request->is_serial ? '1' : '0';

        $attach = $request->file('image');
        if ($request->hasFile('image')) {
            $path = 'assets/product/';
			if (!file_exists($path)) {
				mkdir($path, 0777, true);
            }
            $product_name = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower($request->name));
            $attach->move($path, $product_name.'.'.$attach->getClientOriginalExtension());
            $filename = $path.$product_name.'.'.$attach->getClientOriginalExtension();
			$product->image = $filename;
        }

        if($product->save()){
            $deleteuom = ProductUom::where('product_id',$id)->delete();
            if($request->uom_id){
                foreach($request->uom_id as $key => $uom){
                    $uom = ProductUom::create([
                        'product_id' => $id,
                        'uom_id' => $uom,
                        'ratio' => $request->ratio[$key],
                        'is_show' => $request->show[$key],
                    ]);
                    if(!$uom){
                        DB::rollback();
                        return response()->json([
                            'status' => false,
                            'message'  => "Can't insert data UOM"
                        ], 400);
                    }
                }
            }

            $deleteminmax = MinMaxProduct::where('product_id',$id)->delete();
            if($request->minmax_site){
                foreach($request->minmax_site as $key => $site){
                    $minmax = MinMaxProduct::create([
                        'site_id' => $site,
                        'minimum' => $request->minimum[$key],
                        'maximum' => $request->maximum[$key],
                        'product_id' => $id,
                    ]); 
                    if(!$minmax){
                        DB::rollback();
                        return response()->json([
                            'status' => false,
                            'message'  => "Can't insert data minimum & maximum"
                        ], 400);
                    }
                }
            }

            DB::commit();
            return response()->json([
                'status' => true,
                'message'  => "Data has been updated",
                'results'  => route("product.index"),
                'id' => $product->id
            ], 200);

        }else{
            DB::rollback();
            return response()->json([
                'status' => false,
                'message'     => 'Failed update data product'
            ], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::find($id);
            $product->delete();
        } catch (QueryException $th) {
            return response()->json([
                'status'    => false,
                'message'   => 'Error delete data ' . $th->errorInfo[2]
            ], 400);
        }
        return response()->json([
            'status'    => true,
            'message'   => 'Failed delete data'
        ], 200);
    }

    public function show(Request $request,$id)
    {
        if(in_array('read',$request->actionmenu)){
            $product = Product::with('uoms','minmax')->find($id);
            if ($product) {
                if($product->category->parent_id != 0){
                    $product->category_name = $this->getParent($product->category->parent_id, $product->category->name);
                }else{
                    $product->category_name = $product->category->name;
                }
                return view('admin.product.detail', compact('product'));
            } else {
                abort(404);
            }
        }else{
            abort(403);
        }
    }
    public function select(Request $request)
    {
        $start  = $request->page ? $request->page - 1 : 0;
        $length = $request->limit;
        $name   = strtoupper($request->name);
        $product_category_id = $request->product_category_id;   
    
        $query = Product::selectRaw("
            products.*,            
            product_uoms.uom_id,
            uom_categories.name as uom
        ");        
        $query->leftJoin('product_uoms','product_uoms.product_id','=','products.id');
        $query->leftJoin('uoms','uoms.id','=','product_uoms.uom_id');
        $query->leftJoin('uom_categories','uom_categories.id','=','uoms.uom_category_id');
        if($name){
            $query->whereRaw("
                upper(products.name) like '%$name%'
            ");
        }
        if($product_category_id){
            $query->where('product_category_id',$product_category_id);
        }
    
        $rows  = clone $query;
        $total = $rows->count();
    
        $query->offset($start);
        $query->limit($length);
        $queries = $query->get();
    
        $data = [];
        foreach ($queries as $key => $row) {
            $row->qty_system = 10;            
            $data[] = $row;
        }
    
        return response()->json([
            'total' => $total,
            'rows'  => $data
        ],200);
    }
}