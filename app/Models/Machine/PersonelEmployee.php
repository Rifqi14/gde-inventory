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
}
