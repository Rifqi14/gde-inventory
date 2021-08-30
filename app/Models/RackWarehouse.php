<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RackWarehouse extends Model
{
    protected $guarded = [];

    public function bin()
    {
        return $this->hasMany('App\Models\BinWarehouse', 'rack_id', 'id');
    }

    public function warehouse()
    {
        return $this->hasOne('App\Models\Warehouse', 'id', 'warehouse_id');
    }
}
