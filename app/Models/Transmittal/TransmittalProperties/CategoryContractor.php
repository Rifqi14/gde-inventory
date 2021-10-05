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
        return $this->hasMany(CategoryContractorGroup::class, 'category_contractor_id', 'id');
    }
}
