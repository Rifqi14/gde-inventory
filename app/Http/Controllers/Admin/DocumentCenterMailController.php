<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\DocumentCenterMail;
use App\Models\DocumentCenterDocument;
use Illuminate\Support\Facades\Mail;

class DocumentCenterMailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data   = $this->emailData(54);
        dd($data);
        Mail::to($data->to)->send(new DocumentCenterMail(21, $data));
        return "Email success to be send";
    }

    public function emailData($id)
    {
        $document   = DocumentCenterDocument::with(['approver', 'createdBy', 'updatedBy', 'documentCenter.doctype', 'docdetail', 'supersede.docno', 'void', 'distributors.users'])->find($id);
        $data       = [
            'to'                => [$document->approver->email, @$document->updatedBy->email],
            'cc'                => [],
            'subject'           => "$document->transmittal_no $document->transmittal_status",
            'updated_at'        => date('D, d M Y H:i:s', strtotime($document->updated_at)),
            'updated_by'        => $document->updatedBy->name,
            'remark'            => "",
            'additional'        => "",
            'issue_purpose'     => $document->issue_purpose,
            'document_no'       => $document->documentCenter->document_number,
            'title'             => $document->documentCenter->title,
            'type'              => $document->documentCenter->doctype->name,
            'rev_no'            => $document->revision,
            'issued_by'         => $document->createdBy->name,
            'status'            => ucwords($document->status),
            'link'              => route('documentcenter.edit', ['id' => $document->documentCenter->id, 'page'              => $document->documentCenter->menu]),
        ];
        if ($document->document_type) {
            $data['remark'] = $document->document_type == 'SUPERSEDE' ? "<b>Supersede Remark:</b> {$document->supersede->supersede_remark}" : "<b>Void Remark:</b> {$document->void->void_remark}";
        } else {
            $data['remark'] = "<b>Remark:</b> $document->remark";
        }
        if ($document->status == "REVISED") {
            $data['additional'] = "Please revise the document";
        }
        if ($document->status == "WAITING" || $document->status == "DRAFT") {
            $data['additional'] = "Please approve the issue of the document";
        }
        foreach ($document->distributors as $key => $distributor) {
            foreach ($distributor->users as $keyUser => $user) {
                if (!in_array($user->email, $data['to']) && ($document->status == 'APPROVED' || $document->status == 'REJECTED' || $document->document_type == 'SUPERSEDE' || $document->document_type == 'VOID')) {
                    $data['cc'][]   = $user->email;
                }
            }
        }

        return (object) $data;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
