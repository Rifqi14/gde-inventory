<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductSerial extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }

    public function warehouse()
    {
        return $this->hasOne('App\Models\Warehouse', 'id', 'warehouse_id');
    }

    public function uom()
    {
        return $this->hasOne('App\Models\Uoms', 'id', 'uom_id');
    }

    public function photo()
    {
        return $this->hasMany('App\Models\ProductSerialAsset','product_serial_id')->where('type','photo');
    }

    public function document()
    {
        return $this->hasMany('App\Models\ProductSerialAsset','product_serial_id')->where('type','document');
    }
}
