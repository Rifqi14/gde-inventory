<?php

namespace App\Http\Controllers\API;

use App\Exceptions\ProductException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\ProductRequest;
use App\Http\Resources\ProductResource;

use App\Models\Product;

class ProductController extends Controller
{
    public function read(Request $request)
    {        
        $length  = $request->limit ? $request->limit : 10;   
        $order   = $request->order ? $request->order :null;
        $created = isset($request->created)?$request->created:null;
        $search  = strtoupper($request->search);

        $products = Product::selectRaw("products.*");
        $products->with([
            'stocks' => function($stock){
                $stock->selectRaw("
                    stock_warehouses.id,
                    stock_warehouses.product_id,
                    stock_warehouses.warehouse_id,
                    stock_warehouses.stock
                ");
            }
        ]);
        if($search){
            $products->whereRaw("
                upper(products.name) like '%$search%'
            ");
        }                        
        if($created){
            if($created == 'oldest'){
                $products->oldest();
            }else{
                $products->latest();
            }
        }else {
            $products->orderBy('products.name', $order?$order:'asc');
        }                

        $rows  = clone $products;
        $total = $rows->count();
        
        $products->limit($length);
        $products = $products->get();

        if(!$products){
            return response()->json([
                'status'  =>  Response::HTTP_BAD_REQUEST,
                'message' => 'The given data was invalid.'
            ], Response::HTTP_BAD_REQUEST);    
        }

        $data  = [];        
        foreach ($products as $key => $product) {
            $qty = 0;
            foreach($product->stocks as $stock){
                $qty = $qty +  $stock->stock;
            }                

            $data[] = [       
                'id'         => $product->id,         
                'product'    => $product->name,
                'image'      => $product->image,                
                'added_date' => date('d M Y', strtotime($product->created_at)),
                'qty'        => $qty
            ];
        }        

        return response()->json([
            'status' =>  Response::HTTP_OK,
            'total'  => $total,
            'data'   => $data
        ], Response::HTTP_OK);
    } 
    
    public function show(Request $request)
    {                  
        $id = $request->id;
        
        if(!$id){
            throw new ProductException('The given data was invalid.');
        }

        return new ProductResource(
            Product::with([
                'stocks' => function($stock) {                                        
                    $stock->join('warehouses','warehouses.id','=','stock_warehouses.warehouse_id');           
                },
                'borrowed' => function($borrow){
                    $borrow->selectRaw("goods_issue_products.*");
                }
            ])->findorFail($id)
        );
    }

    public function latest(Request $request)
    {
        $length  = $request->limit ? $request->limit : 10;   

        $products = Product::selectRaw("products.*");
        $products->with([
            'stocks' => function($stock){
                $stock->selectRaw("
                    stock_warehouses.id,
                    stock_warehouses.product_id,
                    stock_warehouses.warehouse_id,
                    stock_warehouses.stock
                ");
            }
        ]);
        $products->latest();

        $rows  = clone $products;
        $total = $rows->count();
        
        $products->limit($length);
        $products = $products->get();

        $data  = [];        
        foreach ($products as $key => $product) {
            $qty = 0;
            foreach($product->stocks as $stock){
                $qty = $qty +  $stock->stock;
            }                

            $data[] = [       
                'product_id' => $product->product_id, 
                'product'    => $product->name,
                'image'      => $product->image,                
                'added_date' => date('d M Y', strtotime($product->created_at)),
                'qty'        => $qty
            ];
        }        

        return response()->json([
            'status' =>  Response::HTTP_OK,
            'total'  => $total,
            'data'   => $data
        ], Response::HTTP_OK);
    }
}
