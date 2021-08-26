<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
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

    public function ratecurrency()
    {
        return $this->belongsTo('App\Models\Currency', 'rate_currency_id', 'id');
    }

    public function salarycurrency()
    {
        return $this->belongsTo('App\Models\Currency', 'salary_currency_id', 'id');
    }

    public function calendar()
    {
        return $this->belongsTo('App\Models\Calendar', 'calendar_id', 'id');
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