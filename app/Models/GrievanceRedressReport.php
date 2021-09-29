<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GrievanceRedressReport extends Model
{
    protected $guarded = [];

    public function grievance_redress()
    {
        return $this->hasOne('App\Models\GrievanceRedress','id','grievance_id');
    }

    public function grievance_redress_report_budgets()
    {
        return $this->hasMany('App\Models\GrievanceRedress','report_id','id');
    }
}
