<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SalaryReportDetail extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Define relation with salary_reports table
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function salary()
    {
        return $this->belongsTo('App\Models\SalaryReport', 'salary_report_id', 'id');
    }

    /**
     * Define relation with currencies table
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function currencies()
    {
        return $this->belongsTo('App\Models\Currency', 'currency_id', 'id');
    }

    /**
     * Scope a query to only include additional item
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdditional($query)
    {
        return $query->where('type', 1);
    }

    /**
     * Scope a query to only include deduction item
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDeduction($query)
    {
        return $query->where('type', 2);
    }

    /**
     * Scope a query to only include specific salary report
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param int $salary_id
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSalaryId($query, $salary_id)
    {
        return $query->where('salary_report_id', $salary_id);
    }
}