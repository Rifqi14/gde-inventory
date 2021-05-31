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
}