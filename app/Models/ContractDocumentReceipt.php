<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractDocumentReceipt extends Model
{
    protected $guarded = [];

    public function detail()
    {
        return $this->hasMany('App\Models\ContractDocumentReceiptDetail', 'contract_document_receipt_id', 'id');
    }

    public function latestDetail()
    {
        return $this->hasOne('App\Models\ContractDocumentReceiptDetail')->latest();
    }
}