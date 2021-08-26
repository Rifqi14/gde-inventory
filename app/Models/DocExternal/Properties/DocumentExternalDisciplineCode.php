<?php

namespace App\Models\DocExternal\Properties;

use Illuminate\Database\Eloquent\Model;

class DocumentExternalDisciplineCode extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function docexternal()
    {
        return $this->hasMany('App\Models\DocExternal\DocumentExternal', 'discipline_code_id', 'id');
    }
}
