<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

use App\Models\Product;

class ProductController extends Controller
{
    public function read(Request $request)
    {
        $start   = $request->page ? $request->page - 1 : 0;
        $length  = $request->limit ? $request->limit : 10;   
        $order   = $request->order ? $request->order : 'asc';
        $search  = $request->search;

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
                products.name like '%$search%'
            ");
        }
        $products->orderBy('products.name', $order);
        $products->orderBy('created_at','desc');

        $rows  = clone $products;
        $total = $rows->count();

        $products->offset($start);
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
}
