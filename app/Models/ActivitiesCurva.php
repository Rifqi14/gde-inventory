<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivitiesCurva extends Model
{
    protected $guarded = [];

    public function created_user()
    {
        return $this->hasOne('App\Users', 'id', 'created_user');
    }

    public function updated_user()
    {
        return $this->hasOne('App\Users', 'id', 'updated_user');
    }

    public function detail()
    {
        return $this->hasMany('App\Models\ActivitiesCurvaDetail', 'activities_id', 'id');
    }
}
