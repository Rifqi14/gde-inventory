<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogRevise extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Scope a query to only include route_menu
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRouteMenu($query, $route)
    {
        return $query->where('route_menu', $route);
    }

    /**
     * Scope a query to only include data_id
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDataId($query, $data)
    {
        return $query->where('data_id', $data);
    }
}