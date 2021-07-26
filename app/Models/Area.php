<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Area extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * The function to define relation with Equipment Model
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function equipments()
    {
        return $this->hasMany('App\Models\Equipment', 'area_id', 'id');
    }

    /**
     * The function to define relation with Site Model
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function site()
    {
        return $this->belongsTo('App\Models\Site', 'site_id', 'id');
    }

    /**
     * The "booting" method of the model.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->create_user     = Auth::user()->id;
            $model->updated_user    = Auth::user()->id;
        });

        static::updating(function ($model) {
            $model->create_user     = Auth::user()->id;
            $model->updated_user    = Auth::user()->id;
        });
    }
}
