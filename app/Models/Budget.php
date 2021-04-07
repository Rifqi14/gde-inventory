<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    protected $guarded = [];
    public function detail()
    {
        return $this->hasMany('App\Models\BudgetDetail', 'budget_id', 'id');
    }
}
