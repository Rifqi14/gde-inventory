<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractProduct extends Model
{
    protected $guarded = [];

    public function contract()
    {
        return $this->hasOne('App\Models\Contract', 'id', 'contract_id');
    }

    public function product()
    {
        return $this->hasOne('App\Models\Product', 'id', 'product_id');
    }

    public function uom()
    {
        return $this->hasOne('App\Models\UomCategory', 'id', 'uom_id');
    }
}
