<?php

namespace App\Http\Controllers\Admin\DocumentExternal;

use App\Exceptions\DocumentExternal\Properties\PropertiesException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentExternal\LogRequest;
use App\Http\Requests\DocumentExternal\RevisionRequest;
use App\Models\DocExternal\DocumentExternal;
use App\Models\DocExternal\DocumentExternalLog;
use App\Models\DocExternal\DocumentExternalRevision;
use App\Models\DocExternal\DocumentExternalRevisionFile;
use App\Traits\InteractWithApiResponse;
use Carbon\Carbon;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class RevisionController extends Controller
{
    use InteractWithApiResponse;
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
    public function store(RevisionRequest $request)
    {
        return DB::transaction(function() use($request) {
            try {
                $data   = [
                    'document_external_id'  => $request->document_external_id,
                    'sheet_size_id'         => $request->sheet_size,
                    'nos_of_pages'          => $request->nos_of_pages,
                    'revision_no'           => $request->revision_number,
                    'revision_remark'       => $request->revision_remark,
                    'contractor_revision_no'=> strtoupper($request->contractor_revision_no),
                    'issue_status'          => $request->issue_status,
                    'status'                => $request->status,
                    'created_by'            => auth()->user()->id,
                    'updated_by'            => auth()->user()->id,
                ];
                $revision = DocumentExternalRevision::create($data);

                $this->storeFile($request, $revision->id);
                $workflowData   = [
                    'document_external_id'  => $revision->document_external_id,
                    'revision_id'           => $revision->id,
                    'start_date'            => $revision->created_at,
                    'current_status'        => $revision->issue_status,
                ];
                $workflow = $revision->workflow()->create($workflowData);

                $matrices = $revision->document->matrix()->orderBy('created_at')->get();
                $data     = [];
                $keying   = 0;
                $no       = 1;
                $created_date_group = ['Information', 'Reviewer'];
                foreach ($matrices as $key => $matrix) {
                    if ($matrix->groupUsers) {
                        foreach ($matrix->groupUsers as $keyGroup => $group) {
                            $order  = "#$no";
                            $data[] = [
                                'workflow_id'   => $workflow->id,
                                'user_id'       => $group->id,
                                'label_group'   => in_array($matrix->matrix_label, $created_date_group) ? "$matrix->matrix_label $order" : $matrix->matrix_label,
                                'need_approval' => in_array($matrix->matrix_label, $created_date_group) ? false : true,
                                'sla'           => $matrix->matrix_sla == 'true' ? true : false,
                                'sla_dates'     => in_array($matrix->matrix_label, $created_date_group) ? date('Y-m-d', strtotime("$revision->created_at + $matrix->matrix_days days")) : date('Y-m-d', strtotime("{$data[$keying-1]['sla_dates']} + $matrix->matrix_days days")),
                            ];
                            $no++;
                            $keying++;
                        }
                        $no = 1;
                    }
                }
                $workflow->groups()->createMany($data);
            } catch (\Illuminate\Database\QueryException $th) {
                throw new PropertiesException("Error create data: {$th->errorInfo[2]}", Response::HTTP_BAD_REQUEST);
            }
            return $this->success(Response::HTTP_OK, "Success Create Data", null);
        });
    }

    public function storeFile(RevisionRequest $request, $revision_id)
    {
        return DB::transaction(function() use($request, $revision_id) {
            $document   = DocumentExternalRevision::find($revision_id);
            if (!$document) {
                throw new PropertiesException("Directory file not found", Response::HTTP_BAD_REQUEST);
            }
            $data       = [];
            foreach ($request->document_upload as $keyFile => $file) {
                $filename       = "{$request->document_name[$keyFile]}.{$request->file('document_upload')[$keyFile]->getClientOriginalExtension()}";
                $src            = "assets/documentexternal/{$document->document->menu}/{$document->document->document_number}/$document->id";

                if (!file_exists($src)) {
                    mkdir($src, 0777, true);
                }
                $move   = $file->move($src, $filename);
                if (!$move) {
                    throw new PropertiesException("Error upload document", Response::HTTP_BAD_REQUEST);
                }
                $data[]   = [
                    'revision_id'   => $revision_id,
                    'document_path' => "$src/$filename",
                    'document_name' => $filename,
                    'file_size'     => File::size("$src/$filename"),
                ];
            }
            $document->files()->createMany($data);

            return true;
        });
    }

    public function storeLog(LogRequest $request)
    {
        return DB::transaction(function() use($request){
            $revision    = DocumentExternalRevision::find($request->document_revision_id);
            $revision->status   = $request->status;
            $revision->save();
            $attachment = '';

            if ($request->file('attachment')) {
                $src        = "assets/documentexternal/{$revision->document->menu}/{$revision->document->document_number}/$revision->id/reason";
                $filename   = "{$request->attachment_name}.{$request->file('attachment')->getClientOriginalExtension()}";
                if (!file_exists($src)) {
                    mkdir($src, 0777, true);
                }
                $move   = $request->file('attachment')->move($src, $filename);
                if (!$move) {
                    throw new PropertiesException("Error upload document", Response::HTTP_BAD_REQUEST);
                }
                $attachment = "$src/$filename";
            }
            $data   = [
                'document_revision_id'  => $request->document_revision_id,
                'status'                => $request->status,
                'revise_number'         => $revision->logs()->where('status', 'REVISED')->latest()->first() ? ++$revision->logs()->where('status', 'REVISED')->latest()->first()->revise_number : 1,
                'reason'                => $request->reason,
                'attachment_name'       => $request->attachment_name,
                'attachment'            => $attachment,
            ];
            $revision->logs()->create($data);

            return $this->success(Response::HTTP_OK, "Success create data", $revision);
        });
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
        $revision   = DocumentExternalRevision::with(['files', 'sheetsize', 'supersede.document', 'void', ])->find($id);
        if (!$revision) {
            throw new PropertiesException("Data not found", Response::HTTP_BAD_REQUEST);
        }
        return $this->success(Response::HTTP_OK, "Data found", $revision);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RevisionRequest $request, $id)
    {
        return DB::transaction(function() use($request, $id) {
            $supersede_status = ['SUPERSEDE', 'VOID', 'UNDO'];
            $revision   = DocumentExternalRevision::find($id);
            if (!$revision) {
                throw new PropertiesException("Revision not found", Response::HTTP_BAD_REQUEST);
            }
            $revision->sheet_size_id            = $request->sheet_size;
            $revision->nos_of_pages             = $request->nos_of_pages;
            $revision->revision_remark          = $request->revision_remark;
            $revision->contractor_revision_no   = strtoupper($request->contractor_revision_no);
            $revision->status                   = $request->status;
            $revision->updated_by               = auth()->user()->id;
            $revision->save();

            if (in_array($revision->status, $supersede_status)) {
                switch ($revision->status) {
                    case 'VOID':
                        $data   = [
                            'revision_id'   => $revision->id,
                            'void_remark'   => $request->void_remark,
                        ];
                        $revision->void()->create($data);
                        break;
                    case 'SUPERSEDE':
                        $data   = [
                            'revision_id'           => $revision->id,
                            'document_external_id'  => $request->supersede_document_no,
                            'supersede_remark'      => $request->supersede_remark,
                        ];
                        $revision->supersede()->create($data);
                        break;

                    default:
                        $revision->supersede()->delete();
                        $revision->void()->delete();
                        $revision->status   = 'APPROVED';
                        $revision->save();
                        break;
                }
            }

            if ($request->document_upload) {
                $this->storeFile($request, $revision->id);
            }
            if (!$revision) {
                throw new PropertiesException("Error update data", Response::HTTP_BAD_REQUEST);
            }
            return $this->success(Response::HTTP_OK, "Success update data", null);
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $destroy    = DocumentExternalRevision::destroy($id);
        if (!$destroy) {
            throw new PropertiesException("Error delete data", Response::HTTP_BAD_REQUEST);
        }
        return $this->success(Response::HTTP_OK, "Succes delete data", null);
    }

    public function destroyFile($id)
    {
        $destroy    = DocumentExternalRevisionFile::destroy($id);
        if (!$destroy) {
            throw new PropertiesException("Error delete data", Response::HTTP_BAD_REQUEST);
        }
        return $this->success(Response::HTTP_OK, "Success Delete data", null);
    }

    public function getLatestRevisionNo(Request $request)
    {
        $issue_status_numeric   = ['IFI', 'IFA'];
        $revision       = DocumentExternalRevision::currentDocument($request->document_id)->latest()->first();

        $revision_no    = '';
        if (!$revision) {
            $revision_no    = in_array($request->issue_status, $issue_status_numeric) ? 'A' : 0;
            return $this->success(Response::HTTP_OK, null, $revision_no);
        }
        return $this->success(Response::HTTP_OK, null, ++$revision->revision_no);
    }

    public function getLatestRevision($document_id)
    {
        $revision       = DocumentExternalRevision::currentDocument($document_id)->latest()->first();
        if (!$revision) {
            return $this->success(Response::HTTP_OK, null, null);
        }
        return $this->success(Response::HTTP_OK, null, $revision);
    }

    /**
     * Read function to datatable
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function read(Request $request)
    {
        $start          = $request->start;
        $length         = $request->length;
        $document_id    = $request->document_external_id;

        // Query Data
        $queryData      = DocumentExternalRevision::with(['document', 'sheetsize', 'files', 'createdby', 'updatedby', 'workflow'])->currentDocument($document_id);

        $row            = clone $queryData;
        $recordsTotal   = $row->count();

        $queryData->offset($start);
        $queryData->limit($length);
        $queryData->orderBy('revision_no', 'asc');
        $externals      = $queryData->get();

        $data           = [];
        foreach ($externals as $key => $external) {
            $external->no   = ++$start;
            $external->modified_date   = Carbon::parse($external->updated_at)->isoFormat('D-MM-YYYY');
            $data[]         = $external;
        }
        return response()->json([
            'draw'              => $request->draw,
            'recordsTotal'      => $recordsTotal,
            'recordsFiltered'   => $recordsTotal,
            'data'              => $data,
        ], 200);
    }
}
