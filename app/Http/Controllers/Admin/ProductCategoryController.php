<?php 
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProductCategoryController extends Controller
{
    function __construct()
    {
        View::share('menu_active',url('admin/'.'productcategory'));
    }

    public function index()
    {
        return view('admin.productcategory.index');
    }

    public function create()
    {
        $url    = route('productcategory.store');
        return view('admin.productcategory.create',compact('url'));
    }

    public function edit($id)
    {
        $productcategory = ProductCategory::with('parent')->find($id);        
        if ($productcategory) {
            $data = $productcategory;                    
            return view('admin.productcategory.edit',compact('data'));
        }else{
            abort(404);
        }     
    }

    public function read(Request $request)
    {
        $draw   = $request->draw;
        $start  = $request->start;
        $length = $request->length;
        $query  = $request->search['value'];
        $sort   = $request->columns[$request->order[0]['column']]['data'];
        $dir    = $request->order[0]['dir'];
        $name   = strtoupper($request->name);

        $query = DB::table('product_categories');
        $query->select('product_categories.*');
        if ($name) {
            $query->whereRaw("upper(product_categories.name) like '%$name%'");
        }

        $rows  = clone $query;
        $total = $rows->count();

        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);

        $procats = $query->get();
        
        $data = [];
        $no   = $start;
        foreach ($procats as $key => $row) {
            $row->no = ++$no;
            $data[]  = $row;
        }

        return response()->json([
            'draw'            => $draw,
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $data
        ],200);
    }

    public function parentcategories(Request $request)
    {
        $start = $request->page?$request->page - 1:0;
        $limit = $request->limit;
        $name  = strtoupper($request->name);        

        $query = ProductCategory::query();
        if($name){
            $query->whereRaw("upper(name) like '%$name%'");
        }

        $rows  = clone $query;
        $total = $rows->count();

        $query->offset($start);
        $query->limit($limit);
        $productcategories = $query->get();

        $data = [];
        foreach ($productcategories as $key => $row) {
            $row->no = ++$start;
            $data[]  = $row;
        }

        return response()->json([
			'total' => $total,
			'rows'  => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name'        => 'required',
            'description' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
        		'status' 	=> false,
        		'message' 	=> 'Failed to insert data.',
                'params'    => $validator->errors()->first()
        	], 400);
        }

        $parent_id   = $request->parent_category;
        $name        = $request->name;
        $description = $request->description;

        $productcategory = ProductCategory::create([
            'parent_id'   => $parent_id?$parent_id:0,
            'name'        => $name,      
            'description' => $description
        ]);

        $productcategory->path     = implode('->',$this->createPath($productcategory->id,[]));
        $productcategory->children = $this->createChildren($productcategory->id,0);
        $productcategory->save();   

        // $this->updateChildren($productcategory->id);

        if ($productcategory) {            
            return response()->json([
                'status'  => true,
                'results' => route('productcategory.index')
            ], 200);
        }else{
            return response()->json([
                'status'  => false,
                'message' => $productcategory
            ], 400);
        }
    }

    public function update(Request $request,$id)
    {
        $validator = Validator::make($request->all(),[
            'name'        => 'required',
            'description' => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
        		'status' 	=> false,
        		'message' 	=> 'Failed to update data.',
                'params'    => $validator->errors()->first()
        	], 400);
        }
        
        $name        = $request->name;
        $description = $request->description;

        $productcategory = ProductCategory::find($id);
        $productcategory->name        = $name;
        $productcategory->description = $description;
        $productcategory->save();

        if ($productcategory) {
            $result['status']  = true;
            $result['message'] = 'Data has been updated.';
            $result['point']   = 200;
        }else{
            $result['status']  = false;
            $result['message'] = 'Failed to update data.';
            $result['point']   = 400;
        }

        return response()->json([
            'status'  => $result['status'],
            'message' => $result['message'],
            'results' => route('productcategory.index')
        ],$result['point']);
    }

    public function destroy($id)
    {        
        try {             
            $productcategory = ProductCategory::find($id);
            $productcategory->delete();

            if ($productcategory) {
                $result['status'] = true;
                $result['point']  = 200;
            }else{
                $result['status'] = false;
                $result['point']  = 400;
            }

            return response()->json([
                'status' => $result['status']
            ],$result['point']);

        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => false                
            ], 400);
        }
    }

    public function createPath($id,$path)
    {
        $productcategory = ProductCategory::find($id);
        array_unshift($path,$productcategory->name);

        if ($productcategory->parent_id) {
            // return $this->createPath($productcategory->parent_id,$path);
        }

        return $path;
    }

    public function updatePath($id)
    {
        $productcategories = ProductCategory::where('id',$id)->get();

        foreach ($productcategories as $key => $row) {
            $row->path = implode('->',$this->createPath($row->id,[]));
            $row->save();
            // $this->updatePath($row->id);
        }
    }

    public function createChildren($id,$children)
    {
        $productcategories = ProductCategory::where('id',$id)->get();

        foreach ($productcategories as $key => $row) {
            $children = $children + $productcategories->count();            
            // return $this->createChildren($row->id,$children);
        }
        return $children;
    }

    public function updateChildren($id)
    {
        $productcategories = ProductCategory::where('id',$id)->get();

        foreach ($productcategories as $key => $row) {
            $row->children = $this->createChildren($row->id,0);
            $row->save();
            // $this->updateChildren($row->parent_id);
        }
    }
}


?>