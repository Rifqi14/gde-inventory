<?php

namespace App\Models;

use Alfa6661\AutoNumber\AutoNumberTrait;
use Illuminate\Database\Eloquent\Model;

class BusinessTripDeclaration extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    use AutoNumberTrait;

    public function getAutoNumberOptions()
    {
        $code       = 'BTD';
        $date       = date('Y');

        return [
            'index_number'  => [
                'format'    => "$code-$date-?",
                'length'    => 6
            ]
        ];
    }

    /**
     * Define relation with business_trips table
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function declarationbt()
    {
        return $this->belongsTo('App\Models\BusinessTrip', 'business_trip_id', 'id');
    }
}