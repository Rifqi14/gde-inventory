<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Equipment extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'equipment';

    /**
     * The function to define relation with Area Model.
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function area()
    {
        return $this->belongsTo('App\Models\Area', 'area_id', 'id');
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->created_user    = Auth::user()->id;
            $model->updated_user    = Auth::user()->id;
        });

        static::updating(function ($model) {
            $model->updated_user    = Auth::user()->id;
        });
    }
}
