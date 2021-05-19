<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\Contract;
use App\Models\Menu;

use App\Models\ContractReceipt;
use App\Models\ContractDocumentReceipt;
use App\Models\ContractDocumentReceiptDetail;
use App\Models\BatchContract;
use ZipArchive;

class ContractReceiptController extends Controller
{
    /**
     * Define default method when access this controller
     */
    public function __construct() {
        $menu   = Menu::where('menu_route', 'contractreceipt')->first();
        $parent = Menu::find($menu->parent_id);
        View::share('parent_name', $parent->menu_name);
        View::share('menu_name', $menu->menu_name);
        View::share('menu_active', url('admin/contractreceipt'));
        $this->middleware('accessmenu', ['except' => ['select','read','selectcontract','selectbatch']]);
    }

    public function selectcontract(Request $request)
    {
        $start          = $request->page ? $request->page - 1 : 0;
        $length         = $request->limit;
        $name           = strtoupper($request->name);

        // Count Data
        $query          = Contract::whereRaw("upper(title) like '%$name%'");

        $row            = clone $query;
        $recordsTotal   = $row->count();

        $query->offset($start);
        $query->limit($length);
        $contracts      = $query->get();

        $data           = [];
        foreach ($contracts as $key => $contract) {
            $contract->no   = ++$start;
            $contract->contract_date = date("d/m/Y", strtotime($contract->contract_signing_date));
            $data[]         = $contract;
        }
        return response()->json([
            'total'     => $recordsTotal,
            'rows'      => $data
        ], 200);
    }

    public function selectbatch(Request $request)
    {
        $start          = $request->page ? $request->page - 1 : 0;
        $length         = $request->limit;
        $no             = $request->name;
        $contract_id    = $request->contract_id;

        // Count Data
        $query          = BatchContract::select("*");
        if($no){
            $query->where("no",$no);
        }
        $query->where("contract_id",$contract_id);

        $row            = clone $query;
        $recordsTotal   = $row->count();

        $query->offset($start);
        $query->limit($length);
        $batchs      = $query->get();

        $data           = [];
        foreach ($batchs as $key => $batch) {
            $batch->no   = ++$start;
            $data[]         = $batch;
        }
        return response()->json([
            'total'     => $recordsTotal,
            'rows'      => $data
        ], 200);
    }

    public function read(Request $request){
        $start = $request->start;
        $length = $request->length;
        $query = $request->search['value'];
        $sort = $request->columns[$request->order[0]['column']]['data'];
        $dir = $request->order[0]['dir'];
        $contract_id = $request->contract_id;
        $status = $request->status;

        //Count Data
        $query = ContractReceipt::select('*')->with("contract","warehouse")->withCount("document");
        if($contract_id){
            $query->where("contract_id",$contract_id);
        }
        if($status){
            $query->where("status",$status);
        }

        $row = clone $query;
        $recordsTotal = $row->count();

        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $contractreceipts = $query->get();

        $data = [];
        foreach ($contractreceipts as $contractreceipt) {
            $contractreceipt->no = ++$start;
            $contractreceipt->contract_date = date("d/m/Y", strtotime($contractreceipt->contract->contract_signing_date));
            $status_receipts       = config('enums.status_receipt');
            // dd($status_receipts);
            switch ($status_receipts) {
                case $contractreceipt->status == 'WAITING':
                    $class_status = "bg-warning";
                    break;
                case $contractreceipt->status == 'INPROGRESS':
                    $class_status = "bg-info";
                    break;
                case $contractreceipt->status == 'COMPLETED':
                    $class_status = "bg-success";
                    break;
                default:
                    $class_status = "bg-info";
                    break;
            }
            $contractreceipt->status_text = '<span class="badge '.$class_status.'">'.$status_receipts[$contractreceipt->status].'</span>';
            $contractreceipt->uploaded_document = $this->getUploadedDocument($contractreceipt->id);
            $data[] = $contractreceipt;
        }
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data
        ], 200);
    }

    public function getUploadedDocument($contract_receipt_id){
        $contractdocumentreceipt = ContractDocumentReceipt::where("contract_receipt_id",$contract_receipt_id)->with("detail")->get();
        $total = 0;
        foreach($contractdocumentreceipt as $key => $row){
            foreach($row->detail as $detail){
                if($detail->source != "" || $detail->source != null){
                    $total++;
                    break;      
                }
            }
        }

        return $total;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.contractreceipt.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (in_array('create', $request->actionmenu)) {
            return view('admin.contractreceipt.create');
        } else {
            abort(403);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'contract_id'         => 'required',
            'warehouse_id'        => 'required',
            'batch'               => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }

        $total_batch = BatchContract::where("contract_id",$request->contract_id)->count();

        DB::beginTransaction();

        $contractreceipt = ContractReceipt::create([
            'contract_id'       => $request->contract_id,
            'warehouse_id'      => $request->warehouse_id,
            'batch'             => $request->batch,
            'total_batch'       => $total_batch,
            'remarks'           => $request->remarks,
            'status'            => 'WAITING',
        ]);
        if ($contractreceipt) {

            if($request->contract_document_receipts){
                foreach($request->contract_document_receipts as $key => $row){
                    $contractdocumentreceipt = ContractDocumentReceipt::create([
                        'contract_receipt_id' => $contractreceipt->id,
                        'batch' => $request->batch,
                        'document_name' => $request->document_name[$row],
                    ]);

                    if($contractdocumentreceipt){
                        if($request->file_contract[$row]){
                            foreach($request->file_contract[$row] as $keys => $rows){
                                $data = [
                                    'contract_document_receipt_id'  => $contractdocumentreceipt->id,
                                    'source' => '',
                                    'upload_date'                   => date("Y-m-d"),
                                ];
                                if (isset($request->file('file')[$row])) {
                                    $file = $request->file('file')[$row][$keys];
                                    $path = 'assets/procurement/contract/recipt/';
                                    if (!file_exists($path)) {
                                        mkdir($path, 0777, true);
                                    }
                                    $document_name = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower($request->document_name[$row]));
                                    $file->move($path, $contractreceipt->id."-".$document_name.'-'.$keys.'.'.$file->getClientOriginalExtension());
                                    $filename = $path.$contractreceipt->id."-".$document_name.'-'.$keys.'.'.$file->getClientOriginalExtension();

                                    $data["source"] = $filename;

                                    $contractdocumentreceiptdetail = ContractDocumentReceiptDetail::create($data);

                                    if(!$contractdocumentreceiptdetail){
                                        DB::rollback();
                                        return response()->json([
                                            'status'    => false,
                                            'message'   => "failed Insert Contract Document Receipt"
                                        ], 400);
                                    }
                                }
                            }
                        }
                    }else{
                        DB::rollback();
                        return response()->json([
                            'status'    => false,
                            'message'   => "failed Insert Contract Document Receipt"
                        ], 400);
                    }
                }
                $countDocument = ContractDocumentReceipt::where('contract_receipt_id', $contractreceipt->id)->count();
                $countDetail = $this->getUploadedDocument($contractreceipt->id);
                if ($countDocument == $countDetail) {
                    $contractreceipt->status = 'INPROGRESS';
                    $contractreceipt->save();
                }
            }

            DB::commit();
            return response()->json([
                'status'     => true,
                'results'     => route('contractreceipt.index'),
            ], 200);
        }else{
            DB::rollback();
            return response()->json([
                'status'    => false,
                'message'   => "failed Insert Contract Receipt"
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        if(in_array('update',$request->actionmenu)){
            $contractreceipt = ContractReceipt::where("id",$id)->with("contract","warehouse","document","document.detail")->withCount("document")->first();
            foreach($contractreceipt->document as $key => $row){
                $contractreceipt->document[$key]->date_uploaded = null;
                foreach($contractreceipt->document[$key]->detail as $detail){
                    if($detail->upload_date){
                        $contractreceipt->document[$key]->date_uploaded = $detail->upload_date;
                        break;
                    }
                }
            }
            // dd($contractreceipt->document[5]->detail);
            if ($contractreceipt) {
                return view('admin.contractreceipt.detail', compact('contractreceipt'));
            } else {
                abort(404);
            }
        }else{
            abort(403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        if(in_array('update',$request->actionmenu)){
            $contractreceipt = ContractReceipt::where("id",$id)->with("contract","warehouse","document","document.detail")->withCount("document")->first();
            foreach($contractreceipt->document as $key => $row){
                $contractreceipt->document[$key]->date_uploaded = null;
                foreach($contractreceipt->document[$key]->detail as $detail){
                    if($detail->upload_date){
                        $contractreceipt->document[$key]->date_uploaded = $detail->upload_date;
                        break;
                    }
                }
            }
            if ($contractreceipt) {
                return view('admin.contractreceipt.edit', compact('contractreceipt'));
            } else {
                abort(404);
            }
        }else{
            abort(403);
        }
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
        $validator = Validator::make($request->all(), [
            'contract_id'         => 'required',
            'warehouse_id'        => 'required',
            'batch'               => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }

        $total_batch = BatchContract::where("contract_id",$request->contract_id)->count();

        DB::beginTransaction();

        $contractreceipt = ContractReceipt::find($id);
        $contractreceipt->contract_id  = $request->contract_id;
        $contractreceipt->warehouse_id = $request->warehouse_id;
        $contractreceipt->batch        = $request->batch;
        $contractreceipt->total_batch  = $total_batch;
        $contractreceipt->remarks      = $request->remarks;

        if ($contractreceipt->save()) {

            if($request->contract_document_receipts){
                foreach($request->contract_document_receipts as $key => $row){
                    if(isset($request->contract_document_receipts_id[$row])){
                        // update
                        if(count(json_decode($request->deleted_file_id[$row])) > 0){
                            foreach(json_decode($request->deleted_file_id[$row]) as $deleted){
                                $docdetail = ContractDocumentReceiptDetail::find($deleted);
                                unlink(public_path($docdetail->source));
                                if(!$docdetail->delete()){
                                    DB::rollback();
                                    return response()->json([
                                        'status'    => false,
                                        'message'   => "Failed delete contract document receipt"
                                    ], 400);
                                }
                            }
                        }

                        $contractdocumentreceipt = ContractDocumentReceipt::find($request->contract_document_receipts_id[$row]);
                        $contractdocumentreceipt->batch = $request->batch;
                        $contractdocumentreceipt->document_name = $request->document_name[$row];

                        if($contractdocumentreceipt->save()){
                            if($request->file_contract[$row]){
                                foreach($request->file_contract[$row] as $keys => $rows){
                                    $data = [
                                        'contract_document_receipt_id'  => $contractdocumentreceipt->id,
                                        'source' => '',
                                        'upload_date'                   => date("Y-m-d"),
                                    ];
                                    if (isset($request->file('file')[$row])) {
                                        $file = $request->file('file')[$row][$keys];
                                        $path = 'assets/procurement/contract/recipt/';
                                        if (!file_exists($path)) {
                                            mkdir($path, 0777, true);
                                        }
                                        $document_name = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower($request->document_name[$row]));
                                        $file->move($path, $contractreceipt->id."-".$document_name.'-'.$keys.'.'.$file->getClientOriginalExtension());
                                        $filename = $path.$contractreceipt->id."-".$document_name.'-'.$keys.'.'.$file->getClientOriginalExtension();

                                        $data["source"] = $filename;

                                        $contractdocumentreceiptdetail = ContractDocumentReceiptDetail::create($data);

                                        if(!$contractdocumentreceiptdetail){
                                            DB::rollback();
                                            return response()->json([
                                                'status'    => false,
                                                'message'   => "Failed add contract document receipt"
                                            ], 400);
                                        }
                                    }
                                }
                            }
                        }else{
                            DB::rollback();
                            return response()->json([
                                'status'    => false,
                                'message'   => "Failed update contract document receipt"
                            ], 400);
                        }
                    }else{
                        // add new
                        $contractdocumentreceipt = ContractDocumentReceipt::create([
                            'contract_receipt_id' => $contractreceipt->id,
                            'batch' => $request->batch,
                            'document_name' => $request->document_name[$row],
                        ]);

                        if($contractdocumentreceipt){
                            if($request->file_contract[$row]){
                                foreach($request->file_contract[$row] as $keys => $rows){
                                    $data = [
                                        'contract_document_receipt_id'  => $contractdocumentreceipt->id,
                                        'source' => '',
                                        'upload_date'                   => date("Y-m-d"),
                                    ];
                                    if (isset($request->file('file')[$row])) {
                                        $file = $request->file('file')[$row][$keys];
                                        $path = 'assets/procurement/contract/recipt/';
                                        if (!file_exists($path)) {
                                            mkdir($path, 0777, true);
                                        }
                                        $document_name = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower($request->document_name[$row]));
                                        $file->move($path, $contractreceipt->id."-".$document_name.'-'.$keys.'.'.$file->getClientOriginalExtension());
                                        $filename = $path.$contractreceipt->id."-".$document_name.'-'.$keys.'.'.$file->getClientOriginalExtension();

                                        $data["source"] = $filename;

                                        $contractdocumentreceiptdetail = ContractDocumentReceiptDetail::create($data);

                                        if(!$contractdocumentreceiptdetail){
                                            DB::rollback();
                                            return response()->json([
                                                'status'    => false,
                                                'message'   => "Failed add contract document receipt"
                                            ], 400);
                                        }
                                    }
                                }
                            }
                        }else{
                            DB::rollback();
                            return response()->json([
                                'status'    => false,
                                'message'   => "Failed add contract document receipt"
                            ], 400);
                        }
                    }
                }
                $countDocument = ContractDocumentReceipt::where('contract_receipt_id', $contractreceipt->id)->count();
                $countDetail = $this->getUploadedDocument($contractreceipt->id);
                if ($countDocument == $countDetail) {
                    $contractreceipt->status = 'INPROGRESS';
                    $contractreceipt->save();
                }
            }

            DB::commit();
            return response()->json([
                'status'     => true,
                'results'     => route('contractreceipt.index'),
            ], 200);
        }else{
            DB::rollback();
            return response()->json([
                'status'    => false,
                'message'   => "failed update Contract Receipt"
            ], 400);
        }
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
            $contractreceipt = ContractReceipt::find($id);
            $contractreceipt->delete();
        } catch (QueryException $th) {
            return response()->json([
                'status'    => false,
                'message'   => 'Error delete data ' . $th->errorInfo[2]
            ], 400);
        }
        return response()->json([
            'status'    => true,
            'message'   => 'Success delete data'
        ], 200);
    }

    public function bulkdownload(Request $request)
    {
        $contractDocument = ContractDocumentReceipt::with('latestDetail')->where('contract_receipt_id', $request->id)->get();
        $contract         = ContractReceipt::with('contract')->find($request->id);
        $zip        = new ZipArchive();
        $filename   = "Download-Document-{$contract->contract->number}-Batch{$contractDocument->first()->batch}.zip";
        if ($zip->open($filename, ZipArchive::CREATE) === true) {
            foreach ($contractDocument as $key => $value) {
                $namefile = explode("/", $value->latestDetail()->first()->source);
                $zip->addFile($value->latestDetail()->first()->source, end($namefile));
            }
            $zip->close();
        }

        response()->download($filename);
        return response()->json([
            'status'    => true,
            'name'      => $filename,
            'message'   => "Success Download Document",
          ], 200);
    }
}