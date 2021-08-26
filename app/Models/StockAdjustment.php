<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Alfa6661\AutoNumber\AutoNumberTrait;
class StockAdjustment extends Model
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
        $code = 'STA';
        $year = date('Y');

        return [
            'key_number' => [
                'format' => "$code-$year-?",
                'length' => 6
            ]
        ];   
    }

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
        return $this->hasMany('App\Models\StockAdjustmentProduct', 'stock_adjustment_id', 'id');
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

    public function warehouse()
    {
        return $this->hasOne('App\Models\Warehouse', 'id', 'warehouse_id');
    }

    public function totalItems(){
        
    }

    use AutoNumberTrait;

    public function getAutoNumberOptions()
    {
        $code = 'ADJ';
        $year = date('Y');

        return [
            'key_number' => [
                'format' => "$code-$year-?",
                'length' => 6
            ]
        ];
    }
}