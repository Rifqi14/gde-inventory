<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

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

        return response()->json([
            'status' => Response::HTTP_OK,
            'total'  => $total,            
            'data'   => $data
        ], Response::HTTP_OK);
    }

    public function edit($id)
    {
        if(!$id){
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'mesage' => 'The given data was invalid'
            ], Response::HTTP_BAD_REQUEST);
        }    
    }
}
