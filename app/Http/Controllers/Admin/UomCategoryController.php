<?php
namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\UomCategory;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UomCategoryController extends Controller
{
    public function __construct()
    {
        $menu       = Menu::GetByRoute('uomcategory')->first();
        $parent     = Menu::parent($menu->parent_id)->first();
        View::share('menu_name', $menu->menu_name);
        View::share('parent_name', $parent->menu_name);
        View::share('menu_active',url('admin/'.'uomcategory'));
    }

    public function index()
    {
        return view('admin.uomcategory.index');
    }

    public function create()
    {
        $url = route('uomcategory.store');        
        return view('admin.uomcategory.create',compact('url'));
    }

    public function read(Request $request)
    {
        $draw   = $request->draw;
        $start  = $request->start;
        $length = $request->length;
        $query  = $request->search['value'];
        $sort   = $request->columns[$request->order[0]['column']]['data'];
        $dir    = $request->order[0]['dir'];
        $code   = strtoupper($request->code);
        $name   = strtoupper($request->name);        

        $query = UomCategory::whereRaw("upper(code) like '%$code%' and upper(name) like '%$name%'");

        $rows  = clone $query;
        $total = $rows->count();

        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);

        $uomcats = $query->get();

        $data = [];
        foreach ($uomcats as $key => $row) {
            $row->no = ++$start;
            $data[]  = $row;
        }

        return response()->json([
            'draw'            => $draw,
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $data
        ],200);
    }

    public function edit($id)
    {
        $uomcat = UomCategory::find($id);        
        if ($uomcat) {
            $data = $uomcat;
            $url  = route('uomcategory.update',['id' => $id]);
            return view('admin.uomcategory.edit',compact('data','url'));   
        }else{
            abort(404);
        }        
    }

    public function store(Request $request)
    {        
        $validator = Validator::make($request->all(),[
            'code' => 'required|unique:uom_categories,code|regex:/(^([a-z]+)(\d+)?$)/u',
            'name' => 'required'
        ], [
            'code.regex' => "Code must be only alphabet in lowercase only",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ],400);
        }        

        $uomcat = UomCategory::create([
            'code'  => $request->code,
            'name'  => $request->name
        ]);

        if ($uomcat) {
            $result['status'] = true;
            $result['message'] = 'Successfully insert data.';
            $result['point']   = 200;
        }else{
            $result['status'] = false;
            $result['message'] = 'Failed to insert data.';
            $result['point']   = 400;
        }

        return response()->json([
            'status'  => $result['status'],
            'message' => $result['message'],
            'results' => route('uomcategory.index')
        ],$result['point']);
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'code' 	=> 'required|regex:regex:/(^([a-z]+)(\d+)?$)/u|unique:uom_categories,code,'.$id,
            'name' 	=> 'required'
        ], [
            'code.regex' => "Code must be only alphabet in lowercase only",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ],400);
        }

        $code = $request->code;
        $name = $request->name;
        
        $uomcat = UomCategory::find($id);
        $uomcat->code = $code;
        $uomcat->name = $name;
        $uomcat->save();

        if ($uomcat) {
            $result = [
                'status'  => true,
                'message' => 'Successfully updated data.',
                'point'   => 200
            ];
        }else{
            $result = [
                'status'  => false,
                'message' => 'Failed to update data.',
                'point'   => 400
            ];
        }

        return response()->json([
            'status'   => $result['status'],
            'message'  => $result['message'],
            'results'  => route('uomcategory.index')
        ],$result['point']);
    }

    public function destroy($id)
    {
        try {
            $uomcats = UomCategory::find($id);
            $uomcats->delete();

            if ($uomcats) {
                $result = [
                    'status'  => true,
                    'message' => 'Data hase been removed.',
                    'point'   => 200
                ];
            }else{
                $result = [
                    'status'  => false,
                    'message' => 'Data hase been removed.',
                    'point'   => 400
                ];
            }

            return response()->json([
                'status'  => $result['status'],
                'message' => $result['message']
            ],$result['point']);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status' => false                
            ], 400);
        }
    }

    public function select(Request $request){
        $start = $request->page ? $request->page - 1 : 0;
        $length = $request->limit;
        $name = strtoupper($request->name);
        $selected = $request->selected;

        //Count Data
        $query = UomCategory::select('*');
        $query->whereRaw("upper(name) like '%$name%'");
        if($selected){
            $query->whereNotIn('id', $selected);
        }

        $row = clone $query;
        $recordsTotal = $row->count();

        $query->offset($start);
        $query->limit($length);
        $uoms = $query->get();

        $data = [];
        foreach ($uoms as $uom) {
            $uom->no = ++$start;
            $data[] = $uom;
        }
        return response()->json([
            'total' => $recordsTotal,
            'rows' => $data
        ], 200);
    }
}