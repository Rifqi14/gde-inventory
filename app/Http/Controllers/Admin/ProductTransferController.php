<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductTransfer;
use App\Models\ProductTransferDetail;
use App\Models\ProductTransferDocument;
use App\Models\ProductTransferLog;

use App\Models\Menu;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class ProductTransferController extends Controller
{

    public function __construct()
    {
        $menu   = Menu::where('menu_route', 'producttransfer')->first();
        $parent = Menu::find($menu->parent_id);
        View::share('parent_name', $parent->menu_name);
        View::share('menu_name', $menu->menu_name);
        View::share('menu_active', url('admin/' . 'producttransfer'));
        $this->middleware('accessmenu', ['except' => ['select']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {        
        return view('admin.producttransfer.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {        
        $url = route('producttransfer.store');
        return view('admin.producttransfer.create',compact('url'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = ProductTransfer::with([
            'originsites' => function($site){
                $site->selectRaw("
                    sites.id,
                    sites.name as origin_site
                ");
            },
            'destinationsites' => function($site) {
                $site->selectRaw("
                    sites.id,
                    sites.name as destination_site
                ");
            },
            'originwarehouses' => function($destination){
                $destination->selectRaw("
                    warehouses.id,
                    warehouses.name as origin_warehouse
                ");
            }, 
            'destinationwarehouses' =>  function($destination){
                $destination->selectRaw("
                    warehouses.id,
                    warehouses.name as destination_warehouse
                ");
            },
            'products' => function($product){
                $product->selectRaw("
                    product_transfer_details.*,
                    products.name as product_name,
                    uoms.name as uom_name,
                    product_categories.path as category
                ");
                $product->leftJoin('products','products.id','=','product_transfer_details.product_id');                
                $product->leftJoin('uoms','uoms.id','=','product_transfer_details.uom_id');
                $product->leftJoin('product_categories','product_categories.id','=','products.product_category_id');
            },
            'files' => function($files){
                $files->orderBy('id');
            },
            'images' => function($images){
                $images->orderBy('id');
            },            
        ]);        
        $data->selectRaw("
            product_transfers.*,
            TO_CHAR(product_transfers.date_transfer,'DD/MM/YYYY') as transfer_date
        ");        
        $data = $data->find($id);

        if ($data) {
            $products = [];
            foreach ($data->products as $key => $product) {               
                $product->category = str_replace('->',' <i class="fas fa-angle-right"></i> ',$product->category);
                $products[] = $product;
            }
            $data->products = $products;

            return view('admin.producttransfer.edit',compact('data'));
        }else{
            abort(404);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = ProductTransfer::with([
            'originsites' => function($site){
                $site->selectRaw("
                    sites.id,
                    sites.name as origin_site
                ");
            },
            'destinationsites' => function($site) {
                $site->selectRaw("
                    sites.id,
                    sites.name as destination_site
                ");
            },
            'originwarehouses' => function($destination){
                $destination->selectRaw("
                    warehouses.id,
                    warehouses.name as origin_warehouse
                ");
            }, 
            'destinationwarehouses' =>  function($destination){
                $destination->selectRaw("
                    warehouses.id,
                    warehouses.name as destination_warehouse
                ");
            },
            'products' => function($product){
                $product->selectRaw("
                    product_transfer_details.*,
                    products.name as product_name,
                    uoms.name as uom_name,
                    product_categories.path as category
                ");
                $product->leftJoin('products','products.id','=','product_transfer_details.product_id');                
                $product->leftJoin('uoms','uoms.id','=','product_transfer_details.uom_id');
                $product->leftJoin('product_categories','product_categories.id','=','products.product_category_id');
            },
            'files',
            'images',            
        ]);        
        $data->selectRaw("
            product_transfers.*,
            TO_CHAR(product_transfers.date_transfer,'DD/MM/YYYY') as transfer_date
        ");        
        $data = $data->find($id);

        if($data){
            $products = [];
            foreach ($data->products as $key => $product) {               
                $product->category = str_replace('->',' <i class="fas fa-angle-right"></i> ',$product->category);
                $products[] = $product;
            }
            $data->products = $products;

            return view('admin.producttransfer.detail',compact('data'));
        }else{
            abort(404);
        }
    }    

    public function archive($id){
        $data = ProductTransfer::with([
            'originsites' => function($site){
                $site->selectRaw("
                    sites.id,
                    sites.name as origin_site
                ");
            },
            'destinationsites' => function($site) {
                $site->selectRaw("
                    sites.id,
                    sites.name as destination_site
                ");
            },
            'originwarehouses' => function($destination){
                $destination->selectRaw("
                    warehouses.id,
                    warehouses.name as origin_warehouse
                ");
            }, 
            'destinationwarehouses' =>  function($destination){
                $destination->selectRaw("
                    warehouses.id,
                    warehouses.name as destination_warehouse
                ");
            },
            'products' => function($product){
                $product->selectRaw("
                    product_transfer_details.*,
                    products.name as product_name,
                    uoms.name as uom_name
                ");
                $product->leftJoin('products','products.id','=','product_transfer_details.product_id');                
                $product->leftJoin('uoms','uoms.id','=','product_transfer_details.uom_id');
            },
            'files',
            'images',            
        ]);        
        $data->selectRaw("
            product_transfers.*,
            TO_CHAR(product_transfers.date_transfer,'DD/MM/YYYY') as transfer_date
        ");        
        $data = $data->withTrashed()->find($id);

        if($data){
            return view('admin.producttransfer.detail',compact('data'));
        }else{
            abort(404);
        }
    }

    public function read(Request $request)
    {
        $draw       = $request->draw;
        $start      = $request->start;
        $length     = $request->length;
        $query      = $request->search['value'];
        $sort       = $request->columns[$request->order[0]['column']]['data'];
        $dir        = $request->order[0]['dir'];
        $number     = strtoupper($request->number);
        $issuedBy   = $request->issuedby;
        $startdate  = $request->start_date;
        $finishdate = $request->finish_date;
        $status     = $request->status;

        $query  = ProductTransfer::selectRaw("
            product_transfers.*,
            TO_CHAR(product_transfers.date_transfer,'DD/MM/YYYY') as transfer_date,
            (case 
                when users.employee_id is not null then employees.name 
                else users.name
            end) as issued
        ");
        $query->leftJoin('users','users.id','=','product_transfers.issued_by');        
        $query->leftJoin('employees','employees.id','=','users.employee_id');
        if($startdate && $finishdate){
            $query->whereBetween('product_transfers.date_transfer',[$startdate,$finishdate]);
        }
        if($number){
            $query->whereRaw("upper(product_transfers.transfer_number) like '%$number'");
        }
        if($issuedBy){
            $query->where('employees.id',$issuedBy);
        }
        if($status){
            if($status == 'approved'){
                $query->whereIn('product_transfers.status',['approved','complete']);    
            }else{
                $query->where('product_transfers.status',$status);
            }
        }else{
            $query->whereNotIn('product_transfers.status',['approved','archived']);
        }

        $rows  = clone $query;
        $total = $rows->count();

        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);
        $queries = $query->get();

        $data = [];
        foreach($queries as $key => $row){
            $row->no = ++$start;
            $data[] = $row;
        }

        return response()->json([
            'draw'            => $draw,
            'recordsTotal'    => $total,
            'recordsFiltered' => $total,
            'data'            => $data
        ],200);
    }

    public function readarchived(Request $request)
    {
        $draw       = $request->draw;
        $start      = $request->start;
        $length     = $request->length;
        $query      = $request->search['value'];
        $sort       = $request->columns[$request->order[0]['column']]['data'];
        $dir        = $request->order[0]['dir'];
        $number     = strtoupper($request->number);
        $issuedBy   = $request->issuedby;
        $startdate  = $request->start_date;
        $finishdate = $request->finish_date;
        $status     = $request->status;

        $query = ProductTransfer::withTrashed();
        $query->selectRaw("
            product_transfers.*,
            TO_CHAR(product_transfers.date_transfer,'DD/MM/YYYY') as transfer_date,
            (case 
                when users.employee_id is not null then employees.name 
                else users.name
            end) as issued,
            (case 
                when deleted_at is not null then 'archived'
                else product_transfers.status
            end) as status
        ");
        $query->leftJoin('users','users.id','=','product_transfers.issued_by');        
        $query->leftJoin('employees','employees.id','=','users.employee_id');                
        if($startdate && $finishdate){
            $query->whereBetween('product_transfers.date_transfer',[$startdate,$finishdate]);
        }
        if($number){
            $query->whereRaw("upper(product_transfers.transfer_number) like '%$number'");
        }
        if($issuedBy){
            $query->where('employees.id',$issuedBy);
        }
        $query->whereNotNull('deleted_at');        

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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'origin_unit'           => 'required',
            'origin_warehouse'      => 'required',
            'destination_unit'      => 'required',            
            'destination_warehouse' => 'required',
            'issued_by'             => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ],400);
        }

        $origin_unit            = $request->origin_unit;
        $origin_warehouse       = $request->origin_warehouse;
        $destination_unit       = $request->destination_unit;
        $destination_warehouse  = $request->destination_warehouse;
        $transferdate           = $request->transferdate;
        $issuedBy               = $request->issued_by;
        $description            = $request->description;
        $status                 = $request->status;
        $vehicle_type           = $request->vehicle_type;
        $police_number          = $request->police_number;

        $query = ProductTransfer::create([
            'origin_site_id'            => $origin_unit,
            'destination_site_id'       => $destination_unit,
            'origin_warehouse_id'       => $origin_warehouse,
            'destination_warehouse_id'  => $destination_warehouse,
            'date_transfer'             => $transferdate,
            'issued_by'                 => $issuedBy,
            'description'               => $description,
            'status'                    => $status,
            'vehicle_type'              => $vehicle_type,
            'police_number'             => $police_number,
        ]);        

        if($query){            
            $now           = date('Y-m-d H:i:s');
            $transfer_id   = $query->id;
            $getProducts   = $request->products;
            $documentNames = $request->document_name;
            $photoNames    = $request->photo_name;
            $documents     = [];

            $this->transferNumber($transfer_id,$query->key_number,$query->created_at);

            if($getProducts){
                $products = [];
                foreach (json_decode($getProducts) as $key => $row) {
                    $products[] = [
                        'product_transfer_id' => $transfer_id,
                        'product_id'          => $row->product_id,
                        'product_category_id' => $row->category_id,
                        'uom_id'              => $row->uom_id,     
                        'qty_system'          => $row->qty_system,
                        'qty_requested'        => $row->qty_transfer,
                        'created_at'          => $now,
                        'updated_at'          => $now
                    ];
                }

                $query = ProductTransferDetail::insert($products);
                if(!$query){
                    return response()->json([
                        'status'    => false,
                        'message'   => 'Failed to create detail product of product transfer.'
                    ],400);
                }
            }

            if(isset($documentNames)){                                
                foreach ($documentNames as $key => $row) {
                    $docName = $row;
                    $docFile = $request->file('attachment')[$key];
                    if(isset($docFile)){                        
                        $filename = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower($docName)).'.'.$docFile->getClientOriginalExtension();
                        $path     = "assets/producttransfer/$transfer_id/document";
                        
                        if(!file_exists($path)){
                            mkdir($path,0777,true);
                        }

                        $docFile->move($path,$filename);

                        $documents[] = [
                            'product_transfer_id'  => $transfer_id,
                            'document_name'        => $docName,
                            'file'                 => $filename,
                            'type'                 => 'file',
                            'created_at'           => $now,
                            'updated_at'           => $now
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
                        $path     = "assets/producttransfer/$transfer_id/image";

                        if(!file_exists($path)){
                            mkdir($path,0777,true);
                        }

                        $photoFile->move($path,$filename);

                        $documents[] = [
                            'product_transfer_id'  => $transfer_id,
                            'document_name'        => $photoName,
                            'file'                 => $filename,
                            'type'                 => 'photo',
                            'created_at'           => $now,
                            'updated_at'           => $now
                        ];
                    }
                }
            }

            if(count($documents) > 0    ){
                $query = ProductTransferDocument::insert($documents);
                if(!$query){
                    return response()->json([
                        'status'  => false,
                        'message' => 'Failed to save document file.'
                    ],400);
                }
            } 

            $log = ProductTransferLog::create([
                'product_transfer_id' => $transfer_id,
                'issued_by'           => $issuedBy,
                'log_description'     => 'Product transfer has been created.'
            ]);

            $result = [
                'status'  => true,
                'message' => 'Data has been saved.',
                'point'   => 200
            ];
        }else{
            $result = [
                'status'  => false,
                'message' => 'Failed to create data.',
                'point'   => 400
            ];
        }

        return response()->json([
            'status'  => $result['status'],
            'message' => $result['message']
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
            'origin_unit'           => 'required',
            'origin_warehouse'      => 'required',
            'destination_unit'      => 'required',            
            'destination_warehouse' => 'required',
            'issued_by'             => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ],400);
        }

        $origin_unit            = $request->origin_unit;
        $origin_warehouse       = $request->origin_warehouse;
        $destination_unit       = $request->destination_unit;
        $destination_warehouse  = $request->destination_warehouse;
        $transferdate           = $request->transferdate;
        $issuedBy               = $request->issued_by;
        $description            = $request->description;
        $status                 = $request->status;
        $vehicle_type           = $request->vehicle_type;
        $police_number          = $request->police_number;

        $query = ProductTransfer::find($id);
        $query->date_transfer            = $transferdate;
        $query->origin_site_id           = $origin_unit;
        $query->destination_site_id      = $destination_unit;
        $query->origin_warehouse_id      = $origin_warehouse;
        $query->destination_warehouse_id = $destination_warehouse;
        $query->issued_by                = $issuedBy;
        $query->description              = $description;
        $query->status                   = $status;
        $query->vehicle_type             = $vehicle_type;
        $query->police_number            = $police_number;
        $query->save();

        if($query){
            $now           = date('Y-m-d H:i:s');
            $transfer_id   = $query->id;
            $getProducts   = $request->products;   
            $documentNames = $request->document_name;
            $photoNames    = $request->photo_name;            
            $updateDoc     = $request->documents;
            $deleteDoc     = $request->undocuments;
            $documents     = [];         

            if(isset($documentNames)){                                
                foreach ($documentNames as $key => $row) {
                    $docName = $row;
                    $docFile = $request->file('attachment')[$key];
                    if(isset($docFile)){                        
                        $filename = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower($docName)).'.'.$docFile->getClientOriginalExtension();
                        $path     = "assets/producttransfer/$transfer_id/document";
                        
                        if(!file_exists($path)){
                            mkdir($path,0777,true);
                        }
                        if(file_exists("$path/$filename")){
                            unlink("$path/$filename");
                        }

                        $docFile->move($path,$filename);

                        $documents[] = [
                            'product_transfer_id'  => $transfer_id,
                            'document_name'        => $docName,
                            'file'                 => $filename,
                            'type'                 => 'file',
                            'created_at'           => $now,
                            'updated_at'           => $now
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
                        $path     = "assets/producttransfer/$transfer_id/image";

                        if(!file_exists($path)){
                            mkdir($path,0777,true);
                        }
                        if(file_exists("$path/$filename")){
                            unlink("$path/$filename");
                        }

                        $photoFile->move($path,$filename);

                        $documents[] = [
                            'product_transfer_id'  => $transfer_id,
                            'document_name'        => $photoName,
                            'file'                 => $filename,
                            'type'                 => 'photo',
                            'created_at'           => $now,
                            'updated_at'           => $now
                        ];
                    }
                }
            }

            if(count($documents) > 0    ){
                $query = ProductTransferDocument::insert($documents);
                if(!$query){
                    return response()->json([
                        'status'  => false,
                        'message' => 'Failed to save document file.'
                    ],400);
                }
            } 

            if($getProducts){
                $clear = ProductTransferDetail::where('product_transfer_id',$transfer_id)->delete();    

                $products    = [];
                foreach(json_decode($getProducts) as $key => $row){
                    $products[] = [
                        'product_transfer_id' => $transfer_id,
                        'product_id'          => $row->product_id,
                        'product_category_id' => $row->category_id,
                        'uom_id'              => $row->uom_id,     
                        'qty_system'          => $row->qty_system,
                        'qty_requested'        => $row->qty_transfer,
                        'created_at'          => $now,
                        'updated_at'          => $now
                    ];
                }
                
                $query = ProductTransferDetail::insert($products);
                if(!$query){
                    return response()->json([
                        'status'    => false,
                        'message'   => 'Failed to create detail product of product transfer.'
                    ],400);
                }
            }            

            if($updateDoc){
                foreach (json_decode($updateDoc) as $key => $row) { 
                    $id         = $row->id;
                    $transferid = $row->transferID;
                    $filename   = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower($row->docName));
                    $file       = $row->file;               
                    $type       = $row->type;     
                    $oldfile    = "assets/producttransfer/$transferid/$type/$file";                    
                    $ext        = pathinfo($oldfile)['extension'];
                    $newfile    = "assets/producttransfer/$transferid/$type/$filename.$ext";            

                    $rename = rename($oldfile,$newfile);                    
                    if($rename){
                        $query = ProductTransferDocument::find($id);
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

                $query = ProductTransferDocument::whereIn('id',$id);
                $query->delete();
            }

            $log = ProductTransferLog::create([
                'product_transfer_id' => $transfer_id,
                'issued_by'           => $issuedBy,
                'log_description'     => 'Product transfer has been updated.'
            ]);

            $result = [
                'status'    => true,
                'message'   => 'Successfully updated data.',
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
            $query = ProductTransfer::find($id);
            $query->delete();

            if($query){
                $result = [
                    'status'  => true,
                    'message' => 'Data has been archived.',
                    'point'   => 200
                ];
            }else{
                $result = [
                    'status'  => false,
                    'message' => 'Failed to archive data.',
                    'point'   => 400
                ];
            }

            return response()->json([
                'status'  => $result['status'],
                'message' => $result['message']
            ],$result['point']);

        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'status'  => false,
                'message' => 'Failed to arcvhive data.'
            ],400);
        }
    }

    function transferNumber($id,$key_number,$created_at)
    {
        $key   = preg_split("/[\s-]+/", "$key_number");
        $code  = $key[0];
        $year  = $key[1];
        $index = $key[2];
        $month = date('m',strtotime($created_at));
        
        $number = "$code-$year-$month-$index";

        $query = ProductTransfer::find($id);
        $query->transfer_number = $number;
        $query->save();

        if(!$query){
            return response()->json([
                'statuss' => false,
                'message' => 'Failed to generate transfer number.'
            ],400);
        }
    }
}