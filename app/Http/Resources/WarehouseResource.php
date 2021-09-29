<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Models\Warehouse;
class WarehouseResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $getVirtual = isset($this['virtual'])?$this['virtual']:false;
        $getMulti   = isset($this['multi'])?$this['multi']:false;
        
        if($getVirtual){
            $data =  $this->virtual();
        }else if($getMulti){
            $data = $this->warehouses();
        }else{
            $data =  parent::toArray($request);
        }

        return $data;
    }

    public function warehouses()
    {
        $site_id = $this['site_id'];
        $warehouse_id = $this['warehouse_id'];

        $query = Warehouse::where(function($shape) use ($site_id, $warehouse_id){
            $shape->where([
                ['id','=',$warehouse_id],
                ['site_id','=',$site_id],
                ['status','=','active']
            ]);
            $shape->orWhere([
                ['site_id','=',$site_id],
                ['type','=','virtual'],
                ['status','=','active']
            ]);
        });
        $queries = $query->get();

        $data = [];
        foreach ($queries as $key => $row) {
            $data[] = [
                'id'      => $row->id,
                'site_id' => $row->site_id,
                'code'    => $row->code,
                'name'    => $row->name,                
                'type'    => $row->type
            ];
        }

        return $data;
    }

    public function virtual()
    {
        $site_id = $this['site_id'];

        $query = Warehouse::where([
            ['site_id','=',$site_id],
            ['type','=','virtual'],
            ['status','=','active']
        ])->first();        

        return [
            'id'      => $query->id,
            'site_id' => $query->site_id,
            'code'    => $query->code,
            'code'    => $query->name,
            'type'    => $query->type
        ];
    }
}
