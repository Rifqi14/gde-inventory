<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    protected $guarded = [];

    public function parent() {
        return $this->hasOne('App\Models\ProductCategory', 'id', 'parent_id');
    }

    public function subcategories(){

        return $this->hasMany('App\Models\ProductCategory', 'parent_id');

    }
}
