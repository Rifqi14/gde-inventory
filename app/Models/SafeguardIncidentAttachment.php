<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SafeguardIncidentAttachment extends Model
{
    protected $guarded = [];

    public function safeguard_incident()
    {
        return $this->hasOne('App\Models\SafeguardIncident', 'id', 'incident_id');
    }
}
