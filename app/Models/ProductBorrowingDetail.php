<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductBorrowingDetail extends Model
{
    protected $guarded = [];

    public function borrowing()
    {
        return $this->belongsTo('App\Models\ProductBorrowing','id','product_borrowing_id');
    }

    public function serials()
    {
        return $this->hasMany('App\Models\ProductBorrowingSerial','borrowing_detail_id');
    }
}