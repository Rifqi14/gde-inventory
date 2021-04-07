<?php

namespace App\Http\Middleware\Custom;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Route;

use App\Models\Role;
use App\Models\RoleUser;
use App\Models\Menu;
use App\Models\MenuRole;
use Session;
class PageAdmin
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
        if (Auth::guard('admin')->check()) {
            $route = explode('.',Route::currentRouteName());
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
            
            $menu = Menu::where('menu_route',$route[0])->first();
            $actionmenu = [];
            if($menu){
                $actions = MenuRole::where('menu_id',$menu->id)->where('role_id',$role->id)->get();
                foreach($actions as $action){
                    if($action->create){
                        if(!in_array('create',$actionmenu)){
                            array_push($actionmenu,'create');
                        }
                    }
                    if($action->read){
                        if(!in_array('read',$actionmenu)){
                            array_push($actionmenu,'read');
                        }
                    }
                    if($action->update){
                        if(!in_array('update',$actionmenu)){
                            array_push($actionmenu,'update');
                        }
                    }
                    if($action->delete){
                        if(!in_array('delete',$actionmenu)){
                            array_push($actionmenu,'delete');
                        }
                    }
                    if($action->import){
                        if(!in_array('import',$actionmenu)){
                            array_push($actionmenu,'import');
                        }
                    }
                    if($action->export){
                        if(!in_array('export',$actionmenu)){
                            array_push($actionmenu,'export');
                        }
                    }
                    if($action->print){
                        if(!in_array('print',$actionmenu)){
                            array_push($actionmenu,'print');
                        }
                    }
                    if($action->approval){
                        if(!in_array('approval',$actionmenu)){
                            array_push($actionmenu,'approval');
                        }
                    }
                }
            }
            View::share('actionmenu', $actionmenu);
            request()->merge(['actionmenu' => $actionmenu]);
            View::share('menuaccess', $rolemenus);
            View::share('accesssite', $role->data_manager);
            // View::share('siteinfo',  Auth::guard('admin')->user()->workforce->site);
            View::share('siteinfo',  'siteinfo');
        }
        return $next($request);
    }
}