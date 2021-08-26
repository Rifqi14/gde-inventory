<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractRetentionMoney extends Model
{
    protected $table = 'contract_retention_moneys';
    protected $guarded = [];

    public function contract()
    {
        return $this->hasOne('App\Models\Contract', 'id', 'contract_id');
    }
}
