<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\Models\StockMovement;
use App\Models\StockWarehouse;

class MovementProcess implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {        
        $movement = StockMovement::find($this->id);
        
        $product_id     = $movement->product_id;
        $type           = $movement->type;        
        $qty            = $movement->qty;        
        $warehouse_id   = $type=='in'?$movement->destination_id:$movement->source_id;
            
        $stocks = StockWarehouse::where([
            ['warehouse_id','=', $warehouse_id],
            ['product_id','=',$product_id]
        ])->first();

        if($type  == 'in'){ 
            $stocks->stock = $stocks->stock + $qty;
        }else if($type == 'out'){
            $stock = $stocks->stock - $qty;
            $stocks->stock = $stock > 0?$stock:0;
        }
        $stock->save();
    
        $movement->proceed = 1;
        $movement->save();
    }
}


