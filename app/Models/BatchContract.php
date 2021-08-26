<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BatchContract extends Model
{
    protected $guarded = [];

    public function batchproduct(){
        return $this->hasMany('App\Models\BatchContractProduct', 'batch_contract_id', 'id');
    }

    public function contract(){
        return $this->hasOne('App\Models\Contract', 'id', 'contract_id');
    }
}
