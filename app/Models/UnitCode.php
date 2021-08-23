<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UnitCode extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The function that define relation with OrganizationCode Model
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function organization()
    {
        return $this->belongsTo('App\Models\OrganizationCode', 'organization_code_id', 'id');
    }
}
