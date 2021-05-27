<?php

namespace App\Http\Controllers\Admin;

use App\Models\ProductBorrowing;
use App\Models\ProductBorrowingLog; // Model for log action
use App\Models\ProductBorrowingDetail; // Model for detail product
use App\Models\ProductBorrowingDocument; // Model for supporting document

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Validator;
class ProductBorrowingController extends Controller
{    

    function __construct()
    {
        View::share('menu_active', url('admin/'.'productborrowing'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {            
        return view('admin.productborrowing.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $url = route('productborrowing.store');
        return view('admin.productborrowing.create',compact('url'));
    }    

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = ProductBorrowing::with([
                    'files',
                    'images',
                    'products' => function($product){
                        $product->selectRaw("
                            product_borrowing_details.*,
                            products.name as product_name,
                            uoms.name as uom_name
                        ");
                        $product->leftJoin('products','products.id','=','product_borrowing_details.product_id');
                        $product->leftJoin('product_uoms',function ($join)
                        {
                            $join->on('product_uoms.product_id','=','product_borrowing_details.product_id');
                            $join->on('product_uoms.id','=','product_borrowing_details.uom_id');
                        });
                        $product->leftJoin('uoms','uoms.id','=','product_uoms.uom_id');
                    }
                ])
                ->selectRaw("
                    product_borrowings.*,
                    TO_CHAR(product_borrowings.borrowing_date,'DD/MM/YYYY') as date_borrowing,
                    sites.name as site_name,
                    warehouses.name as warehouse_name,
                    product_categories.name as category_name,
                    users.name as issued_name
                ")
                ->leftJoin('sites','sites.id','=','product_borrowings.site_id')
                ->leftJoin('warehouses','warehouses.id','=','product_borrowings.warehouse_id')
                ->leftJoin('product_categories','product_categories.id','=','product_borrowings.product_category_id')
                ->leftJoin('users','users.id','=','product_borrowings.issued_by')
                ->find($id);           

        if($data){
            return view('admin.productborrowing.detail',compact('data'));   
        }else{
            abort(404);
        }        
    }

    public function archive($id)
    {
        $data = ProductBorrowing::with([
                    'files',
                    'images',
                    'products' => function($product){
                        $product->selectRaw("
                            product_borrowing_details.*,
                            products.name as product_name,
                            uoms.name as uom_name
                        ");
                        $product->leftJoin('products','products.id','=','product_borrowing_details.product_id');
                        $product->leftJoin('product_uoms',function ($join)
                        {
                            $join->on('product_uoms.product_id','=','product_borrowing_details.product_id');
                            $join->on('product_uoms.id','=','product_borrowing_details.uom_id');
                        });
                        $product->leftJoin('uoms','uoms.id','=','product_uoms.uom_id');
                    }
                ])
                ->selectRaw("
                    product_borrowings.*,
                    TO_CHAR(product_borrowings.borrowing_date,'DD/MM/YYYY') as date_borrowing,
                    sites.name as site_name,
                    warehouses.name as warehouse_name,
                    product_categories.name as category_name,
                    users.name as issued_name
                ")
                ->leftJoin('sites','sites.id','=','product_borrowings.site_id')
                ->leftJoin('warehouses','warehouses.id','=','product_borrowings.warehouse_id')
                ->leftJoin('product_categories','product_categories.id','=','product_borrowings.product_category_id')
                ->leftJoin('users','users.id','=','product_borrowings.issued_by')
                ->withTrashed()
                ->find($id);                

        if($data){
            return view('admin.productborrowing.detail',compact('data'));   
        }else{
            abort(404);
        }        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {        
        $data = ProductBorrowing::with([
                    'files',
                    'images',
                    'products' => function($product){
                        $product->selectRaw("
                            product_borrowing_details.*,
                            products.name as product_name,
                            uoms.name as uom_name
                        ");
                        $product->leftJoin('products','products.id','=','product_borrowing_details.product_id');
                        $product->leftJoin('product_uoms',function ($join)
                        {
                            $join->on('product_uoms.product_id','=','product_borrowing_details.product_id');
                            $join->on('product_uoms.id','=','product_borrowing_details.uom_id');
                        });
                        $product->leftJoin('uoms','uoms.id','=','product_uoms.uom_id');
                    }
                ])
                ->selectRaw("
                    product_borrowings.*,
                    TO_CHAR(product_borrowings.borrowing_date,'DD/MM/YYYY') as date_borrowing,
                    sites.name as site_name,
                    warehouses.name as warehouse_name,
                    product_categories.name as category_name,
                    users.name as issued_name
                ")
                ->leftJoin('sites','sites.id','=','product_borrowings.site_id')
                ->leftJoin('warehouses','warehouses.id','=','product_borrowings.warehouse_id')
                ->leftJoin('product_categories','product_categories.id','=','product_borrowings.product_category_id')
                ->leftJoin('users','users.id','=','product_borrowings.issued_by')
                ->find($id);        
                
        if($data){
            return view('admin.productborrowing.edit',compact('data'));
        }else{
            abort(404);
        }
    }

    public function read(Request $request)
    {
        $draw               = $request->draw;
        $start              = $request->start;
        $length             = $request->length;
        $search             = $request->search['value'];
        $sort               = $request->columns[$request->order[0]['column']]['data'];
        $dir                = $request->order[0]['dir'];
        $number             = strtoupper($request->number);
        $issuedby           = strtoupper($request->issuedby);
        $startDate          = $request->start_date;
        $finishDate         = $request->finish_date;
        $status             = $request->status;

        $query = ProductBorrowing::selectRaw("
            product_borrowings.*,
            TO_CHAR(product_borrowings.borrowing_date,'DD/MM/YYYY') as borrowing_date,
            users.name as issued
        ");        
        $query->leftJoin('users','users.id','=','product_borrowings.issued_by');
        if($number){
            $query->whereRaw("upper(product_borrowings.borrowing_number) like '%$number%'");
        }
        if($issuedby){
            $query->whereRaw("upper(users.name) like '%$issuedby%'");
        }
        if($startDate && $finishDate){
            $query->whereBetween('borrowing_date',[$startDate,$finishDate]);
        }
        if($status){
            $query->where('status',$status);
        }else{
            $query->whereNotIn('status',['approved','archived']);
        }        
        
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

    public function readarchived(Request $request)
    {
        $draw               = $request->draw;
        $start              = $request->start;
        $length             = $request->length;
        $search             = $request->search['value'];
        $sort               = $request->columns[$request->order[0]['column']]['data'];
        $dir                = $request->order[0]['dir'];

        $query = ProductBorrowing::withTrashed();
        $query->selectRaw("
            product_borrowings.*,
            TO_CHAR(product_borrowings.borrowing_date,'DD/MM/YYYY') as borrowing_date,
            users.name as issued
        ");        
        $query->leftJoin('users','users.id','=','product_borrowings.issued_by');
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
            'borrowing_number' => 'required|unique:product_borrowings,borrowing_number',
            'product_category' => 'required',
            'site'             => 'required',
            'warehouse'        => 'required',
            'issuedby'         => 'required',
            'dateborrowing'    => 'required',            
        ]);

        if($validator->fails()){
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first(),
            ],400);
        }                              

        $number          = $request->borrowing_number;
        $productcategory = $request->product_category;
        $site            = $request->site;
        $warehouse       = $request->warehouse;
        $issuedby        = $request->issuedby;
        $borrowingdate   = $request->dateborrowing;
        $description     = $request->description;
        $status          = $request->status;

        $query = ProductBorrowing::create([
            'borrowing_number'      => $number,
            'product_category_id'   => $productcategory,
            'site_id'               => $site,
            'warehouse_id'          => $warehouse,
            'issued_by'             => $issuedby,
            'borrowing_date'        => $borrowingdate,
            'description'           => $description,
            'status'                => $status
        ]);                    
        
        if ($query) {
            $now           = date('Y-m-d H:i:s');
            $borrowing_id  = $query->id;
            $getProducts   = $request->products;                        
            $documentNames = $request->document_name;    
            $photoNames    = $request->photo_name;       
            $documents     = [];             

            if($getProducts){
                $products = [];
                foreach (json_decode($getProducts) as $key => $row) {
                    $products[] = [
                        'product_borrowing_id' => $borrowing_id,
                        'product_id'           => $row->product_id,
                        'product_category_id'  => $row->category_id,
                        'uom_id'               => $row->uom_id,
                        'qty_system'           => $row->qty_system,
                        'qty_requested'        => $row->qty_requested,
                        'created_at'           => $now,
                        'updated_at'           => $now
                    ];
                }

                $query = ProductBorrowingDetail::insert($products);
                if(!$query){
                    return response()->json([
                        'status'  => false,
                        'message' => 'Failed to create detail product of product borrowig.'
                    ],400);
                }
            }            
    
            if(isset($documentNames)){                                
                foreach ($documentNames as $key => $row) {
                    $docName = $row;
                    $docFile = $request->file('attachment')[$key];
                    if(isset($docFile)){                        
                        $filename = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower($docName)).'.'.$docFile->getClientOriginalExtension();
                        $path     = "assets/productborrowing/$borrowing_id/document";
                        
                        if(!file_exists($path)){
                            mkdir($path,0777,true);
                        }

                        $docFile->move($path,$filename);

                        $documents[] = [
                            'product_borrowing_id' => $borrowing_id,
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
                        $path     = "assets/productborrowing/$borrowing_id/image";

                        if(!file_exists($path)){
                            mkdir($path,0777,true);
                        }

                        $photoFile->move($path,$filename);

                        $documents[] = [
                            'product_borrowing_id' => $borrowing_id,
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
                $query = ProductBorrowingDocument::insert($documents);
                if(!$query){
                    return response()->json([
                        'status'  => false,
                        'message' => 'Failed to save document file.'
                    ],400);
                }
            }   

            $log = ProductBorrowingLog::create([
                'product_borrowing_id' => $borrowing_id,
                'issued_by'            => $issuedby,
                'log_description'      => 'Product borrowing has been created.',            
            ]);
            
            $return = [
                'status'    => true,
                'message'   => 'Data has been saved.',
                'point'     => 200
            ];
        }else{
            $return = [
                'status'    => false,
                'message'   => 'Failed to create data.',
                'point'     => 400
            ];
        }

        return response()->json([
            'status'    => $return['status'],
            'message'   => $return['message']
        ],$return['point']);
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
            'borrowing_number' => 'required|unique:product_borrowings,borrowing_number,'.$id,
            'product_category' => 'required',
            'site'             => 'required',
            'warehouse'        => 'required',
            'issuedby'         => 'required',
            'dateborrowing'    => 'required',            
        ]);   

        if($validator->fails()){
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ],400);
        }

        $number          = $request->borrowing_number;
        $productcategory = $request->product_category;
        $site            = $request->site;
        $warehouse       = $request->warehouse;
        $issuedby        = $request->issuedby;
        $borrowingdate   = $request->dateborrowing;
        $description     = $request->description;
        $status          = $request->status;

        $query = ProductBorrowing::find($id);
        $query->borrowing_number    = $number;
        $query->product_category_id = $productcategory;
        $query->site_id             = $site;
        $query->warehouse_id        = $warehouse;
        $query->issued_by           = $issuedby;
        $query->borrowing_date      = $borrowingdate;
        $query->description         = $description;
        $query->status              = $status;
        $query->save();

        if($query){
            $cleared = ProductBorrowingDetail::where('product_borrowing_id',$query->id);
            $cleared->delete();
            if(!$cleared){
                return response()->json([
                    'status'  => false,
                    'message' => 'Failed to clear detail borrowing product.'
                ],400);
            }

            $now           = date('Y-m-d H:i:s');  
            $borrowing_id  = $query->id;          
            $getProducts   = $request->products;  
            $documentNames = $request->document_name;
            $photoNames    = $request->photo_name;
            $updatedDoc    = $request->documents;

            if($getProducts){
                $products = [];
                foreach (json_decode($getProducts) as $key => $row) {
                    $products[] = [
                        'product_borrowing_id' => $id,
                        'product_id'           => $row->product_id,
                        'product_category_id'  => $row->category_id,
                        'uom_id'               => $row->uom_id,
                        'qty_system'           => $row->qty_system,
                        'qty_requested'        => $row->qty_requested,
                        'created_at'           => $now,
                        'updated_at'           => $now
                    ];
                }

                $query = ProductBorrowingDetail::insert($products);
                if(!$query){
                    return response()->json([
                        'status'  => false,
                        'message' => 'Failed to create detail product of product borrowig.'
                    ],400);
                }
            }

            $documents = [];
            if($documentNames){                
                foreach ($documentNames as $key => $row) {
                    $docName  = $row;
                    $docFile  = $request->file('attachment')[$key];
                    if(isset($docFile)) {
                        $filename = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower($docName)).'.'.$docFile->getClientOriginalExtension();
                        $path     = "assets/productborrowing/$borrowing_id/document";                        
                        
                        if(!file_exists($path)){
                            mkdir($path,0777,true);
                        }

                        $docFile->move($path,$filename);

                        $documents[] = [
                            'product_borrowing_id' => $borrowing_id,
                            'document_name'        => $docName,
                            'file'                 => $filename,
                            'type'                 => 'file',
                            'created_at'           => $now,
                            'updated_at'           => $now
                        ];
                    }
                }                                                  
            }

            if($photoNames){
                foreach ($photoNames as $key => $row) {
                    $photoName = $row;
                    $photoFile = $request->file('photo')[$key];
                    if(isset($photoFile)){
                        $filename = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower($photoName)).'.'.$photoFile->getClientOriginalExtension();
                        $path     = "assets/productborrowing/$borrowing_id/image";
                        
                        if(!file_exists($path)){
                            mkdir($path,0777,true);
                        }

                        $photoFile->move($path,$filename);
                        $documents[] = [
                            'product_borrowing_id' => $borrowing_id,
                            'document_name'        => $photoName,
                            'file'                 => $filename,
                            'type'                 => 'photo',
                            'created_at'           => $now,
                            'updated_at'           => $now
                        ];
                    }
                }
            }

            if(count($documents) > 0){
                $query = ProductBorrowingDocument::insert($documents);
                if(!$query){
                    return response()->json([
                        'status'    => false,
                        'message '  => 'Failed to update file supporting document.'
                    ],400);
                }
            }

            if($updatedDoc){
                foreach(json_decode($updatedDoc) as $key => $row){
                    $id       = $row->id;
                    $docName  = $row->docName;
                    $path     = $row->path;                                        
                    $dir      = pathinfo($path)['dirname'];
                    $filename = pathinfo($path)['filename'];
                    $basename = pathinfo($path)['basename'];
                    $ext      = pathinfo($path)['extension'];                    
                    $file     = "$dir/$filename";

                    if(file_exists($file)){                        
                        $docName  = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower($docName)).'.'.$ext;
                        $newname  = "$docName.$ext";
                        
                        $changename = rename($basename,$newname);

                        if($changename){
                            $query = ProductBorrowingDocument::find($id);
                            $query->document_name = $row->docName;
                            $query->file          = $newname;
                            $query->save();
                        }
                    }
                }
            }

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
            $query = ProductBorrowing::find($id);
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
                'message' => 'Failed to remove data.'
            ],400);
        }
    }    
}
