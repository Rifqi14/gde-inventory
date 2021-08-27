<?php

namespace App\Http\Controllers\API\Auth;

use App\Http\Controllers\Auth\API\APIController;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\LoginRequest;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use App\User;

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

    public function login(LoginRequest $request)
    {
        return $request->authentication();
    }

    public function logout()
    {
        Auth::guard('api')->logout();

        return response()->json([],Response::HTTP_NO_CONTENT);
    }

    public function profile()
    {
        return response()->json(auth('api')->user());
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