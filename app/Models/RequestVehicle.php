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

    public function business_trip()
    {
        return $this->hasMany('App\Models\BusinessTripVehicle', 'request_vehicle_id');
    }

    public function issuedbyrequest()
    {
        return $this->belongsTo('App\User', 'issued_by', 'id');
    }

    public function vehiclerequest()
    {
        return $this->belongsTo('App\Models\Vehicle', 'vehicle_id', 'id');
    }
}