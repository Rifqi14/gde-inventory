<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessTripTransportation extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function transportCurrency()
    {
        return $this->belongsTo('App\Models\Currency', 'currency_id', 'id');
    }
}
