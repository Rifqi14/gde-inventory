<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\DateFactory;

use App\Models\StockMovement;
use App\Models\ProductConsumable;
use App\Models\ProductTransfer;
use App\Models\ProductBorrowing;

class StockMovementController extends Controller
{
    public function read(Request $request)
    {         
        if(!isset($request)){
            return response()->json([
                'status'    => Response::HTTP_UNPROCESSABLE_ENTITY,
                'message'   => 'The given data was invalid.'
            ],Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $limit      = $request->limit > 0 ? $request->limit : 10;
        $range      = isset($request->range)?$request->range:'day';
        $date       = isset($request->date)?date('Y-m-d', strtotime($request->date)):date('Y-m-d');

        $movement = StockMovement::selectRaw("
            stock_movements.*,
            products.name as product,
            products.image,
            users.name as issued_by
        ");
        $movement->join('products','products.id','=','stock_movements.product_id');
        $movement->join('users','users.id','=','stock_movements.creation_user');        
        $movement->where('stock_movements.status','complete');
        if($range == 'week' || $range == 'month'){
            switch ($range) {
                case 'month':
                    $startDate = date('Y-m-01', strtotime($date));
                    $endDate   = date('Y-m-t', strtotime($date));
                    break;                
                default:
                    $startDate = date('Y-m-d', strtotime('monday this week', strtotime($date)));
                    $endDate   = date('Y-m-d', strtotime('sunday this week', strtotime($date)));
                    break;
            }
            $movement->whereBetween('date', [$startDate,$endDate]);
        }else{
            $movement->whereDate('date', $date);
        }
        $movement->orderBy('date','desc');        

        $movement->limit($limit);        
        $movements = $movement->get();

        $in        = 0;
        $out       = 0;
        $data      = [];        

        foreach ($movements as $key => $row) {
            $type = $row->type;
            if($type == 'in'){
                $in = $in + $row->qty;
            }else{
                $out = $out + $row->qty;
            }

            $data[] = [                
                'product_id'      => $row->product_id,
                'product' => $row->product,
                'image'   => $row->image,
                'date'    => date('d M Y', strtotime($row->date)),
                'qty'     => $row->qty,
                'status'  => $type,
                'issued_by' => $row->issued_by
            ];
        }

        $data = [
            [
                'product_id' => 1,
                'product' => 'Flange Adapter 12" 900#',
                'image' => null,
                'date' => date('d M Y'),
                'qty'   => 4,
                'status' => 'in',
                'issued_by' => 'Admin'
            ],
            [
                'product_id' => 2,
                'product' => 'Gate Valve 3-1/8" 3000#',
                'image' => null,
                'date' => date('d M Y'),
                'qty'   => 4,
                'status' => 'out',
                'issued_by' => 'Anonim'
            ]
        ];

        return response()->json([
            'status'     => Response::HTTP_OK,              
            'data'       => [
                'total_in'   => $in,
                'total_out'  => $out,
                'history'    => $data
            ]
        ],Response::HTTP_OK);
    }

    public function total(Request $request)
    {
        $date = isset($request->date)?date('Y-m-d', strtotime($request->date)):Carbon::now();

        $transfers = ProductTransfer::selectRaw('product_transfers.*');
        $transfers->where('status','approved');
        $transfers->whereDate('date_transfer', $date);

        $totalTransfer = $transfers->get()->count();
        
        $consumes = ProductConsumable::selectRaw('product_consumables.*');
        $consumes->where('status','approved');
        $consumes->whereDate('consumable_date', $date);

        $totalConsume = $consumes->get()->count();            

        $borrowings = ProductBorrowing::selectRaw('product_borrowings.*');
        $borrowings->where(function ($shape)
        {
            $shape->where('status','approved');
            $shape->orWhere('status','borrowed');
        });
        $borrowings->whereDate('borrowing_date', $date);

        $totalBorrow = $borrowings->get()->count();        

        $data = [
            'transfers'     => $totalTransfer,
            'consumables'   => $totalConsume,
            'borrowings'    => $totalBorrow,
            'inventories'   => $totalTransfer + $totalConsume + $totalBorrow
        ];

        $dummy = [
            'transfers'     => 7,
            'consumables'   => 8,
            'borrowings'    => 5,
            'inventories'   => 7+8+5
        ];

        return response()->json([
            'status' => Response::HTTP_OK,
            'data'   => $dummy
        ], Response::HTTP_OK);
    }
}
