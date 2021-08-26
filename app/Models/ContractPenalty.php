<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractPenalty extends Model
{
    protected $table = 'contract_penaltys';
    protected $guarded = [];
    public function contract()
    {
        return $this->hasOne('App\Models\Contract', 'id', 'contract_id');
    }
}
