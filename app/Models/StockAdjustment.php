<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockAdjustment extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    
    /**
     * Define relation with StockAdjustmentAsset
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function asset()
    {
        return $this->hasMany('App\Models\StockAdjustmentAsset', 'stock_adjustment_id', 'id');
    }

    /**
     * Define relation with StockAdjustmentLog
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function log()
    {
        return $this->hasMany('App\Models\StockAdjustmentLog', 'stock_adjustment_id', 'id');
    }

    /**
     * Define relation with StockAdjusmentProduct
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function product()
    {
        return $this->hasMany('App\Models\StockAdjusmentProduct', 'stock_adjusment_id', 'id');
    }

    /**
     * Define relation with StockAdjustmentProductAsset
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function productasset()
    {
        return $this->hasMany('App\Models\StockAdjustmentProductAsset', 'stock_adjustment_id', 'id');
    }
}