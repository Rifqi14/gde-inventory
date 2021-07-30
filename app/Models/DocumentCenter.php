<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class DocumentCenter extends Model
{
    use SoftDeletes;
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

    public static function boot() {
        parent::boot();

        static::creating(function ($model) {
            $model->created_user    = Auth::user()->id;
            $model->updated_user    = Auth::user()->id;
        });

        static::updating(function ($model) {
            $model->updated_user    = Auth::user()->id;
        });
    }

    /**
     * The function to define relation with Equipment Model
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function doctype()
    {
        return $this->belongsTo('App\Models\DocumentType', 'document_type_id', 'id');
    }

    /**
     * The function to define relation with Area Model
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function organization()
    {
        return $this->belongsTo('App\Models\OrganizationCode', 'organization_code_id', 'id');
    }

    /**
     * The function to define relation with Site Model
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function unitcode()
    {
        return $this->belongsTo('App\Models\UnitCode', 'unit_code_id', 'id');
    }

    /**
     * The function to define relation with User Model
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function createdBy()
    {
        return $this->belongsTo('App\User', 'created_user', 'id');
    }

    /**
     * The function to define relation with User Model
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function updatedBy()
    {
        return $this->belongsTo('App\User', 'updated_user', 'id');
    }

    /**
     * The function to define relation with DcCategory Model
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function category()
    {
        return $this->belongsTo('App\Models\DcCategory', 'category_id', 'id');
    }

    /**
     * The function to define relation with DocumentCenterContract Models
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function contracts()
    {
        return $this->hasMany('App\Models\DocumentCenterContract', 'document_center_id', 'id');
    }

    /**
     * The function to define relation with DocumentCenterDocument Models
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function documents()
    {
        return $this->hasMany('App\Models\DocumentCenterDocument', 'document_center_id', 'id');
    }

    /**
     * The function to define relation with DocumentCenterInformed Models
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function informeds()
    {
        return $this->hasMany('App\Models\DocumentCenterInformed', 'document_center_id', 'id');
    }

    /**
     * The function to define relation with DocumentCenterInformed Models
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function informers()
    {
        return $this->belongsToMany('App\Models\Role', 'document_center_informeds', 'document_center_id', 'role_id')->withPivot(['created_by', 'updated_by']);
    }
}
