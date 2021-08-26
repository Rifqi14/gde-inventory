<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Uom extends Model
{
    /**
     * Define guarded column in uoms table
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Define relation many to one with uom_categories table
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function category()
    {
        return $this->belongsTo('\App\Models\UomCategory', 'uom_category_id', 'id');
    }

    /**
     * Define scope to get data from uoms by name column
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetByName($query, $name)
    {
        $name   = strtoupper($name);
        return $query->whereRaw("upper(name) like '%$name%'");
    }

    /**
     * Define scope to get data from uoms by type column
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $type
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Define scope to get data from uoms by uom_category_id column
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $category
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetByCategory($query, $category)
    {
        return $query->where('uom_category_id', $category);
    }
}