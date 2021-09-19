<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Models\ProductConsumable;
use App\Models\ProductConsumableLog;
use App\Models\ProductConsumableDetail;
use App\Models\ProductConsumableDocument;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;

class ProductConsumableController extends Controller
{
    /**
     * Default method when access this controller
     *
     */
    function __construct() {
        $menu       = Menu::GetByRoute('consumable')->first();
        $parent     = Menu::find($menu->parent_id);
        View::share('menu_name', $menu->menu_name);
        View::share('menu_parent', $parent->menu_name);
        View::share('menu_active', url('admin/consumable'));
        $this->middleware('accessmenu', ['except'   => ['select']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (in_array('read', $request->actionmenu)) {
            return view('admin.consumable.index');
        } else {
            abort(403);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (in_array('create', $request->actionmenu)) {
            return view('admin.consumable.create');
        } else {
            abort(403);
        }
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
        if (in_array('update', $request->actionmenu)) {
            $query = ProductConsumable::with([
                'products' => function($product){
                    $product->selectRaw("
                        product_consumable_details.*,
                        products.name as product_name,
                        uoms.name as uom_name,
                        product_categories.path as category
                    ");
                    $product->leftJoin('products','products.id','=','product_consumable_details.product_id');
                    $product->leftJoin('uoms','uoms.id','=','product_consumable_details.uom_id');
                    $product->leftJoin('product_categories','product_categories.id','=','products.product_category_id');
                },
                'files',
                'images'
            ]);
            $query->selectRaw("
                product_consumables.*,
                TO_CHAR(product_consumables.consumable_date,'DD/MM/YYYY') as date_consumable,
                sites.name as site,
                warehouses.name as warehouse
            ");
            $query->leftJoin('sites','sites.id','=','product_consumables.site_id');
            $query->leftJoin('warehouses','warehouses.id','=','product_consumables.warehouse_id');
            $data = $query->find($id);

            if ($data) {
                $products = [];
                foreach($data->products as $product){
                    $product->category = str_replace('->',' <i class="fas fa-angle-right"></i> ',$product->category);                                                                                    
                    $products[] = $product;
                }
                $data->products = $products;

                return view('admin.consumable.edit', compact('data'));
            } else {
                abort(404);
            }
        } else {
            abort(403);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request,$id)
    {
        if (in_array('read', $request->actionmenu)) {
            $data = ProductConsumable::with([
                'products' => function($product){
                    $product->selectRaw("
                        product_consumable_details.*,
                        products.name as product_name,
                        uoms.name as uom_name,
                        product_categories.path as category
                    ");
                    $product->leftJoin('products','products.id','=','product_consumable_details.product_id');
                    $product->leftJoin('uoms','uoms.id','=','product_consumable_details.uom_id');
                    $product->leftJoin('product_categories','product_categories.id','=','products.product_category_id');
                },
                'files',
                'images'
            ]);
            $data->selectRaw("
                product_consumables.*,
                TO_CHAR(product_consumables.consumable_date,'DD/MM/YYYY') as date_consumable,
                sites.name as site,
                warehouses.name as warehouse
            ");
            $data->leftJoin('sites','sites.id','=','product_consumables.site_id');
            $data->leftJoin('warehouses','warehouses.id','=','product_consumables.warehouse_id');
            $data = $data->find($id);

            if ($data) {
                $products = [];
                foreach($data->products as $product){
                    $product->category = str_replace('->',' <i class="fas fa-angle-right"></i> ',$product->category);                                                                                    
                    $products[] = $product;
                }
                $data->products = $products;

                return view('admin.consumable.detail', compact('data'));
            } else {
                abort(404);
            }
        } else {
            abort(403);
        }
    } 

    /**
     * Get data to show in index page
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function read(Request $request)
    {
        $draw       = $request->draw;
        $start      = $request->start;
        $length     = $request->length;
        $query      = $request->search['value'];
        $sort       = $request->columns[$request->order[0]['column']]['data'];
        $dir        = $request->order[0]['dir'];
        $number     = strtoupper($request->number);
        $status     = $request->status;
        $startdate  = $request->startdate;
        $finishdate = $request->finishdate;
        $employee   = $request->employee;

        $query  = ProductConsumable::selectRaw("
            product_consumables.*,
            TO_CHAR(product_consumables.consumable_date,'DD/MM/YYYY') as date_consumable,
            (case
                when users.employee_id is not null then employees.name 
                else null
            end) as issued_by
        ");
        $query->leftJoin('users','users.id','=','product_consumables.issued_by');
        $query->leftJoin('employees','employees.id','=','users.employee_id');
        if($number){
            $query->whereRaw("upper(product_consumables.consumable_number) like '%$number%'");
        }
        if($status){
            if($status == 'approved'){
                $query->whereIn('product_consumables.status',['approved','complete']);
            }else{
                $query->where('product_consumables.status',$status);
            }
        }
        if($startdate && $finishdate){
            $query->whereBetween('product_consumables.consumable_date',[$startdate,$finishdate]);
        }
        if($employee){
            $query->where('users.employee_id','=',$employee);
        }
        $query->orderBy('product_consumables.id','desc');

        $rows  = clone $query;
        $total = $rows->count();

        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);

        $queries = $query->get();

        $data = [];
        foreach ($queries as $key => $row) {
            $row->no = ++$start;            
            $data[]  = $row;
        }

        return response()->json([
            'draw'              => $draw,
            'recordsTotal'      => $total,
            'recordsFiltered'   => $total,
            'data'              => $data
        ],200);
    }

    /**
     * Get data to show in select2
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function select(Request $request)
    {
        # code...
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {        
       $validator = Validator::make($request->all(),[
            'site'             => 'required',            
            'warehouse'        => 'required',
            'consumedate'      => 'required',
            'issued_by'        => 'required',
            'status'           => 'required'
       ]);

       if($validator->fails()){
           return response()->json([
               'status'     => false,
               'message'    => $validator->errors()->first()
           ],400);
       }

       $dateconsume     = $request->consumedate;
       $site            = $request->site;
       $warehouse       = $request->warehouse;       
       $issuedby        = $request->issued_by;
       $description     = $request->description;
       $status          = $request->status;

       $query = ProductConsumable::create([
           'site_id'         => $site,
           'warehouse_id'    => $warehouse,
           'issued_by'       => $issuedby,
           'consumable_date' => $dateconsume,
           'status'          => $status,
           'description'     => $description
       ]);

       if($query){
        $now           = date('Y-m-d H:i:s');
        $consumable_id = $query->id;
        $issuedby      = $query->issued_by;
        $key_number    = $query->key_number;
        $created_at    = $query->created_at;

        $this->consumeNumber($consumable_id,$key_number,$created_at);
        
        $getProducts   = $request->products;
        $documentNames = $request->document_name;
        $photoNames    = $request->photo_name;
        $documents     = [];

        if(isset($getProducts)){
            $consumable_id = $consumable_id;
            $products = [];
            foreach (json_decode($getProducts) as $key => $row) {
                $products[] = [
                    'product_consumable_id' => $consumable_id,
                    'product_id'            => $row->product_id,
                    'product_category_id'   => $row->category_id,
                    'uom_id'                => $row->uom_id,
                    'qty_system'            => $row->qty_system,
                    'qty_consume'           => $row->qty_consume,
                    'created_at'            => $now,
                    'updated_at'            => $now
                ];
            }            

            if(count($products) > 0){
                $query = ProductConsumableDetail::insert($products);
                if(!$query){                                        
                    return response()->json([
                        'status'    => false,
                        'message'   => "Failed to create detail of consumable."
                    ],400);
                }
            }
        }

        if(isset($documentNames)){                                
            foreach ($documentNames as $key => $row) {
                $docName = $row;
                $docFile = $request->file('attachment')[$key];
                if(isset($docFile)){                        
                    $filename = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower($docName)).'.'.$docFile->getClientOriginalExtension();
                    $path     = "assets/consumable/$consumable_id/document";
                    
                    if(!file_exists($path)){
                        mkdir($path,0777,true);
                    }

                    $docFile->move($path,$filename);

                    $documents[] = [
                        'product_consumable_id'  => $consumable_id,
                        'document_name'          => $docName,
                        'file'                   => $filename,
                        'type'                   => 'file',
                        'created_at'             => $now,
                        'updated_at'             => $now
                    ];
                }                    
            }                                             
        }

        if(isset($photoNames)){
            foreach ($photoNames as $key => $row) {
                $photoName = $row;
                $photoFile = $request->file('photo')[$key];
                if(isset($photoFile)){                        
                    $filename = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower($photoName)).'.'.$photoFile->getClientOriginalExtension();
                    $path     = "assets/consumable/$consumable_id/image";

                    if(!file_exists($path)){
                        mkdir($path,0777,true);
                    }

                    $photoFile->move($path,$filename);

                    $documents[] = [
                        'product_consumable_id'  => $consumable_id,
                        'document_name'          => $photoName,
                        'file'                   => $filename,
                        'type'                   => 'photo',
                        'created_at'             => $now,
                        'updated_at'             => $now
                    ];
                }
            }
        }

        if(count($documents) > 0    ){
            $query = ProductConsumableDocument::insert($documents);
            if(!$query){
                return response()->json([
                    'status'  => false,
                    'message' => 'Failed to save document file.'
                ],400);
            }
        }        

        $log = ProductConsumableLog::create([
            'product_consumable_id' => $consumable_id,
            'issued_by'             => $issuedby,
            'log_description'       => 'Consumable has been created.'
        ]);

        $result = [
            'status'    => true,
            'message'   => 'Data has been saved.',
            'point'     => 200
        ];
       }else{
        $result = [
            'status'    =>  false,
            'message'   => 'Failed to create data.',
            'point'     => 400
        ];
       }
       
       return response()->json([
           'status'     => $result['status'],
           'message'    => $result['message']
       ],$result['point']);
       
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
        $validator = Validator::make($request->all(),[
            'site'             => 'required',            
            'warehouse'        => 'required',
            'consumedate'      => 'required',
            'issued_by'        => 'required',
            'status'           => 'required'
       ]);

       if($validator->fails()){
           return response()->json([
               'status'     => false,
               'message'    => $validator->errors()->first()
           ],400);
       }

       $dateconsume     = $request->consumedate;
       $site            = $request->site;
       $warehouse       = $request->warehouse;       
       $issuedby        = $request->issued_by;
       $description     = $request->description;
       $status          = $request->status;

       $query = ProductConsumable::find($id);
       $query->consumable_date = $dateconsume;
       $query->site_id         = $site;
       $query->warehouse_id    = $warehouse;
       $query->issued_by       = $issuedby;
       $query->description     = $description;
       $query->status          = $status;
       $query->save();

       if ($query) {
            $now           = date('Y-m-d H:i:s');
            $consumable_id = $query->id;
            $getProducts   = $request->products;
            $documentNames = $request->document_name;
            $photoNames    = $request->photo_name;            
            $updateDoc     = $request->documents;
            $deleteDoc     = $request->undocuments;
            $documents     = [];         

            $clear = ProductConsumableDetail::where('product_consumable_details.product_consumable_id',$consumable_id)->delete();

            if($getProducts){
                $products = [];
                foreach (json_decode($getProducts) as $key => $row) {
                    $products[] = [
                        'product_consumable_id' => $consumable_id,
                        'product_id'            => $row->product_id,
                        'product_category_id'   => $row->category_id,
                        'uom_id'                => $row->uom_id,
                        'qty_system'            => $row->qty_system,
                        'qty_consume'           => $row->qty_consume,
                        'created_at'            => $now,
                        'updated_at'            => $now
                    ];
                }

                if(count($products) > 0){
                    $query = ProductConsumableDetail::insert($products);
                    
                    if(!$query){
                        return response()->json([
                            'status'    => false,
                            'message'   => 'Failed to update detail of consumable.'
                        ],400);
                    }
                }
            }

            if(isset($documentNames)){                                
                foreach ($documentNames as $key => $row) {
                    $docName = $row;
                    $docFile = $request->file('attachment')[$key];
                    if(isset($docFile)){                        
                        $filename = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower($docName)).'.'.$docFile->getClientOriginalExtension();
                        $path     = "assets/consumable/$consumable_id/document";
                        
                        if(!file_exists($path)){
                            mkdir($path,0777,true);
                        }
                        if(file_exists("$path/$filename")){
                            unlink("$path/$filename");
                        }

                        $docFile->move($path,$filename);

                        $documents[] = [
                            'product_consumable_id' => $consumable_id,
                            'document_name'         => $docName,
                            'file'                  => $filename,
                            'type'                  => 'file',
                            'created_at'            => $now,
                            'updated_at'            => $now
                        ];
                    }                    
                }                                             
            }

            if(isset($photoNames)){
                foreach ($photoNames as $key => $row) {
                    $photoName = $row;
                    $photoFile = $request->file('photo')[$key];
                    if(isset($photoFile)){                        
                        $filename = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower($photoName)).'.'.$photoFile->getClientOriginalExtension();
                        $path     = "assets/consumable/$consumable_id/image";

                        if(!file_exists($path)){
                            mkdir($path,0777,true);
                        }
                        if(file_exists("$path/$filename")){
                            unlink("$path/$filename");
                        }

                        $photoFile->move($path,$filename);

                        $documents[] = [
                            'product_consumable_id' => $consumable_id,
                            'document_name'         => $photoName,
                            'file'                  => $filename,
                            'type'                  => 'photo',
                            'created_at'            => $now,
                            'updated_at'            => $now
                        ];
                    }
                }
            }

            if(count($documents) > 0    ){
                $query = ProductConsumableDocument::insert($documents);
                if(!$query){
                    return response()->json([
                        'status'  => false,
                        'message' => 'Failed to save document file.'
                    ],400);
                }
            }

            if($updateDoc){
                foreach (json_decode($updateDoc) as $key => $row) { 
                    $id         = $row->id;
                    $consumeid  = $row->consumeID;
                    $filename   = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower($row->docName));
                    $file       = $row->file;               
                    $type       = $row->type;     
                    $oldfile    = "assets/consumable/$consumeid/$type/$file";                    
                    $ext        = pathinfo($oldfile)['extension'];
                    $newfile    = "assets/consumable/$consumeid/$type/$filename.$ext";            

                    $rename = rename($oldfile,$newfile);                    
                    if($rename){
                        $query = ProductConsumableDocument::find($id);
                        $query->document_name = $row->docName;
                        $query->file          = "$filename.$ext";
                        $query->save();
                    }
                }
            }

            if($deleteDoc){
                $id = [];
                foreach(json_decode($deleteDoc) as $key => $row){                    
                    array_push($id,$row->id);
                }

                $query = ProductConsumableDocument::whereIn('id',$id);
                $query->delete();
            }

            $log = ProductConsumableLog::create([
                'product_consumable_id' => $consumable_id,
                'issued_by'             => $issuedby,
                'log_description'       => 'Consumable has been updated.'
            ]);

           $result = [
                'status'    => true,
                'message'   => 'Successfully to update data.',
                'point'     => 200
           ];
       }else{
            $result = [
                'status'    => false,
                'message'   => 'Failed to update data.',
                'point'     => 400
            ];
       }

        return response()->json([
            'status'    => $result['status'],
            'message'   => $result['message']
        ],$result['point']);
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
            $query = ProductConsumable::find($id)->delete();            
        } catch (\Illuminate\Database\QueryException $ex) {
            return response()->json([
                'status'    => false,
                'message'   => "Error delete data {$ex->errorInfo[2]}",
            ], 400);
        }
        return response()->json([
            'status'    => true,
            'message'   => "Success delete data"
        ], 200);
    }    

    function consumeNumber($id,$key_number,$created_at)
    {
        $key   = preg_split("/[\s-]+/", "$key_number");
        $code  = $key[0];
        $year  = $key[1];
        $index = $key[2];
        $month = date('m',strtotime($created_at));
        
        $number = "$code-$year-$month-$index";

        $query = ProductConsumable::find($id);
        $query->consumable_number = $number;
        $query->save();

        if(!$query){
            return response()->json([
                'statuss' => false,
                'message' => 'Failed to generate transfer number.'
            ],400);
        }
    }
}