<?php

namespace App\Models\DocExternal\Properties;

use Illuminate\Database\Eloquent\Model;

class DocumentExternalKksCategory extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    
    public function docexternal()
    {
        return $this->hasMany('App\Models\DocExternal\DocumentExternal', 'kks_category_id', 'id');
    }

    public function codes()
    {
        return $this->hasMany('App\Models\DocExternal\DocumentExternal', 'document_external_kks_categories_id', 'id');
    }
}
