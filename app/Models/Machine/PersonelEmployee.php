<?php

namespace App\Models\Machine;

use Illuminate\Database\Eloquent\Model;

class PersonelEmployee extends Model
{
    /**
     * The connection name for the model.
     *
     * @var string
     */
    protected $connection = 'pgsql_biotime';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'personnel_employee';

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
    public $timestamps = false;

    public function payloads()
    {
        return $this->hasMany(\App\Models\Machine\AttPayloadpairing::class, 'emp_id', 'id');
    }

    public function department()
    {
        return $this->belongsTo(\App\Models\Machine\PersonnelDepartment::class, 'department_id', 'id');
    }
}
