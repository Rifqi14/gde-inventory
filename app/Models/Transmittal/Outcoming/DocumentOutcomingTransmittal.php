<?php

namespace App\Models\Transmittal\Outcoming;

use App\Models\DocExternal\DocumentExternalRevision;
use Illuminate\Database\Eloquent\Model;

class DocumentOutcomingTransmittal extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function revision()
    {
        return $this->belongsTo(DocumentExternalRevision::class,' revision_id', 'id');
    }

    public function outcoming()
    {
        return $this->belongsTo(OutcomingTransmittal::class, 'outcoming_transmittal_id', 'id');
    }
}
