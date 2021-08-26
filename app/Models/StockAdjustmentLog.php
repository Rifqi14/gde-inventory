<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAdjustmentLog extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Define relation with StockAdjustment
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function adjustment()
    {
        return $this->belongsTo('App\Models\StockAdjustment', 'stock_adjustment_id', 'id');
    }

    /**
     * Define relation with User
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function issuedby()
    {
        return $this->belongsTo('App\User', 'issued_by', 'id');
    }
}