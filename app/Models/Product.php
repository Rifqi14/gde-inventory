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

    public function issues()
    {
        return $this->hasMany('App\Models\GoodsIssueProduct','product_id','id');
    }

    public function borrowed()
    {
        return $this->hasManyThrough(
                'App\Models\GoodsIssue',
                'App\Models\GoodsIssueProduct',
                'goods_issue_products.product_id',
                'goods_issues.id',
                'id',
                'goods_issue_products.goods_issue_id')
            ->where([
                ['goods_issue_products.type','=','borrowing'],
                ['goods_issue_products.status','=','out'],
                ['goods_issues.status','=','approved']
            ]);
    }

    public function receipts()
    {
        return $this->hasMany('App\Models\GoodsReceiptProduct','product_id','id');
    }

    public function returned()
    {
        return $this->hasManyThrough(
                'App\Models\GoodsReceipt',
                'App\Models\GoodsReceiptProduct',
                'goods_receipt_products.product_id',
                'goods_receipts.id',
                'id',
                'goods_receipt_products.goods_receipt_id')        
            ->where([                
                ['goods_receipts.status','=','approved']                
            ]);
    }
    
    public function stocks()
    {
        return $this->hasMany('App\Models\StockWarehouse','product_id','id');
    }
}
