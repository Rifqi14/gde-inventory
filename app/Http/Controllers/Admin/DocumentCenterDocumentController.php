<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DocumentCenterDocument;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
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
                'status'                => 'DRAFT',
                'remark'                => $request->revision_remark,
                'issue_purpose'         => $request->issue_purpose,
            ];
            $document   = DocumentCenterDocument::create($data);
            if ($document && $request->file('document_upload')) {
                $filename       = "$document->transmittal_no.".$request->file('document_upload')->getClientOriginalExtension();
                $categorymenu   = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->category_menu)));
                $documentnumber = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->document_number)));
                
                $src    = "assets/documentcenter/$request->menu/$categorymenu/$documentnumber";
                if (!file_exists($src)) {
                    mkdir($src, 0777, true);
                }
                $move = $request->file('document_upload')->move($src, $filename);

                if (!$move) {
                    DB::rollBack();
                    return response()->json([
                        'status'    => false,
                        'message'   => "Error upload document",
                    ], 400);
                }
                $document->document_path    = "$src/$filename";
                $document->save();
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
        $center     = DocumentCenterDocument::with(['createdBy', 'updatedBy'])->find($id);
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
            $document->document_center_id   = $request->document_id;
            $document->issued_by            = Auth::guard('admin')->user()->id;
            $document->revision             = $request->revision_number;
            $document->status               = 'DRAFT';
            $document->remark               = $request->revision_remark;
            $document->issue_purpose        = $request->issue_purpose;
            $saveDoc                        = $document->save();
            if ($saveDoc && $request->file('document_upload')) {
                $filename       = "$document->transmittal_no.".$request->file('document_upload')->getClientOriginalExtension();
                $categorymenu   = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->category_menu)));
                $documentnumber = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $request->document_number)));

                if (file_exists($document->document_path)) {
                    File::delete($document->document_path);
                }

                $src    = "assets/documentcenter/$request->menu/$categorymenu/$documentnumber";
                if (!file_exists($src)) {
                    mkdir($src, 0777, true);
                }
                $move = $request->file('document_upload')->move($src, $filename);

                if (!$move) {
                    DB::rollBack();
                    return response()->json([
                        'status'    => false,
                        'message'   => "Error upload document",
                    ], 400);
                }
                $document->document_path    = "$src/$filename";
                $document->save();
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
        //
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
        $query          = DocumentCenterDocument::with(['createdBy', 'updatedBy'])->where('document_center_id', $document_id);

        $row            = clone $query;
        $recordsTotal   = $row->count();

        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $documents      = $query->get();

        $data           = [];
        foreach ($documents as $key => $document) {
            $document->no   = ++$start;
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
}
