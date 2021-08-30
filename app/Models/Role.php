<?php

namespace App\Models;

use App\Models\DocExternal\ReviewerMatrix\DocumentExternalMatrix;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = [];

    public function users()
    {
        return $this->belongsToMany('App\User', 'role_users', 'user_id', 'role_id');
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
}
