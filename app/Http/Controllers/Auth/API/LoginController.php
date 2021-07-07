<?php

namespace App\Http\Controllers\Auth\API;

use App\Http\Controllers\Auth\API\APIController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class LoginController extends APIController
{
    /**
     * Create a new AuthController instance
     * 
     * @return void
     */
    public function __construct() {
        // $this->middleware('jwt.auth', ['except' => ['login', 'register']]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required|string|min:6',
        ]);
        
        if ($validator->fails()) {
            return $this->responseUnprocessable($validator->errors()->first());
        }
        $username   = $request->username;
        $user       = User::where('username', $username)->first();
        if (!$user) {
            return $this->responseUnprocessable('These credentials do not match our records.');
        }
        
        $credentials = $request->only('email', 'password');

        if ($token = auth('api')->attempt($credentials)) {
            return $this->respondWithToken($token);
        }
        return $this->responseUnauthorized();
    }

    public function profile()
    {
        return response()->json(auth('api')->user());
    }

    public function logout()
    {
        auth('api')->logout();

        return $this->responseResourceUpdated('Successfully logged out');
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }
    
    protected function respondWithToken($token) {
        return response()->json([
            'access_token'  => "Bearer $token",
            'token_type'    => 'bearer',
            'expires_in'    => auth('api')->factory()->getTTL() * 60
        ]);
    }
}