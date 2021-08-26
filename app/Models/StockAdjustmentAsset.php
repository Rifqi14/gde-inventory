<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAdjustmentAsset extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Define relation with StockAdjustmentProductAsset
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function adjustment()
    {
        return $this->belongsTo('App\Models\StockAdjustment', 'stock_adjustment_id', 'id');
    }
}