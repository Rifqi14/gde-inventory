<?php

namespace App\Http\Controllers\Admin\DocumentExternal\Workflow;

use App\Exceptions\DocumentExternal\Properties\PropertiesException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DocExternal\Workflow\GroupWorkflow;
use App\Traits\InteractWithApiResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use phpDocumentor\Reflection\Types\Null_;

class GroupWorkflowController extends Controller
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
        $groupworkflow = GroupWorkflow::find($id);
        if (!$groupworkflow) {
            return $this->error("Data not found");
        }
        return $this->success(Response::HTTP_OK, "", $groupworkflow) ;
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
        return DB::transaction(function() use($request, $id) {
            try {
                $groupWorkflow  = GroupWorkflow::find($id);
                $data   = [];

                switch ($request->status) {
                    case 'APPROVE':
                        if ($groupWorkflow->comment) {
                            $groupWorkflow->status  = 'APPROVE WITH COMMENT';
                        }else{
                            $groupWorkflow->status  = 'APPROVE WITHOUT COMMENT';
                        }
                        break;
                    case 'REJECT':
                        if ($groupWorkflow->comment) {
                            $groupWorkflow->status  = 'REJECT WITH COMMENT';
                        }else{
                            $groupWorkflow->status  = 'REJECT WITHOUT COMMENT';
                        }
                        break;
                    case 'NO COMMENT':
                        $groupWorkflow->status  = $request->status;
                        break;
                    case 'COMMENT':
                        if ($groupWorkflow->status == 'APPROVE WITHOUT COMMENT') {
                            $groupWorkflow->status          = 'APPROVE WITH COMMENT';
                        }
                        else if ($groupWorkflow->status == 'REJECT WITHOUT COMMENT') {
                            $groupWorkflow->status          = 'REJECT WITH COMMENT';
                        }else{
                            $groupWorkflow->status          = $request->status;
                        }
                        $groupWorkflow->nos_of_pages    = $request->nos_of_pages;
                        $groupWorkflow->comment         = $request->comment;
                        break;

                    default:
                        # code...
                        break;
                }
                $groupWorkflow->save();

                if ($request->status == 'REJECT') {
                    $group  = GroupWorkflow::find($id);
                    $workflow_id = $group->workflow_id;

                    $groupWorkflow = GroupWorkflow::where([
                        'status' => NULL,
                        'workflow_id' => $workflow_id
                    ])->get();

                    foreach ($groupWorkflow as $value) {
                        $value->status = 'REJECT WITHOUT COMMENT';
                        $value->save();
                    }
                }

            } catch (\Illuminate\Database\QueryException $th) {
                throw new PropertiesException("Error delete data: {$th->errorInfo[2]}", Response::HTTP_BAD_REQUEST);
            }
            return $this->success(Response::HTTP_OK, "Success Create Data", null);
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
        //
    }
}
