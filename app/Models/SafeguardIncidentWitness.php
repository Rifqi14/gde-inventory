<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SafeguardIncidentWitness extends Model
{
    protected $table = 'safeguard_incident_witness';
    protected $guarded = [];

    public function safeguard_incident()
    {
        return $this->hasOne('App\Models\SafeguardIncident', 'id', 'incident_id');
    }

    public function user()
    {
        return $this->hasOne('App\User', 'id', 'user_id');
    }
}
