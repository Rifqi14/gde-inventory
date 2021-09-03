<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockWarehouse extends Model
{
    protected $guarded = [];

    public function warehouse()
    {
        return $this->hasManyThrough(
            'App\Models\Warehouse',
            'App\Models\Site', 
            'warehouse_id', 
            'site_id',
            'id',
            'id'
        );
    }
}
