<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class DocumentCenterContract extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The "booting" method of the model.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($model) {
            $model->created_by      = Auth::user()->id;
            $model->updated_by      = Auth::user()->id;
        });

        static::updating(function ($model) {
            $model->updated_by      = Auth::user()->id;
        });
    }

    /**
     * The function to define relationship with DocumentCenter Model
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function documentCenter()
    {
        return $this->belongsTo('App\Models\DocumentCenter', 'document_center_id', 'id');
    }

    /**
     * The function to define relationship with User Model
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function updatedBy()
    {
        return $this->belongsTo('App\User', 'updated_by', 'id');
    }

    /**
     * The function to define relationship with User Model
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }
}
