<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BudgetDetail extends Model
{
    protected $guarded = [];
    public function budget()
    {
        return $this->hasOne('App\Models\Budget', 'id', 'budget_id');
    }
}
