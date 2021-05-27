<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Alfa6661\AutoNumber\AutoNumberTrait;
class BusinessTrip extends Model
{
    protected $guarded = [];
    use AutoNumberTrait;

    public function getAutoNumberOptions()
    {
        $code = 'BTR';       
        $date = date('Y');

        return [
            'business_trip_number' => [
                'format' => "$code-$date-?",
                'length' => 6
            ]
        ];
    }
    
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
