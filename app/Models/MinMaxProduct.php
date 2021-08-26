<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MinMaxProduct extends Model
{
    protected $guarded = [];

    public function product()
    {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }

    public function site(){
        return $this->hasOne('App\Models\Site', 'id', 'site_id');
    }
}
