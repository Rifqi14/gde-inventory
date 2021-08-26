<?php

use App\Models\Country;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class CountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (env('DB_CONNECTION', 'pgsql') == 'mysql') {
            DB::statement('SET foreign_key_checks=0');
            Country::truncate();
            DB::statement('SET foreign_key_checks=1');
        } else {
            Country::truncate();
        }
        $countries = json_decode(File::get(database_path('datas/countries.json')));
        foreach ($countries as $key => $country) {
            Country::create([
                'code'      => $country->code,
                'country'   => $country->country
            ]);
        }
        
    }
}