<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkingShift extends Model
{
    protected $guarded = [];

    /**
     * Define relation with calendars table
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function calendar()
    {
        return $this->belongsTo('App\Models\Calendar', 'calendar_id', 'id');
    }
}
