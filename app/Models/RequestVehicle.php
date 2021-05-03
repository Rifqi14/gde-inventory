<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestVehicle extends Model
{
    protected $guarded = [];

    public function borrowers()
    {
        return $this->hasMany('App\Models\BorrowerRequestVehicle','request_vehicle_id');
    }
}
