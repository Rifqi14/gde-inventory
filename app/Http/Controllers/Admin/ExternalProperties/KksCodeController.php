<?php

namespace App\Http\Controllers\Admin\ExternalProperties;

use App\Exceptions\DocumentExternal\Properties\PropertiesException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentExternal\Properties\KksCodeRequest;
use App\Models\DocExternal\Properties\DocumentExternalKksCode;
use App\Models\Menu;
use App\Traits\InteractWithApiResponse;
use DeepCopy\Exception\PropertyException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;

class KksCodeController extends Controller
{
    use InteractWithApiResponse;
    function __construct() {
        $menu       = Menu::GetByRoute('kkscode')->first();
        $parent     = Menu::parent($menu->parent_id)->first();
        View::share('menu_name', $menu->menu_name);
        View::share('parent_name', $parent->menu_name);
        View::share('menu_active', url('admin/docexternalproperties'));
        $this->middleware('accessmenu', ['except' => ['select']]);
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
    public function create(Request $request)
    {
        if (in_array('create', $request->actionmenu)) {
            return view('admin.docexternal.properties.kkscode.create');
        }
        abort(403);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KksCodeRequest $request)
    {
        return DB::transaction(function() use($request) {
            try {
                $data   = [
                    'code'  => strtoupper($request->code),
                    'name'  => $request->name,
                    'document_external_kks_categories_id'   => $request->kks_category_id,
                ];
                DocumentExternalKksCode::create($data);
            } catch (\Illuminate\Database\QueryException $ex) {
                throw new PropertiesException("Error create data: {$ex->errorInfo[2]}", 400);
            }
            return $this->success(Response::HTTP_OK, "Success Create Data", null, route('docexternalproperties.index'));
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
    public function edit(\Illuminate\Http\Request $request, $id)
    {
        if (in_array('update', $request->actionmenu)) {
            $kkscode   = DocumentExternalKksCode::find($id);
            if ($kkscode) {
                return view('admin.docexternal.properties.kkscode.edit', compact('kkscode'));
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
    public function update(KksCodeRequest $request, $id)
    {
        return DB::transaction(function() use($request, $id) {
            try {
                $documenttype   = DocumentExternalKksCode::find($id);
                $documenttype->code     = strtoupper($request->code);
                $documenttype->name     = $request->name;
                $documenttype->document_external_kks_categories_id     = $request->kks_category_id;
                $documenttype->save();
            } catch (\Illuminate\Database\QueryException $ex) {
                throw new PropertyException("Error update data: {$ex->errorInfo[2]}", 400);
            }
            return $this->success(Response::HTTP_OK, "Success Update Data", null, route('docexternalproperties.index'));
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
        try {
            DocumentExternalKksCode::destroy($id);
        } catch (\Illuminate\Database\QueryException $ex) {
            throw new PropertiesException("Error delete data: {$ex->errorInfo[2]}", 400);
        }
        return $this->success(Response::HTTP_OK, "Success Delete Data", null);
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
        $code           = strtoupper($request->code);
        $name           = strtoupper($request->name);

        // Query Data
        $queryData      = DocumentExternalKksCode::with(['category'])->where('code', 'like', "%$code%")->where(DB::raw('upper(name)'), 'like', "%$name%");

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
        $code   = strtoupper($request->code);
        $category = $request->category;

        // Count Data
        $query  = DocumentExternalKksCode::with(['category'])->where('code', 'like', "%$code%");
        if ($category) {
            $query->where('document_external_kks_categories_id', $category);
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
