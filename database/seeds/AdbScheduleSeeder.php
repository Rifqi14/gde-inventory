<?php

use Illuminate\Database\Seeder;

class AdbScheduleSeeder extends Seeder
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
            App\Models\AdbSchedule::truncate();
            DB::statement("SET foreign_key_checks=1");
        }
        else{
            App\Models\AdbSchedule::truncate();
        }
        $adb_schedules = json_decode(File::get(database_path('datas/adb_schedules.json')));
        foreach ($adb_schedules as $adb) {
            $newadb = App\Models\AdbSchedule::create([
                'type' => $adb->type,
                'schedule_name' => $adb->schedule_name,
                'period' => $adb->period
            ]);
        }
    }
}
