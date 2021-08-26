<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractReceipt extends Model
{
    protected $guarded = [];

    public function contract()
    {
        return $this->hasOne('App\Models\Contract', 'id', 'contract_id');
    }

    public function warehouse()
    {
        return $this->hasOne('App\Models\Warehouse', 'id', 'warehouse_id');
    }

    public function role()
    {
        return $this->belongsTo('App\Models\Role', 'role_id', 'id');
    }

    public function document()
    {
        return $this->hasMany('App\Models\ContractDocumentReceipt', 'contract_receipt_id', 'id')->orderBy('id','asc');
    }
}