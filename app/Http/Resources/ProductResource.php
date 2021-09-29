<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {                        
        return [
            'product'     => $this->name,
            'image'       => $this->image,
            'description' => trim(preg_replace('/ +/', ' ', preg_replace('/[^A-Za-z0-9 ]/', ' ', urldecode(html_entity_decode(strip_tags($this->description)))))),
            'stocks'      => $this->stock($request),                        
        ];
    }    

    public function stock($request)
    {   
        $total     = 0;    
        $available = 0;  
        $borrowed  = 0;      
        $site_id = $request->site_id?$request->site_id:null;

        foreach ($this->stocks as $key => $row) {
            $stock = $row->stock>=0?$row->stock:0;
            
            if($row->site_id == $site_id){
                $available = $available + $stock;
            }
            $total = $total + $stock;                                               
        } 

        if($this->borrowed){
            foreach ($this->borrowed as $key => $row) {
                $borrowed = $borrowed + $row->qty_receive;
            }
        }

        return [
            'total'         => $total,
            'available'     => $available,
            'borrowed'      => $borrowed
        ];
    }
}
