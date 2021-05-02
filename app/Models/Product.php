<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $guarded = [];

    public function minmax()
    {
        return $this->hasMany('App\Models\MinMaxProduct', 'product_id', 'id');
    }

    public function uoms()
    {
        return $this->hasMany('App\Models\ProductUom', 'product_id', 'id');
    }

    public function category()
    {
        return $this->hasOne('App\Models\ProductCategory', 'id', 'product_category_id');
    }
}
