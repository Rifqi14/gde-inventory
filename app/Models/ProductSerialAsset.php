<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSerialAsset extends Model
{
    protected $guarded = [];

    public function productserial()
    {
        return $this->hasOne('App\Models\ProductSerial', 'id', 'product_serial_id');
    }
}
