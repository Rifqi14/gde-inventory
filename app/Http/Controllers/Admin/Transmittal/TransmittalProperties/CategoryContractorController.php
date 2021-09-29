<?php

namespace App\Http\Controllers\Admin\Transmittal\TransmittalProperties;

use App\Exceptions\DocumentExternal\Properties\PropertiesException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Transmittal\TransmittalProperties\CategoryContractorRequest;
use App\Models\Menu;
use \Illuminate\Http\File;
use App\Models\Transmittal\TransmittalProperties\CategoryContractor;
use App\Traits\InteractWithApiResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class CategoryContractorController extends Controller
{
    use InteractWithApiResponse;
    function __construct() {
        $menu   = Menu::with(['parentMenu'])->GetByRoute('transmittalproperties')->first();
        view()->share('menu_name', 'Category Contractor');
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
            return view('admin.transmittal.categorycontractor.create');
        }
        abort(403);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CategoryContractorRequest $request)
    {
        return DB::transaction(function() use($request) {
            try {
                $data   = [
                    "code"      => strtoupper($request->code),
                    "name"      => $request->name,
                    "address"   => $request->address,
                ];
                $create = CategoryContractor::create($data);
                $create->groups()->sync($request->tagged_group_id);
                if ($request->file('app_logo')) {
                    $extension_file = $request->file('app_logo')->getClientOriginalExtension();
                    $filename   = "{$create->name}_logo.{$extension_file}";
                    $file_dir   = "assets/categorycontractor/$create->code";

                    if (!file_exists($file_dir)) {
                        mkdir($file_dir, 0777, true);
                    }
                    $move       = $request->file('app_logo')->move($file_dir, $filename);
                    if (!$move) {
                        return $this->error("Error upload logo");
                    }
                    $create->logo = "$file_dir/$filename";
                    $create->save();
                }
            } catch (\Illuminate\Database\QueryException $ex) {
                throw new PropertiesException("Error create data: {$ex->errorInfo[2]}", 400);
            }
            return $this->success(Response::HTTP_OK, "Success Create Data", $create, route('transmittalproperties.index'));
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
            $data   = CategoryContractor::with(['groups'])->find($id);
            if ($data) {
                return view('admin.transmittal.categorycontractor.edit', compact('data'));
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
    public function update(CategoryContractorRequest $request, $id)
    {
        return DB::transaction(function() use($request, $id){
            try {
                $update = CategoryContractor::find($id);
                $update->code   = strtoupper($request->code);
                $update->name   = $request->name;
                $update->address= $request->address;
                $update->save();
                $update->groups()->sync($request->tagged_group_id);
                if ($request->file('app_logo')) {
                    $extension_file = $request->file('app_logo')->getClientOriginalExtension();
                    $filename   = "{$update->name}_logo.{$extension_file}";
                    $file_dir   = "assets/categorycontractor/$update->code";

                    if (file_exists("$file_dir/$filename")) {
                        unlink("$file_dir/$filename");
                    }
                    $move       = $request->file('app_logo')->move($file_dir, $filename);
                    if (!$move) {
                        return $this->error("Error upload logo");
                    }
                    $update->logo = "$file_dir/$filename";
                    $update->save();
                }
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
        $destroy    = CategoryContractor::destroy($id);
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
        $address    = $request->address;
        $tagged_group_id    = $request->tagged_group_id;

        // Count Data
        $query      = CategoryContractor::with(['groups'])->when($tagged_group_id, function(\Illuminate\Database\Eloquent\Builder $q) {
            $q->whereHas('groups', function(\Illuminate\Database\Eloquent\Builder $que) {
                $que->where('role_id', request('tagged_group_id'));
            });
        })->when($address, function(\Illuminate\Database\Eloquent\Builder $q) {
            $q->where('address', request('address'));
        })->whereRaw("upper(name) like '%$name%'");

        $row        = clone $query;
        $recordsTotal = $row->get()->count();

        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $categories = $query->get();

        $data       = [];
        foreach ($categories as $key => $category) {
            $category->no   = ++$start;
            $data[]         = $category;
        }
        return response()->json([
            'draw'              => $request->draw,
            'recordsTotal'      => $recordsTotal,
            'recordsFiltered'   => $recordsTotal,
            'data'              => $data,
        ], 200);
    }
}
