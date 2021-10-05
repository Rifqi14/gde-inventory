<?php

namespace App\Models\Transmittal\TransmittalProperties;

use App\Models\Role;
use Illuminate\Database\Eloquent\Model;

class CategoryContractorGroup extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function contractor()
    {
        return $this->belongsTo(CategoryContractor::class, 'category_contractor_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}
