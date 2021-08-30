<?php

namespace App\Http\Controllers\Admin\DocumentExternal;

use App\Exceptions\DocumentExternal\Properties\PropertiesException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentExternal\DocumentExternalRequest;
use App\Models\DocExternal\DocExternalCategories\CategoryDocumentExternal;
use App\Models\DocExternal\DocumentExternal;
use App\Models\DocExternal\DocumentExternalRevision;
use App\Models\DocExternal\Properties\DocumentExternalContractorName;
use App\Models\DocExternal\Properties\DocumentExternalPhaseCode;
use App\Models\DocExternal\ReviewerMatrix\DocumentExternalMatrix;
use App\Models\Menu;
use App\Traits\InteractWithApiResponse;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;

class DocumentExternalController extends Controller
{
    use InteractWithApiResponse;
    public $page;
    public $type;
    public $menu_id;
    function __construct(Request $request) {
        $page   = $request->page ?? $request->menu;
        $this->setPage(Route::current()->parameter('page') ?? $page);
        // dd($request->all());
        $menu   = Menu::GetByRoute("documentcenterexternal/{$this->getPage()}")->first();
        $this->setMenu($menu->id);
        $this->setType($menu->menu_name);
        $parent = Menu::parent($menu->parent_id)->first();
        $page   = explode('_', $this->getPage());
        $phase  = DocumentExternalPhaseCode::where('code',$page[1])->first();
        View::share('menu_name', $menu->menu_name);
        View::share('parent_name', $parent->menu_name);
        View::share('page', $this->getPage());
        View::share('phase', $phase);
        View::share('menu_active', url("admin/documentcenterexternal/{$this->getPage()}"));
        $this->middleware('accessmenu', ['except' => ['select']]);
    }

    function setPage($page)
    {
        $this->page = $page;
    }

    function getPage() 
    {
        return $this->page;
    }
    
    function setType($type)
    {
        $this->type = $type;
    }

    function getType() 
    {
        return $this->type;
    }

    function setMenu($menu_id)
    {
        $this->menu_id = $menu_id;
    }

    function getMenu() 
    {
        return $this->menu_id;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $categories = CategoryDocumentExternal::with(['menu', 'disciplinecode'])->where('menu_id', $this->getMenu())->get();
        if (in_array('read', $request->actionmenu)) {
            return view('admin.docexternal.index', compact('categories'));
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
        if (in_array('create', $request->actionmenu)) {
            return view('admin.docexternal.create');
        }
        abort(403);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DocumentExternalRequest $request)
    {
        return DB::transaction(function() use($request) {
            try {
                $contractorName     = DocumentExternalContractorName::where('name', $request->contractor_name_id)->where('role_id', $request->contractor_group_id)->first();
                $data       = [
                    'document_number'               => $request->document_number,
                    'document_title'                => $request->document_title,
                    'site_code_id'                  => $request->site_code_id,
                    'discipline_code_id'            => $request->discipline_code_id,
                    'kks_category_id'               => $request->kks_category_id,
                    'kks_code_id'                   => $request->kks_code_id,
                    'document_type_id'              => $request->document_type_id,
                    'originator_code_id'            => $request->originator_code_id,
                    'phase_code_id'                 => $request->phase_code_id,
                    'document_sequence'             => $request->document_sequence,
                    'document_category_id'          => $request->document_category_id,
                    'contractor_document_number'    => $request->contractor_document_no,
                    'contractor_name_id'            => $contractorName->id,
                    'contractor_group_id'           => $request->contractor_group_id,
                    'planned_ifi_ifa_date'          => dbDate($request->planned_ifi_ifa_date),
                    'planned_ifc_ifu_date'          => dbDate($request->planned_ifc_ifu_date),
                    'planned_afc_date'              => dbDate($request->planned_afc_date),
                    'planned_ab_date'               => dbDate($request->planned_ab_date),
                    'created_by'                    => request()->user()->id,
                    'updated_by'                    => request()->user()->id,
                    'document_remark'               => $request->remark,
                    'menu'                          => $request->page,
                ];
                $document   = DocumentExternal::create($data);
                
                $matrixData     = [];
                foreach ($request->reviewer_matrix as $key => $value) {
                    $matrixData[]   = [
                        'document_external_id'  => $document->id,
                        'matrix_label'          => $value->label,
                        'matrix_sla'            => $value->sla == 'on' ? 'true' : 'false',
                        'matrix_days'           => $value->days,
                    ];
                }
                dd($matrixData);
            } catch (\Illuminate\Database\QueryException $ex) {
                throw new PropertiesException("Error create data: {$ex->errorInfo[2]}", 400);
            }
            return $this->success(Response::HTTP_OK, "Success create data", null, route('documentcenterexternal.index', ['page' => $request->page]));
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
    public function edit(Request $request, $page, $id)
    {
        if (in_array('create', $request->actionmenu)) {
            $external   = DocumentExternal::with(['matrix'])->find($id);
            // dd($external->matrix()->with(['groups'])->get());
            if ($external) {
                return view('admin.docexternal.edit', compact('external'));
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
    public function update(DocumentExternalRequest $request, $id)
    {
        return DB::transaction(function() use($request, $id) {
            try {
                $contractorName     = DocumentExternalContractorName::where('name', $request->contractor_name_id)->where('role_id', $request->contractor_group_id)->first();
                $external       = DocumentExternal::find($id);
                $external->document_number               = $request->document_number;
                $external->document_title                = $request->document_title;
                $external->site_code_id                  = $request->site_code_id;
                $external->discipline_code_id            = $request->discipline_code_id;
                $external->kks_category_id               = $request->kks_category_id;
                $external->kks_code_id                   = $request->kks_code_id;
                $external->document_type_id              = $request->document_type_id;
                $external->originator_code_id            = $request->originator_code_id;
                $external->phase_code_id                 = $request->phase_code_id;
                $external->document_sequence             = $request->document_sequence;
                $external->document_category_id          = $request->document_category_id;
                $external->contractor_document_number    = $request->contractor_document_no;
                $external->contractor_name_id            = $contractorName->id;
                $external->contractor_group_id           = $request->contractor_group_id;
                $external->planned_ifi_ifa_date          = dbDate($request->planned_ifi_ifa_date);
                $external->planned_ifc_ifu_date          = dbDate($request->planned_ifc_ifu_date);
                $external->planned_afc_date              = dbDate($request->planned_afc_date);
                $external->planned_ab_date               = dbDate($request->planned_ab_date);
                $external->updated_by                    = request()->user()->id;
                $external->document_remark               = $request->remark;
                $external->menu                          = $request->page;
                $external->save();

                $external->matrix()->delete();
                foreach ($request->reviewer_matrix as $key => $value) {
                    $matrixData     = [
                        'document_external_id'  => $external->id,
                        'matrix_label'          => $value['label'],
                        'matrix_sla'            => $value['sla'] == 'on' ? 'true' : 'false',
                        'matrix_days'           => $value['days'],
                    ];

                    
                    $matrix = DocumentExternalMatrix::create($matrixData);

                    $matrixGroup    = [];
                    foreach ($value['group'] as $keyGroup => $group) {
                        $matrix->groups()->attach($group);
                    }
                }
            } catch (\Illuminate\Database\QueryException $th) {
                throw new PropertiesException("Error update data: {$th->errorInfo[2]}", 400);
            }
            return $this->success(Response::HTTP_OK, "Success update data", null, route('documentcenterexternal.index', ['page' => $request->page]));
        });
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateMatrix(Request $request, $id)
    {
        return DB::transaction(function() use($request, $id) {
            try {
                foreach ($request->reviewer_matrix as $keyMatrix => $matrixData) {
                    $matrix     = DocumentExternalMatrix::selectedLabel($matrixData['label'])->where('document_external_id', $id)->first();
                    $matrix->matrix_sla     = isset($matrixData['sla']) == true ? 'true' : 'false';
                    $matrix->matrix_days    = isset($matrixData['sla']) == true ? $matrixData['days'] : null;
                    $groupId    = [];
                    if (isset($matrixData['group'])) {
                        foreach ($matrixData['group'] as $keyGroup => $group) {
                            array_push($groupId, $group);
                        }
                    }
                    $matrix->groups()->sync($groupId);
                    $matrix->save();
                }
            } catch (\Illuminate\Database\QueryException $th) {
                throw new PropertiesException("Error update data: {$th->errorInfo[2]}", 400);
            }
            return $this->success(Response::HTTP_OK, "Success update data", null);
        });
    }

    public function readMatrix($page, $id)
    {
        $matrixs     = DocumentExternalMatrix::with(['groups'])->where('document_external_id', $id)->get();

        $data   = [];
        foreach ($matrixs as $keyMatrix => $matrix) {
            $data[] = $matrix;
        }

        return $this->success(Response::HTTP_OK, "Success retrieve data", $data);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($page, $id)
    {
        try {
            DocumentExternal::destroy($id);
        } catch (\Illuminate\Database\QueryException $th) {
            throw new PropertiesException("Error delete data: {$th->errorInfo[2]}", 400);
        }
        return $this->success(Response::HTTP_OK, "Success delete data", null);
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
        $query          = $request->search['value'];
        $sort           = $request->columns[$request->order[0]['column']]['data'];
        $dir            = $request->order[0]['dir'];
        $category       = $request->category;
        $menu           = $request->page;

        // Query Data
        $queryData      = DocumentExternal::with(['documenttype']);
        $queryData->where('discipline_code_id', $category);
        $queryData->where('menu', $menu);

        $row            = clone $queryData;
        $recordsTotal   = $row->count();

        $queryData->offset($start);
        $queryData->limit($length);
        $queryData->orderBy($sort, $dir);
        $externals      = $queryData->get();

        $data           = [];
        foreach ($externals as $key => $external) {
            $external->no   = ++$start;
            $data[]         = $external;
        }
        return response()->json([
            'draw'              => $request->draw,
            'recordsTotal'      => $recordsTotal,
            'recordsFiltered'   => $recordsTotal,
            'data'              => $data,
        ], 200);
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
        $document_no        = strtoupper($request->document_no);
        $site_code          = $request->site_code;
        $discipline_code    = $request->discipline_code;
        $kks_category       = $request->kks_category;
        $kks_code           = $request->kks_code;
        $document_type      = $request->document_type;
        $originator_code    = $request->originator_code;
        $phase_code         = $request->phase_code;
        $document_category  = $request->document_category;

        // Count Data
        $query  = DocumentExternal::currentDocumetNo($document_no)->when($site_code, function (Builder $q) use ($site_code) {
            $q->currentSiteCode($site_code);
        })->when($discipline_code, function (Builder $q) use ($discipline_code) {
            $q->currentDisciplineCode($discipline_code);
        })->when($kks_category, function (Builder $q) use ($kks_category) {
            $q->currentKksCategory($kks_category);
        })->when($kks_code, function (Builder $q) use ($kks_code) {
            $q->currentKksCode($kks_code);
        })->when($document_type, function (Builder $q) use ($document_type) {
            $q->currentDocumentType($document_type);
        })->when($originator_code, function (Builder $q) use ($originator_code) {
            $q->currentOriginatorCode($originator_code);
        })->when($phase_code, function (Builder $q) use ($phase_code) {
            $q->currentPhaseCode($phase_code);
        })->when($document_category, function (Builder $q) use ($document_category) {
            $q->currentDocumentCategory($document_category);
        });

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
