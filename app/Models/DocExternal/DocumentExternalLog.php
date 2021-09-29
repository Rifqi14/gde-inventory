<?php

namespace App\Models\DocExternal;

use Illuminate\Database\Eloquent\Model;

class DocumentExternalLog extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function documentexternal()
    {
        return $this->belongsTo('App\Models\DocExternal\DocumentExternal', 'document_external_id', 'id');
    }

    /**
     * Scope a query to only include 
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrentStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}
