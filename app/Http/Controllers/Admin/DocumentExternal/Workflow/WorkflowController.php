<?php

namespace App\Http\Controllers\Admin\DocumentExternal\Workflow;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DocExternal\Workflow\GroupWorkflow;
use App\Models\DocExternal\Workflow\Workflow;
use App\Models\Menu;

class WorkflowController extends Controller
{
    function __construct() {
        $menu   = Menu::GetByRoute("workflow")->first();
        $parent = Menu::parent($menu->parent_id)->first();
        view()->share('menu_name', $menu->menu_name);
        view()->share('parent_name', $parent->menu_name);
        view()->share('menu_active', url('admin/documentccenterexternal'));
        // $this->middleware('accessmenu', ['except' => ['select']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id)
    {
        $workflow   = Workflow::with(['document', 'revision'])->find($id);
        if ($workflow) {
            return view('admin.docexternal.workflow.workflow', compact('workflow'));
        }
        abort(404);
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

    /**
     * Read function to datatable
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function read(Request $request)
    {
        $id             = $request->id;

        // Query Data
        $queryData      = GroupWorkflow::with(['workflow.revision'])->where('workflow_id', $id);

        $row            = clone $queryData;
        $recordsTotal   = $row->get()->count();
        $queryData->orderBy('id', 'asc');
        $workflows      = $queryData->get();

        $data           = [];

        $total_review = 0;
        $review_label = 0;

        foreach ($workflows as $key => $workflow) {

            if (strpos($workflow->label_group, 'Reviewer') !== false) {
                $total_review++;
            }
            if (strpos($workflow->label_group, 'Reviewer') !== false && $workflow->status) {
                $review_label++;
            }

            $data[]         = [
                'role_id'   => "<span class='text-bold'>{$workflow->user->name}</span><br><small>$workflow->label_group</small>",
                'comment'   => $workflow->comment,
                'id'        => $workflow->id,
                'sla'       => $workflow->sla ? "<span class='badge bg-success'><i class='fa fa-check'></i></span>" : '<span class="badge bg-red"><i class="fa fa-times"></i></span>',
                'sla_dates' => $workflow->sla_dates,
                'need_approval' => $workflow->need_approval,
                'status'        => $workflow->status,
                'nos_of_pages'  => $workflow->workflow->revision->nos_of_pages,
                'label'     => $workflow->label_group,
            ];
        }

        $approve = false;
        if ($total_review == $review_label) {
            $approve = true;
        }

        return response()->json([
            'draw'              => $request->draw,
            'recordsTotal'      => $recordsTotal,
            'recordsFiltered'   => $recordsTotal,
            'data'              => $data,
            'approve'           => $approve,
        ], 200);
    }
}
