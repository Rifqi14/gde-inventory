<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class AdminLoginController extends Controller
{
    use AuthenticatesUsers;
    protected $redirectTo = '/dashboard';
    public function __construct()
    {
        $limit      = env('RATE_LIMIT', 5);
        $remaining  = env('RATE_REMAINING',10);
        $this->middleware('guest.admin')->except('logout');
        $this->middleware(["throttle:$limit,$remaining",'XssSanitization'])->only('login');
    }
    protected function credentials(Request $request)
    {
        return $request->only($this->username());
    }
    public function username()
    {
        return 'username';
    }
    public function guard()
    {
        return Auth::guard('admin');
    }

    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function logout(Request $request)
    {
        Auth::guard("admin")->logout();
        $request->session()->forget('role_id');
        return redirect('/admin');
    }

    protected function login(Request $request){
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        // $username = strtoupper($request->username);
        $username = $request->username;
        if (Auth::guard("admin")->attempt([$this->username() => $username,'password'=>$request->password])) {
            return response()->json([
                'success'  => true,
                'message'  => 'Login Failed!',
                'redirect' => $this->redirectTo
            ], 200);
        }
        return response()->json([
            'success'     => false,
            'message'      => 'Login Failed!'
        ], 200);
    }
}