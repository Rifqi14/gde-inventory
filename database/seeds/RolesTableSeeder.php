<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
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
            App\Models\Role::truncate();
            App\User::truncate();
            App\Models\MenuRole::truncate();
            DB::statement("SET foreign_key_checks=1");
        }
        else{
            App\Models\Role::truncate();
            App\User::truncate();
            App\Models\MenuRole::truncate();
        }
        $roles = json_decode(File::get(database_path('datas/roles.json')));
        foreach ($roles as $role) {
            $newrole = App\Models\Role::create([
                'code'=> $role->name,
                'name'=> $role->display_name,
                'data_manager'=> 1,
                'guest'=> 1,
            ]);
            $newuser = App\User::create([
                'name' 	=> $role->display_name,
                'email' 	=> $role->name.'@geodipa.com',
                'username' 	=> $role->name,
                'password'	=> Hash::make(123456),
                'is_active' 	=> 1,
            ]);
            $newuser = App\Models\RoleUser::create([
                'role_id' 	=> $newrole->id,
                'user_id' 	=> $newuser->id,
            ]);

            $menus = App\Models\Menu::all();
            foreach($menus as $menu){
                App\Models\MenuRole::create([
                    'role_id' => $newrole->id,
                    'menu_id' => $menu->id,
                    'role_access' => 1
                ]);
            }
        }
    }
}
