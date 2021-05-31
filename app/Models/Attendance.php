<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Relation many to one with employee model
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function employee()
    {
        return $this->belongsTo('App\Models\Employee', 'employee_id', 'id');
    }

    /**
     * Relation many to one with workingshift model
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function shift()
    {
        return $this->belongsTo('App\Models\WorkingShift', 'working_shift_id', 'id');
    }

    /**
     * Scope a query to only include date
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param date $date
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDate($query, $date)
    {
        return $query->where('attendance_date', $date);
    }

    /**
     * Scope a query to only include employee
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param integer $employee
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEmployee($query, $employee)
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