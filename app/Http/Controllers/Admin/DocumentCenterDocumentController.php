<?php

namespace App\Http\Controllers\Admin;

use App\Models\DocumentCenterSupersede;
use App\Models\DocumentCenterVoid;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Mail\DocumentCenterMail;
use App\Models\DocumentCenterDocument;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class DocumentCenterDocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
        $validator      = Validator::make($request->all(), [
            'document_number'   => 'required',
            'document_name.*'   => 'required',
            'document_upload.*' => 'required',
            'issue_purpose'     => 'required',
        ], [
            'document_name.*.required'    => 'Document name field is required',
            'document_upload.*.required'  => 'Upload document field is required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        try {
            $data   = [
                'document_center_id'    => $request->document_id,
                'issued_by'             => Auth::guard('admin')->user()->id,
                'revision'              => $request->revision_number,
                'status'                => $request->status,
                'remark'                => $request->revision_remark,
                'issue_purpose'         => $request->issue_purpose,
                'approver_id'           => $request->approver_id
            ];
            $document   = DocumentCenterDocument::create($data);
            if ($document && $request->document_upload) {
                $dataDocument   = [];
                $no             = 1;
                foreach ($request->document_upload as $key => $detail) {
                    $transNo        = "{$request->document_name[$key]}";
                    $filename       = "$transNo.".$request->file('document_upload')[$key]->getClientOriginalExtension();
                    $categorymenu   = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->category_menu)));
                    $documentnumber = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->document_number)));
                    
                    $src    = "assets/documentcenter/$request->menu/$categorymenu/$documentnumber";
                    if (!file_exists($src)) {
                        mkdir($src, 0777, true);
                    }
                    $move = $detail->move($src, $filename);
    
                    if (!$move) {
                        DB::rollBack();
                        return response()->json([
                            'status'    => false,
                            'message'   => "Error upload document",
                        ], 400);
                    }
                    $dataDocument[] = [
                        'document_center_document_id'   => $document->id,
                        'document_path'                 => "$src/$filename",
                        'document_name'                 => $filename,
                        'file_size'                     => File::size("$src/$filename"),
                    ];
                    $no++;
                }

                $document->distributors()->sync($request->distribution_id);
                $createDetail   = $document->docdetail()->createMany($dataDocument);
                $this->sendEmail($document->id);
                if (!$createDetail) {
                    DB::rollBack();
                    return response()->json([
                        'status'    => false,
                        'message'   => "Error create revise document detail",
                    ], 400);
                }
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => "Error create revise document: {$ex->errorInfo[2]}",
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status'    => true,
            'message'   => "Success create revise document.",
        ], 200);
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
        $center     = DocumentCenterDocument::with(['createdBy', 'updatedBy', 'docdetail', 'supersede', 'void', 'supersede.docno', 'approver', 'distributors'])->find($id);
        $center->created_date   = date('d/m/Y', strtotime($center->created_at));
        $center->last_modified  = date('d/m/Y', strtotime($center->updated_at));
        if ($center) {
            return response()->json([
                'status'    => true,
                'message'   => 'Data found',
                'data'      => $center,
            ], 200);
        }
        return response()->json([
            'status'        => false,
            'message'       => "Data not found",
        ], 400);
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
        $validator      = Validator::make($request->all(), [
            'document_number'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        try {
            $document       = DocumentCenterDocument::find($id);
            $document->document_center_id   = $request->document_id ? $request->document_id : $document->document_center_id;
            $document->issued_by            = Auth::guard('admin')->user()->id;
            $document->revision             = $request->revision_number ? $request->revision_number : $document->revision;
            $document->status               = $request->status ? $request->status : $document->status;
            $document->remark               = $request->revision_remark ? $request->revision_remark : $document->remark;
            $document->issue_purpose        = $request->issue_purpose ? $request->issue_purpose : $document->issue_purpose;
            $document->transmittal_status   = $document->status == 'DRAFT' || $document->status == 'WAITING' ? 'Waiting for Issue' : 'Issued';
            $saveDoc                        = $document->save();

            if ($request->distribution_id) {
                $document->distributors()->sync($request->distribution_id);
            }
            if ($saveDoc && $request->document_upload) {
                $dataDocument   = [];
                $no             = 1;
                foreach ($request->document_upload as $key => $detail) {
                    $transNo        = "{$request->document_name[$key]}";
                    $filename       = "$transNo.".$request->file('document_upload')[$key]->getClientOriginalExtension();
                    $categorymenu   = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->category_menu)));
                    $documentnumber = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->document_number)));
                    
                    $src    = "assets/documentcenter/$request->menu/$categorymenu/$documentnumber";
                    if (!file_exists($src)) {
                        mkdir($src, 0777, true);
                    }
                    $move = $detail->move($src, $filename);
    
                    if (!$move) {
                        DB::rollBack();
                        return response()->json([
                            'status'    => false,
                            'message'   => "Error upload document",
                        ], 400);
                    }
                    $dataDocument[] = [
                        'document_center_document_id'   => $document->id,
                        'document_path'                 => "$src/$filename",
                        'document_name'                 => $filename,
                        'file_size'                     => File::size("$src/$filename"),
                    ];
                    $no++;
                }
                $createDetail   = $document->docdetail()->createMany($dataDocument);
                if (!$createDetail) {
                    DB::rollBack();
                    return response()->json([
                        'status'    => false,
                        'message'   => "Error upload document",
                    ], 400);
                }
            }

            if ($saveDoc && $request->document_type_supersede) {
                if ($request->document_type_supersede == 'VOID') {
                    try {
                        $data       = [
                            'document_center_document_id'   => $document->id,
                            'void_remark'                   => $request->void_remark,
                        ];
                        if ($document->void) {
                            $document->void()->delete();
                        }
                        DocumentCenterVoid::create($data);

                        if ($document->supersede) {
                            $document->supersede()->delete();
                        }
                    } catch (\Illuminate\Database\QueryException $ex) {
                        DB::rollBack();
                        return response()->json([
                            'status'    => false,
                            'message'   => "Error update document: {$ex->errorInfo[2]}",
                        ], 400);
                    }
                } else {
                    try {
                        $data       = [
                            'document_center_document_id'   => $document->id,
                            'document_center_id'            => $request->document_center_id_supersede,
                            'supersede_remark'              => $request->supersede_remark,
                        ];
                        if ($document->supersede) {
                            $document->supersede()->delete();
                        }
                        DocumentCenterSupersede::create($data);

                        if ($document->void) {
                            $document->void()->delete();
                        }
                    } catch (\Illuminate\Database\QueryException $ex) {
                        DB::rollBack();
                        return response()->json([
                            'status'    => false,
                            'message'   => "Error update document: {$ex->errorInfo[2]}",
                        ], 400);
                    }
                }
                $document->document_type    = $request->document_type_supersede;
                $document->save();
            }

            if ($request->undo == "UNDO") {
                $document->void()->delete();
                $document->supersede()->delete();
                $document->document_type    = null;
                $document->save();
            }
            if (!$request->undo || $request->undo == "null") {
                $this->sendEmail($document->id);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => "Error update revise document: {$ex->errorInfo[2]}",
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status'    => true,
            'message'   => "Success update revise document.",
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DocumentCenterDocument::destroy($id);
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json([
                'status'    => false,
                'message'   => "Error delete data: {$ex->errorInfo[2]}"
            ], 400);
        }
        return response()->json([
            'status'    => true,
            'message'   => "Success delete data!",
        ], 200);
    }
    
    /**
     * Define method to get data and show in datatable
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function read(Request $request)
    {
        $start          = $request->start;
        $length         = $request->length;
        $query          = $request->search['value'];
        $sort           = $request->columns[$request->order[0]['column']]['data'];
        $dir            = $request->order[0]['dir'];
        $document_id    = $request->document_id;

        // Query Data
        $query          = DocumentCenterDocument::with(['createdBy', 'updatedBy', 'docdetail', 'supersede.docno', 'void'])->where('document_center_id', $document_id);

        $row            = clone $query;
        $recordsTotal   = $row->count();

        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $documents      = $query->get();

        $data           = [];
        foreach ($documents as $key => $document) {
            $document->no   = ++$start;
            foreach ($document->docdetail() as $keyDetail => $detail) {
                $document->file_size    = $this->formatBytes($document->file_size, 2);
            }
            $document->last_modified   = date('d/m/Y', strtotime($document->updated_at));
            $data[]         = $document;
        }
        return response()->json([
            'draw'              => $request->draw,
            'recordsTotal'      => $recordsTotal,
            'recordsFiltered'   => $recordsTotal,
            'data'              => $data,
        ], 200);
    }

    function formatBytes($bytes, $precision = 2) { 
        $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
    
        $bytes = max($bytes, 0); 
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
        $pow = min($pow, count($units) - 1); 
    
        // Uncomment one of the following alternatives
        // $bytes /= pow(1024, $pow);
        // $bytes /= (1 << (10 * $pow)); 
    
        return round($bytes, $precision) . ' ' . $units[$pow]; 
    } 

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendEmail($id)
    {
        $data   = $this->emailData($id);
        Mail::to($data->to)->cc($data->cc)->send(new DocumentCenterMail($id, $data));
    }

    public function emailData($id)
    {
        $document   = DocumentCenterDocument::with(['approver', 'createdBy', 'updatedBy', 'documentCenter.doctype', 'docdetail', 'supersede.docno', 'void', 'distributors.users', 'log'])->find($id);
        $data       = [
            'to'                => [$document->approver->email, @$document->updatedBy->email],
            'cc'                => [],
            'subject'           => "[Document Issue Notice] $document->transmittal_no: {$document->documentCenter->title}",
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
            $data['remark'] = $document->document_type == 'SUPERSEDE' ? "<b>Remark:</b> {$document->supersede->supersede_remark}" : "<b>Remark:</b> {$document->void->void_remark}";
            $data['subject'] = $document->document_type == 'SUPERSEDE' ? "[Supersede Document Issue Notice] $document->transmittal_no: {$document->documentCenter->title}" : "[Void Document Issue Notice] $document->transmittal_no: {$document->documentCenter->title}";
        } else {
            if ($document->status == "REVISED") {
                $data['additional'] = "Please revise the document";
                $data['subject'] = "[Request to Revise] $document->transmittal_no: {$document->documentCenter->title}";
                $data['remark'] = "<b>Comment:</b> {$document->log()->orderBy('revise_number', 'desc')->first()->reason}";
            }
            if ($document->status == "WAITING" || $document->status == "DRAFT" || $document->status == "APPROVED") {
                $data['additional'] = $document->status == "APPROVED" ? '' : "Please approve the issue of the document";
                if ($document->status == "WAITING") {
                    $data['subject'] = "[Issue Request] $document->transmittal_no: {$document->documentCenter->title}";
                }
                if ($document->status == "DRAFT") {
                    $data['subject'] = "[Document Issue Notice] -> [Issue Draft] $document->transmittal_no: {$document->documentCenter->title}";
                }
                $data['remark'] = "<b>Remark:</b> $document->remark";
            }
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
}
