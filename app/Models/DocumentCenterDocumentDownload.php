<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class DocumentCenterDocumentDownload extends Pivot
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function docdetail()
    {
        return $this->belongsTo('App\Models\DocumentCenterDocumentDetail', 'document_id', 'id');
    }
}
