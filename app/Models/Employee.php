<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->hasOne('App\User','employee_id');
    }

    public function region()
    {
        return $this->hasOne('App\Models\Region','id','region_id');
    }

    public function province()
    {
        return $this->hasOne('App\Models\Province','id','province_id');
    }

    public function workingshift()
    {
        return $this->hasOne('App\Models\WorkingShift','id','working_shift_id');
    }
}
