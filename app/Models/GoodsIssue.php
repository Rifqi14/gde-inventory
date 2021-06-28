<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Alfa6661\AutoNumber\AutoNumberTrait;

class GoodsIssue extends Model
{
    protected $guarded = [];
    use AutoNumberTrait;

    public function getAutoNumberOptions()
    {
        $code = 'GIS';
        $year = date('Y');

        return [
            'key_number' => [
                'format' => "$code-$year-?",
                'length' => 6
            ]
        ];
    }

    public function products()
    {
        return $this->hasMany('App\Models\GoodsIssueProduct','goods_issue_id');
    }

    public function consumableproducts()
    {
        return $this->hasMany('App\Models\GoodsIssueProduct','goods_issue_id')->where('goods_issue_products.type','consumable');
    }

    public function transferproducts()
    {
        return $this->hasMany('App\Models\GoodsIssueProduct','goods_issue_id')->where('goods_issue_products.type','transfer');
    }

    public function documents()
    {
        return $this->hasMany('App\Models\GoodsIssueDocument','goods_issue_id');
    }

    public function files()
    {
        return $this->hasMany('App\Models\GoodsIssueDocument','goods_issue_id')->where('goods_issue_documents.type','file');
    }
    
    public function images()
    {
        return $this->hasMany('App\Models\GoodsIssueDocument','goods_issue_id')->where('goods_issue_documents.type','photo');
    }
}
