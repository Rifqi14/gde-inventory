<?php

namespace App\Http\Requests\DocumentExternal;

use Illuminate\Foundation\Http\FormRequest;

class DocumentCategoryRequest extends FormRequest
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
            "menu_id"           => 'required',
            'discipline_code_id'=> 'required',
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
            'menu_id.required'              => "Menu field is required",
            'discipline_code_id.required'   => "Discipline code field is required",
        ];
    }
}
