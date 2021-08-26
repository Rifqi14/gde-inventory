<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentCenterLog extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function document()
    {
        return $this->belongsTo('App\Models\DocumentCenterDocument', 'document_center_document_id', 'id');
    }
}
