<?php

namespace App\Models;

use Alfa6661\AutoNumber\AutoNumberTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class DocumentCenterDocument extends Model
{
    use SoftDeletes;
    use AutoNumberTrait;

    public $orgcode = '';

    public function setCode($code)
    {
        $this->orgcode = $code;
    }

    public function getCode()
    {
        return $this->orgcode;
    }

    public function getAutoNumberOptions()
    {
        $orgcode    = $this->documentCenter()->first()->organization ? $this->documentCenter()->first()->organization->code : null;
        $year       = date('Y');

        return [
            'transmittal_no'    => [
                'format'        => "?-TRM-$orgcode-GDE-$year",
                'length'        => 3
            ]
        ];
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
            $model->created_by      = Auth::user()->id;
            $model->updated_by      = Auth::user()->id;
        });

        static::updating(function ($model) {
            $model->updated_by      = Auth::user()->id;
        });
    }

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

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
     * The function to define relation with User Model
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_by', 'id');
    }

    /**
     * The function to define relation with User Model
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
    public function downloaders()
    {
        return $this->belongsToMany('App\User')->using('App\Models\DocumentCenterDocumentDownload')->withPivot([
            'created_at',
            'updated_at',
        ]);
    }
}
