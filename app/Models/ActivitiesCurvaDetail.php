<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivitiesCurvaDetail extends Model
{
    protected $guarded = [];

    public function activitie()
    {
        return $this->hasOne('App\Models\ActivitiesCurva', 'id', 'activities_id');
    }

    public function created_user()
    {
        return $this->hasOne('App\Users', 'id', 'created_user');
    }

    public function file()
    {
        return $this->hasMany('App\Models\ActivitiesCurvaFile', 'detail_id', 'id');
    }
}
