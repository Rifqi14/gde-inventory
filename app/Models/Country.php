<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Define relation one to one with currency table
     * 
     * @author Muhammad Rifqi <rifqi.persie@gmail.com>
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function Currency()
    {
        return $this->belongsTo('App\Models\Currency', 'countries_id', 'id');
    }
}