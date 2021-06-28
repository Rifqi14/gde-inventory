<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Database\QueryException;

use App\Models\Menu;
use App\Models\GoodsIssue;
use App\Models\GoodsIssueProduct;
use App\Models\GoodsIssueDocument;
use App\Models\ProductConsumableDetail;
use App\Models\ProductTransferDetail;

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
                        uoms.name as uom,                    
                        product_consumables.consumable_number as reference,
                        rack_warehouses.name as rack,
                        bin_warehouses.name as bin
                    ");
                    $products->leftJoin('products','products.id','=','goods_issue_products.product_id');
                    $products->leftJoin('uoms','uoms.id','=','goods_issue_products.uom_id');
                    $products->leftJoin('rack_warehouses', 'rack_warehouses.id', '=', 'goods_issue_products.rack_id');
                    $products->leftJoin('bin_warehouses', 'bin_warehouses.id', '=', 'goods_issue_products.bin_id');
                    $products->leftJoin('product_consumables','product_consumables.id','=','goods_issue_products.reference_id');
                },
                'transferproducts' => function($products){
                    $products->selectRaw("
                        goods_issue_products.*,
                        products.name as product,
                        uoms.name as uom,                    
                        product_transfers.transfer_number as reference,
                        rack_warehouses.name as rack,
                        bin_warehouses.name as bin
                    ");
                    $products->leftJoin('products','products.id','=','goods_issue_products.product_id');
                    $products->leftJoin('uoms','uoms.id','=','goods_issue_products.uom_id');
                    $products->leftJoin('rack_warehouses', 'rack_warehouses.id', '=', 'goods_issue_products.rack_id');
                    $products->leftJoin('bin_warehouses', 'bin_warehouses.id', '=', 'goods_issue_products.bin_id');
                    $products->leftJoin('product_transfers','product_transfers.id','=','goods_issue_products.reference_id');
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
                        uoms.name as uom,                    
                        product_consumables.consumable_number as reference,
                        rack_warehouses.name as rack,
                        bin_warehouses.name as bin
                    ");
                    $products->leftJoin('products','products.id','=','goods_issue_products.product_id');
                    $products->leftJoin('uoms','uoms.id','=','goods_issue_products.uom_id');
                    $products->leftJoin('rack_warehouses', 'rack_warehouses.id', '=', 'goods_issue_products.rack_id');
                    $products->leftJoin('bin_warehouses', 'bin_warehouses.id', '=', 'goods_issue_products.bin_id');
                    $products->leftJoin('product_consumables','product_consumables.id','=','goods_issue_products.reference_id');
                },
                'transferproducts' => function($products){
                    $products->selectRaw("
                        goods_issue_products.*,
                        products.name as product,
                        uoms.name as uom,                    
                        product_transfers.transfer_number as reference,
                        rack_warehouses.name as rack,
                        bin_warehouses.name as bin
                    ");
                    $products->leftJoin('products','products.id','=','goods_issue_products.product_id');
                    $products->leftJoin('uoms','uoms.id','=','goods_issue_products.uom_id');
                    $products->leftJoin('rack_warehouses', 'rack_warehouses.id', '=', 'goods_issue_products.rack_id');
                    $products->leftJoin('bin_warehouses', 'bin_warehouses.id', '=', 'goods_issue_products.bin_id');
                    $products->leftJoin('product_transfers','product_transfers.id','=','goods_issue_products.reference_id');
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
                $products = [];
                foreach (json_decode($getProducts) as $key => $row) {
                    $products[] = [
                        'goods_issue_id'   => $issued_id,
                        'reference_id'     => $row->reference_id,
                        'product_id'       => $row->product_id,
                        'uom_id'           => $row->uom_id,
                        'qty_request'      => $row->qty_request,
                        'qty_receive'      => $row->qty_receive,
                        'rack_id'          => $row->rack_id,
                        'bin_id'           => $row->bin_id,
                        'type'             => $row->type,
                        'created_at'       => $now,
                        'updated_at'       => $now
                    ];
                }

                if (count($products)) {
                    $query = GoodsIssueProduct::insert($products);
                    if (!$query) {
                        return response()->json([
                            'status'    => false,
                            'message'   => 'Failed to create detail data.'
                        ], 400);
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
                $products = [];
                foreach (json_decode($getProducts) as $key => $row) {
                    $products[] = [
                        'goods_issue_id'   => $issued_id,
                        'reference_id'     => $row->reference_id,
                        'product_id'       => $row->product_id,
                        'uom_id'           => $row->uom_id,
                        'qty_request'      => $row->qty_request,
                        'qty_receive'      => $row->qty_receive,
                        'rack_id'          => $row->rack_id,
                        'bin_id'           => $row->bin_id,
                        'type'             => $row->type,
                        'created_at'       => $now,
                        'updated_at'       => $now
                    ];
                }

                if (count($products)) {
                    $query = GoodsIssueProduct::insert($products);
                    if (!$query) {
                        return response()->json([
                            'status'    => false,
                            'message'   => 'Failed to create detail data.'
                        ], 400);
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
        $draw       = $request->draw;
        $start      = $request->start;
        $length     = $request->length;
        $query      = $request->search['value'];
        $sort       = $request->columns[$request->order[0]['column']]['data'];
        $dir        = $request->order[0]['dir'];
        $except     = $request->except;

        $query = ProductConsumableDetail::selectRaw("
            product_consumables.consumable_number,
            TO_CHAR(product_consumables.consumable_date,'DD/MM/YYYY') as date_consumable,
            product_consumable_details.product_consumable_id,
            product_consumable_details.product_id,
            product_consumable_details.uom_id,
            product_consumable_details.qty_consume as qty,
            products.name as product,
            uoms.name as uom
        ");
        $query->leftJoin('product_consumables','product_consumables.id','=','product_consumable_details.product_consumable_id');
        $query->leftJoin('products','products.id','=','product_consumable_details.product_id');
        $query->leftJoin('uoms','uoms.id','=','product_consumable_details.uom_id');
        $query->where('product_consumables.status','approved');
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
        $draw       = $request->draw;
        $start      = $request->start;
        $length     = $request->length;
        $query      = $request->search['value'];
        $sort       = $request->columns[$request->order[0]['column']]['data'];
        $dir        = $request->order[0]['dir'];
        $except     = $request->except;

        $query = ProductTransferDetail::selectRaw("
            product_transfers.transfer_number,
            TO_CHAR(product_transfers.date_transfer,'DD/MM/YYYY') as transfer_date,
            product_transfer_details.product_transfer_id,
            product_transfer_details.product_id,
            product_transfer_details.uom_id,
            product_transfer_details.qty_requested as qty,
            products.name as product,
            uoms.name as uom
        ");
        $query->leftJoin('product_transfers','product_transfers.id','=','product_transfer_details.product_transfer_id');
        $query->leftJoin('products','products.id','=','product_transfer_details.product_id');
        $query->leftJoin('uoms','uoms.id','=','product_transfer_details.uom_id');
        $query->where('product_transfers.status','approved');
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
            $data[]  = $row;
        }

        return response()->json([
            'draw'              => $draw,
            'recordsTotal'      => $total,
            'recordsFiltered'   => $total,
            'data'              => $data
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
}
