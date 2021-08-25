<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\RegisterRequest;
use App\Exceptions\RegisterException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

use App\User;
use App\Models\RoleUser;

class RegisterController extends Controller
{
    

    public function signup(RegisterRequest $request)
    {                
        $username = $request->username;
        $email    = $request->email;
        $password = $request->password;
        $role_id     = $request->role_id;
        
        $user = User::create([
            'username' => $username,            
            'name'     => Str::studly($username),            
            'email'    => $email,
            'password' => Hash::make($password)
        ]);                

        if(!$user){
            throw new RegisterException('Register failed due to error. Failed to create account.');
        }

        $roleUser = RoleUser::create([
            'role_id'   => $role_id,
            'user_id'   => $user->id
        ]);

        if(!$roleUser){
            throw new RegisterException('Register failed due to error. Failed to set role user.');            
        }
        
        return response()->json([
            'status'    => Response::HTTP_OK,
            'message'   => 'Register has been success.'
        ],Response::HTTP_OK);
    }
}
