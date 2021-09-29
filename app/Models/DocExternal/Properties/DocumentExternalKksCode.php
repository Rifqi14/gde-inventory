<?php

namespace App\Models\DocExternal\Properties;

use Illuminate\Database\Eloquent\Model;

class DocumentExternalKksCode extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    
    public function docexternal()
    {
        return $this->hasMany('App\Models\DocExternal\DocumentExternal', 'kks_code_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo('App\Models\DocExternal\Properties\DocumentExternalKksCategory', 'document_external_kks_category_id', 'id');
    }
}
