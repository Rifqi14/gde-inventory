<?php

namespace App\Http\Controllers\Admin\Transmittal\TransmittalProperties;

use App\Exceptions\DocumentExternal\Properties\PropertiesException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transmittal\TransmittalProperties\OrganizationCodeRequest;
use App\Models\Menu;
use App\Models\Transmittal\TransmittalProperties\TransmittalOrganizationCode;
use App\Traits\InteractWithApiResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class OrganizationCodeController extends Controller
{
    use InteractWithApiResponse;
    function __construct() {
        $menu   = Menu::with(['parentMenu'])->GetByRoute('transmittalproperties')->first();
        view()->share('menu_name', 'Organization Code');
        view()->share('parent_name', $menu->menu_name);
        view()->share('menu_active', url("admin/transmittalproperties"));
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
            return view('admin.transmittal.organizationcode.create');
        }
        abort(403);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(OrganizationCodeRequest $request)
    {
        return DB::transaction(function() use($request) {
            try {
                $data   = [
                    'code'          => strtoupper($request->code),
                    'name'          => $request->name,
                ];
                $create = TransmittalOrganizationCode::create($data);
                $create->groups()->sync($request->tagged_group_id);
            } catch (\Illuminate\Database\QueryException $ex) {
                throw new PropertiesException("Error create data: {$ex->errorInfo[2]}", 400);
            }
            return $this->success(Response::HTTP_OK, "Success create data", $create, route('transmittalproperties.index'));
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
    public function edit(Request $request, $id)
    {
        if (in_array('update', $request->actionmenu)) {
            $data   = TransmittalOrganizationCode::with(['groups'])->find($id);
            if ($data) {
                return view('admin.transmittal.organizationcode.edit', compact('data'));
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
    public function update(OrganizationCodeRequest $request, $id)
    {
        return DB::transaction(function() use($request, $id) {
            try {
                $update = TransmittalOrganizationCode::find($id);
                $update->code   = strtoupper($request->code);
                $update->name   = $request->name;
                $update->save();
                $update->groups()->sync($request->tagged_group_id);
            } catch (\Illuminate\Database\QueryException $ex) {
                throw new PropertiesException("Error create data: {$ex->errorInfo[2]}", 400);
            }
            return $this->success(Response::HTTP_OK, "Success update data", $update, route('transmittalproperties.index'));
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
        $destroy    = TransmittalOrganizationCode::destroy($id);
        if (!$destroy) {
            throw new PropertiesException("Error delete data", Response::HTTP_BAD_REQUEST);
        }
        return $this->success(Response::HTTP_OK, "Success delete data", null);
    }

    public function read(Request $request)
    {
        $start      = $request->start;
        $length     = $request->length;
        $sort       = $request->columns[$request->order[0]['column']]['data'];
        $dir        = $request->order[0]['dir'];
        $name       = strtoupper($request->name);
        $tagged_group_id    = $request->tagged_group_id;

        // Count Data
        $query      = TransmittalOrganizationCode::with(['groups'])->when($tagged_group_id, function(\Illuminate\Database\Eloquent\Builder $q) {
            $q->whereHas('groups', function(\Illuminate\Database\Eloquent\Builder $que) {
                $que->where('role_id', request('tagged_group_id'));
            });
        })->whereRaw("upper(name) like '%$name%'");

        $row        = clone $query;
        $recordsTotal = $row->get()->count();

        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $codes      = $query->get();

        $data       = [];
        foreach ($codes as $key => $code) {
            $code->no   = ++$start;
            $data[]     = $code;
        }
        return response()->json([
            'draw'              => $request->draw,
            'recordsTotal'      => $recordsTotal,
            'recordsFiltered'   => $recordsTotal,
            'data'              => $data,
        ], 200);
    }
}
