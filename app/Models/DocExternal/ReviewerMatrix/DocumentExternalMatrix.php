<?php

namespace App\Models\DocExternal\ReviewerMatrix;

use App\Models\Role;
use Illuminate\Database\Eloquent\Model;

class DocumentExternalMatrix extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function document()
    {
        return $this->belongsTo(DocumentExternal::class, 'document_external_id' ,'id');
    }

    public function groups()
    {
        return $this->belongsToMany(Role::class, 'document_external_matrix_groups', 'matrix_id', 'role_id', 'id', 'id')->withTimestamps();
    }

    public function groupUsers()
    {
        return $this->belongsToMany('App\User', 'document_external_matrix_groups', 'matrix_id', 'user_id', 'id', 'id')->withTimestamps();
    }

    /**
     * Scope a query to only include
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSelectedLabel($query, $label)
    {
        return $query->where('matrix_label', $label);
    }
}
