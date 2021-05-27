<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductBorrowing extends Model
{
    protected $guarded = [];
    use SoftDeletes;        

    public function products()
    {
        return $this->hasMany('App\Models\ProductBorrowingDetail','product_borrowing_id');
    }

    public function documents() // all supporting document
    {
        return $this->hasMany('App\Models\ProductBorrowingDocument','product_borrowing_id');
    }

    public function files() // supporting document with type is document
    {
        return $this->hasMany('App\Models\ProductBorrowingDocument','product_borrowing_id')->where('type','file');
    }

    public function images() // supporting document with type is photo
    {
        return $this->hasMany('App\Models\ProductBorrowingDocument','product_borrowing_id')->where('type','photo');
    }
}