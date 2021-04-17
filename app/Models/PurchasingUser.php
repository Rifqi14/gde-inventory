<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PurchasingUser extends Model
{
    protected $guarded = [];

    public function purchasing()
    {
        return $this->hasOne('App\Models\Purchasing', 'id', 'purchasing_id');
    }

    public function group()
    {
        return $this->hasOne('App\Models\Role', 'id', 'group_id');
    }
}
