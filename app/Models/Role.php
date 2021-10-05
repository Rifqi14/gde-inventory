<?php

namespace App\Models;

use App\Models\DocExternal\ReviewerMatrix\DocumentExternalMatrix;
use App\Models\Transmittal\TransmittalProperties\CategoryContractor;
use App\Models\Transmittal\TransmittalProperties\CategoryContractorGroup;
use App\Models\Transmittal\TransmittalProperties\TransmittalOrganizationCode;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany(User::class, 'role_users', 'role_id', 'user_id');
    }

    public function employeeNotNull()
    {
        return $this->users()->wherePivot('employee_id');
    }

    public function documentCenters()
    {
        return $this->belongsToMany('App\Models\DocumentCenter')->using('App\Models\DocumentCenterInformed')->withPivot([
            'created_by',
            'updated_by',
        ]);
    }

    public function contractors()
    {
        return $this->hasMany('App\Models\DocExternal\DocumentExternal', 'role_id', 'id');
    }

    public function matrices()
    {
        return $this->belongsToMany(DocumentExternalMatrix::class, 'document_external_matrix_groups', 'role_id', 'matrix_id', 'id', 'id');
    }

    public function groupworkflows()
    {
        return $this->hasMany(\App\Models\DocExternal\Workflow\GroupWorkflow::class, 'role_id');
    }

    public function roleDistributors()
    {
        return $this->belongsToMany(DocumentCenterDocument::class, 'document_center_distributors', 'distributors_id', 'document_id');
    }

    public function categoryContractors()
    {
        return $this->hasOne(CategoryContractorGroup::class, 'role_id', 'id');
    }

    public function organizationCodes()
    {
        return $this->belongsToMany(TransmittalOrganizationCode::class, 'transmittal_organization_code_groups', 'role_id', 'transmittal_organization_code_id');
    }
}
