<?php

namespace App\Models\Transmittal\TransmittalProperties;

use App\Models\Role;
use Illuminate\Database\Eloquent\Model;

class CategoryContractor extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function groups()
    {
        return $this->belongsToMany(Role::class, 'category_contractor_groups', 'category_contractor_id', 'role_id');
    }
}
