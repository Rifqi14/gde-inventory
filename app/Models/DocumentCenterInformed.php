<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Support\Facades\Auth;

class DocumentCenterInformed extends Pivot
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
    protected $table = 'document_center_informeds';

    /**
     * The "booting" method of the model.
     *
     * @return \Illuminate\Database\Query\Builder
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

    public function document()
    {
        return $this->belongsTo('App\Models\DocumentCenter', 'document_center_id', 'id');
    }
}
