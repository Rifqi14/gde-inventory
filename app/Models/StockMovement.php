<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $guarded = [];

    public function uom()
    {
        return $this->hasOne('App\Models\Uoms', 'id', 'uom_id');
    }

    public function product()
    {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }

    public function source()
    {
        return $this->hasOne('App\Models\Warehouse', 'id', 'source_id');
    }

    public function destination()
    {
        return $this->hasOne('App\Models\Warehouse', 'id', 'destination_id');
    }

    public function productserial()
    {
        return $this->hasMany('App\Models\ProductSerial','product_serial_id')->whereNotNull('product_serial_id');
    }
}
