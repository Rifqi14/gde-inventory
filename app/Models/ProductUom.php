<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductUom extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }

    public function uom()
    {
        return $this->hasOne('App\Models\Uom', 'id', 'uom_id');
    }
}