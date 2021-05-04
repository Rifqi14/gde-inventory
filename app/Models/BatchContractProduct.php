<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchContractProduct extends Model
{
    protected $guarded = [];

    public function batch(){
        return $this->hasOne('App\Models\BatchContract', 'id', 'batch_contract_id');
    }

    public function contractproduct(){
        return $this->hasOne('App\Models\ContractProduct', 'id', 'contract_product_id');
    }
}
