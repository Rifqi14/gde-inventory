<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Database\QueryException;

use App\Models\Menu;
use App\Models\GoodsIssue;
use App\Models\GoodsIssueProduct;
use App\Models\GoodsIssueDocument;
use App\Models\GoodsIssueSerial;
use App\Models\ProductBorrowing;
use App\Models\ProductConsumableDetail;
use App\Models\ProductTransferDetail;
use App\Models\ProductBorrowingDetail;
use App\Models\ProductSerial;
use App\Models\StockWarehouse;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadSheet\Worksheet\HeaderFooter;
use PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooterDrawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\IOFactory;

class GoodsIssueController extends Controller
{

    /**
     * Define default method when access this controller
     */
    public function __construct()
    {
        $menu   = Menu::where('menu_route', 'goodsissue')->first();
        $parent = Menu::find($menu->parent_id);
        View::share('parent_name', $parent->menu_name);
        View::share('menu_name', $menu->menu_name);
        View::share('menu_active', url('admin/goodsissue'));
        $this->middleware('accessmenu', ['except' => ['select']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.goodsissue.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (in_array('create', $request->actionmenu)) {
            return view('admin.goodsissue.create');
        } else {
            abort(403);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        if (in_array('update', $request->actionmenu)) {

            $query = GoodsIssue::with([
                'consumableproducts' => function($products){
                    $products->selectRaw("
                        goods_issue_products.*,
                        products.name as product,
                        products.is_serial,
                        product_categories.path as category,
                        uoms.name as uom,                    
                        product_consumables.consumable_number as reference,
                        rack_warehouses.name as rack,
                        bin_warehouses.name as bin
                    ");
                    $products->leftJoin('products','products.id','=','goods_issue_products.product_id');
                    $products->leftJoin('product_categories','product_categories.id','=','products.product_category_id');
                    $products->leftJoin('uoms','uoms.id','=','goods_issue_products.uom_id');
                    $products->leftJoin('rack_warehouses', 'rack_warehouses.id', '=', 'goods_issue_products.rack_id');
                    $products->leftJoin('bin_warehouses', 'bin_warehouses.id', '=', 'goods_issue_products.bin_id');
                    $products->leftJoin('product_consumables','product_consumables.id','=','goods_issue_products.reference_id');
                },
                'transferproducts' => function($products){
                    $products->selectRaw("
                        goods_issue_products.*,
                        products.name as product,
                        products.is_serial,
                        product_categories.path as category,
                        uoms.name as uom,                    
                        product_transfers.transfer_number as reference,
                        rack_warehouses.name as rack,
                        bin_warehouses.name as bin
                    ");
                    $products->leftJoin('products','products.id','=','goods_issue_products.product_id');
                    $products->leftJoin('product_categories','product_categories.id','=','products.product_category_id');
                    $products->leftJoin('uoms','uoms.id','=','goods_issue_products.uom_id');
                    $products->leftJoin('rack_warehouses', 'rack_warehouses.id', '=', 'goods_issue_products.rack_id');
                    $products->leftJoin('bin_warehouses', 'bin_warehouses.id', '=', 'goods_issue_products.bin_id');
                    $products->leftJoin('product_transfers','product_transfers.id','=','goods_issue_products.reference_id');
                },
                'borrowingproducts' => function($products){
                    $products->with([
                        'serials' => function($serial){
                            $serial->selectRaw("
                                goods_issue_serials.*,
                                product_serials.serial_number
                            ");
                            $serial->leftJoin('product_serials','product_serials.id','=','goods_issue_serials.serial_id');
                        }
                    ]);
                    $products->selectRaw("
                        goods_issue_products.*,
                        products.name as product,
                        products.is_serial,
                        product_categories.path as category,
                        uoms.name as uom,                    
                        product_borrowings.borrowing_number as reference,
                        rack_warehouses.name as rack,
                        bin_warehouses.name as bin
                    ");
                    $products->leftJoin('products','products.id','=','goods_issue_products.product_id');
                    $products->leftJoin('product_categories','product_categories.id','=','products.product_category_id');
                    $products->leftJoin('uoms','uoms.id','=','goods_issue_products.uom_id');
                    $products->leftJoin('rack_warehouses', 'rack_warehouses.id', '=', 'goods_issue_products.rack_id');
                    $products->leftJoin('bin_warehouses', 'bin_warehouses.id', '=', 'goods_issue_products.bin_id');
                    $products->leftJoin('product_borrowings','product_borrowings.id','=','goods_issue_products.reference_id');
                },
                'files',
                'images'
            ]);
            $query->selectRaw("
                goods_issues.*,
                TO_CHAR(goods_issues.date_issued,'DD/MM/YYYY') as issueddate,
                users.name as issued,
                sites.name as site,
                warehouses.site_id,
                warehouses.name as warehouse
            ");
            $query->leftJoin('users','users.id','=','goods_issues.issued_by');
            $query->leftJoin('warehouses','warehouses.id','=','goods_issues.warehouse_id');
            $query->leftJoin('sites','sites.id','=','warehouses.site_id');
            $query->find($id);
            $data = $query->first();                

            if($data){
                if($data->status == 'approved'){
                    abort(403);
                }
                return view('admin.goodsissue.edit',compact('data'));
            }else{
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

            $query = GoodsIssue::with([
                'consumableproducts' => function($products){
                    $products->selectRaw("
                        goods_issue_products.*,
                        products.name as product,
                        products.is_serial,
                        product_categories.path as category,
                        uoms.name as uom,                    
                        product_consumables.consumable_number as reference,
                        rack_warehouses.name as rack,
                        bin_warehouses.name as bin
                    ");
                    $products->leftJoin('products','products.id','=','goods_issue_products.product_id');
                    $products->leftJoin('product_categories','product_categories.id','=','products.product_category_id');
                    $products->leftJoin('uoms','uoms.id','=','goods_issue_products.uom_id');
                    $products->leftJoin('rack_warehouses', 'rack_warehouses.id', '=', 'goods_issue_products.rack_id');
                    $products->leftJoin('bin_warehouses', 'bin_warehouses.id', '=', 'goods_issue_products.bin_id');
                    $products->leftJoin('product_consumables','product_consumables.id','=','goods_issue_products.reference_id');
                },
                'transferproducts' => function($products){
                    $products->selectRaw("
                        goods_issue_products.*,
                        products.name as product,
                        products.is_serial,
                        product_categories.path as category,
                        uoms.name as uom,                    
                        product_transfers.transfer_number as reference,
                        rack_warehouses.name as rack,
                        bin_warehouses.name as bin
                    ");
                    $products->leftJoin('products','products.id','=','goods_issue_products.product_id');
                    $products->leftJoin('product_categories','product_categories.id','=','products.product_category_id');
                    $products->leftJoin('uoms','uoms.id','=','goods_issue_products.uom_id');
                    $products->leftJoin('rack_warehouses', 'rack_warehouses.id', '=', 'goods_issue_products.rack_id');
                    $products->leftJoin('bin_warehouses', 'bin_warehouses.id', '=', 'goods_issue_products.bin_id');
                    $products->leftJoin('product_transfers','product_transfers.id','=','goods_issue_products.reference_id');
                },
                'borrowingproducts' => function($products){
                    $products->with([
                        'serials' => function($serial){
                            $serial->selectRaw("
                                goods_issue_serials.*,
                                product_serials.serial_number
                            ");
                            $serial->leftJoin('product_serials','product_serials.id','=','goods_issue_serials.serial_id');
                        }
                    ]);
                    $products->selectRaw("
                        goods_issue_products.*,
                        products.name as product,
                        products.is_serial,
                        product_categories.path as category,
                        uoms.name as uom,                    
                        product_borrowings.borrowing_number as reference,
                        rack_warehouses.name as rack,
                        bin_warehouses.name as bin
                    ");
                    $products->leftJoin('products','products.id','=','goods_issue_products.product_id');
                    $products->leftJoin('product_categories','product_categories.id','=','products.product_category_id');
                    $products->leftJoin('uoms','uoms.id','=','goods_issue_products.uom_id');
                    $products->leftJoin('rack_warehouses', 'rack_warehouses.id', '=', 'goods_issue_products.rack_id');
                    $products->leftJoin('bin_warehouses', 'bin_warehouses.id', '=', 'goods_issue_products.bin_id');
                    $products->leftJoin('product_borrowings','product_borrowings.id','=','goods_issue_products.reference_id');
                },
                'files',
                'images'
            ]);
            $query->selectRaw("
                goods_issues.*,
                TO_CHAR(goods_issues.date_issued,'DD/MM/YYYY') as issueddate,
                users.name as issued,
                sites.name as site,
                warehouses.site_id,
                warehouses.name as warehouse
            ");
            $query->leftJoin('users','users.id','=','goods_issues.issued_by');
            $query->leftJoin('warehouses','warehouses.id','=','goods_issues.warehouse_id');
            $query->leftJoin('sites','sites.id','=','warehouses.site_id');
            $query->find($id);
            $data = $query->first();

            if($data){
                return view('admin.goodsissue.detail',compact('data'));
            }else{
                abort(404);
            }
        } else {
            abort(403);
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
        $startdate  = $request->startdate;
        $enddate    = $request->enddate;
        $number     = strtoupper($request->number);
        $products   = $request->products;
        $status     = strtolower($request->status);

        $query = GoodsIssue::selectRaw("
            goods_issues.*,
            TO_CHAR(goods_issues.date_issued,'DD/MM/YYYY') as issued_date,
            count(goods_issue_products.goods_issue_id) as products
        ");
        $query->leftJoin('goods_issue_products','goods_issue_products.goods_issue_id','=','goods_issues.id');
        if ($startdate && $enddate) {
            $query->whereBetween('goods_issues.date_issued', [$startdate, $enddate]);
        }
        if ($number) {
            $query->whereRaw("upper(goods_issues.issued_number) like '%$number%'");
        }
        if ($status) {
            $query->where('goods_issues.status', $status);
        }
        $query->groupBy(
            'goods_issues.id'          
        );

        $rows  = clone $query;
        $total = $rows->count();

        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);

        $queries = $query->get();

        $data = [];
        foreach ($queries as $key => $row) {
            if (isset($products) && $row->products == $products) {
                $row->no = ++$start;
                $data[]  = $row;
            } else if (!isset($products)) {
                $row->no = ++$start;
                $data[]  = $row;
            }
        }

        return response()->json([
            'draw'              => $draw,
            'recordsTotal'      => $total,
            'recordsFiltered'   => $total,
            'data'              => $data
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'issueddate'   => 'required',
            'unit'         => 'required',
            'warehouse'    => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()    
            ],400);
        }

        $issueddate  = $request->issueddate;
        $warehouse   = $request->warehouse;
        $description = $request->description;
        $status      = $request->status;
        $issuedby    = $request->issuedby;

        $query = GoodsIssue::create([
            'date_issued'   => $issueddate,
            'warehouse_id'  => $warehouse,
            'issued_by'     => $issuedby,
            'description'   => $description,
            'status'        => $status
        ]);

        if($query){
            $now           = date('Y-m-d');
            $issued_id     = $query->id;
            $getProducts   = $request->products;
            $documentNames = $request->document_name;
            $photoNames    = $request->photo_name;
            $documents     = [];

            $this->issuedNumber($issued_id, $query->key_number, $query->created_at);

            if ($getProducts) {                
                $this->approval($getProducts,$issued_id, $status);
            }

            if (isset($documentNames)) {
                foreach ($documentNames as $key => $row) {
                    $docName = $row;
                    $docFile = $request->file('attachment')[$key];
                    if (isset($docFile)) {
                        $filename = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower($docName)) . '.' . $docFile->getClientOriginalExtension();
                        $path     = "assets/goodsissue/$issued_id/document";

                        if (!file_exists($path)) {
                            mkdir($path, 0777, true);
                        }

                        $docFile->move($path, $filename);

                        $documents[] = [
                            'goods_issue_id'       => $issued_id,
                            'document_name'        => $docName,
                            'file'                 => $filename,
                            'type'                 => 'file',
                            'created_at'           => $now,
                            'updated_at'           => $now
                        ];
                    }
                }
            }

            if (isset($photoNames)) {
                foreach ($photoNames as $key => $row) {
                    $photoName = $row;
                    $photoFile = $request->file('photo')[$key];
                    if (isset($photoFile)) {
                        $filename = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower($photoName)) . '.' . $photoFile->getClientOriginalExtension();
                        $path     = "assets/goodsissue/$issued_id/image";

                        if (!file_exists($path)) {
                            mkdir($path, 0777, true);
                        }

                        $photoFile->move($path, $filename);

                        $documents[] = [
                            'goods_issue_id'       => $issued_id,
                            'document_name'        => $photoName,
                            'file'                 => $filename,
                            'type'                 => 'photo',
                            'created_at'           => $now,
                            'updated_at'           => $now
                        ];
                    }
                }
            }

            if (count($documents) > 0) {
                $query = GoodsIssueDocument::insert($documents);
                if (!$query) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Failed to save document file.'
                    ], 400);
                }
            }

            $result = [
                'status'    => true,
                'message'   => 'Data has been saved.',
                'point'     => 200
            ];
        }else{
            $result = [
                'status'    => false,
                'message'   => 'Failed to create data.',
                'point'     => 400
            ];
        }

        return response()->json([
            'status'    => $result['status'],
            'message'   => $result['message']
        ],$result['point']);
    }       

    // Data approval process
    public function approval($products, $issued_id, $status)    
    {        
        $referenceID = [];
        $type        = '';
        $warehouseID = 0;

        foreach (json_decode($products) as $key => $row) {                                        
            $type        = $row->type;
            $warehouseID = $row->warehouse_id;
            array_push($referenceID, $row->reference_id);

            $query = GoodsIssueProduct::create([
                'goods_issue_id'   => $issued_id,
                'reference_id'     => $row->reference_id,
                'product_id'       => $row->product_id,
                'uom_id'           => $row->uom_id,
                'qty_request'      => $row->qty_request,
                'qty_receive'      => $row->qty_receive,
                'rack_id'          => $row->rack_id,
                'bin_id'           => $row->bin_id,
                'type'             => $row->type
            ]);                                       

            if($row->has_serial && $row->type == 'borrowing'){                
                $this->insertSerial($query->id,$row->serials,$status);                                                

                if(!$query){
                    return response()->json([
                        'status'    => false,
                        'message'   => 'Failed to create detail data.'
                    ], 400);
                }
            }

            if (!$query) {
                return response()->json([
                    'status'    => false,
                    'message'   => 'Failed to create detail data.'
                ], 400);
            }
        }

        if($type == 'borrowing' && $status == 'rejected'){                    
            $item = ProductBorrowingDetail::whereIn('product_borrowing_details.product_borrowing_id', $referenceID)->get();
            
            foreach($item as $key => $row){
                $warehouse = StockWarehouse::where([
                    'warehouse_id' => $warehouseID,
                    'product_id'   => $row->product_id
                ])->first();                       

                $warehouse->stock = $warehouse->stock + $row->qty_requested;
                $warehouse->save();

                if(!$warehouse){
                    return response()->json([
                        'status'    => false,
                        'message'   => 'Failed to recalculate stock on warehouse.'
                    ],400);
                }
            }            
        };        

        $query = ProductBorrowing::whereIn('id', $referenceID)->update(['status' => $status=='approved'?'borrowed':'rejected']);
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
        $validator = Validator::make($request->all(), [
            'issueddate'   => 'required',
            'unit'         => 'required',
            'warehouse'    => 'required'
        ]);

        if($validator->fails()){
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()    
            ],400);
        }

        $issueddate  = $request->issueddate;
        $warehouse   = $request->warehouse;
        $description = $request->description;
        $status      = $request->status;

        $query = GoodsIssue::find($id);
        $query->warehouse_id = $warehouse;
        $query->date_issued  = $issueddate;
        $query->description  = $description;
        $query->status       = $status;
        $query->save();

        if($query){
            $now           = date('Y-m-d');
            $issued_id     = $query->id;
            $getProducts   = $request->products;
            $documentNames = $request->document_name;
            $photoNames    = $request->photo_name;
            $updateDoc     = $request->documents;
            $deleteDoc     = $request->undocuments;
            $documents     = [];

            $cleared  = GoodsIssueProduct::where('goods_issue_id',$issued_id)->delete();

            if ($getProducts) {                
                foreach (json_decode($getProducts) as $key => $row) {                    
                    $query = GoodsIssueProduct::create([
                        'goods_issue_id'   => $issued_id,
                        'reference_id'     => $row->reference_id,
                        'product_id'       => $row->product_id,
                        'uom_id'           => $row->uom_id,
                        'qty_request'      => $row->qty_request,
                        'qty_receive'      => $row->qty_receive,
                        'rack_id'          => $row->rack_id,
                        'bin_id'           => $row->bin_id,
                        'type'             => $row->type
                    ]);
                    
                    if (!$query) {
                        return response()->json([
                            'status'    => false,
                            'message'   => 'Failed to create detail data.'
                        ], 400);
                    }

                    if($row->has_serial && $row->type == 'borrowing'){
                        $this->insertSerial($query->id,$row->serials,$status);

                        if($status == 'approved'){
                            $query = ProductBorrowing::where('id',$row->reference_id)->update(['status' => 'borrowed']);
                            
                            if(!$query){
                                return response()->json([
                                    'status'    => false,
                                    'message'   => 'Failed to create detail data.'
                                ], 400);
                            }
                        }                        
                    }
                }                
            }

            if (isset($documentNames)) {
                foreach ($documentNames as $key => $row) {
                    $docName = $row;
                    $docFile = $request->file('attachment')[$key];
                    if (isset($docFile)) {
                        $filename = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower($docName)) . '.' . $docFile->getClientOriginalExtension();
                        $path     = "assets/goodsissue/$issued_id/document";

                        if (!file_exists($path)) {
                            mkdir($path, 0777, true);
                        }

                        $docFile->move($path, $filename);

                        $documents[] = [
                            'goods_issue_id'    => $issued_id,
                            'document_name'     => $docName,
                            'file'              => $filename,
                            'type'              => 'file',
                            'created_at'        => $now,
                            'updated_at'        => $now
                        ];
                    }
                }
            }

            if (isset($photoNames)) {
                foreach ($photoNames as $key => $row) {
                    $photoName = $row;
                    $photoFile = $request->file('photo')[$key];
                    if (isset($photoFile)) {
                        $filename = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower($photoName)) . '.' . $photoFile->getClientOriginalExtension();
                        $path     = "assets/goodsissue/$issued_id/image";

                        if (!file_exists($path)) {
                            mkdir($path, 0777, true);
                        }

                        $photoFile->move($path, $filename);

                        $documents[] = [
                            'goods_issue_id'    => $issued_id,
                            'document_name'     => $photoName,
                            'file'              => $filename,
                            'type'              => 'photo',
                            'created_at'        => $now,
                            'updated_at'        => $now
                        ];
                    }
                }
            }

            if ($updateDoc) {
                foreach (json_decode($updateDoc) as $key => $row) {
                    $id         = $row->id;
                    $issued_id  = $row->issuedID;
                    $filename   = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower($row->docName));
                    $file       = $row->file;
                    $type       = $row->type;
                    $oldfile    = "assets/goodsissue/$issued_id/$type/$file";
                    $ext        = pathinfo($oldfile)['extension'];
                    $newfile    = "assets/goodsissue/$issued_id/$type/$filename.$ext";

                    $rename = rename($oldfile, $newfile);

                    if ($rename) {
                        $query = GoodsIssueDocument::find($id);
                        $query->document_name = $row->docName;
                        $query->file          = "$filename.$ext";
                        $query->save();
                    }
                }
            }

            if ($deleteDoc) {
                $id = [];
                foreach (json_decode($deleteDoc) as $key => $row) {
                    array_push($id, $row->id);
                }

                $query = GoodsIssueDocument::whereIn('id', $id);
                $query->delete();
            }

            if (count($documents) > 0) {
                $query = GoodsIssueDocument::insert($documents);
                if (!$query) {
                    return response()->json([
                        'status'  => false,
                        'message' => 'Failed to save document file.'
                    ], 400);
                }
            }

            $result = [
                'status'    => true,
                'message'   => 'Data has been updated.',
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
            $query = GoodsIssue::find($id);
            $query->delete();

            return response()->json([
                'status'    => true,
                'message'   => 'Successfully delete data'
            ], 200);
        } catch (QueryException $th) {
            return response()->json([
                'status'    => false,
                'message'   => 'Failed to delete data.'
            ], 400);
        }
    }        

    public function consumableproducts(Request $request)
    {
        $draw        = $request->draw;
        $start       = $request->start;
        $length      = $request->length;
        $query       = $request->search['value'];
        $sort        = $request->columns[$request->order[0]['column']]['data'];
        $dir         = $request->order[0]['dir'];
        $except      = $request->except;
        $category_id = $request->category_id;

        $query = ProductConsumableDetail::selectRaw("
            product_consumables.consumable_number,
            TO_CHAR(product_consumables.consumable_date,'DD/MM/YYYY') as date_consumable,
            product_consumable_details.product_consumable_id,
            product_consumable_details.product_id,
            product_consumable_details.uom_id,
            product_consumable_details.qty_consume as qty,
            products.name as product,
            products.is_serial,
            product_categories.path as category,
            uoms.name as uom
        ");
        $query->leftJoin('product_consumables','product_consumables.id','=','product_consumable_details.product_consumable_id');
        $query->leftJoin('products','products.id','=','product_consumable_details.product_id');
        $query->leftJoin('product_categories','product_categories.id','=','products.product_category_id');
        $query->leftJoin('uoms','uoms.id','=','product_consumable_details.uom_id');
        $query->where('product_consumables.status','approved');
        if($category_id){
            $query->where('product_categories.id',$category_id);
        }
        if($except){
            $query->whereNotIn('product_consumable_details.product_id',$except);
        }
        
        $rows  = clone $query;
        $total = $rows->count();

        $rows  = clone $query;
        $total = $rows->count();

        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);

        $queries = $query->get();

        $data = [];
        foreach ($queries as $key => $row) {
            $row->no = ++$start;
            $row->category = str_replace('->',' <i class="fas fa-angle-right"></i> ',$row->category);                        
            $data[]  = $row;
        }

        return response()->json([
            'draw'              => $draw,
            'recordsTotal'      => $total,
            'recordsFiltered'   => $total,
            'data'              => $data
        ], 200);
    }

    public function transferproducts(Request $request)
    {
        $draw        = $request->draw;
        $start       = $request->start;
        $length      = $request->length;
        $query       = $request->search['value'];
        $sort        = $request->columns[$request->order[0]['column']]['data'];
        $dir         = $request->order[0]['dir'];
        $except      = $request->except;
        $category_id = $request->category_id;

        $query = ProductTransferDetail::selectRaw("
            product_transfers.transfer_number,
            TO_CHAR(product_transfers.date_transfer,'DD/MM/YYYY') as transfer_date,
            product_transfer_details.product_transfer_id,
            product_transfer_details.product_id,
            product_transfer_details.uom_id,
            product_transfer_details.qty_requested as qty,
            products.name as product,
            products.is_serial,
            product_categories.path as category,
            uoms.name as uom
        ");
        $query->leftJoin('product_transfers','product_transfers.id','=','product_transfer_details.product_transfer_id');
        $query->leftJoin('products','products.id','=','product_transfer_details.product_id');
        $query->leftJoin('product_categories','product_categories.id','=','products.product_category_id');
        $query->leftJoin('uoms','uoms.id','=','product_transfer_details.uom_id');
        $query->where('product_transfers.status','approved');
        if($category_id){
            $query->where('product_categories.id',$category_id);
        }
        if($except){
            $query->whereNotIn('product_transfer_details.product_id',$except);
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
            $row->category = str_replace('->',' <i class="fas fa-angle-right"></i> ',$row->category);                        
            $data[]  = $row;
        }

        return response()->json([
            'draw'              => $draw,
            'recordsTotal'      => $total,
            'recordsFiltered'   => $total,
            'data'              => $data
        ], 200);        
    }

    public function borrowingproducts(Request $request)
    {
        $draw         = $request->draw;
        $start        = $request->start;
        $length       = $request->length;
        $query        = $request->search['value'];
        $sort         = $request->columns[$request->order[0]['column']]['data'];
        $dir          = $request->order[0]['dir'];
        $warehouse_id = $request->warehouse_id;
        $except       = $request->except;
        
        $query = ProductBorrowingDetail::selectRaw("
            product_borrowing_details.*,
            TO_CHAR(product_borrowings.borrowing_date,'DD/MM/YYYY') as date_borrowing,
            product_borrowings.borrowing_number,
            products.name as product,
            products.is_serial,
            product_categories.path as category,
            uoms.name as uom
        ");
        $query->leftJoin('product_borrowings','product_borrowings.id','=','product_borrowing_details.product_borrowing_id');
        $query->leftJoin('products','products.id','=','product_borrowing_details.product_id');
        $query->leftJoin('product_categories','product_categories.id','=','product_borrowing_details.product_category_id');
        $query->leftJoin('uoms','uoms.id','=','product_borrowing_details.uom_id');        
        if($except){
            $query->whereNotIn('product_borrowing_details.product_id',$except);
        }
        $query->where('product_borrowings.warehouse_id','=',$warehouse_id);        
        $query->whereIn('product_borrowings.status',['waiting','approved']);

        $rows  = clone $query;
        $total = $rows->count();

        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);

        $queries = $query->get();

        $data = [];
        foreach ($queries as $key => $row) {
            $row->no = ++$start;
            $row->category = str_replace('->',' <i class="fas fa-angle-right"></i> ',$row->category);                        
            $data[]  = $row;
        }

        return response()->json([
            'draw'              => $draw,
            'recordsTotal'      => $total,
            'recordsFiltered'   => $total,
            'data'              => $data
        ], 200);        
    }

    public function readserial(Request $request)
    {
        $draw        = $request->draw;
        $start       = $request->start;
        $length      = $request->length;
        $query       = $request->search['value'];
        $sort        = $request->columns[$request->order[0]['column']]['data'];
        $dir         = $request->order[0]['dir'];
        $product_id  = $request->product_id;
        $except      = $request->except;

        $query = ProductSerial::selectRaw("
            product_serials.*,
            products.name as product,
            product_categories.path as category
        ");
        $query->leftJoin('products','products.id','=','product_serials.product_id');
        $query->leftJoin('product_categories','product_categories.id','=','products.product_category_id');
        if($product_id){
            $query->where('product_serials.product_id',$product_id);
        }    
        if($except){
            $query->whereNotIn('product_serials.id',$except);
        }
        $query->where([
            ['product_serials.movement','=','in'],
            ['product_serials.status','=',1]
        ]);                

        $rows  = clone $query;
        $total = $rows->count();      

        $query->offset($start);
        $query->limit($length);
        $query->orderBy($sort, $dir);

        $queries = $query->get();

        $data = [];
        foreach ($queries as $key => $row) {
            $row->no = ++$start;
            $row->category = str_replace('->',' <i class="fas fa-angle-right"></i> ',$row->category);                        
            $data[]  = $row;
        }

        return response()->json([
            'draw'              => $draw,
            'recordsTotal'      => $total,
            'recordsFiltered'   => $total,
            'data'              => $data,
            'exception'         => $except
        ], 200);
    }

    public function issuedNumber($id, $key_number, $created_at)
    {
        $key   = preg_split("/[\s-]+/", "$key_number");
        $code  = $key[0];
        $year  = $key[1];
        $index = $key[2];
        $month = date('m', strtotime($created_at));

        $number = "$code-$year-$month-$index";

        $query = GoodsIssue::find($id);
        $query->issued_number = $number;
        $query->save();
    }

    public function insertSerial($primary_id, $serials, $status)
    {
        $now       = date('Y-m-d H:i:s');
        $data      = [];
        $serial_id = [];

        foreach($serials as $key => $row){
            $data[] = [
                'goods_issue_product_id' => $primary_id,
                'serial_id'              => $row->serial_id,
                'created_at'             => $now,
                'updated_at'             => $now
            ];
            
            array_push($serial_id,intval($row->serial_id));
        }        

        $query = GoodsIssueSerial::insert($data);

        if($query && $status == 'approved'){
            $query = ProductSerial::whereIn('id',$serial_id)->update(['movement' => 'out']);

            if(!$query){
                return response()->json([
                    'status'    => false,
                    'message'   => 'Failed to update product serial status.'
                ],400);
            }

        }else{
            return response()->json([
                'status'    => false,
                'message'   => 'Failed to create data product serial.'
            ],400);
        }
    }

    public function print(Request $request)
    {   
        $id    = $request->id;   

        $query = GoodsIssue::find($id);
        $data  = $query?$query:[];

        return view('admin.goodsissue.print',compact('data'));
    }

    public function export(Request $request)
    {
        $now = date('Y-m-d');
        $id  = $request->id;

        $query = GoodsIssue::with([
            'consumableproducts' => function($products){
                $products->selectRaw("                    
                    goods_issue_products.goods_issue_id,
                    goods_issue_products.product_id,                                        
                    goods_issue_products.qty_receive,    
                    goods_issue_products.type,
                    products.name as product,                    
                    product_categories.path as category,
                    uoms.name as uom,                    
                    product_consumables.consumable_number as reference,
                    users.name as issued
                ");
                $products->leftJoin('products','products.id','=','goods_issue_products.product_id');
                $products->leftJoin('product_categories','product_categories.id','=','products.product_category_id');
                $products->leftJoin('uoms','uoms.id','=','goods_issue_products.uom_id');
                $products->leftJoin('rack_warehouses', 'rack_warehouses.id', '=', 'goods_issue_products.rack_id');
                $products->leftJoin('bin_warehouses', 'bin_warehouses.id', '=', 'goods_issue_products.bin_id');
                $products->leftJoin('product_consumables','product_consumables.id','=','goods_issue_products.reference_id');
                $products->leftJoin('users','users.id','=','product_consumables.issued_by');
            },
            'transferproducts' => function($products){
                $products->selectRaw("  
                    goods_issue_products.goods_issue_id,
                    goods_issue_products.product_id,                                                                               
                    goods_issue_products.qty_receive,    
                    goods_issue_products.type,
                    products.name as product,
                    products.is_serial,
                    product_categories.path as category,
                    uoms.name as uom,                    
                    product_transfers.transfer_number as reference,
                    users.name as issued
                ");
                $products->leftJoin('products','products.id','=','goods_issue_products.product_id');
                $products->leftJoin('product_categories','product_categories.id','=','products.product_category_id');
                $products->leftJoin('uoms','uoms.id','=','goods_issue_products.uom_id');
                $products->leftJoin('rack_warehouses', 'rack_warehouses.id', '=', 'goods_issue_products.rack_id');
                $products->leftJoin('bin_warehouses', 'bin_warehouses.id', '=', 'goods_issue_products.bin_id');
                $products->leftJoin('product_transfers','product_transfers.id','=','goods_issue_products.reference_id');
                $products->leftJoin('users','users.id','=','product_transfers.issued_by');
            },
            'borrowingproducts' => function($products){               
                $products->selectRaw("       
                    goods_issue_products.goods_issue_id,
                    goods_issue_products.product_id,                                                                     
                    goods_issue_products.qty_receive, 
                    goods_issue_products.type,   
                    products.name as product,                    
                    product_categories.path as category,
                    uoms.name as uom,                    
                    product_borrowings.borrowing_number as reference,
                    users.name as issued
                ");
                $products->leftJoin('products','products.id','=','goods_issue_products.product_id');
                $products->leftJoin('product_categories','product_categories.id','=','products.product_category_id');
                $products->leftJoin('uoms','uoms.id','=','goods_issue_products.uom_id');
                $products->leftJoin('rack_warehouses', 'rack_warehouses.id', '=', 'goods_issue_products.rack_id');
                $products->leftJoin('bin_warehouses', 'bin_warehouses.id', '=', 'goods_issue_products.bin_id');
                $products->leftJoin('product_borrowings','product_borrowings.id','=','goods_issue_products.reference_id');
                $products->leftJoin('users','users.id','=','product_borrowings.issued_by');
            }            
        ]);
        $query->selectRaw("
            goods_issues.id,
            goods_issues.issued_number,
            TO_CHAR(goods_issues.date_issued,'DD/MM/YYYY') as date_issued,
            goods_issues.description,            
            warehouses.name as warehouse,
            users.name as issued
        ");        
        $query->leftJoin('warehouses','warehouses.id','=','goods_issues.warehouse_id');
        $query->leftJoin('sites','sites.id','=','warehouses.site_id');
        $query->leftJoin('users','users.id','=','goods_issues.issued_by');
        $data = $query->find($id);
        
        $dateIssued = $data->date_issued;
        $sender     = $data->issued;

        $build        = new Spreadsheet();        
        $activesheet  = $build->getActiveSheet();                       
        $headerFooter = $activesheet->getHeaderFooter();                

        // Default Style
        $build->getDefaultStyle()->getFont()->setName('Arial');
        $build->getDefaultStyle()->getFont()->setSize(10);                                

        // Header and footer setting        

        $logo = new HeaderFooterDrawing(); 
        $logo->setName('Geodipa Energi Logo');
        $logo->setPath(public_path('assets/logo.png'));
        $logo->setHeight(20);
        $logo->setWidth(20);

        $headerFooter->setOddHeader('&B&U&"Arial" SURAT BARANG KELUAR');
        $headerFooter->addImage($logo,HeaderFooter::IMAGE_HEADER_LEFT);
                
        $startRow     = $activesheet->getHighestRow();
        $lastRow      = $activesheet->getHighestRow();
        $lastColumn   = $activesheet->getHighestColumn();

        $thead = [
            ['column' => ['A'], 'label' => 'NOMOR REFERENSI','width' => 20, 'uom' => 'pt'],
            ['column' => ['B'], 'label' => 'JUMLAH','width' => 10, 'uom' => 'pt'],
            ['column' => ['C'], 'label' => 'SATUAN','width' => 10, 'uom' => 'pt'],
            ['column' => ['D'], 'label' => 'MATERIAL','width' => 40, 'uom' => 'pt'],
            ['column' => ['E'], 'label' => 'KATEGORI','width' => 25, 'uom' => 'pt', 'signature' => 'Pengirim'],
            ['column' => ['F'], 'label' => 'PEMOHON','width' => 25, 'uom' => 'pt', 'signature' => 'Pengirim'],
            ['column' => ['G'], 'label' => 'KETERANGAN','width' => 25, 'uom' => 'pt' , 'signature' => 'Penerima']
        ];

        $activesheet->setCellValue("F$lastRow",'Tanggal :');        
        $activesheet->setCellValue("G$lastRow","$dateIssued");                
        $activesheet->getStyle("F$lastRow")->applyFromArray([
            'font'      => ['bold' => true],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_DISTRIBUTED,
                'vertical'   => Alignment::VERTICAL_TOP
            ]
        ]);
        $activesheet->getStyle("G$lastRow")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_RIGHT,
                'vertical'   => Alignment::VERTICAL_TOP
            ]
        ]);                

        // Draw Information
        for ($i=0; $i <= 2 ; $i++) { 
            $lastRow = $lastRow+($i>0?1:0);

            switch ($i) {
                case 0:
                    $label = 'No';
                    $value = $data->issued_number;
                    break;
                case 1:
                    $label = 'Warehouse';
                    $value = $data->warehouse;
                    break;
                case 2:
                    $label = 'Catatan';                                                                                
                    $value = $data->description;
                    break;
                default:
                    $value = '';
                    break;
            }            
            
            $activesheet->setCellValue("A$lastRow","$label : ");            
            $activesheet->setCellValue("B$lastRow","$value");                                
            $activesheet->getStyle("A$lastRow")->applyFromArray([
                'font'      => ['bold' => true],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_DISTRIBUTED,
                    'vertical'   => Alignment::VERTICAL_TOP
                ]
            ]);
        }        

        $lastRow  = $lastRow+5;
        $firstRow = $lastRow;                    

        foreach ($thead as $key => $tr) {
            $column   = $tr['column'];
            $width    = intval($tr['width']);
            $uom      = $tr['uom'];
            $firstCol = $column[0];                    

            $td        = $activesheet;
            $cellStyle = $activesheet->getStyle("$firstCol$lastRow");
            
            $td->setCellValue("$firstCol$lastRow",$tr['label']);                                                
            $td->getColumnDimension("$firstCol")->setWidth($width,$uom);
            $cellStyle->applyFromArray([
                'font'       => ['bold' => true],
                'alignment'  => ['horizontal' => Alignment::HORIZONTAL_CENTER],                
                'borders'    => [
                    'allBorders' => ['borderStyle' => Border::BORDER_THICK]
                ]
            ]);
        }      

        $material = [];
        
        if(count($data->consumableproducts) > 0){
            $material = $data->consumableproducts;
        }else if(count($data->transferproducts) > 0){
            $material = $data->transferproducts;
        }else if(count($data->borrowingproducts) > 0){
            $material = $data->borrowingproducts;
        }

        foreach ($material as $key => $item) {
            $lastRow  = $lastRow+1;
            $category = strip_tags($item->category);
            $category = str_replace('&nbsp;','',str_replace('&rsaquo;',"->",$category));
            
            switch ($item->type) {
                case 'consumable':
                    $type = 'Usage';
                    break;
                case 'transfer':
                    $type = 'Transfer';
                    break;
                case 'borrowing':
                    $type = 'Borrow';
                    break;
                default:
                    $type = '';
                    break;
            }
            

            $param = [
                ['data' => $item->reference, 'alignment' => null],
                ['data' => $item->qty_receive , 'alignment' => Alignment::HORIZONTAL_RIGHT],
                ['data' => $item->uom, 'alignment' => null],
                ['data' => $item->product, 'alignment' => null],
                ['data' => $category, 'alignment' => null],
                ['data' => $item->issued, 'alignment' => null],
                ['data' => $type, 'alignment' => Alignment::HORIZONTAL_CENTER]
            ];

            foreach($thead as $ind => $tr){
                $column   = $tr['column'];
                $firstCol = $column[0];
                $data     = $param[$ind];
                
                if(count($column) > 1){
                    $lastCol   = $column[1];
                    $colRange  = "$firstCol$lastRow:$lastCol$lastRow";
                    $td        = $activesheet->mergeCells("$colRange");
                    $cellStyle = $activesheet->getStyle("$colRange");                
                }else{
                    $colRange  = "$firstCol";
                    $td        = $activesheet;
                    $cellStyle = $activesheet->getStyle("$firstCol$lastRow");                                
                }                
                $td->setCellValue("$firstCol$lastRow",$data['data']);        
                $cellStyle->applyFromArray([
                    'alignment' => [
                        'vertical'   => Alignment::VERTICAL_TOP,
                        'horizontal' => $data['alignment']?$data['alignment']:Alignment::HORIZONTAL_LEFT,
                        'wrapText'   => true
                    ]
                ]);                            
            }
        }

        $firstCol = $thead[0]['column'][0];
        $lastCol  = end($thead)['column'][0];
        $lastRow  = $lastRow+15;   

         // Data Outline          
        $activesheet->getStyle("$firstCol".($firstRow+1).":$lastCol$lastRow")->applyFromArray([
            'borders' => [
                'inside' => ['borderStyle' => Border::BORDER_THIN],
                'bottom' => ['borderStyle' => Border::BORDER_THIN]
            ]
        ]);           

        $expedition = [
            ['label' => 'DIISI OLEH BAGIAN EKSPEDISI :'],
            ['label' => 'Terima'],
            ['label' => 'Nomor Urut'],
            ['label' => 'Tanggal Angkut'],
            ['label' => 'Kendaraan'],
            ['label' => 'Nama Supir']
        ];

        // Expedition Information
        foreach($expedition as $key => $param){
            $lastRow = $lastRow+1;

            if($key == 0){
                $activesheet->mergeCells("$firstCol$lastRow:C$lastRow")->setCellValue("$firstCol$lastRow",$param['label']);                
                // Signature
                for ($i=4; $i <=6 ; $i++) { 
                    $column = $thead[$i]['column'][0];
                    $label  = $thead[$i]['signature'];
                    
                    $activesheet->setCellValue("$column$lastRow", $label);
                    $activesheet->getStyle("$column$lastRow")->applyFromArray([
                        'alignment' => [
                            'horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical'   => Alignment::VERTICAL_TOP
                        ]
                    ]);
                }

                $activesheet->getStyle("$firstCol$lastRow:D$lastRow")->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => [
                        'vertical'   => Alignment::VERTICAL_TOP,                        
                    ]
                ]);
                $activesheet->getStyle("$firstCol$lastRow:$lastCol$lastRow")->applyFromArray([
                    'alignment' => [
                        'vertical'   => Alignment::VERTICAL_TOP,                        
                    ]
                ]);                
            }else{                
                $activesheet->setCellValue("$firstCol$lastRow",$param['label']);
                $activesheet->mergeCells("B$lastRow:D$lastRow")->setCellValue("B$lastRow",':');                               
            }     

            for ($i=4; $i <=6 ; $i++) { 
                $column = $thead[$i]['column'][0];
                $activesheet->getStyle("$column$lastRow")->applyFromArray([
                    'borders' => [
                        'left'   => ['borderStyle' => Border::BORDER_THIN]
                    ]
                ]);
            }
                    
        }      

        $activesheet->setCellValue("$lastCol$lastRow",$sender);
        $activesheet->getStyle("$lastCol")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_BOTTOM
            ]
        ]);

        // Outline Data Table
        $activesheet->getStyle("$firstCol$firstRow:$lastCol$lastRow")->applyFromArray([
            'borders' => [                
                'outline' => ['borderStyle' => Border::BORDER_THICK],
            ]
        ]);      

        // Page Setting (Orientation, Papersize, etc)
        $pageSetup    = $activesheet->getPageSetup();

        $pageSetup->setPrintArea("A1:$lastCol".($lastRow+25));
        $pageSetup->setOrientation(PageSetup::PAPERSIZE_A4);   
        $pageSetup->setOrientation(PageSetup::ORIENTATION_LANDSCAPE);                        
        $pageSetup->setFitToWidth(1);
        $pageSetup->setFitToHeight(1);

        $filename = "SuratBarangKeluar.xlsx";
        $file     = IOFactory::createWriter($build,'Xlsx');        

        ob_start();
        $file->save('php://output');
        $export = ob_get_contents();
        ob_end_clean();
        header('Content-Type: application/json');

        return response()->json([
            'status'    => true,
            'document'  => "$filename",            
            'file'      => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($export)
        ],200);
                
    }
}
