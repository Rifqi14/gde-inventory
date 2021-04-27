<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    /**
     * define protected column
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Define scope to get data by menu_route column
     *
     * @param $query
     * @param string $route
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function scopeGetByRoute($query, $route)
    {
        return $query->where('menu_route', $route);
    }
}