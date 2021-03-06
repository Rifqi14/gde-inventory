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
    protected $role;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($movement, $role)
    {
        $this->movement       = $movement;
        $this->id             = $movement->id;
        $this->product_id     = $movement->product_id;
        $this->type           = $movement->type;        
        $this->source_id      = $movement->source_id;
        $this->destination_id = $movement->destination_id;
        $this->qty            = $movement->qty;
        $this->proceed        = $movement->proceed;
        $this->role           = $role; // Movement type (consumable, borrowing, contract etc.)
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {                               
        if($this->proceed == 0){            
            if($this->type  == 'in'){ 
                $move = $this->movein();
            }else if($this->type == 'out'){                
                $move = $this->moveout();
            }else if($this->type == 'adjustment'){
                $move = $this->adjust();
            }

            if($move){
                return $this->log();
            }
        }                    
    }

    public function movein()
    {
        $role  = $this->role;

        if($role == 'adjustment'){
            $move =$this->updateStock($this->destination_id, $this->qty);
        }

        if($move){
            return true;
        }
    }

    public function moveout()
    {        
        $role = $this->role;        
        
        if($role == 'consumable' || $role == 'borrowing'){
            $warehouse = StockWarehouse::where([
                ['warehouse_id','=', $this->source_id],
                ['product_id','=',$this->product_id]
            ])->first();
                
            $stock = $warehouse->stock - $this->qty;

            if($this->updateStock($warehouse->id, $stock)){
                return true;
            }
            
        }else if($role == 'transfer'){
            $origin = StockWarehouse::where([
                ['warehouse_id','=', $this->source_id],
                ['product_id','=',$this->product_id]
            ])->first();            

            $originStock = $origin->stock - $this->qty;

            if($this->updateStock($origin->id, $originStock)){
                return true;
            }
        }
    }

    public function adjust()
    {                  
        $warehouse = $this->stockwarehouse($this->destination_id, $this->product_id);        
        $move      = $this->updateStock($warehouse->id, $this->qty);

        if($move){
            $this->log();
        }
    }

    
    public function updateStock($warehouse_id, $stock)
    {        
        $warehouse = StockWarehouse::find($warehouse_id);
        $warehouse->stock = $stock;
        $warehouse->save();

        if($warehouse){
            return true;
        }
    }

    // Update Stock movement
    public function log()
    {
        $movement = StockMovement::find($this->id);
        $movement->status  = 'complete';
        $movement->proceed = 1;
        $movement->save();   
    }

    public function stockwarehouse($warehouse_id, $product_id)
    {
        $warehouse = StockWarehouse::where([
            ['warehouse_id','=', $warehouse_id],
            ['product_id','=', $product_id]
        ])->first();

        if(!$warehouse){
            $warehouse = StockWarehouse::create([
                'product_id'    => $product_id,
                'warehouse_id'  => $warehouse_id
            ]);
        }
                
        return $warehouse;
    }
}


