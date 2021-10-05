<?php

namespace App\Models\DocExternal;

use App\Models\DocExternal\Properties\DocumentExternalContractorName;
use App\Models\DocExternal\Properties\DocumentExternalDisciplineCode;
use App\Models\DocExternal\Properties\DocumentExternalDocumentType;
use App\Models\DocExternal\Properties\DocumentExternalKksCategory;
use App\Models\DocExternal\Properties\DocumentExternalKksCode;
use App\Models\DocExternal\Properties\DocumentExternalOriginatorCode;
use App\Models\DocExternal\ReviewerMatrix\DocumentExternalMatrix;
use App\Models\DocExternal\ReviewerMatrix\DocumentExternalMatrixGroup;
use App\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DocumentExternal extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function sitecode()
    {
        return $this->belongsTo(\App\Models\DocExternal\Properties\DocumentExternalSiteCode::class, 'site_code_id', 'id');
    }

    public function phasecode()
    {
        return $this->belongsTo(\App\Models\DocExternal\Properties\DocumentExternalPhaseCode::class, 'sheet_size_id', 'id');
    }

    public function contractorgroup()
    {
        return $this->belongsTo(\App\Models\Role::class, 'contractor_group_id', 'id');
    }

    public function contractorname()
    {
        return $this->belongsTo(DocumentExternalContractorName::class, 'contractor_name_id', 'id');
    }

    public function createdby()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function disciplinecode()
    {
        return $this->belongsTo(DocumentExternalDisciplineCode::class, 'discipline_code_id', 'id');
    }

    public function documenttype()
    {
        return $this->belongsTo(DocumentExternalDocumentType::class, 'document_type_id', 'id');
    }

    public function kkscategory()
    {
        return $this->belongsTo(DocumentExternalKksCategory::class, 'kks_category_id', 'id');
    }

    public function kkscode()
    {
        return $this->belongsTo(DocumentExternalKksCode::class, 'kks_code_id', 'id');
    }

    public function originatorcode()
    {
        return $this->belongsTo(DocumentExternalOriginatorCode::class, 'originator_code_id', 'id');
    }

    public function updatedby()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    public function matrix()
    {
        return $this->hasMany(DocumentExternalMatrix::class, 'document_external_id', 'id');
    }

    public function revisions()
    {
        return $this->hasMany(DocumentExternalRevision::class, 'document_external_id', 'id');
    }

    public function workflows()
    {
        return $this->hasMany(\App\Models\DocExternal\Workflow\Workflow::class, 'document_external_id');
    }

    public function latestRevisionOwnership()
    {
        return $this->hasOne(DocumentExternalRevision::class, 'document_external_id')->doesntHave('outcoming')->whereHas('workflow', function(Builder $q) { 
            $q->whereNotNull('return_code');
        })->latest();
    }

    public function latestRevision()
    {
        return $this->hasOne(DocumentExternalRevision::class, 'document_external_id')->doesntHave('outcoming')->where('status', 'WAITING')->latest();
    }

    /**
     * Scope a query to only include 
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrentDocumetNo($query, $document_no)
    {
        return $query->where(DB::raw("upper(document_number)"), 'like', "%$document_no%");
    }

    /**
     * Scope a query to only include 
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrentDocumentType($query, $document_type)
    {
        return $query->where('document_type_id', $document_type);
    }

    /**
     * Scope a query to only include 
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrentSiteCode($query, $site_code)
    {
        return $query->where('site_code_id', $site_code);
    }

    /**
     * Scope a query to only include 
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrentDisciplineCode($query, $discipline_code)
    {
        return $query->where('discipline_code_id', $discipline_code);
    }

    /**
     * Scope a query to only include 
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrentKksCategory($query, $kks_category)
    {
        return $query->where('kks_category_id', $kks_category);
    }

    /**
     * Scope a query to only include 
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrentKksCode($query, $kks_code)
    {
        return $query->where('kks_code_id', $kks_code);
    }

    /**
     * Scope a query to only include 
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrentOriginatorCode($query, $originator_code)
    {
        return $query->where('originator_code_id', $originator_code);
    }

    /**
     * Scope a query to only include 
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrentPhaseCode($query, $phase_code)
    {
        return $query->where('phase_code_id', $phase_code);
    }

    /**
     * Scope a query to only include 
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrentDocumentCategory($query, $document_category)
    {
        return $query->where('document_category_id', $document_category);
    }
}
