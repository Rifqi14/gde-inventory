<?php

namespace App\Http\Requests\DocumentExternal;

use Illuminate\Foundation\Http\FormRequest;

class RevisionRequest extends FormRequest
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
            'revision_number'   => 'required',
            'revision_remark'   => 'required',
            'issue_status'      => 'required',
            'document_name.*'   => 'required_if:status,DRAFT',
            'document_upload.*' => 'required_if:status,DRAFT',
            'sheet_size'        => 'required',
            'nos_of_pages'      => 'required',
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
            'revision_number.required'      => "Revision No is required",
            'nos_of_pages.required'         => "Nos Of Pages is required",
            'sheet_size.required'           => "Sheet Size is required",
            'revision_remark.required'      => "Revision Remarks is required",
            'issue_status.required'         => "Issue Status is required",
            'document_name.*.required'      => 'Document name field is required',
            'document_upload.*.required'    => 'Upload document field is required',
        ];
    }
}
