<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Http\Response;

use App\Models\StockWarehouse;
use App\Models\StockMovement;

class CheckStockProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $product_id = $this->product_id;

        $movements = StockMovement::where([
            ['product_id', '=', $product_id],
            ['proceed', '=', 0]
        ]);
        $movements = $movements->get();

        foreach ($movements as $key => $row) {
                $id          = $row->id;
                $type        = $row->type;
                $qty         = $row->qty;
                $source      = $row->source_id;
                $destination = $row->destination_id;
                $warehouse   = $type=='in'?$destination:$source;

                $stock  = StockWarehouse::where('warehouse_id', $warehouse)->first();                    
                $update = StockWarehouse::find($stock->id);

                if($type == 'in'){                                        
                    $update->stock = $update->stock + $qty;                    
                }else if($type == 'out'){
                    $newStock = $update->stock - $qty;

                    $update->stock = $newStock>0?$newStock:0;
                }

                $update->save();

                if(!$update){
                    return response()->json([
                        'status'    => Response::HTTP_UNPROCESSABLE_ENTITY,
                        'message'   => 'Failed to checking stock.'
                    ], Response::HTTP_UNPROCESSABLE_ENTITY);
                }

                $movement = StockMovement::find($id);
                $movement->proceed = 1;
                $movement->save();
        }
    }
}
