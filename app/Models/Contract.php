<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    protected $guarded = [];

    public function jvmember()
    {
        return $this->hasMany('App\Models\ContractJvmember', 'contract_id', 'id');
    }

    public function pb()
    {
        return $this->hasMany('App\Models\ContractPerformanceBond', 'contract_id', 'id');
    }

    public function ab()
    {
        return $this->hasMany('App\Models\ContractAdvanceBond', 'contract_id', 'id');
    }

    public function rb()
    {
        return $this->hasMany('App\Models\ContractRetentionBond', 'contract_id', 'id');
    }

    public function rm()
    {
        return $this->hasMany('App\Models\ContractRetentionMoney', 'contract_id', 'id');
    }

    public function pen()
    {
        return $this->hasMany('App\Models\ContractPenalty', 'contract_id', 'id');
    }

    public function wb()
    {
        return $this->hasMany('App\Models\ContractWarrantyBond', 'contract_id', 'id');
    }

    public function owner()
    {
        return $this->hasMany('App\Models\ContractOwner', 'contract_id', 'id');
    }

    public function adden()
    {
        return $this->hasMany('App\Models\ContractAddendum', 'contract_id', 'id');
    }

    public function site(){
        return $this->hasOne('App\Models\Site', 'id', 'unit');
    }
}
