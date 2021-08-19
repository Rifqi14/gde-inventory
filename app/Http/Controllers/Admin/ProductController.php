<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

use App\Models\Menu;
use App\Models\Product;
use App\Models\MinMaxProduct;
use App\Models\ProductUom;
use App\Models\Site;
use App\Models\ProductCategory;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Phpoffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ProductController extends Controller
{
    function __construct()
    {
        $menu   = Menu::where('menu_route', 'product')->first();
        $parent = Menu::find($menu->parent_id);
        View::share('parent_name', $parent->menu_name);
        View::share('menu_name', $menu->menu_name);
        View::share('menu_active', url('admin/' . 'product'));
        $this->middleware('accessmenu', ['except' => ['select']]);
    }

    public function index(){
        return view('admin.product.index');
    }
    
    public function read(Request $request){
        $start = $request->start;
        $length = $request->length;
        $query = $request->search['value'];
        // $sort = $request->columns[$request->order[0]['column']]['data'];
        // $dir = $request->order[0]['dir'];
        $name = strtoupper($request->name);
        $product_category_id = $request->product_category_id;

        //Count Data
        $query = ProductCategory::when($name, function ($q) use ($name) {
            $q->whereHas('products', function($qy) use ($name){
                $qy->whereRaw("upper(name) like '%$name%'");
            });
        });
        if($product_category_id){
            $query->where('id',$product_category_id);
        }

        $row = clone $query;
        $recordsTotal = $row->count();

        $query->offset($start);
        $query->limit($length);
        // $query->orderBy($sort, $dir);
        $products = $query->get();

        $data = [];
        foreach ($products as $keyProduct => $product) {
            $data[]   = [
                'id'        => $product->id,
                'name'      => $product->path,
                'stock'     => 0,
                'isParent'  => true,
                'children'  => [],
            ];
            foreach ($product->products()->get() as $key => $value) {
                $data[$keyProduct]['children'][]   = [
                    'id'        => $value->id,
                    'name'      => $value->name,
                    'isParent'  => false,
                    'stock'     => 0,
                    'children'  => [],
                ];
            };
            // $data[] = $product;
        }
        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'data' => $data
        ], 200);
    }

    public function create(Request $request)
    {
        if(in_array('create',$request->actionmenu)){
            $sites = Site::all();
            return view('admin.product.create', compact('sites'));
        }else{
            abort(403);
        }
    }

    public function store(Request $request){
        $validator = Validator::make($request->all(), [
            'name'                  => 'required',
            'product_category_id'   => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();

        $data = [
            'name'                   => $request->name,
            'description'            => $request->description,
            'product_category_id'    => $request->product_category_id,
            'merek'                  => $request->merek,
            'sku'                    => $request->sku,
            'is_serial'              => $request->is_serial ? '1' : '0',
        ];

        $attach = $request->file('image');
        if ($request->hasFile('image')) {
            $path = 'assets/product/';
			if (!file_exists($path)) {
				mkdir($path, 0777, true);
            }
            $product_name = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower($request->name));
            $attach->move($path, $product_name.'.'.$attach->getClientOriginalExtension());
            $filename = $path.$product_name.'.'.$attach->getClientOriginalExtension();
			$data['image'] = $filename;
        }

        $product = Product::create($data);
        
        if($product) {
            if($request->uom_id){
                foreach($request->uom_id as $key => $uom){
                    $uom = ProductUom::create([
                        'product_id' => $product->id,
                        'uom_id' => $uom,
                        'ratio' => $request->ratio[$key],
                        'is_show' => $request->show[$key],
                    ]);
                    if(!$uom){
                        DB::rollback();
                        return response()->json([
                            'status' => false,
                            'message'  => "Can't insert data UOM"
                        ], 400);
                    }
                }
            }

            if($request->minmax_site){
                foreach($request->minmax_site as $key => $site){
                    $minmax = MinMaxProduct::create([
                        'site_id' => $site,
                        'minimum' => $request->minimum[$key],
                        'maximum' => $request->maximum[$key],
                        'product_id' => $product->id,
                    ]); 
                    if(!$minmax){
                        DB::rollback();
                        return response()->json([
                            'status' => false,
                            'message'  => "Can't insert data minimum & maximum"
                        ], 400);
                    }
                }
            }

            DB::commit();
            return response()->json([
                'status' => true,
                'message'  => "Data has been saved",
                'results'  => route("product.index"),
                'id' => $product->id
            ], 200);
        }else{
            DB::rollback();
            return response()->json([
                'status'    => false,
                'message'   => 'Failed Insert Product.'
            ], 400);
        }
    }

    public function edit(Request $request,$id)
    {
        if(in_array('update',$request->actionmenu)){
            $product = Product::with('uoms','minmax')->find($id);
            if ($product) {
                if($product->category->parent_id != 0){
                    $product->category_name = $this->getParent($product->category->parent_id, $product->category->name);
                }else{
                    $product->category_name = $product->category->name;
                }
                return view('admin.product.edit', compact('product'));
            } else {
                abort(404);
            }
        }else{
            abort(403);
        }
    }

    public function getParent($id, $name){
        $categorys = ProductCategory::select('*')->where("id",$id)->get();
        
        foreach ($categorys as $category) {
            $name = $category->name.' &nbsp;&nbsp;<span style="font-size: 23px;position:relative;top: 2px;line-height: 0.1;">&rsaquo;</span>&nbsp;&nbsp;  '.$name;
            if($category->parent_id != 0){
                $name = $this->getParent($category->parent_id, $name);
            }
        }

        return $name;
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name'                  => 'required',
            'product_category_id'   => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'     => false,
                'message'     => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();

        $product = Product::find($id);
        $product->name = $request->name;
        $product->description = $request->description;
        $product->product_category_id = $request->product_category_id;
        $product->merek = $request->merek;
        $product->sku   = $request->sku;
        $product->is_serial = $request->is_serial ? '1' : '0';

        $attach = $request->file('image');
        if ($request->hasFile('image')) {
            $path = 'assets/product/';
			if (!file_exists($path)) {
				mkdir($path, 0777, true);
            }
            $product_name = preg_replace('/[^a-zA-Z0-9_-]+/', '-', strtolower($request->name));
            $attach->move($path, $product_name.'.'.$attach->getClientOriginalExtension());
            $filename = $path.$product_name.'.'.$attach->getClientOriginalExtension();
			$product->image = $filename;
        }

        if($product->save()){
            $deleteuom = ProductUom::where('product_id',$id)->delete();
            if($request->uom_id){
                foreach($request->uom_id as $key => $uom){
                    $uom = ProductUom::create([
                        'product_id' => $id,
                        'uom_id' => $uom,
                        'ratio' => $request->ratio[$key],
                        'is_show' => $request->show[$key],
                    ]);
                    if(!$uom){
                        DB::rollback();
                        return response()->json([
                            'status' => false,
                            'message'  => "Can't insert data UOM"
                        ], 400);
                    }
                }
            }

            $deleteminmax = MinMaxProduct::where('product_id',$id)->delete();
            if($request->minmax_site){
                foreach($request->minmax_site as $key => $site){
                    $minmax = MinMaxProduct::create([
                        'site_id' => $site,
                        'minimum' => $request->minimum[$key],
                        'maximum' => $request->maximum[$key],
                        'product_id' => $id,
                    ]); 
                    if(!$minmax){
                        DB::rollback();
                        return response()->json([
                            'status' => false,
                            'message'  => "Can't insert data minimum & maximum"
                        ], 400);
                    }
                }
            }

            DB::commit();
            return response()->json([
                'status' => true,
                'message'  => "Data has been updated",
                'results'  => route("product.index"),
                'id' => $product->id
            ], 200);

        }else{
            DB::rollback();
            return response()->json([
                'status' => false,
                'message'     => 'Failed update data product'
            ], 400);
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::find($id);
            $product->delete();
        } catch (QueryException $th) {
            return response()->json([
                'status'    => false,
                'message'   => 'Error delete data ' . $th->errorInfo[2]
            ], 400);
        }
        return response()->json([
            'status'    => true,
            'message'   => 'Failed delete data'
        ], 200);
    }

    public function show(Request $request,$id)
    {
        if(in_array('read',$request->actionmenu)){
            $product = Product::with('uoms','minmax')->find($id);
            if ($product) {
                if($product->category->parent_id != 0){
                    $product->category_name = $this->getParent($product->category->parent_id, $product->category->name);
                }else{
                    $product->category_name = $product->category->name;
                }
                return view('admin.product.detail', compact('product'));
            } else {
                abort(404);
            }
        }else{
            abort(403);
        }
    }

    public function select(Request $request)
    {
        $start               = $request->page ? $request->page - 1 : 0;
        $length              = $request->limit;
        $name                = strtoupper($request->name);
        $product_category_id = $request->product_category_id;
        $productException    = $request->products ? $request->products : null;
        $warehouse_id        = $request->warehouse_id;
    
        $query = Product::selectRaw("
            products.*,            
            product_uoms.uom_id,
            uoms.name as uom,
            product_categories.name as category
        ");        
        $query->leftJoin('product_uoms','product_uoms.product_id','=','products.id');
        $query->leftJoin('uoms','uoms.id','=','product_uoms.uom_id');
        $query->leftJoin('uom_categories','uom_categories.id','=','uoms.uom_category_id');
        $query->leftJoin('product_categories','product_categories.id','=','products.product_category_id');
        if($warehouse_id){
            $query->selectRaw("stock_warehouses.stock::INTEGER as stock_warehouse");
            $query->leftJoin('stock_warehouses',function($join) use ($warehouse_id){
                $join->on('stock_warehouses.product_id','=','products.id');
                $join->on('stock_warehouses.warehouse_id','=',DB::raw($warehouse_id));
            });
        }
        if($name){
            $query->whereRaw("
                upper(products.name) like '%$name%'
            ");
        }
        if($product_category_id){
            $query->where('product_category_id',$product_category_id);
        }
        if ($productException) {
            $query->whereNotIn('products.id', $productException);
        }
    
        $rows  = clone $query;
        $total = $rows->count();
    
        $query->offset($start);
        $query->limit($length);
        $queries = $query->get();
    
        $data = [];
        foreach ($queries as $key => $row) {                    
            $data[] = $row;
        }
    
        return response()->json([
            'total' => $total,
            'rows'  => $data
        ],200);
    }

    public function export(Request $request)
    {
        $start    = date('Y-m-01', strtotime($request->start));
        $end      = date('Y-m-t',strtotime($request->end));
        $category = $request->category_id;
        $year     = date('Y');        

        $query = ProductCategory::with([            
            'subcategories' => function($sub){                
                $sub->selectRaw("
                    product_categories.id,
                    product_categories.parent_id,
                    product_categories.name as subcategory
                ");                
                $sub->with([
                    'products' => function($product){
                        $product->selectRaw("
                            products.id,
                            products.product_category_id,
                            products.name as product
                        ");                        
                        $product->with([
                            'receipts' => function($movein){
                                $movein->selectRaw("
                                    goods_receipts.receipt_date as date_movement,
                                    goods_receipt_products.product_id,
                                    goods_receipt_products.qty_receipt as qty,
                                    uoms.name as uom,
                                    sites.name as site
                                ");
                                $movein->join('goods_receipts','goods_receipts.id','=','goods_receipt_products.goods_receipt_id');
                                $movein->join('uoms','uoms.id','=','goods_receipt_products.uom_id');
                                $movein->join('warehouses','warehouses.id','=','goods_receipts.warehouse_id');
                                $movein->join('sites','sites.id','=','warehouses.site_id');
                                $movein->where('goods_receipts.status','approved');
                            },
                            'issues' => function($moveout){
                                $moveout->selectRaw("
                                    goods_issues.date_issued as date_movement,
                                    goods_issue_products.product_id,
                                    goods_issue_products.qty_receive as qty,
                                    uoms.name as uom,
                                    sites.name as site
                                ");                                
                                $moveout->join('goods_issues','goods_issues.id','=','goods_issue_products.goods_issue_id');
                                $moveout->join('uoms','uoms.id','=','goods_issue_products.uom_id');
                                $moveout->join('warehouses','warehouses.id','=','goods_issues.warehouse_id');
                                $moveout->join('sites','sites.id','=','warehouses.site_id');
                                $moveout->whereIn('goods_issues.status',['approved','borrowed']);
                            }
                        ]);
                    }
                ]);                
            }
        ]);
        $query->selectRaw("
            product_categories.id,
            product_categories.parent_id,
            product_categories.name as category
        ");           
        if($category){
            $query->where('product_categories.id',$category);
        }else{
            $query->where('product_categories.parent_id',0);
        }        
        $queries = $query->get();

        if(!$queries){
            return response()->json([
                'status'    => false,
                'message'   => 'Failed to collect data.'
            ],400);    
        }

        $categories = [];
        foreach($queries as $row){                          
            $products = [];
            foreach($row->subcategories as $sub){
                foreach($sub->products as $product){
                    if(count($product->receipts) == 0 && count($product->issues) == 0){
                        continue;
                    }
                    $products[] = [
                        'product'   => $product->product,
                        'receipts'  => $product->receipts,
                        'issues'    => $product->issues
                    ];
                }
            }            
            if(count($row->subcategories) > 0 && count($products) > 0){                                                                  
                $categories[] = [
                    'category' => $row->category,
                    'products' => $products
                ];                                
            }
        }
        
        
        $build  = new Spreadsheet();         
        // Default Style
        $build->getDefaultStyle()->getFont()->setName('Arial');       

        $ranges = getDatesFromRange($start,$end, 'F Y','1 month');
        $header = [
            'left' => [
                'PT Geo Dipa Energi (Persero)',
                'Aldevco Octagon 2nd Floor',
                'Jl. Warung Jati Barat No. 75',
                'Jakarta Selatan 12740 - Indonesia'
            ],
            'right' => [
                'PHYSICAL CHECK MATERIAL INVENTORY',
                'STOCK ON HAND',
                'PT GEO DIPA ENERGY (PERSERO)',
                "$year"
            ]
        ];            

        foreach ($ranges as $key => $range) {        
            $sheetname = strtoupper($range);
            $worksheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($build, "SOH $sheetname");
            $build->addSheet($worksheet,$key);

            $sheet      = $build->getSheet($key);            

            $highestRow = $sheet->getHighestRow();
            $highestCol = $sheet->getHighestColumn();

            $columns = [
                ['index' => 0, 'width' => 5],
                ['index' => 1, 'width' => 80],
                ['index' => 2, 'width' => 20],
                ['index' => 3, 'width' => 15],
                ['index' => 4, 'width' => 15],
                ['index' => 5, 'width' => 15],
                ['index' => 6, 'width' => 10],
                ['index' => 7, 'width' => 20],
                ['index' => [9, 70], 'width' => 5],
                ['index' => [71, 78], 'width' => 10],
                ['index' => [79, 80], 'width' => 15],
                ['index' => [81, 82], 'width' => 30],
            ];

            foreach ($columns as $key => $value) {
                if(is_array($value['index'])){
                    $start = current($value['index']);
                    $end   = end($value['index']);

                    for($i = $start; $i <= $end; $i++){
                        $column = $this->getColumnFromNumber($i);
                        $width  = $value['width'];
                        $uom    = isset($value['uom'])?$value['uom']:'pt';

                        $sheet->getColumnDimension("$column")->setWidth($width,"$uom");
                    }
                }else{
                    $column = $this->getColumnFromNumber($value['index']);
                    $width  = $value['width'];
                    $uom    = isset($value['uom'])?$value['uom']:'pt';

                    $sheet->getColumnDimension("$column")->setWidth($width,"$uom");
                }                
            }

            $sheet->freezePane('C10');                        

            for ($i=1; $i <= 7 ; $i++) { 
                $sheet->getRowDimension("$i")->setRowHeight(18.5, 'pt');
            }

            foreach ($header['left'] as $key => $text) {                
                $highestRow = $highestRow + 1;

                $rightHeader = $header['right'][$key];
                
                $sheet->setCellValue("B$highestRow", $text);
                $sheet->setCellValue("D$highestRow", $rightHeader);
                $sheet->getStyle("B$highestRow:D$highestRow")->applyFromArray([
                    'font' => [                    
                        'bold' => true,
                        'size' => 12,
                        'color' => ['rgb' => '0000FF']
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER                    
                    ]
                ]);                
            }                

            //  Add the In-Memory image to a worksheet
            $logo = new Drawing();
            $logo->setName('Logo');
            $logo->setDescription('Logo');
            $logo->setPath(public_path('assets/logo.png'));
            $logo->setCoordinates('B1');
            $logo->setHeight(150);
            $logo->setWidth(150);
            $logo->setOffsetX(15);   
            $logo->setOffsetY(10);            
            $logo->setWorksheet($sheet);

            $thead = [
                [
                    ['column' => 'A', 'rows' => [8,9], 'merge' => true, 'label' => 'No.', 'style' => [
                            'font'      => ['bold' => true, 'size' => 12],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                            'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => 'b7dee8']],
                            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                        ]
                    ],
                    ['column' => 'B', 'rows' => [8,9], 'merge' => true, 'label' => 'Description', 'style' => [
                            'font'      => ['bold' => true, 'size' => 12],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                            'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => 'b7dee8']],
                            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                        ]
                    ],
                    ['column' => 'C', 'rows' => [8,9], 'merge' => true, 'label' => 'Part Number', 'style' => [
                            'font'      => ['bold' => true, 'size' => 12],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                            'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => 'b7dee8']],
                            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                        ]
                    ],
                    ['column' => 'D', 'rows' => [8,9], 'merge' => true, 'label' => 'Serial Number', 'style' => [
                            'font'      => ['bold' => true, 'size' => 12],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                            'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => 'b7dee8']],
                            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                        ]
                    ],
                    ['column' => ['E','F'], 'rows' => 8, 'merge' => true, 'label' => 'Qty', 'style' => [
                            'font'      => ['bold' => true, 'size' => 12, 'color' => ['argb' => '0000FF']],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                            'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => 'b7dee8']],
                            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                        ]
                    ],
                    ['column' => 'G', 'rows' => [8,9], 'merge' => true, 'label' => 'Unit', 'style' => [
                            'font'      => ['bold' => true, 'size' => 12, 'color' => ['argb' => '0000FF']],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                            'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => 'b7dee8']],
                            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                        ]
                    ],
                    ['column' => 'H', 'rows' => [8,9], 'merge' => true, 'label' => 'Loc. /Rack /Bin', 'style' => [
                            'font'      => ['bold' => true, 'size' => 12],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                            'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => 'b7dee8']],
                            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                        ]
                    ],
                    ['column' => 'I', 'rows' => 8, 'merge' => false, 'label' => 'Status', 'style' => [
                            'font'      => ['bold' => true, 'size' => 12],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                            'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => 'b7dee8']],
                            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                        ]
                    ],
                    ['column' => ['J','BS'], 'rows' => 8, 'merge' => true, 'label' => $range, 'style' => [
                            'font'      => ['bold' => true, 'size' => 12],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                            'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => 'b7dee8']],
                            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                        ]
                    ],
                    ['column' => ['BT','BU'], 'rows' => [8, 9], 'merge' => true, 'label' => 'In / Out', 'style' => [
                            'font'      => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'fdfefe']],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                            'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => '4f81bd']],
                            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                        ]
                    ],
                    ['column' => ['BV','BW'], 'rows' => 8, 'merge' => true, 'label' => 'S.O.H', 'style' => [
                            'font'      => ['bold' => true, 'size' => 12],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                            'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => 'b7dee8']],
                            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                        ]
                    ],
                    ['column' => 'BX', 'rows' => [8, 9], 'merge' => true, 'label' => 'Unit', 'style' => [
                            'font'      => ['bold' => true, 'size' => 12],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                            'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => 'b7dee8']],
                            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                        ]
                    ],
                    ['column' => ['BY','CA'], 'rows' => 8, 'merge' => true, 'label' => 'STOCK', 'style' => [
                            'font'      => ['bold' => true, 'size' => 12],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                            'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => 'b7dee8']],
                            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                        ]
                    ],
                    ['column' => ['CB','CD'], 'rows' => 8, 'merge' => true, 'label' => 'STATUS', 'style' => [
                            'font'      => ['bold' => true, 'size' => 12],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                            'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => 'b7dee8']],
                            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                        ]
                    ],
                    ['column' => 'CE', 'rows' => [8,9], 'merge' => true, 'label' => 'KETERANGAN', 'style' => [
                            'font'      => ['bold' => true, 'size' => 12],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                            'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => 'b7dee8']],
                            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                        ]
                    ],      
                ],
                [                    
                    ['column' => ['E','F'], 'rows' => 9, 'merge' => true, 'label' => date('t-M', strtotime($range)), 'style' => [
                            'font'      => ['bold' => true, 'size' => 12, 'color' => ['argb' => '0000FF']],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                            'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => 'b7dee8']],
                            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                        ]
                    ],                    
                    ['column' => 'I', 'rows' => 9, 'merge' => false, 'label' => 'In / Out', 'style' => [
                            'font'      => ['bold' => true, 'size' => 12],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                            'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => 'b7dee8']],
                            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                        ]
                    ],    
                    ['column' => ['BV','BW'], 'rows' => 9, 'merge' => true, 'label' => "", 'style' => [
                            'font'      => ['bold' => true, 'size' => 12],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                            'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => 'b7dee8']],
                            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                        ]
                    ],     
                    ['column' => 'BY', 'rows' => 9, 'merge' => false, 'label' => 'Min (D)', 'style' => [
                            'font'      => ['bold' => true, 'size' => 12],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                            'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => 'ff0000']],
                            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                        ]
                    ],               
                    ['column' => 'BZ', 'rows' => 9, 'merge' => false, 'label' => 'Min (P)', 'style' => [
                            'font'      => ['bold' => true, 'size' => 12],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                            'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => 'ff0000']],
                            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                        ]
                    ],               
                    ['column' => 'CA', 'rows' => 9, 'merge' => false, 'label' => 'Max', 'style' => [
                            'font'      => ['bold' => true, 'size' => 12],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                            'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => '00b050']],
                            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                        ]
                    ],         
                    ['column' => ['CB','CC'], 'rows' => 9, 'merge' => true, 'label' => 'Stock', 'style' => [
                            'font'      => ['bold' => true, 'size' => 12],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                            'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => 'b7dee8']],
                            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                        ]
                    ],
                    ['column' => 'CD', 'rows' => 9, 'merge' => false, 'label' => 'Order', 'style' => [
                            'font'      => ['bold' => true, 'size' => 12],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                            'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => 'b7dee8']],
                            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                        ]
                    ],                          
                ],
                [                          
                    ['column' => 'E', 'rows' => 10, 'merge' => false, 'label' => 'DIENG', 'style' => [
                            'font'      => ['bold' => true, 'size' => 12, 'color' => ['argb' => '963634']],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                            'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => 'b8cce4']],
                            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                        ]
                    ],
                    ['column' => 'F', 'rows' => 10, 'merge' => false, 'label' => 'PATUHA', 'style' => [
                            'font'      => ['bold' => true, 'size' => 12, 'color' => ['argb' => '0000ff']],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                            'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => 'fabf8f']],
                            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                        ]
                    ],                    
                    ['column' => 'BT', 'rows' => 10, 'merge' => false, 'label' => 'DIENG', 'style' => [
                            'font'      => ['bold' => true, 'size' => 12, 'color' => ['argb' => '963634']],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                            'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => 'b8cce4']],
                            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                        ]
                    ],
                    ['column' => 'BU', 'rows' => 10, 'merge' => false, 'label' => 'PATUHA', 'style' => [
                            'font'      => ['bold' => true, 'size' => 12, 'color' => ['argb' => '0000ff']],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                            'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => 'fabf8f']],
                            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                        ]
                    ],
                    ['column' => 'BV', 'rows' => 10, 'merge' => false, 'label' => 'DIENG', 'style' => [
                            'font'      => ['bold' => true, 'size' => 12, 'color' => ['argb' => '963634']],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                            'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => 'b8cce4']],
                            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                        ]
                    ],
                    ['column' => 'BW', 'rows' => 10, 'merge' => false, 'label' => 'PATUHA', 'style' => [
                            'font'      => ['bold' => true, 'size' => 12, 'color' => ['argb' => '0000ff']],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                            'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => 'fabf8f']],
                            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                        ]
                    ],                    
                    ['column' => 'CB', 'rows' => 10, 'merge' => false, 'label' => 'DIENG', 'style' => [
                            'font'      => ['bold' => true, 'size' => 12, 'color' => ['argb' => '963634']],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                            'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => 'b8cce4']],
                            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                        ]
                    ],
                    ['column' => 'CC', 'rows' => 10, 'merge' => false, 'label' => 'PATUHA', 'style' => [
                            'font'      => ['bold' => true, 'size' => 12, 'color' => ['argb' => '0000ff']],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                            'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => 'fabf8f']],
                            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                        ]
                    ],
                ]
            ];

            for($i = 0; $i <= 82; $i++){   

                if($i == 4 || $i == 5){
                    continue;
                }else if($i >= 71 && $i <= 74){
                    continue;
                }else if($i == 79 || $i == 80){
                    continue;
                }

                $column = $this->getColumnFromNumber($i);                
                $param = ['column' => $column, 'rows' => 10, 'merge' => true, 'label' => '', 'style' => [
                        'font'      => ['bold' => true, 'size' => 12],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                        'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => 'b8cce4']],
                        'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                    ]
                ];

                array_push($thead[2], $param);                
            }            


            for($i = 9, $d = 1; $i <= 70, $d <= 31; $i+=2, $d++){
                $col1 = $this->getColumnFromNumber($i);
                $col2 = $this->getColumnFromNumber($i+1);

                if($i >= 9 && $i < 70){
                    $cols = [$col1, $col2];
                }else{
                    $cols = [$col1];
                }

                foreach($cols as $key => $col){
                    if($key == 0){
                        $label = 'D';
                        $color = 'b8cce4';
                    }else{
                        $label = 'P';
                        $color = 'fabf8f';
                    }

                    $param = ['column' => $col, 'rows' => 10, 'merge' => true, 'label' => "$label", 'style' => [
                            'font'      => ['bold' => true, 'size' => 12],
                            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                            'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => "$color"]],
                            'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                        ]
                    ];

                    array_push($thead[2], $param);   

                }
    
                $param = ['column' => $cols, 'rows' => 9, 'merge' => true, 'label' => "$d", 'style' => [
                        'font'      => ['bold' => true, 'size' => 12],
                        'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
                        'fill'      => ['fillType' => Fill::FILL_SOLID,'color' => ['argb' => 'b7dee8']],
                        'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                    ]
                ];
    
                array_push($thead[1], $param);                

            }

            $highestRow = 8;
            foreach($thead as $index =>  $tcell){
                foreach ($tcell as $key => $cell) {
                    if($cell['merge'] == true){
                        if(is_array($cell['column'])){
                            $colStart = current($cell['column']);
                            $colEnd   = end($cell['column']);
                        }else{
                            $colStart = $cell['column'];
                            $colEnd   = $colStart;
                        }                

                        if(is_array($cell['rows'])){
                            $rowStart = current($cell['rows']);
                            $rowEnd   = end($cell['rows']);
                        }else{
                            $rowStart = $cell['rows'];
                            $rowEnd   = $rowStart;
                        }

                        $sheet->mergeCells("$colStart$rowStart:$colEnd$rowEnd")->setCellValue("$colStart$rowStart",$cell['label']);

                        if(isset($cell['style'])){
                            $sheet->getStyle("$colStart$rowStart:$colEnd$rowEnd")->applyFromArray($cell['style']);
                        }
                    }else{
                        $column = $cell['column'];
                        $row    = $cell['rows'];

                        $sheet->setCellValue("$column$row", $cell['label']);                    
                        if(isset($cell['style'])){
                            $sheet->getStyle("$column$row")->applyFromArray($cell['style']);
                        }
                    }
                }

                $highestRow = $highestRow + 1;
            }  

            $indexCategory = 1;
            $indexProduct  = 1;            
            foreach($categories as $item){
                $category = $item['category'];
                $sheet->setCellValue("A$highestRow","$indexCategory. $category"); $indexCategory++;                
                $sheet->getStyle("A$highestRow:CE$highestRow")->applyFromArray([
                    'font' => ['bold' => true, 'size' => 12],
                    'fill' => ['fillType' =>  Fill::FILL_SOLID, 'color' => ['argb' => 'E26E0A']]                   
                ]);

                $highestRow = $highestRow + 1;                
                $startRow   = $highestRow;                                               
                

                foreach($item['products'] as $product){                                           
                    $uom = '';                    
                    $qtyDieng   = 0;
                    $qtyPatuha  = 0;
                                        

                    foreach($product['issues'] as $issue){
                        $unit = strtolower($issue['site']);
                        $qty  = $issue['qty'];
                        if($unit == 'dieng'){
                            $qtyDieng   = $qtyDieng + $qty;
                            $qtyDieng   = $qtyDieng<0?0:$qtyDieng;
                        }else if($unit == 'patuha'){
                            $qtyPatuha   = $qtyPatuha + $qty;
                            $qtyPatuha   = $qtyPatuha<0?0:$qtyPatuha;
                        }

                        $uom = $issue['uom'];
                    }
         
                    foreach($product['receipts'] as $receipt){
                        $unit = strtolower($receipt['site']);
                        $qty = $receipt['qty'];
                        if($unit == 'dieng'){
                            $qtyDieng   = $qtyDieng + $qty;
                        }else if($unit == 'patuha'){
                            $qtyPatuha   = $qtyPatuha + $qty;
                        }

                        $uom = $receipt['uom'];
                    }      
                                    

                    $sheet->setCellValue("A$highestRow",$indexProduct); $indexProduct++;
                    $sheet->setCellValue("B$highestRow",$product['product']);
                    $sheet->setCellValue("E$highestRow",$qtyDieng);
                    $sheet->setCellValue("F$highestRow",$qtyPatuha);
                    $sheet->setCellValue("G$highestRow",$uom);
                    $sheet->setCellValue("I$highestRow",'In');

                    $sheet->getStyle("A$highestRow")->applyFromArray([
                        'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'horizontal' => Alignment::HORIZONTAL_CENTER],
                        'borders'   => ['outline' => ['borderStyle' => Border::BORDER_THIN]]
                    ]);
                    $sheet->getStyle("B$highestRow:CE$highestRow")->applyFromArray([
                        'borders'   => [
                            'allBorders' => ['borderStyle' => Border::BORDER_THIN]]
                    ]);  
                    $sheet->getStyle("E$highestRow:F$highestRow")->applyFromArray([
                        'font' => ['bold' => true, 'color' => ['argb' => '0000ff']]
                    ]);                 
                    $sheet->getStyle("G$highestRow")->applyFromArray([
                        'font' => ['color' => ['argb' => '0000ff']],
                        'alignment' => ['vertical' => Alignment::VERTICAL_CENTER, 'horizontal' => Alignment::HORIZONTAL_CENTER]
                    ]); 

                    $highestRow = $highestRow + 1;

                }                                
            }
        }                        
        
        $build->setActiveSheetIndex(0);
        $build->removeSheetByIndex(count($ranges));         

        $filename = "STOCK_ON_HAND_GDE_INVENTORY.xlsx";
        $file     = IOFactory::createWriter($build,'Xlsx');        

        ob_start();
        $file->save('php://output');
        $export = ob_get_contents();
        ob_end_clean();
        header('Content-Type: application/json');        

        return response()->json([
            'status'    => true,
            'document'  => "$filename", 
            'data'      => $categories,
            'logo'      => public_path("assets/logo.png"),
            'file'      => "data:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;base64," . base64_encode($export)
        ],200);
    }    

    function getColumnFromNumber($num) {//(Example 0 = A, 1 = B)
        $numeric = $num % 26;
        $letter  = chr(65 + $numeric);
        $num2    = intval($num / 26);
        
        if ($num2 > 0) {
            return $this->getColumnFromNumber($num2 - 1) . $letter;
        } else {
            return $letter;
        }
    }
}