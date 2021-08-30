<?php

namespace App\Models\DocExternal;

use Illuminate\Database\Eloquent\Model;

class DocumentExternalRevision extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function document()
    {
        return $this->belongsTo('App\Models\DocExternal\DocumentExternal', 'document_external_id', 'id');
    }

    public function sheetsize()
    {
        return $this->belongsTo('App\Models\DocExternal\Properties\DocumentExternalSheetSize', 'sheet_size_id', 'id');
    }

    public function files()
    {
        return $this->hasMany('App\Models\DocExternal\DocumentExternalRevisionFile', 'revision_id', 'id');
    }

    public function createdby()
    {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }

    public function updatedby()
    {
        return $this->belongsTo('App\User', 'updated_by', 'id');
    }

    public function logs()
    {
        return $this->hasMany('App\Models\DocExternal\DocumentExternalLog', 'document_revision_id', 'id');
    }
    
    public function void()
    {
        return $this->hasOne('App\Models\DocExternal\DocumentExternalRevisionVoid', 'revision_id', 'id');
    }

    public function supersede()
    {
        return $this->hasOne('App\Models\DocExternal\DocumentExternalRevisionSupersede', 'revision_id', 'id');
    }

    public function workflow()
    {
        return $this->hasOne(\App\Models\DocExternal\Workflow\Workflow::class, 'revision_id');
    }

    /**
     * Scope a query to only include 
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrentDocument($query, $document_id)
    {
        return $query->where('document_external_id', $document_id);
    }

    /**
     * Scope a query to only include 
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLatest($query)
    {
        return $query->where('revision_no', 'desc');
    }

    /**
     * Scope a query to only include 
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrentStatus($query, $status)
    {
        return $query->whereIn('issue_status', $status);
    }
}
