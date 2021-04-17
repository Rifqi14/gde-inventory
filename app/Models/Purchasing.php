<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchasing extends Model
{
    protected $guarded = [];

    public function puser()
    {
        return $this->hasMany('App\Models\PurchasingUser', 'purchasing_id', 'id');
    }

    public function budget()
    {
        return $this->hasMany('App\Models\PurchasingBudget', 'purchasing_id', 'id');
    }
}
