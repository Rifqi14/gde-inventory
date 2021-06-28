<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceRequest extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Define relation one to one with attendances table 
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function attendances()
    {
        return $this->belongsTo('App\Models\Attendance', 'attendance_id', 'id');
    }

    /**
     * Define relation many to one with working_shifts table
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function requestshift()
    {
        return $this->belongsTo('App\Models\WorkingShift', 'working_shift_id', 'id');
    }

    /**
     * Scope a query to only include attendance_id
     *
     * @param int $attendanceId
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetByAttendanceId($query, $attendanceId)
    {
        return $query->where('attendance_id', $attendanceId);
    }
}