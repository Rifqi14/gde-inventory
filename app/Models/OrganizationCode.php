<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationCode extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The function that define relation with UnitCode Model
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function units()
    {
        return $this->hasMany('App\Models\UnitCode', 'organization_code_id', 'id');
    }
}
