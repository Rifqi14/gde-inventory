<?php

namespace App\Models\DocumentCenter;

use App\Models\DocumentCenterDocument;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;

class DocumentCenterDistributor extends Model
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
     * @return \Illuminate\Database\Query\Builder
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at      = date('Y-m-d H:i:s');
            $model->updated_at      = date('Y-m-d H:i:s');
        });

        static::updating(function ($model) {
            $model->updated_at      = date('Y-m-d H:i:s');
        });
    }
    public function document()
    {
        return $this->belongsTo(DocumentCenterDocument::class, 'document_id', 'id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'distributor_id', 'id');
    }
}
