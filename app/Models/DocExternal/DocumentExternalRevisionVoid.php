<?php

namespace App\Models\DocExternal;

use Illuminate\Database\Eloquent\Model;

class DocumentExternalRevisionVoid extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function revision()
    {
        return $this->belongsTo('App\Models\DocExternal\DocumentExternalRevision', 'revision_id', 'id');
    }
}
