<?php

namespace App\Models\DocExternal\Properties;

use Illuminate\Database\Eloquent\Model;

class DocumentExternalPhaseCode extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    
    public function docexternal()
    {
        return $this->hasMany('App\Models\DocExternal\DocumentExternal', 'phase_code_id', 'id');
    }
}
