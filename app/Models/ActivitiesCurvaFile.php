<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivitiesCurvaFile extends Model
{
    protected $guarded = [];

    public function activitiecurvadetail()
    {
        return $this->hasOne('App\Models\ActivitiesCurvaDetail', 'id', 'detail_id');
    }

    public function created_user()
    {
        return $this->hasOne('App\Users', 'id', 'created_user');
    }
}
