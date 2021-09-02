<?php

namespace App\Models\DocExternal\Workflow;

use Illuminate\Database\Eloquent\Model;

class GroupWorkflow extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function workflow()
    {
        return $this->belongsTo(\App\Models\DocExternal\Workflow\Workflow::class, 'workflow_id');
    }

    public function role()
    {
        return $this->belongsTo(\App\Models\Role::class, 'role_id');
    }
}
