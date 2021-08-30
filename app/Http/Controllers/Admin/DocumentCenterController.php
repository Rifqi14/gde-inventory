<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\DcCategory;
use App\Models\DocumentCenter;
use App\Models\DocumentCenterInformed;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;

class DocumentCenterController extends Controller
{
    protected $page = "";
    protected $type = "";
    protected $menu_id = "";
    function __construct(Request $request) {
        $this->page = Route::current()->parameter('page');
        $this->page = $this->page ? $this->page : $request->menu;
        $page   = $this->page;
        $menu   = Menu::GetByRoute("documentcenter/$this->page")->first();
        $this->menu_id  = $menu->id;
        $parent = Menu::parent($menu->parent_id)->first();
        View::share('menu_name', $menu->menu_name);
        View::share('parent_name', $parent->menu_name);
        View::share('page', $page);
        View::share('menu_active', url("admin/documentcenter/$this->page"));
        $this->middleware('accessmenu', ['except' => ['select']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = DcCategory::with(['menu', 'doctype'])->where('menu_id', $this->menu_id)->get();
        if (in_array('read', $request->actionmenu)) {
            return view('admin.documentcenter.index', compact('categories'));
        }
        abort(403);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $this->type = Route::current()->parameter('type');
        $route      = Menu::GetByRoute("documentcenter/$this->page")->first();
        $page       = $this->page;
        if (in_array('create', $request->actionmenu) && $route) {
            return view('admin.documentcenter.create', compact('page', 'request'));
        }
        abort(403);
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
            'number'            => 'required',
            'title'             => 'required',
            'document_type_id'  => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        try {
            $data       = [
                'document_number'       => $request->number,
                'title'                 => $request->title,
                'status'                => 'DRAFT',
                'menu'                  => $request->menu,
                'category_id'           => $request->category,
                'document_type_id'      => $request->document_type_id,
                'organization_code_id'  => $request->organization_code_id,
                'unit_code_id'          => $request->unit_code_id,
                'remark'                => $request->remark,
                'originator_id'         => $request->originator_id,
            ];
            $documentCenter     = DocumentCenter::create($data);
        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => "Error create document data: {$th->errorInfo[2]}",
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status'    => true,
            'message'   => "Success create data",
            'results'   => route('documentcenter.edit', ['page' => $request->menu, 'id' => $documentCenter->id]),
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
     * @param  string  $menu
     * @param  int  $category
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $page, $id)
    {
        if (in_array('create', $request->actionmenu) && $id) {
            $document   = DocumentCenter::find($id);
            if ($document) {
                return view('admin.documentcenter.edit', compact('document'));
            }
            abort(404);
        }
        abort(403);
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
            'number'        => 'required_if:locked_status,unlock',
            'title'         => 'required_if:locked_status,unlock',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        try {
            $documentCenter     = DocumentCenter::find($id);
            $documentCenter->document_number        = $request->number;
            $documentCenter->title                  = $request->title;
            $documentCenter->status                 = 'DRAFT';
            $documentCenter->menu                   = $request->menu;
            $documentCenter->category_id            = $request->category;
            $documentCenter->document_type_id       = $request->document_type_id;
            $documentCenter->organization_code_id   = $request->organization_code_id;
            $documentCenter->unit_code_id           = $request->unit_code_id;
            $documentCenter->remark                 = $request->remark;
            $documentCenter->originator_id          = $request->originator_id;
            $documentCenter->save();
        } catch (\Illuminate\Database\QueryException $ex) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => "Error create directory data: {$ex->errorInfo[2]}"
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status'    => true,
            'message'   => "Success create data",
            'results'   => route('documentcenter.index', ['page' => $request->menu]),
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
            $documentCenter = DocumentCenter::destroy($id);
        } catch (\Illuminate\Database\QueryException $th) {
            return response()->json([
                'status'    => false,
                'message'   => "Error delete data",
            ], 400);
        }

        return response()->json([
            'status'    => true,
            'message'   => "Success delete data",
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
        $category       = $request->category;
        $menu           = $request->menu;

        // Query Data
        $queryData      = DocumentCenter::with(['documents', 'createdBy']);
        $queryData->where('category_id', $category);
        $queryData->where('menu', $menu);

        $row            = clone $queryData;
        $recordsTotal   = $row->count();

        $queryData->offset($start);
        $queryData->limit($length);
        $queryData->orderBy($sort, $dir);
        $documents      = $queryData->get();

        $data           = [];
        foreach ($documents as $key => $document) {
            $revision       = 0;
            $purpose        = '';
            $revisionDate   = '';
            $issue_purpose  = '';
            $issue_status   = '';
            if ($document->documents()->count() > 0) {
                $revisionDate       = date('d/m/Y', strtotime($document->documents()->latest()->first()->created_at));
                $purpose            = $document->documents()->latest()->first()->remark;
                $issue_purpose      = $document->documents()->latest()->first()->issue_purpose;
                $issue_status       = $document->documents()->latest()->first()->status;
                $revision           = $document->documents()->latest()->first()->revision;
                $document->document_type    = $document->documents()->latest()->first()->document_type;
                $document->status   = $document->documents()->first()->status;
            }
            $document->no   = ++$start;
            $document->revision = $revision;
            $document->purpose  = $purpose ? $purpose : "";
            $document->issue_purpose= $issue_purpose;
            $document->issue_status = $issue_status;
            $document->discipline   = ucwords($document->discipline);
            $document->revision_date= $revisionDate;
            $document->first_issue  = date('d/m/Y', strtotime($document->first_issue));
            $data[]         = $document;
        }
        return response()->json([
            'draw'              => $request->draw,
            'recordsTotal'      => $recordsTotal,
            'recordsFiltered'   => $recordsTotal,
            'data'              => $data,
        ], 200);
    }

    /**
     * Method to get data by specific id
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function getDocumentCenterData(Request $request, $id)
    {
        $this->page = $request->menu;
        $document_id= $request->document_id;
        $document   = DocumentCenter::with(['documents' => function ($q) use ($document_id) {
            if ($document_id) {
                $q->where('id', $document_id);
            }
        }])->find($id);
        if ($document) {
            return response()->json([
                'status'    => true,
                'message'   => "Data Found",
                'data'      => $document,
            ], 200);
        }
        return response()->json([
            'status'    => false,
            'message'   => "Data not found",
        ], 400);
    }

    /**
     * Method to get data to show in select2
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function select(Request $request)
    {
        $start  = $request->page ? $request->page - 1 : 0;
        $length = $request->limit;
        $name   = strtoupper($request->name);
        $document_type_id       = $request->document_type_id;
        $organization_code_id   = $request->organization_code_id;
        $unit_code_id           = $request->unit_code_id;

        // Count Data
        $query  = DocumentCenter::whereRaw("upper(document_number) like '%$name%'");
        if ($document_type_id) {
            $query->where('document_type_id', $document_type_id);
        }
        if ($organization_code_id) {
            $query->where('organization_code_id', $organization_code_id);
        }
        if ($unit_code_id) {
            $query->where('unit_code_id', $unit_code_id);
        }

        $row    = clone $query;
        $recordsTotal   = $row->count();

        $query->offset($start);
        $query->limit($length);
        $documents  = $query->get();

        $data = [];
        foreach ($documents as $document) {
            $document->no   = ++$start;
            $data[]         = $document;
        }
        return response()->json([
            'total'     => $recordsTotal,
            'rows'      => $data,
        ], 200);
    }
}
