<?php

namespace App\Models;

use Alfa6661\AutoNumber\AutoNumberTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductBorrowing extends Model
{
    protected $guarded = [];
    use SoftDeletes;
    use AutoNumberTrait;

    public function getAutoNumberOptions()
    {
        $code   = 'BRW';
        $year   = date('Y');
        $month  = date('m');

        return [
            'index_number'  => [
                'format'    => "$code-$year-?",
                'length'    => 6
            ]
        ];
    }
    
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