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

    protected $movement;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($movement)
    {
        $this->movement     = $movement;
        $this->id           = $movement->id;
        $this->product_id   = $movement->product_id;
        $this->type         = $movement->type;
        $this->warehouse_id = $movement->type=='in'?$movement->destination_id:$movement->source_id;
        $this->qty          = $movement->qty;
        $this->proceed      = $movement->proceed;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {                               
        if($this->proceed == 0){
            $stocks = StockWarehouse::where([
                ['warehouse_id','=', $this->warehouse_id],
                ['product_id','=',$this->product_id]
            ])->first();
    
            if($this->type  == 'in'){ 
                $stock = $stocks->stock + $this->qty;
            }else if($this->type == 'out'){
                $stock = $stocks->stock - $this->qty;      
                $stock = $stock<=0?0:$stock;                  
            }
    
            $stockWarehouse = StockWarehouse::find($stocks->id);
            $stockWarehouse->stock = $stock;
            $stockWarehouse->save();
    
            if($stockWarehouse){
                $movement = StockMovement::find($this->id);
                $movement->status  = 'complete';
                $movement->proceed = 1;
                $movement->save();        
            }
        }                    
    }
}


