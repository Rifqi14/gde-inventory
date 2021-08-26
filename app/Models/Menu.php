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

    /**
     * Scope a query to only include parent_id
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param integer $parent_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function scopeParent($query, $parent_id)
    {
        return $query->where('id', $parent_id);
    }

    public function child()
    {
        return $this->hasMany('App\Models\Menu', 'parent_id', 'id')->orderBy('menu_sort', 'asc');
    }

    public function parentMenu()
    {
        return $this->belongsTo('App\Models\Menu', 'parent_id', 'id');
    }
}