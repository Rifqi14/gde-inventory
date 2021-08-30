<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarException extends Model
{
    /**
     * Protected column in calendar_exceptions table
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Define relation many to one with calendars table
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function calendar()
    {
        return $this->belongsTo('App\Models\Calendar', 'calendar_id', 'id');
    }

    /**
     * Define scope to get data with date parameter from calendar_exceptions table
     *
     * @param $query
     * @param date $date
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function scopeGetByDate($query, $date)
    {
        return $query->where('date_exception', $date);
    }
}