<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BinWarehouse extends Model
{
    protected $guarded = [];

    public function rack()
    {
        return $this->hasOne('App\Models\RackWarehouse', 'id', 'rack_id');
    }
}
