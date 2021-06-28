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

    public function attendances()
    {
        return $this->hasMany('App\Models\Attendance', 'employee_id', 'id');
    }

    /**
     * Scope a query to only include payroll yes
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePayrollYes($query)
    {
        return $query->where('payroll_type', 1);
    }
}