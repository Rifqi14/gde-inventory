<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    protected $guarded = [];

    public function rack()
    {
        return $this->hasMany('App\Models\RackWarehouse', 'warehouse_id', 'id');
    }

    public function site()
    {
        return $this->hasOne('App\Models\Site', 'id', 'site_id');
    }

    public function province()
    {
        return $this->hasOne('App\Models\Province', 'id', 'province_id');
    }

    public function region()
    {
        return $this->hasOne('App\Models\Region', 'id', 'region_id');
    }

    public function district()
    {
        return $this->hasOne('App\Models\District', 'id', 'district_id');
    }

    public function village()
    {
        return $this->hasOne('App\Models\Village', 'id', 'subdistrict_id');
    }

    /**
     * Scope a query to only include name
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param string $name
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeName($query, $name)
    {
        $name   = strtoupper($name);
        return $query->whereRaw("upper(name) like '%$name%'");
    }
}