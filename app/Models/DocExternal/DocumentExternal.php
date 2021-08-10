<?php

namespace App\Models\DocExternal;

use Illuminate\Database\Eloquent\Model;

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

    public function sheetsize()
    {
        return $this->belongsTo(\App\Models\DocExternal\Properties\DocumentExternalSheetSize::class, 'sheet_size_id', 'id');
    }

    public function phasecode()
    {
        return $this->belongsTo(\App\Models\DocExternal\Properties\DocumentExternalPhaseCode::class, 'sheet_size_id', 'id');
    }
}
