<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DocumentCenterDocument;
use App\Models\DocumentCenterLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DocumentCenterLogController extends Controller
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
            'attachment'    => 'required_if:reason,null',
            'reason'        => 'required_if:attachment,null',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        try {
            $revise_number      = DocumentCenterLog::where('document_center_document_id', $request->document_center_document_id)->where('status', 'REVISED')->latest()->first();
            $data       = [
                'document_center_document_id'   => $request->document_center_document_id,
                'status'                        => $request->status == 'Issued' ? strtoupper('Approved') : strtoupper($request->status),
                'revise_number'                 => $revise_number ? $revise_number->revise_number + 1 : 1,
                'reason'                        => $request->reason,
            ];
            $create         = DocumentCenterLog::create($data);
            if ($create) {
                if ($request->file('attachment')) {
                    $name       = ucwords(str_replace(' ', '-', $request->attachment_name));
                    $filename   = "$name-$create->revise_number.{$request->file('attachment')->getClientOriginalExtension()}";
                    $categorymenu   = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->category_menu)));
    
                    $moveFile   = $this->reasonAttachment($request->file('attachment'), $filename, "documentcenter/$request->menu/$categorymenu/revise", $create->document_center_document_id);
                    if ($moveFile) {
                        $create->attachment_name    = $request->attachment_name;
                        $create->attachment         = "assets/documentcenter/$request->menu/$categorymenu/revise/$create->document_center_document_id/$filename";
                        $create->save();
                    } else {
                        DB::rollBack();
                        return response()->json([
                            'status'    => false,
                            'message'   => "Error upload attachment",
                        ], 400);
                    }
                }
                $document = DocumentCenterDocument::find($create->document_center_document_id);
                $document->status   = $create->status;
                $document->transmittal_status = $document->status == 'DRAFT' || $document->status == 'WAITING' ? 'Waiting for Issue' : 'Issued';
                $document->save();

                $email = new DocumentCenterDocumentController();
                $email->sendEmail($create->document_center_document_id);
            }
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => "Error create data"
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status'    => true,
            'message'   => "Success create data"
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

    public function read(\Illuminate\Http\Request $request)
    {
        $start          = $request->start;
        $length         = $request->length;
        $query          = $request->search['value'];
        $sort           = $request->columns[$request->order[0]['column']]['data'];
        $dir            = $request->order[0]['dir'];
        $document_id    = $request->document_id;

        // Query Data
        $query          = DocumentCenterLog::where('document_center_document_id', $document_id);

        $row            = clone $query;
        $recordsTotal   = $row->count();

        $query->paginate($length);
        $query->orderBy($sort, $dir);
        $documents      = $query->get();

        $data           = [];
        foreach ($documents as $key => $document) {
            $document->no       = ++$start;
            $data[]             = $document;
        }
        
        return response()->json([
            'draw'              => $request->draw,
            'recordsTotal'      => $recordsTotal,
            'recordsFiltered'   => $recordsTotal,
            'data'              => $data,
        ], 200);
    }
}
