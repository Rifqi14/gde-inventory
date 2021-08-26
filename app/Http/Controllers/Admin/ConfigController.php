<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Config;
use App\Models\Menu;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class ConfigController extends Controller
{
    function __construct() {
        $menu       = Menu::getByRoute('config')->first();
        $parent     = Menu::find($menu->parent_id);
        View::share('parent_name', $parent->menu_name);
        View::share('menu_name', $menu->menu_name);
        View::share('menu_active', url('admin/config'));
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.config.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = [
            'app_name',
            'app_copyright',
            'app_logo',
            'app_icon',
            'login_background',
            'company_name',
            'company_email',
            'company_phone',
            'company_address',
        ];

        $validator  = Validator::make($request->all(), [
            'app_name'          => 'required',
            'app_copyright'     => 'required',
            'app_logo.*'        => 'mimes:png',
            'app_icon.*'        => 'mimes:png',
            'login_background.*'=> 'mimes:jpeg, jpg, png',
            'company_name'      => 'required',
            'company_email'     => 'required',
            'company_phone'     => 'required',
            'company_address'   => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'        => false,
                'message'       => $validator->errors()->first()
            ], 400);
        }
        foreach ($fields as $field) {
            switch ($field) {
                case 'app_logo':
                    $app_logo   = $request->file('app_logo');
                    if ($app_logo) {
                        if (file_exists('assets/config/logo.png')) {
                            unlink('assets/config/logo.png');
                        }
                        $app_logo->move('assets/config/', 'logo.png');
                    }
                    $config = Config::where('option', $field)->first();
                    if ($config) {
                        $config = Config::find($config->id);
                        $config->value  = 'assets/config/logo.png';
                        $config->save();
                    } else {
                        $config = Config::create([
                            'option'    => $field,
                            'value'     => 'assets/config/logo.png'
                        ]);
                    }
                    break;
                case 'app_icon':
                    $app_icon = $request->file('app_icon');
                    if ($app_icon) {
                        if (file_exists('assets/config/icon.png')) {
                            unlink('assets/config/icon.png');
                        }
                        $app_icon->move('assets/config/', 'icon.png');
                    }
                    $config = Config::where('option', $field)->first();
                    if ($config) {
                        $config = Config::find($config->id);
                        $config->value = 'assets/config/icon.png';
                        $config->save();
                    } else {
                        $config = Config::create([
                            'option' => $field,
                            'value'  => 'assets/config/icon.png'
                        ]);
                    }
                    break;
                case 'login_background':
                    $login_background = $request->file('login_background');
                    if ($login_background) {
                        if (file_exists('assets/config/login.jpg')) {
                            unlink('assets/config/login.jpg');
                        }
                        $login_background->move('assets/config/', 'login.jpg');
                    }
                    $config = Config::where('option', $field)->first();
                    if ($config) {
                        $config = Config::find($config->id);
                        $config->value = 'assets/config/login.jpg';
                        $config->save();
                    } else {
                        $config = Config::create([
                            'option' => $field,
                            'value'  => 'assets/config/login.jpg'
                        ]);
                    }
                    break;
                
                default:
                    $config = Config::where('option', $field)->first();
                    if ($config) {
                        $config = Config::find($config->id);
                        $config->value = $request->{$field};
                        $config->save();
                    } else {
                        $config = Config::create([
                            'option' => $field,
                            'value'  => $request->{$field}
                        ]);
                    }
                    break;
            }
        }
        return response()->json([
            'status'    => true,
            'results'   => route('config.index'),
        ], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}