<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use App\Models\StockMovement;

class StockMovementResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // return parent::toArray($request);

        $limit      = $request->limit > 0 ? $request->limit : 10;
        $startDate  = $request->startDate ? $request->startDate : Carbon::now();
        $endDate    = $request->endDate ? $request->endDate : Carbon::now();        

        $movement = StockMovement::selectRaw("
            stock_movements.*,
            products.name as product,
            products.image,
            users.name as issued_by
        ");
        $movement->join('products','products.id','=','stock_movements.product_id');
        $movement->join('users','users.id','=','stock_movements.creation_user');        
        $movement->where('stock_movements.status','complete');
        $movement->whereBetween('stock_movements.date',[$startDate, $endDate]);
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

        return [
            'total_in'   => $in,
            'total_out'  => $out,
            'data'       => $data,            
        ];
    }    
}
