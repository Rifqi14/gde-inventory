<?php

namespace App\Http\Requests\DocumentExternal;

use Illuminate\Foundation\Http\FormRequest;

class DocumentExternalRequest extends FormRequest
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
            'document_number'       => 'required',
            'document_title'        => 'required',
            'site_code_id'          => 'required',
            'discipline_code_id'    => 'required',
            'kks_category_id'       => 'required',
            'kks_code_id'           => 'required',
            'document_type_id'      => 'required',
            'originator_code_id'    => 'required',
            'phase_code_id'         => 'required',
            'document_sequence'     => 'required',
            'document_category_id'  => 'required',
            'contractor_name_id'    => 'required',
            'contractor_group_id'   => 'required',
            'planned_ifi_ifa_date'  => 'required',
            'planned_ifc_ifu_date'  => 'required',
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
            'document_number.required'      => "Document Number is required",     
            'document_title.required'       => "Document Title is required",     
            'site_code_id.required'         => "Site Code is required", 
            'discipline_code_id.required'   => "Discipline Code is required",         
            'kks_category_id.required'      => "KKS Category is required",     
            'kks_code_id.required'          => "KKS Code is required", 
            'document_type_id.required'     => "Document Type is required",     
            'originator_code_id.required'   => "Originator Code is required",         
            'phase_code_id.required'        => "Phase Code is required",     
            'document_sequence.required'    => "Document Sequence is required",         
            'document_category_id.required' => "Document Category is required",         
            'contractor_name_id.required'   => "Contractor Name is required",         
            'contractor_group_id.required'  => "Contractor Group is required",         
            'planned_ifi_ifa_date.required' => "Planned IFI/IFA Date is required",         
            'planned_ifc_ifu_date.required' => "Planned IFC/IFU Date is required",
        ];
    }
}
