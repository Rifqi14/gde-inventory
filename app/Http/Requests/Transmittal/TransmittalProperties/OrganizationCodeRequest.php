<?php

namespace App\Http\Requests\Transmittal\TransmittalProperties;

use Illuminate\Foundation\Http\FormRequest;

class OrganizationCodeRequest extends FormRequest
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
            'code'              => 'required',
            'name'              => 'required',
            'tagged_group_id'   => 'required',
        ];
    }

    /**
     * Get the validation message that apply to the request
     *
     * @return array
     */
    public function messages()
    {
        return [
            'code.required'         => "Code field is required",
            'name.required'         => "Name field is required",
            'tagged_group_id'       => "Associated Group is required at least 1 data choosen",
        ];
    }
}
