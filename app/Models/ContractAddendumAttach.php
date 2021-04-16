<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractAddendumAttach extends Model
{
    protected $guarded = [];
    public function adden()
    {
        return $this->hasOne('App\Models\ContractAddendum', 'id', 'contract_id');
    }
}
