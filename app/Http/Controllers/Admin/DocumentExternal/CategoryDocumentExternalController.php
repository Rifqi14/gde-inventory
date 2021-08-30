<?php

namespace App\Http\Controllers\Admin\DocumentExternal;

use App\Exceptions\DocumentExternal\Properties\PropertiesException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentExternal\DocumentCategoryRequest;
use App\Models\DocExternal\DocExternalCategories\CategoryDocumentExternal;
use App\Models\Menu;
use App\Traits\InteractWithApiResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class CategoryDocumentExternalController extends Controller
{
    use InteractWithApiResponse;
    function __construct() {
        $menu       = Menu::GetByRoute('documentcategoriesexternal')->first();
        $parent     = Menu::parent($menu->parent_id)->first();
        View::share('menu_name', $menu->menu_name);
        View::share('parent_name', $parent->menu_name);
        View::share('menu_active', url('admin/documentcategoriesexternal'));
        $this->middleware('accessmenu', ['except' => ['select']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $documentCategoryRequest)
    {
        if (in_array('read', $documentCategoryRequest->actionmenu)) {
            return view('admin.docexternal.doccategories.index');
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
            return view('admin.docexternal.doccategories.create');
        }
        abort(403);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DocumentCategoryRequest $request)
    {
        return DB::transaction(function() use($request) {
            try {
                $data   = [];
                foreach ($request->menu_id as $key => $menu) {
                    $data[]     = [
                        'menu_id'           => $menu,
                        'discipline_code_id'=> $request->discipline_code_id,
                        'created_at'        => now(),
                        'updated_at'        => now(),
                    ];
                }
                CategoryDocumentExternal::insert($data);
            } catch (\Illuminate\Database\QueryException $ex) {
                throw new PropertiesException("Error insert data {$ex->errorInfo[2]}", Response::HTTP_BAD_REQUEST);
            }
            return $this->success(Response::HTTP_OK, "Success create data", null, route('documentcategoriesexternal.index'));
        });
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        if (in_array('update', $request->actionmenu)) {
            $doccategory    = CategoryDocumentExternal::find($id);
            if ($doccategory) {
                return view('admin.docexternal.doccategories.edit');
            }
            abort(404);
        }
        abort(403);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, $id)
    {
        if (in_array('update', $request->actionmenu)) {
            $doccategory    = CategoryDocumentExternal::find($id);
            if ($doccategory) {
                return view('admin.docexternal.doccategories.edit', compact('doccategory'));
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
    public function update(DocumentCategoryRequest $request, $id)
    {
        return DB::transaction(function() use($request, $id) {
            try {
                $category   = CategoryDocumentExternal::find($id);
                $category->menu_id              = $request->menu_id;
                $category->discipline_code_id   = $request->discipline_code_id;
                $category->save();
            } catch (\Illuminate\Database\QueryException $ex) {
                throw new PropertiesException("Error update data: {$ex->errorInfo[2]}");
            }
            return $this->success(Response::HTTP_OK, "Success udpate data", null, route('documentcategoriesexternal.index'));
        });
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        try {
            if (!in_array('delete', $request->actionmenu)) {
                throw new PropertiesException("You dont have permission to delete this data!", Response::HTTP_BAD_REQUEST);
            }
            CategoryDocumentExternal::destroy($id);
        } catch (\Illuminate\Database\QueryException $ex) {
            return $this->error("Error delete data {$ex->errorInfo[2]}");
        }
        return $this->success(Response::HTTP_OK, "Success add data", null);
    }

    /**
     * Read function to datatable
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function read(Request $request)
    {
        $start              = $request->start;
        $length             = $request->length;
        $query              = $request->search['value'];
        $sort               = $request->columns[$request->order[0]['column']]['data'];
        $dir                = $request->order[0]['dir'];
        $menu_id            = $request->menu_id;
        $discipline_code_id = $request->discipline_code_id;

        // Query Data
        $queryData      = CategoryDocumentExternal::with(['menu', 'disciplinecode']);
        if ($menu_id) {
            $queryData->where('menu_id', $menu_id);
        }
        if ($discipline_code_id) {
            $queryData->where('discipline_code_id', $discipline_code_id);
        }

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
}
