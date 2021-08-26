<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessTripVehicle extends Model
{
    protected $guarded = [];

    public function businesstrip()
    {
        return $this->belongsTo('App\Models\BusinessTrip', 'business_trip_id');
    }
}