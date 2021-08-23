<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryReport extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Define relation with employee table
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function employee()
    {
        return $this->belongsTo('App\Models\Employee', 'employee_id', 'id');
    }

    /**
     * Define relation with user table
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id', 'id');
    }

    /**
     * Define relation with salary_report_details table
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function details()
    {
        return $this->hasMany('App\Models\SalaryReportDetail', 'salary_report_id', 'id');
    }

    /**
     * Scope a query to only include 
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param int $employee
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEmployeeById($query, $employee)
    {
        return $query->where('employee_id', $employee);
    }

    /**
     * Scope a query to only include status
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }
}