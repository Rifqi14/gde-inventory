<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Alfa6661\AutoNumber\AutoNumberTrait;
class GoodsReceipt extends Model
{
    protected $guarded = [];
    use AutoNumberTrait;

    public function getAutoNumberOptions()
    {
        $code  = 'GRC';
        $year  = date('Y');
        
        return [
            'key_number' => [
                'format' => "$code-$year-?",
                'length' => 6
            ]
        ];
    }

    public function references()
    {
        return $this->hasMany('App\Models\GoodsReceiptProduct','goods_receipt_id');
    }

    public function contractref()
    {        
        return $this->hasMany('App\Models\GoodsReceiptProduct','goods_receipt_id')->where('goods_receipt_products.type','contract');
    }

    public function borrowingref()
    {
        return $this->hasMany('App\Models\GoodsReceiptProduct','goods_receipt_id')->where('goods_receipt_products.type','borrowing');
    }

    public function documents()
    {
        return $this->hasMany('App\Models\GoodsReceiptAsset','goods_receipt_id');
    }

    public function files()
    {
        return $this->hasMany('App\Models\GoodsReceiptAsset','goods_receipt_id')->where('type','file');
    }

    public function images()
    {
        return $this->hasMany('App\Models\GoodsReceiptAsset','goods_receipt_id')->where('type','photo');
    }
}
