<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str; 

class RegisterRequest extends FormRequest
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
            'username'  => 'required|unique:users,username|max:255',
            'email'     => 'required|unique:users,email|max:255',
            'password'  => 'required|min:6',
            'role_id'   => 'required'
        ];
    }    

    public function attributes()
    {
        return [
            'username' => 'Username',
            'email'    => 'Email Address',
            'password' => 'Password',
            'role_id'  => 'Role'
        ];
    }

    public function messages()
    {
        return [
            'required' => ':attribute is required.',
            'unique'   => ':attribute :input already in use.',
            'min'      => ':attribute charachter at least :min.',
            'max'      => ':attribute exceeds :max character.'
        ];
    }
    
}
