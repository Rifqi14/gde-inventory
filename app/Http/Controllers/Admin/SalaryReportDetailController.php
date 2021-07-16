<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SalaryReport;
use App\Models\SalaryReportDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use NumberFormatter;

class SalaryReportDetailController extends Controller
{
    public function __construct() {
    }
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
            'type'              => 'required',
            'salary_report_id'  => 'required',
            'currency_id'       => 'required',
            'total'             => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        try {
            $detail     = SalaryReportDetail::create([
                'salary_report_id'      => $request->salary_report_id,
                'description'           => $request->item,
                'total'                 => str_replace('.', '', $request->total),
                'type'                  => $request->type,
                'is_added'              => 1,
                'currency_id'           => $request->currency_id,
            ]);
            recalculateTotal($request->salary_report_id);
        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => "Error create item"
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status'    => true,
            'message'   => "Success add item"
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        $detail     = SalaryReportDetail::with(['currencies'])->find($id);
        $detail->total = number_format($detail->total, 0,);
        if ($detail) {
            return response()->json([
                'status'    => true,
                'data'      => $detail,
            ], 200);
        } else {
            return response()->json([
                'status'    => false,
                'message'   => "This data not found",
            ], 400);
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
        $validator      = Validator::make($request->all(), [
            'type'              => 'required',
            'salary_report_id'  => 'required',
            'currency_id'       => 'required',
            'total'             => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        try {
            $detail     = SalaryReportDetail::find($id);
            $detail->description    = $request->item;
            $detail->total          = str_replace('.', '', $request->total);
            $detail->currency_id    = $request->currency_id;
            $detail->save();
            recalculateTotal($detail->salary_report_id);
        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => "Error edit item {$th->errorInfo[2]}"
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status'    => true,
            'message'   => "Success edit item"
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
            $detail = SalaryReportDetail::find($id);
            $salary = $detail->salary_report_id;
            $detail->delete();

            recalculateTotal($salary);
        } catch (\Illuminate\Database\QueryException $th) {
            return response()->json([
                'status'    => false,
                'message'   => "Error delete data {$th->errorInfo[2]}",
            ], 400);
        }
        return response()->json([
            'status'    => true,
            'message'   => "Success delete data"
        ], 200);
    }

    public function read(Request $request)
    {
        $start          = $request->start;
        $length         = $request->length;
        $query          = $request->search['value'];
        $sort           = $request->columns[$request->order[0]['column']]['data'];
        $dir            = $request->order[0]['dir'];
        $salaryreport   = $request->salary_report_id;
        $type           = $request->type;

        $queryData      = SalaryReportDetail::salaryId($salaryreport);
        if ($type == 1) {
            $queryData->additional();
        } else {
            $queryData->deduction();
        }

        $row            = clone $queryData;
        $recordsTotal   = $row->count();

        $queryData->offset($start);
        $queryData->limit($length);
        $queryData->orderBy($sort, $dir);
        $details        = $queryData->get();

        $data           = [];
        foreach ($details as $key => $detail) {
            $fmt        = new NumberFormatter('id_ID', NumberFormatter::CURRENCY);
            $fmt->setAttribute($fmt::FRACTION_DIGITS, 0);
            $detail->no = ++$start;
            $detail->total  = $fmt->formatCurrency($detail->total, $detail->currencies->code);
            $data[]     = $detail;
        }
        return response()->json([
            'draw'              => $request->draw,
            'recordsTotal'      => $recordsTotal,
            'recordsFiltered'   => $recordsTotal,
            'data'              => $data
        ], 200);
        
    }
}
