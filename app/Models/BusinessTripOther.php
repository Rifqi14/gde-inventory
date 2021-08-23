<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BusinessTripOther extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function othersCurrency()
    {
        return $this->belongsTo('App\Models\Currency', 'currency_id', 'id');
    }
}
