<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UomCategory extends Model
{
    protected $guarded = [];

    /**
     * Define relation one to many with uoms table
     * 
     * @author Muhammad Rifqi <rifqi.persie@gmail.com>
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function uoms()
    {
        return $this->hasMany('App\Models\Uom', 'uom_category_id', 'id');
    }
}