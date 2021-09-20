<?php

namespace App\Models\Transmittal\TransmittalProperties;

use App\Models\Role;
use Illuminate\Database\Eloquent\Model;

class TransmittalOrganizationCode extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function groups()
    {
        return $this->belongsToMany(Role::class, 'transmittal_organization_code_groups', 'transmittal_organization_code_id', 'role_id');
    }
}
