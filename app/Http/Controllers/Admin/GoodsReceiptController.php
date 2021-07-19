<?php

namespace App\Http\Controllers\Admin;

use App\Models\Menu;
use App\Models\GoodsReceipt;
use App\Models\ContractProduct;
use App\Models\ProductBorrowingDetail;
use App\Models\GoodsReceiptProduct;
use App\Models\GoodsReceiptAsset;
use App\Models\Site;
use App\Models\Warehouse;
use App\Models\Product;
use App\Models\ProductSerial;
use App\Models\StockWarehouse;
use App\Models\StockMovement;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Database\QueryException;

class GoodsReceiptController extends Controller
{
    /**
     * Define default method when access this controller
     */
    public function __construct()
    {
        $menu   = Menu::where('menu_route', 'goodsreceipt')->first();
        $parent = Menu::find($menu->parent_id);
        View::share('parent_name', $parent->menu_name);
        View::share('menu_name', $menu->menu_name);
        View::share('menu_active', url('admin/goodsreceipt'));
        $this->middleware('accessmenu', ['except' => ['select']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.goodsreceipt.index');
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
            return view('admin.goodsreceipt.create');
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
            $query = GoodsReceipt::with([
                'contractref' => function ($product) {
                    $product->selectRaw("
                        goods_receipt_products.*,
                        products.name as product,
                        products.sku,
                        products.is_serial,            
                        products.last_serial,
                        product_categories.path as category,
                        uoms.name as uom,                    
                        contracts.number as reference,
                        rack_warehouses.name as rack,
                        bin_warehouses.name as bin
                    ");
                    $product->leftJoin('products', 'products.id', '=', 'goods_receipt_products.product_id');
                    $product->leftJoin('product_categories','product_categories.id','=','products.product_category_id');
                    $product->leftJoin('uoms', 'uoms.id', '=', 'goods_receipt_products.uom_id');
                    $product->leftJoin('rack_warehouses', 'rack_warehouses.id', '=', 'goods_receipt_products.rack_id');
                    $product->leftJoin('bin_warehouses', 'bin_warehouses.id', '=', 'goods_receipt_products.bin_id');
                    $product->leftJoin('contracts', 'contracts.id', '=', 'goods_receipt_products.reference_id');
                    $product->where('goods_receipt_products.type', 'contract');
                },
                'borrowingref' => function ($product) {
                    $product->selectRaw("
                        goods_receipt_products.*,
                        products.name as product,
                        products.sku,
                        products.is_serial,            
                        products.last_serial,
                        product_categories.path as category,
                        uoms.name as uom,                    
                        product_borrowings.borrowing_number as reference,
                        rack_warehouses.name as rack,
                        bin_warehouses.name as bin
                    ");
                    $product->leftJoin('products', 'products.id', '=', 'goods_receipt_products.product_id');
                    $product->leftJoin('product_categories','product_categories.id','=','products.product_category_id');
                    $product->leftJoin('uoms', 'uoms.id', '=', 'goods_receipt_products.uom_id');
                    $product->leftJoin('rack_warehouses', 'rack_warehouses.id', '=', 'goods_receipt_products.rack_id');
                    $product->leftJoin('bin_warehouses', 'bin_warehouses.id', '=', 'goods_receipt_products.bin_id');
                    $product->leftJoin('product_borrowings', 'product_borrowings.id', '=', 'goods_receipt_products.reference_id');
                    $product->where('goods_receipt_products.type', 'borrowing');
                },
                'files',
                'images'
            ]);
            $query->selectRaw("
                goods_receipts.*,
                TO_CHAR(goods_receipts.receipt_date,'DD/MM/YYYY') as date_receipt,
                users.name as issued,
                warehouses.name as warehouse,
                warehouses.site_id,
                sites.name as site
            ");
            $query->leftJoin('warehouses', 'warehouses.id', '=', 'goods_receipts.warehouse_id');
            $query->leftJoin('sites', 'sites.id', '=', 'warehouses.site_id');
            $query->leftJoin('users', 'users.id', '=', 'goods_receipts.issued_by');
            $data  = $query->find($id);    
    
            if ($data) {
                return view('admin.goodsreceipt.edit', compact('data'));
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
    public function show($id)
    {
        $query = GoodsReceipt::with([
            'contractref' => function ($product) {
                    $product->selectRaw("
                    goods_receipt_products.*,
                    products.name as product,
                    products.is_serial,            
                    product_categories.path as category,
                    uoms.name as uom,                    
                    contracts.number as reference,
                    rack_warehouses.name as rack,
                    bin_warehouses.name as bin
                ");
                $product->leftJoin('products', 'products.id', '=', 'goods_receipt_products.product_id');
                $product->leftJoin('product_categories','product_categories.id','=','products.product_category_id');
                $product->leftJoin('uoms', 'uoms.id', '=', 'goods_receipt_products.uom_id');
                $product->leftJoin('rack_warehouses', 'rack_warehouses.id', '=', 'goods_receipt_products.rack_id');
                $product->leftJoin('bin_warehouses', 'bin_warehouses.id', '=', 'goods_receipt_products.bin_id');
                $product->leftJoin('contracts', 'contracts.id', '=', 'goods_receipt_products.reference_id');
                $product->where('goods_receipt_products.type', 'contract');
            },
            'borrowingref' => function ($product) {
                    $product->selectRaw("
                    goods_receipt_products.*,
                    products.name as product,
                    product_categories.name as category,
                    uoms.name as uom,                    
                    product_borrowings.borrowing_number as reference,
                    rack_warehouses.name as rack,
                    bin_warehouses.name as bin
                ");
                $product->leftJoin('products', 'products.id', '=', 'goods_receipt_products.product_id');
                $product->leftJoin('product_categories','product_categories.id','=','products.product_category_id');
                $product->leftJoin('uoms', 'uoms.id', '=', 'goods_receipt_products.uom_id');
                $product->leftJoin('rack_warehouses', 'rack_warehouses.id', '=', 'goods_receipt_products.rack_id');
                $product->leftJoin('bin_warehouses', 'bin_warehouses.id', '=', 'goods_receipt_products.bin_id');
                $product->leftJoin('product_borrowings', 'product_borrowings.id', '=', 'goods_receipt_products.reference_id');
                $product->where('goods_receipt_products.type', 'borrowing');
            },
            'files',
            'images'
        ]);
        $query->selectRaw("
            goods_receipts.*,
            TO_CHAR(goods_receipts.receipt_date,'DD/MM/YYYY') as date_receipt,
            users.name as issued,
            warehouses.name as warehouse,
            warehouses.site_id,
            sites.name as site
        ");
        $query->leftJoin('warehouses', 'warehouses.id', '=', 'goods_receipts.warehouse_id');
        $query->leftJoin('sites', 'sites.id', '=', 'warehouses.site_id');
        $query->leftJoin('users', 'users.id', '=', 'goods_receipts.issued_by');
        $data  = $query->find($id);

        if($data){
            return view('admin.goodsreceipt.detail',compact('data'));
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
        $startdate  = $request->startdate;
        $enddate    = $request->enddate;
        $number     = strtoupper($request->number);
        $products   = $request->products;
        $status     = strtolower($request->status);

        $query  = GoodsReceipt::selectRaw("
            goods_receipts.*,
            TO_CHAR(goods_receipts.receipt_date,'DD/MM/YYYY') as date_receipt,
            warehouses.name as warehouse,
            sites.name as site,
            count(goods_receipt_products.goods_receipt_id) as products
        ");
        $query->leftJoin('warehouses', 'warehouses.id', '=', 'goods_receipts.warehouse_id');
        $query->leftJoin('sites', 'sites.id', '=', 'warehouses.site_id');
        $query->leftJoin('goods_receipt_products', 'goods_receipt_products.goods_receipt_id', '=', 'goods_receipts.id');
        if ($startdate && $enddate) {
            $query->whereBetween('goods_receipts.receipt_date', [$startdate, $enddate]);
        }
        if ($number) {
            $query->whereRaw("upper(goods_receipts.good_receipt_no) like '%$number%'");
        }
        if ($status) {
            $query->where('goods_receipts.status', $status);
        }
        $query->groupBy(
            'goods_receipts.id',
            'warehouses.name',
            'sites.name'
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
            'receipt_date' => 'required',
            'site'         => 'required',
            'warehouse'    => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        $receiptdate = $request->receiptdate;
        $site        = $request->site;
        $warehouse   = $request->warehouse;
        $description = $request->description;
        $status      = $request->status;
        $issuedby    = $request->issuedby;

        $query = GoodsReceipt::create([
            'receipt_date' => $receiptdate,
            'warehouse_id' => $warehouse,
            'description'  => $description,
            'status'       => $status,
            'issued_by'    => $issuedby
        ]);

        if ($query) {
            $now           = date('Y-m-d');
            $receipt_id    = $query->id;
            $getProducts   = $request->products;
            $documentNames = $request->document_name;
            $photoNames    = $request->photo_name;
            $documents     = [];

            $this->receiptNumber($receipt_id, $query->key_number, $query->created_at);

            if ($getProducts) {
                $products = [];
                foreach (json_decode($getProducts) as $key => $row) {
                    $products[] = [
                        'goods_receipt_id' => $receipt_id,
                        'reference_id'     => $row->reference_id,
                        'product_id'       => $row->product_id,
                        'uom_id'           => $row->uom_id,
                        'qty_order'        => $row->qty_order,
                        'qty_receipt'      => $row->qty_receipt,
                        'rack_id'          => $row->rack_id,
                        'bin_id'           => $row->bin_id,
                        'type'             => $row->type,
                        'created_at'       => $now,
                        'updated_at'       => $now
                    ];
                }

                if (count($products)) {
                    $query = GoodsReceiptProduct::insert($products);
                    if (!$query) {
                        return response()->json([
                            'status'    => false,
                            'message'   => 'Failed to create detail data.'
                        ], 400);
                    }

                    // Calculate and move stock warehouse                    
                    if($status == 'approved'){
                        $calculate = $this->calculateStock($getProducts);
                    }
                }
            }

            if (isset($documentNames)) {
                foreach ($documentNames as $key => $row) {
                    $docName = $row;
                    $docFile = $request->file('attachment')[$key];
                    if (isset($docFile)) {
                        $filename = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower($docName)) . '.' . $docFile->getClientOriginalExtension();
                        $path     = "assets/goodsreceipt/$receipt_id/document";

                        if (!file_exists($path)) {
                            mkdir($path, 0777, true);
                        }

                        $docFile->move($path, $filename);

                        $documents[] = [
                            'goods_receipt_id'     => $receipt_id,
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
                        $path     = "assets/goodsreceipt/$receipt_id/image";

                        if (!file_exists($path)) {
                            mkdir($path, 0777, true);
                        }

                        $photoFile->move($path, $filename);

                        $documents[] = [
                            'goods_receipt_id'     => $receipt_id,
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
                $query = GoodsReceiptAsset::insert($documents);
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
        } else {
            $result = [
                'status'    => false,
                'message'   => 'Failed tp create data.',
                'point'     => 400
            ];
        }

        return response()->json([
            'status'  => $result['status'],
            'message' => $result['message']
        ], $result['point']);
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
            'receipt_date' => 'required',
            'site'         => 'required',
            'warehouse'    => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        $receiptdate = $request->receiptdate;
        $site        = $request->site;
        $warehouse   = $request->warehouse;
        $description = $request->description;
        $status      = $request->status;

        $query = GoodsReceipt::find($id);
        $query->receipt_date = $receiptdate;
        $query->warehouse_id = $warehouse;
        $query->description  = $description;
        $query->status       = $status;
        $query->save();

        if ($query) {
            $now           = date('Y-m-d');
            $receipt_id    = $query->id;
            $getProducts   = $request->products;
            $documentNames = $request->document_name;
            $photoNames    = $request->photo_name;
            $updateDoc    = $request->documents;
            $deleteDoc     = $request->undocuments;
            $documents     = [];

            $cleared = GoodsReceiptProduct::where('goods_receipt_id', $id)->delete();

            if ($getProducts) {
                $products = [];
                foreach (json_decode($getProducts) as $key => $row) {
                    $products[] = [
                        'goods_receipt_id' => $receipt_id,
                        'reference_id'     => $row->reference_id,
                        'product_id'       => $row->product_id,
                        'uom_id'           => $row->uom_id,
                        'qty_order'        => $row->qty_order,
                        'qty_receipt'      => $row->qty_receipt,
                        'rack_id'          => $row->rack_id,
                        'bin_id'           => $row->bin_id,
                        'type'             => $row->type,
                        'created_at'       => $now,
                        'updated_at'       => $now
                    ];
                }

                if (count($products)) {
                    $query = GoodsReceiptProduct::insert($products);
                    if (!$query) {
                        return response()->json([
                            'status'    => false,
                            'message'   => 'Failed to create detail data.'
                        ], 400);
                    }

                    // Calculate and move stock warehouse                    
                    if($status == 'approved'){
                        $calculate = $this->calculateStock($getProducts);
                    }
                }
            }

            if (isset($documentNames)) {
                foreach ($documentNames as $key => $row) {
                    $docName = $row;
                    $docFile = $request->file('attachment')[$key];
                    if (isset($docFile)) {
                        $filename = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower($docName)) . '.' . $docFile->getClientOriginalExtension();
                        $path     = "assets/goodsreceipt/$receipt_id/document";

                        if (!file_exists($path)) {
                            mkdir($path, 0777, true);
                        }

                        $docFile->move($path, $filename);

                        $documents[] = [
                            'goods_receipt_id'  => $receipt_id,
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
                        $path     = "assets/goodsreceipt/$receipt_id/image";

                        if (!file_exists($path)) {
                            mkdir($path, 0777, true);
                        }

                        $photoFile->move($path, $filename);

                        $documents[] = [
                            'goods_receipt_id'  => $receipt_id,
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
                    $transferid = $row->transferID;
                    $filename   = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower($row->docName));
                    $file       = $row->file;
                    $type       = $row->type;
                    $oldfile    = "assets/goodsreceipt/$receipt_id/$type/$file";
                    $ext        = pathinfo($oldfile)['extension'];
                    $newfile    = "assets/goodsreceipt/$receipt_id/$type/$filename.$ext";

                    $rename = rename($oldfile, $newfile);
                    if ($rename) {
                        $query = GoodsReceiptAsset::find($id);
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

                $query = GoodsReceiptAsset::whereIn('id', $id);
                $query->delete();
            }

            if (count($documents) > 0) {
                $query = GoodsReceiptAsset::insert($documents);
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
        } else {
            $result = [
                'status'    => false,
                'message'   => 'Failed to update data.',
                'point'     => 400
            ];
        }

        return response()->json([
            'status'    => $result['status'],
            'message'   => $result['message']
        ], $result['point']);
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
            $query = GoodsReceipt::find($id);
            $query->delete();

            return response()->json([
                'status'    => true,
                'message'   => 'Successfully delete data'
            ], 200);
        } catch (QueryException $th) {
            return response()->json([
                'status'    => false,
                'message'   => 'Error delete data ' . $th->errorInfo[2]
            ], 400);
        }
    }

    public function contractproducts(Request $request)
    {
        $draw        = $request->draw;
        $start       = $request->start;
        $length      = $request->length;
        $query       = $request->search['value'];
        $sort        = $request->columns[$request->order[0]['column']]['data'];
        $dir         = $request->order[0]['dir'];
        $except      = $request->except;
        $category_id = $request->category_id;

        $query = ContractProduct::selectRaw("
            contracts.number as contract_number,
            TO_CHAR(contracts.contract_signing_date,'DD/MM/YYYY') as signing_date,
            contract_products.contract_id,
            contract_products.product_id,
            contract_products.uom_id,
            contract_products.qty,            
            products.name as product,
            products.sku,
            products.is_serial,            
            products.last_serial,
            product_categories.path as category,
            uoms.name as uom
        ");        
        $query->leftJoin('contracts', 'contracts.id', '=', 'contract_products.contract_id');
        $query->leftJoin('products', 'products.id', '=', 'contract_products.product_id');
        $query->leftJoin('uoms', 'uoms.id', '=', 'contract_products.uom_id');        
        $query->leftJoin('product_categories','product_categories.id','=','products.product_category_id');
        $query->where('contracts.status', 'approved');
        $query->whereNotIn('contract_products.product_id', function($que){
            $que->selectRaw("goods_receipt_products.product_id");
            $que->from('goods_receipt_products');
            $que->leftJoin('goods_receipts','goods_receipts.id','goods_receipt_products.goods_receipt_id');
            $que->where([
                ['goods_receipts.status','=','approved'],
                ['goods_receipt_products.type','=','contract']
            ]);
        });
        if($category_id){
            $query->where('product_categories.id',$category_id);
        }
        if ($except) {
            $query->whereNotIn('contract_products.product_id', $except);
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
        $draw        = $request->draw;
        $start       = $request->start;
        $length      = $request->length;
        $query       = $request->search['value'];
        $sort        = $request->columns[$request->order[0]['column']]['data'];
        $dir         = $request->order[0]['dir'];
        $except      = $request->except;
        $category_id = $request->category_id;

        $query = ProductBorrowingDetail::selectRaw("
            product_borrowings.borrowing_number,
            TO_CHAR(product_borrowings.borrowing_date,'DD/MM/YYYY') as date_borrowing,
            product_borrowing_details.product_borrowing_id,
            product_borrowing_details.product_id,
            product_borrowing_details.uom_id,
            product_borrowing_details.qty_requested as qty,
            products.name as product,
            products.sku,
            products.is_serial,            
            products.last_serial,
            product_categories.path as category,
            uoms.name as uom
        ");
        $query->leftJoin('product_borrowings', 'product_borrowings.id', '=', 'product_borrowing_details.product_borrowing_id');
        $query->leftJoin('products', 'products.id', '=', 'product_borrowing_details.product_id');
        $query->leftJoin('uoms', 'uoms.id', '=', 'product_borrowing_details.uom_id');
        $query->leftJoin('product_categories','product_categories.id','=','products.product_category_id');
        $query->where('product_borrowings.status', 'approved');    
        $query->whereNotIn('product_borrowing_details.product_id',function($que){
            $que->selectRaw("goods_receipt_products.product_id");
            $que->from('goods_receipt_products');
            $que->leftJoin('goods_receipts','goods_receipts.id','=','goods_receipt_products.goods_receipt_id');
            $que->where([
                ['goods_receipts.status','=','approved'],
                ['goods_receipt_products.type','=','borrowing']
            ]);
        });  
        if($category_id){
            $query->where('product_categories.id',$category_id);
        }
        if($except){
            $query->whereNotIn('product_borrowing_details.product_id',$except);
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

    function receiptNumber($id, $key_number, $created_at)
    {
        $key   = preg_split("/[\s-]+/", "$key_number");
        $code  = $key[0];
        $year  = $key[1];
        $index = $key[2];
        $month = date('m', strtotime($created_at));

        $number = "$code-$year-$month-$index";

        $query = GoodsReceipt::find($id);
        $query->good_receipt_no = $number;
        $query->save();
    }
    
    function calculateStock($products){
        foreach (json_decode($products) as $key => $row) {
            $site_id      = $row->site_id;
            $warehouse_id = $row->warehouse_id;
            $product_id   = $row->product_id;
            $qty_receipt  = $row->qty_receipt;
            $ref_type     = $row->type;
            $now          = date('Y-m-d H:i:s');
            
            if($ref_type == 'contract'){
                $virtual = $this->generateWarehouseVirtual($site_id);
            }

            $product = Product::find($product_id);

            if($product->is_serial == '1'){
                $serials = [];

                foreach ($row->serials as $key => $num) {
                    $serials[] = [
                        'warehouse_id'  => $num->warehouse_id,
                        'product_id'    => $num->product_id,
                        'serial_number' => $num->serial_number,
                        'status'        => 1,
                        'created_at'    => $now,
                        'updated_at'    => $now
                    ];                    
                }
                
                $query = ProductSerial::insert($serials);

                if($query){
                    $product->last_serial = $product->last_serial+$qty_receipt;
                    $product->save();
                }else{
                    return response()->json([
                        'status'    => false,
                        'message'   => 'Failed to update data product serials.'
                    ],400);
                }
            }

            // Checking stock product on warehouse
            $stock = StockWarehouse::where([
                ['stock_warehouses.warehouse_id','=',$warehouse_id],
                ['stock_warehouses.product_id','=',$product_id]
            ])->first();
            
            if($stock){
                $query = $stock;
                $query->stock = $stock->stock + $qty_receipt;
                $query->save();

                if(!$query){
                    return response()->json([
                        'status'    => false,
                        'message'   => 'Failed to update stock on warehouse.'
                    ],400);
                }
            }else{
                $query = StockWarehouse::create([
                    'product_id'    => $product_id,
                    'warehouse_id'  => $warehouse_id,
                    'stock'         => $qty_receipt
                ]);

                if(!$query){
                    return response()->json([
                        'status'    => false,
                        'message'   => 'Failed to update stock on warehouse.'
                    ],400);
                }
            }

        }
    }

    function generateWarehouseVirtual($site_id)
    {
        $query = Warehouse::where([
            ['site_id','=',$site_id],
            ['type','=','virtual']
        ])->first();

        if(!$query){
            $site     = Site::find($site_id);
            $sitecode = strtoupper($site->code);
            $sitename = $site->name;

            $query = Warehouse::create([
                'code'          => "$sitecode - WV",
                'name'          => "$sitename - Warehouse Virtual",
                'type'          => 'virtual',
                'site_id'       => $site_id,
                'description'   => "Warehouse virtual of Unit or Site $sitename",
                'postal_code'   => 0,
                'address'       => '---',
                'status'        => 'active'
            ]);

            if($query){
                return true;
            }else{
                return false;
            }
        }

    }
}   