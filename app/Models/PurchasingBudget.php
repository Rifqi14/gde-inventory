<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchasingBudget extends Model
{
    protected $guarded = [];

    public function purchasing()
    {
        return $this->hasOne('App\Models\Purchasing', 'id', 'purchasing_id');
    }
}
