<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Define relation one to one with country table
     * 
     * @author Muhammad Rifqi <rifqi.persie@gmail.com>
     *
     * @return \Illuminate\Database\Query\Builder
     */
    public function country()
    {
        return $this->hasOne('App\Models\Country', 'id', 'countries_id');
    }

    /**
     * Define relation one to many with employees table
     *
     * @author Muhammad Rifqi <rifqi.persie@gmail.com>
     * 
     * @return \Illuminate\Database\Query\Builder
     */
    public function currencyrate()
    {
        return $this->hasMany('App\Models\Employee', 'rate_currency_id', 'id');
    }

    /**
     * Define relation one to many with employees table
     *
     * @author Muhammad Rifqi <rifqi.persie@gmail.com>
     * 
     * @return \Illuminate\Database\Query\Builder
     */
    public function currencysalary()
    {
        return $this->hasMany('App\Models\Employee', 'salary_currency_id', 'id');
    }

    /**
     * Scope a query to only include country_id
     *
     * @param int $country_id
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCountryId($query, $country_id)
    {
        return $query->where('countries_id', $country_id);
    }

    /**
     * Scope a query to only include currency
     *
     * @param string $currency
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCurrencyData($query, $currency)
    {
        $currency   = strtoupper($currency);
        return $query->whereRaw("UPPER(currency) like '%$currency%'");
    }
}