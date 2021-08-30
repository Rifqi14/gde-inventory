<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttendanceMachine extends Model
{
    /**
     * Define guarded column of table
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Define relation one to many with attendance_logs table
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function attendancelog()
    {
        return $this->hasMany('\App\Models\AttendanceLog', 'machine_id', 'id');
    }

    /**
     * Define scope to get data from attendance_machines by machine_name column
     *
     * @param $query
     * @param string $name
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function scopeGetByName($query, $name)
    {
        return $query->whereRaw("machine_name like '%$name%'");
    }

    /**
     * Define scope to get data from attendance_machines by type column
     *
     * @param $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function scopeGetByType($query, $type)
    {
        return $query->where('type', $type);
    }
}