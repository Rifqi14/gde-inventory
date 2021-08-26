<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Menu;
use App\Models\MenuRole;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Session;
class AccessMenu
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
        if(Auth::guard('admin')->check()){
            $route = explode('.',Route::currentRouteName());
            $route[0] = $request->page ? "{$route[0]}/$request->page" : $route[0];
            $accessmenu = [];
            $user_id = Auth::guard('admin')->user()->id;
            $role_user = RoleUser::where("user_id", $user_id)->first();
            $role = Role::find($role_user->role_id);
            $rolemenus = MenuRole::select('menus.id','menus.parent_id','menus.menu_name','menus.menu_route','menus.menu_icon','menus.menu_sort')
            ->leftJoin('menus', 'menus.id', '=', 'menu_roles.menu_id')
            ->where('role_id',$role->id)
            ->where('role_access', '=', 1)
            ->orderBy('menus.menu_sort', 'asc')
            ->groupBy('menus.id','menus.parent_id','menus.menu_name','menus.menu_route','menus.menu_icon','menus.menu_sort')
            ->get();
            foreach($rolemenus as $rolemenu){
                $accessmenu[] = $rolemenu->menu_route;
            }

            if(!in_array($route[0],$accessmenu)){
                abort(403);
            }
            return $next($request);
        }
    }
}