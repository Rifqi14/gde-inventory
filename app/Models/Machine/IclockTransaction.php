<?php

namespace App\Models\Machine;

use Illuminate\Database\Eloquent\Model;

class IclockTransaction extends Model
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
    protected $table = 'iclock_transaction';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}
