<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductBorrowingResource;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Carbon;
use App\Http\Requests\API\ProductBorrowingRequest;

use App\User;
use App\Models\Menu;
use App\Models\ProductBorrowing;
use App\Models\ProductBorrowingDetail;

class ProductBorrowingController extends Controller
{        
    public function edit(Request $request, $id)
    {        
        if(!$id){
            return response()->json([
                'status' => Response::HTTP_BAD_REQUEST,
                'message' => 'The given data was invalid.'
            ], Response::HTTP_BAD_REQUEST);
        }            

        $user = User::selectRaw("
                        employees.name as employee_name,
                        employees.photo,
                        roles.name as role    
                    ")
                    ->join('employees','employees.id','=','users.employee_id')
                    ->join('role_users','role_users.user_id','=','users.id')
                    ->join('roles','roles.id','=','role_users.role_id')
                    ->find(Auth::guard('api')->user()->id);

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
                        products.image,
                        uoms.name as uom
                    ");
                    $product->join('products','products.id','=','product_borrowing_details.product_id');
                    $product->join('uoms','uoms.id','=','product_borrowing_details.uom_id');
                }
            ]);
            $query = $query->find($id);

            if($query){                
                $products = [];
                foreach ($query->products as $key => $product) {
                    $product->borrowing_date = $query->borrowing_date;
                    $products[] = $product;
                }
                $query->products = $products;

                $data = $query;
            }            
        }        

        $user = [
            "employee_name" => "Anonim",
            "photo" => null,
            "role" => "Admin"
        ];

        $data = [            
                "id" =>  1,
                "borrowing_number" => "BRW-2021-09-000001",
                "borrowing_date" => "28 Sep 2021",
                "products" => [
                    [
                        "id" => 1,
                        "product_borrowing_id"=> 1,
                        "product_id"=> 3,
                        "uom_id"=> 2,
                        "qty_system"=> 5,
                        "qty_requested"=> 2,
                        "product"=> "Pipe Plug 1/2\"",
                        "uom"=> "each",
                        "image"=> null,
                        "borrowing_date" => "28 Sep 2021"
                    ],
                    [
                        'id' => 4,
                        "product_borrowing_id"=> 1,
                        "product_id"=> 12,
                        "uom_id"=> 2,
                        "qty_system"=> 5,
                        "qty_requested"=> 2,
                        "product"=> "Stud Bolt Two Nuts 1-3/4",
                        "uom"=> "each",
                        "image"=> null,
                        "borrowing_date" => "28 Sep 2021"
                    ],
                    [
                        'id' => 3,
                        "product_borrowing_id"=> 1,
                        "product_id"=> 2,
                        "uom_id"=> 2,
                        "qty_system"=> 5,
                        "qty_requested"=> 2,
                        "product"=> 'Bull Plug 3\" x 1/2\"',
                        "uom"=> "each",
                        "image"=> null,
                        "borrowing_date" => "28 Sep 2021"
                    ]
                ]            
        ];

        
        return response()->json([
            'status' => Response::HTTP_OK,            
            'data'   => [
                'employee'  => $user,
                'borrowing' => $data?$data:[]
            ],                        
        ], Response::HTTP_OK);
    }
    
    public function show(Request $request, $id)
    {
        # code...
    }

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
            count(product_borrowing_details.id) as number_of_products,
            product_borrowings.created_at
        ");
        $query->join('product_borrowing_details','product_borrowing_details.product_borrowing_id','=','product_borrowings.id');
        if($search){
            $query->whereRaw("
                upper(product_borrowings.borrowing_number) like '%$search%'
            ");
        }                        
        if($created){
            if($created == 'oldest'){
                $query->orderBy('product_borrowings.created_at', 'asc');
            }else{
                $query->orderBy('product_borrowings.created_at', 'desc');
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

    public function update(Request $request, $id)
    {                  
        return $request->product;

        if(!$id)    {
            return response()->json([
                'status'    => Response::HTTP_BAD_REQUEST,
                'message'   => 'The given data was invalid.'
            ], Response::HTTP_BAD_REQUEST);
        }

        if($request->actionmenu->approval){

            $products  = $request->products;
            $status    = $request->status;
            
            $query = ProductBorrowing::where('id', $id)->update(['status' => $status]);

            if($query){
                ProductBorrowingDetail::where('product_borrowing_id', $id)->delete();
                
                $items = [];
                foreach ($products as $key => $product) {
                    $items[] = [
                        'product_borrowing_id' => $id,
                        'product_id'           => $product->product_id,
                        'product_category_id'  => $product->product_category_id,
                        'uom_id'               => $product->uom_id,
                        'qty_system'           => $product->qty_system,
                        'qty_requested'        => $product->qty_requested,
                        'created_at'           => Carbon::now(),
                        'updated_at'           => Carbon::now()
                    ];
                }

                $query = ProductBorrowingDetail::insert($items);

                if(!$query){
                    return response()->json([
                        'status'    => Response::HTTP_UNPROCESSABLE_ENTITY,
                        'message'   => 'Failed to update data.'
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }
            }

            return response()->json([
                'status'  => Response::HTTP_OK,
                'message' => 'Successfully update data.'
            ], Response::HTTP_OK);

        }else{
            return response()->json([
                'status'    => Response::HTTP_FORBIDDEN,
                'message'   => 'Forbidden access.'
            ],Response::HTTP_FORBIDDEN);
        }
    }
}
