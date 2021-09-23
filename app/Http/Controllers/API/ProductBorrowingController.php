<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProductBorrowingResource;
use Illuminate\Support\Facades\Route;

use App\Models\Menu;
use App\Models\ProductBorrowing;
use App\Models\ProductBorrowingDetail;

class ProductBorrowingController extends Controller
{    
    
    public function read(Request $request)
    {
        $length  = $request->limit ? $request->limit : 10;   
        $order   = $request->order ? $request->order :null;
        $created = isset($request->created)?$request->created:null;
        $search  = strtoupper($request->search);

        $query = ProductBorrowing::selectRaw("
            product_borrowings.id,
            product_borrowings.borrowing_number,    
            product_borrowings.borrowing_date,
            product_borrowings.status,
            count(product_borrowing_details.id) as number_of_products
        ");
        $query->join('product_borrowing_details','product_borrowing_details.product_borrowing_id','=','product_borrowings.id');
        if($search){
            $query->whereRaw("
                upper(product_borrowings.borrowing_number) like '%$search%'
            ");
        }                        
        if($created){
            if($created == 'oldest'){
                $query->oldest();
            }else{
                $query->latest();
            }
        }else {
            $query->orderBy('product_borrowings.borrowing_number', $order?$order:'asc');
        }       
        $query->whereNotIn('product_borrowings.status',['approved','borrowed','rejected']);
        $query->groupBy(
            'product_borrowings.id'
        );
        
        $rows  = clone $query;
        $total = $rows->count();

        $query->limit($length);
        $borrowings = $query->get();

        if(!$borrowings){
            return response()->json([
                'status'  =>  Response::HTTP_BAD_REQUEST,
                'message' => 'The given data was invalid.'
            ], Response::HTTP_BAD_REQUEST);    
        }

        $data = [];
        foreach ($borrowings as $key => $row) {
            $row->borrowing_date = date('d M Y', strtotime($row->borrowing_date));
            $data[] = $row;
        }

        $data = [
            [
                "id" => 78,
                "borrowing_number" => "BRW-2021-09-000033",
                "borrowing_date" => "22 Sep 2021",
                "status" => "waiting",
                "number_of_products" =>  1
            ],
            [
                "id" => 75,
                "borrowing_number" => "BRW-2021-09-000030",
                "borrowing_date" => "22 Sep 2021",
                "status" => "draft",
                "number_of_products" => 2
            ]
        ];

        return response()->json([
            'status' => Response::HTTP_OK,
            'total'  => $total,            
            'data'   => $data
        ], Response::HTTP_OK);
    }

    public function edit(Request $request, $id)
    {
        if(!$id){
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'mesage' => 'The given data was invalid'
            ], Response::HTTP_BAD_REQUEST);
        }    

        $data = [];

        if($request->actionmenu->approval){
            $query = ProductBorrowing::selectRaw("
                product_borrowings.id,
                product_borrowings.borrowing_number,
                TO_CHAR(product_borrowings.borrowing_date, 'DD Mon YYYY') as borrowing_date
            ");
            $query->with([  
                'products' => function($product){
                    $product->selectRaw("
                        product_borrowing_details.id,
                        product_borrowing_details.product_borrowing_id,
                        product_borrowing_details.product_id,                    
                        product_borrowing_details.uom_id,
                        product_borrowing_details.qty_system,
                        product_borrowing_details.qty_requested,
                        products.name as product,
                        uoms.name as uom
                    ");
                    $product->join('products','products.id','=','product_borrowing_details.product_id');
                    $product->join('uoms','uoms.id','=','product_borrowing_details.uom_id');
                }
            ]);

            $data = $query->find($id);

            if(!$data){
                return response()->json([
                    'status'    => Response::HTTP_UNPROCESSABLE_ENTITY,
                    'message'   => 'Failed to get data.',
                ], Response::HTTP_UNPROCESSABLE_ENTITY);
            }
        }

        return response()->json([
            'status' => Response::HTTP_OK,
            'data'   => $data,                        
        ], Response::HTTP_OK);
    }
}
