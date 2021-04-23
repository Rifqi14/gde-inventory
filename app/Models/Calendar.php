<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{
    /**
     * Protected column in calendars table
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Define relation one to many with calendar_exceptions table
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function exceptions()
    {
        return $this->hasMany('App\Models\CalendarException', 'calendar_id', 'id');
    }

    /**
     * Scope to get data by name
     *
     * @param $query
     * @param string $name
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function scopeGetByName($query, $name)
    {
        return $query->whereRaw("upper(name) like '%$name%'");
    }

    /**
     * Scope to get data by code
     *
     * @param $query
     * @param string $code
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function scopeGetByCode($query, $code)
    {
        return $query->whereRaw("upper(name) like '%$code%'");
    }

    /**
     * Scope to get data by description
     *
     * @param $query
     * @param string $description
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function scopeGetByDescription($query, $description)
    {
        return $query->whereRaw("upper(description) like '%$description%'");
    }
}