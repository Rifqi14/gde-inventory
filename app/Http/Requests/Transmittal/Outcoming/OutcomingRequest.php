<?php

namespace App\Http\Requests\Transmittal\Outcoming;

use Illuminate\Foundation\Http\FormRequest;

class OutcomingRequest extends FormRequest
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
            'gde_contract_no'       => 'required',
            'gde_contract_title'    => 'required',
            'transmittal_title'     => 'required',
            'contractor_group_id'   => 'required',
            'sender_signed_copy'    => 'required_if:status,ISSUED',
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
            'gde_contract_no.required'       => 'GDE Contract No is required.',
            'gde_contract_title.required'    => 'GDE Contract Title is required',
            'transmittal_title.required'     => 'Transmittal Title is required',
            'contractor_group_id.required'   => 'Contractor Group is required',
            'sender_signed_copy.required'    => 'Sender Signed Copy is required if you choose to issued this transmittal.',
        ];
    }
}
