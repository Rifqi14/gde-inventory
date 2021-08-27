<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SafeguardCategory extends Model
{
    protected $guarded = [];

    public function area()
    {
        return $this->hasOne('App\Models\Area', 'id', 'area_id');
    }

    public function createduser()
    {
        return $this->hasOne('App\User', 'id', 'created_user');
    }

    public function updateduser()
    {
        return $this->hasOne('App\User', 'id', 'updated_user');
    }
}
