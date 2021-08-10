<?php

namespace App\Http\Requests\DocumentExternal\Properties;

use Illuminate\Foundation\Http\FormRequest;

class ContractorNameRequest extends FormRequest
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
            'name'  => 'required',
            'role_id'   => 'required',
        ];
    }

    /**
     * Get the validation message that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required'  => "Name field is required",
            'role_id.required'  => "KKS Category is required",
        ];
    }
}
