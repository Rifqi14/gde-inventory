<?php

use App\Models\Site;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SitesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(env('DB_CONNECTION', 'pgsql') == 'mysql'){
            DB::statement("SET foreign_key_checks=0");
            Site::truncate();
            DB::statement("SET foreign_key_checks=1");
        }
        else{
            Site::truncate();
        }
        $sites = json_decode(File::get(database_path('datas/sites.json')));
        foreach ($sites as $site) {
            Site::create([
                'code'=> $site->code,
                'name'=> $site->name
            ]);
        }
    }
}
