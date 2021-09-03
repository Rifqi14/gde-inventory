<?php

namespace App\Models\Machine;

use Illuminate\Database\Eloquent\Model;

class AttEmployee extends Model
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
    protected $table = 'att_attemployee';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    const CREATED_AT = 'create_time';
    const UPDATED_AT = 'change_time';
}
