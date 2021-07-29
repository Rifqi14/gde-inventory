<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsIssueProduct extends Model
{
    protected $guarded = [];

    public function serials()
    {
        return $this->hasMany('App\Models\GoodsIssueSerial','goods_issue_product_id');
    }
}
