<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractAddendum extends Model
{
    protected $guarded = [];
    public function contract()
    {
        return $this->hasOne('App\Models\Contract', 'id', 'contract_id');
    }
    public function attach()
    {
        return $this->hasMany('App\Models\ContractAddendumAttach', 'addendum_id', 'id');
    }
}
