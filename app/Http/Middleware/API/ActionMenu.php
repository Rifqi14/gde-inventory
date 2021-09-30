<?php

namespace App\Http\Middleware\API;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Models\MenuRole;

class ActionMenu
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {              
        if(Auth::guard('api')->user()){            
            $user_id = Auth::guard('api')->user()->id;
            $route   = explode('.',explode('/',Route::current()->action['prefix'])[1]);        

            $menu = MenuRole::selectRaw("                
                (case when menu_roles.create = '1' then true else false end) as create,
                (case when menu_roles.read = '1' then true else false end) as read,
                (case when menu_roles.update = '1' then true else false end) as update,
                (case when menu_roles.delete = '1' then true else false end) as delete,
                (case when menu_roles.import = '1' then true else false end) as import,
                (case when menu_roles.export = '1' then true else false end) as export,
                (case when menu_roles.print = '1' then true else false end) as print,
                (case when menu_roles.approval = '1' then true else false end) as approval
            ");
            $menu->join('menus', function($join) use ($route){
                $join->on('menus.id','=','menu_roles.menu_id')->where('menus.menu_route',$route);
            });
            $menu->join('role_users', function($join) use ($user_id){
                $join->on('role_users.role_id','=','menu_roles.role_id')->where('role_users.user_id', $user_id);
            });            
            $menu = $menu->first();            

            request()->merge([
                'actionmenu' => $menu?$menu:[]
            ]);
        }    
        
        return $next($request);
    }
}
