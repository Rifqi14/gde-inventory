<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractWarrantyBond extends Model
{
    protected $guarded = [];
    public function contract()
    {
        return $this->hasOne('App\Models\Contract', 'id', 'contract_id');
    }
}
