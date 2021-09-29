<?php

namespace App\Http\Requests\API;

use Illuminate\Foundation\Http\FormRequest;

class ProductBorrowingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'products' => 'required',
            'status'   => 'required|min:1'
        ];
    }

    public function attribute()
    {
        return [
            'products' => 'Products',
            'status'   => 'Status'
        ];
    }

    public function message()
    {
        return [
            'required' => ':attribute is required.',
            'min'      => ':attribute at least :min.'
        ];
    }
}
