<?php

namespace App\Models\Machine;

use Illuminate\Database\Eloquent\Model;

class AttPayloadpairing extends Model
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
    protected $table = 'att_payloadparing';

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function employee()
    {
        return $this->belongsTo(\App\Models\Machine\PersonelEmployee::class, 'emp_id', 'id');
    }
}
