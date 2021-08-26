<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function machine()
    {
        return $this->belongsTo('App\Models\AttendanceMachine', 'attendance_machine_id', 'id');
    }

    /**
     * Scope a query to only include attendance_id
     *
     * @param int $attendance_id
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAttendanceId($query, $attendance_id)
    {
        return $query->where('attendance_id', $attendance_id);
    }

    /**
     * Scope a query to only include employee_id
     *
     * @param int $employee_id
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEmployeeId($query, $employee_id)
    {
        return $query->where('employee_id', $employee_id);
    }

    /**
     * Scope a query to only include attendance
     *
     * @param timestamp $attendance
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAttendanceTime($query, $attendance)
    {
        return $query->where('attendance', $attendance);
    }

    /**
     * Scope a query to only include type
     *
     * @param string $type
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }
}