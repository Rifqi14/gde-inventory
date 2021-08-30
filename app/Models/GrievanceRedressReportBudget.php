<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrievanceRedressReportBudget extends Model
{
    protected $guarded = [];

    public function grievance_redress_reports()
    {
        return $this->hasOne('App\Models\GrievanceRedressReport','id','report_id');
    }
}
