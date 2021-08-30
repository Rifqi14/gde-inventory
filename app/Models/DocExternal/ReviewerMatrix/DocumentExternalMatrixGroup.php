<?php

namespace App\Models\DocExternal\ReviewerMatrix;

use App\Models\DocExternal\DocumentExternal;
use App\Models\Role;
use Illuminate\Database\Eloquent\Model;

class DocumentExternalMatrixGroup extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function matrix()
    {
        return $this->belongsTo(DocumentExternalMatrix::class, 'matrix_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }
}
