<?php

namespace App\Models;

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
}
