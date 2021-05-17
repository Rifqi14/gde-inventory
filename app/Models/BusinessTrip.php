<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessTrip extends Model
{
    protected $guarded = [];
    
    public function transportation()
    {
        return $this->hasMany('App\Models\BusinessTripTransportation','business_trip_id');
    }

    public function departs()
    {
        return $this->hasMany('App\Models\BusinessTripTransportation','business_trip_id')->where('transportation_type','depart');
    }

    public function returns()
    {
        return $this->hasMany('App\Models\BusinessTripTransportation','business_trip_id')->where('transportation_type','return');
    }

    public function vehicles()
    {
        return $this->hasMany('App\Models\BusinessTripVehicle','business_trip_id');
    }

    public function lodgings()
    {
        return $this->hasMany('App\Models\BusinessTripLodging','business_trip_id');
    }

    public function others()
    {
        return $this->hasMany('App\Models\BusinessTripOther','business_trip_id');
    }
}
