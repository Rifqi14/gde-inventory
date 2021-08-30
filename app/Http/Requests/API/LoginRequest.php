<?php

namespace App\Http\Controllers\Auth\API;

use App\Libraries\Facades\RateLimiter;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;


class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'username' => 'required',
            'password' => 'required|min:6'
        ];
    }

    public function attributes()
    {
        return [
            'username'  => 'Username',
            'password'  => 'Password'
        ];
    }
    public function messages()
    {
        return [
            'required' => ':attribute is required.',
            'min'      => ':attribute at least :min character.'
        ];
    }

    public function authentication()
    {        
        $this->ensureIsNotRateLimited();

        $authToken = Auth::guard('api')->attempt($this->only('username','password'));

        if(!$authToken){
            RateLimiter::hit($this->throttleKey(), 300);

            throw ValidationException::withMessages([
                'data' => __('auth.failed')
            ])->status(Response::HTTP_TOO_MANY_REQUESTS);
        }

        RateLimiter::clear($this->throttleKey());

        return $this->createToken($authToken);
    }

    protected function throttleKey()
    {
        return Str::lower("$this->input('username')|$this->ip()");
    }

    protected function ensureIsNotRateLimited()
    {
        if(!RateLimiter::tooManyAttempts($this->throttleKey(), 5)){
            return;
        }

        $seconds = RateLimiter::availableIn($this->throttleKey());        

        throw ValidationException::withMessages([
            'data' => [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60)
            ]
        ])->status(Response::HTTP_TOO_MANY_REQUESTS);        
    }    

    protected function createToken($token)
    {           
        return response()->json([
            'status'        => Response::HTTP_OK,
            'data'          => Auth::guard('api')->user(),
            'access_token'  => $token,
            'token_type'    => 'bearer',
            'expired_in'    => auth('api')->factory()->getTTL() *  60
        ], Response::HTTP_OK);
    }
}
