<?php

namespace App\Models\DocExternal\Workflow;

use Illuminate\Database\Eloquent\Model;

class Workflow extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function document()
    {
        return $this->belongsTo(\App\Models\DocExternal\DocumentExternal::class, 'document_external_id');
    }

    public function revision()
    {
        return $this->belongsTo(\App\Models\DocExternal\DocumentExternalRevision::class, 'revision_id');
    }

    public function groups()
    {
        return $this->hasMany(\App\Models\DocExternal\Workflow\GroupWorkflow::class, 'workflow_id');
    }

    public function files()
    {
        return $this->hasMany(\App\Models\DocExternal\Workflow\FileWorkflow::class, 'workflow_id');
    }
}
