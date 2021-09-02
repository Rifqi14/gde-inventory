<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Menu;

use PHPExcel;
use PHPExcel_IOFactory;
use PHPExcel_Style_Alignment;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;
use PHPExcel_Calculation;
use PHPExcel_Cell_DataType;
use PHPExcel_Shared_Date;
use PHPExcel_Style_NumberFormat;
use PHPExcel_Worksheet_MemoryDrawing;
use Carbon\Carbon;

use App\Models\ActivitiesCurva;
use App\Models\ActivitiesCurvaDetail;
use App\Models\ActivitiesCurvaFile;
use App\Models\ActivitiesCurvaView;

class ActivitieController extends Controller
{
    public function __construct()
    {
        $menu   = Menu::where('menu_route', 'activitie')->first();
        $parent = Menu::find($menu->parent_id);
        View::share('parent_name', $parent->menu_name);
        View::share('menu_name', $menu->menu_name);
        View::share('menu_active', url('admin/activitie'));
        $this->middleware('accessmenu', ['except' => ['select','dieng','patuha','chart']]);
    }

    public function index(Request $request)
    {
        $userid = Auth::guard('admin')->user()->id;
        
        if($request->loc){
            $location = strtolower($request->loc);
        }else{
            $location = strtolower('Dieng');
        }

        $typ = 'plan';
        if($request->typ){
            $typ = strtolower($request->typ);
        }

        $data = $this->activity($location, $typ);
        $type = $this->activity_type($location);
        return view('admin.activitie.index', compact('userid','location','typ','type','data'));
    }

    function activity($location, $type){
        $rows = array();

        $activitie = ActivitiesCurva::whereRaw('parent_id is not null');
        $activitie->where('location', $location);
        $activitie->where('type', $type);
        $activitie->orderBy("sort","asc");
        $read = $activitie->get();
		foreach ($read as $data) {
			array_push(
				$rows,
				array(
					'id' => $data->id,
					'parent_id' => $data->parent_id,
					'activity' => $data->activity,
				)
			);
		}

		return $rows;
    }

    function activity_type($loc)
	{
		$rows = array();

        $activitie = ActivitiesCurva::select('type');
        $activitie->whereRaw('parent_id is not null');
        if($loc){
            $activitie->where('location', $loc);
		}
        $activitie->groupBy("type");
        $read = $activitie->get();
		foreach ($read as $data) {
			$rows[] = $data;
		}

		return $rows;
	}

    public function addType(Request $request)
    {
        DB::beginTransaction();

        $type = $request->type;
		$location = $request->location;
		$creation_user = Auth::guard('admin')->user()->id;

        $data = array(
			'parent_id' => 0,
			'activity' => 'Financial',
			'start_date' => null,
			'finish_date' => null,
			'type' => strtolower($type),
			'location' => $location,
			'created_user' => $creation_user,
			'w1' => null,
			'w2' => null,
			'w3' => null,
			'w4' => null,
			'w5' => null,
		);

        $ok = ActivitiesCurva::create($data);
        if($ok){
            $ok->sort = $ok->id;
            $ok->save();

            DB::commit();
            return response()->json([
                'success'     => true,
                'message'   => "Data has been saved",
                'data'   => $data,
                'results'     => route('activitie.index'),
            ], 200);
        }else{
            DB::rollback();
            return response()->json([
                'success'    => false,
                'message'   => "Can't insert data type"
            ], 400);
        }

    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        $data = $request;
        $userid = Auth::guard('admin')->user()->id;
		$table = 'activities_curva';

        $data = array(
			'parent_id' => 0,
			'activity' => $request->activity,
			'start_date' => ($request->start_date) ? $request->start_date : null,
			'finish_date' => ($request->finish_date) ? $request->finish_date : null,
			'type' => 'plan',
			'location' => $request->location,
			'created_user' => $userid,
			'w1' => ($request->w1) ? $request->w1 : null,
			'w2' => ($request->w2) ? $request->w2 : null,
			'w3' => ($request->w3) ? $request->w3 : null,
			'w4' => ($request->w4) ? $request->w4 : null,
			'w5' => ($request->w5) ? $request->w5 : null,
		);

        $ok = ActivitiesCurva::create($data);
        if($ok){
            $ok->sort = $ok->id;
            $ok->save();

            DB::commit();
            return response()->json([
                'success'     => true,
                'message'   => "Data has been saved",
                'data'   => $ok,
                'results'     => route('activitie.index'),
            ], 200);
        }else{
            DB::rollback();
            return response()->json([
                'success'    => false,
                'message'   => "Can't insert data progress"
            ], 400);
        }

    }

    public function getDetail(Request $request)
    {
        $data = ActivitiesCurva::find($request->id);

        return response()->json([
            'success'     => true,
            'data'   => $data,
        ], 200);
    }

    public function updateact(Request $request)
    {
        DB::beginTransaction();

        $data = $request;
        $userid = Auth::guard('admin')->user()->id;
		$table = 'activities_curva';

        $data = array(
			'parent_id' => 0,
			'activity' => $request->activity,
			'start_date' => ($request->start_date) ? $request->start_date : null,
			'finish_date' => ($request->finish_date) ? $request->finish_date : null,
			'type' => 'plan',
			'location' => $request->location,
			'created_user' => $userid,
			'w1' => ($request->w1) ? $request->w1 : null,
			'w2' => ($request->w2) ? $request->w2 : null,
			'w3' => ($request->w3) ? $request->w3 : null,
			'w4' => ($request->w4) ? $request->w4 : null,
			'w5' => ($request->w5) ? $request->w5 : null,
		);

        $ok = ActivitiesCurva::find($request->id);
        $ok->update($data);

        if($ok){
            DB::commit();
            return response()->json([
                'success'     => true,
                'message'   => "Data has been saved",
                'data'   => $ok,
                'results'     => route('activitie.index'),
            ], 200);
        }else{
            DB::rollback();
            return response()->json([
                'success'    => false,
                'message'   => "Can't insert data progress"
            ], 400);
        }

    }

    public function destroyact(Request $request)
    {
        try {
            $act = ActivitiesCurva::find($request->id);
            $act->delete();
        } catch (QueryException $th) {
            return response()->json([
                'success'    => false,
                'message'   => 'Error delete data ' . $th->errorInfo[2]
            ], 400);
        }
        return response()->json([
            'success'    => true,
            'message'   => 'Success delete data'
        ], 200);
    }

    public function order(Request $request)
    {
        $order = $request->order;
		$location = $request->location;
		$table = 'activities_curva';

        $order = json_decode($order);
        $sort = 1;
        foreach ($order as $parent) {
			$data = array(
				'parent_id' => 0,
				'sort' => $sort
			);
            $act = ActivitiesCurva::where('id', $parent->id);
            $act->where('location', $location);
            $act->update($data);
            $sort = $sort + 1;
			if (isset($parent->children)) {
				$sort = $this->orderChild($parent->children, $parent->id, $location, $sort);
			}
		}

        return response()->json([
            'success'    => true,
            'message'   => 'Success delete data'
        ], 200);
    }

    function orderChild($childrens, $parent_id, $location, $sort)
	{
		foreach ($childrens as $children) {
			$data = array(
				'parent_id' => $parent_id,
				'sort' => $sort
			);
            $act = ActivitiesCurva::where('id', $children->id);
            $act->where('location', $location);
            $act->update($data);
			$sort = $sort + 1;
			if (isset($children->children)) {
				$sort = $this->orderChild($children->children, $children->id, $location, $sort);
			}
		}

        return $sort;
	}

    public function import(Request $request)
    {
        DB::beginTransaction();

        $type = $request->type;
		$choose = $request->choose;
		$location = $request->location;
		$creation_user = Auth::guard('admin')->user()->id;

        if($this->choose == 'all'){
            $delete = ActivitiesCurva::where('location', $location)->where('type', $type);
            $delete->delete();
			if (!$delete) {
                DB::rollback();
                return response()->json([
                    'success'    => false,
                    'message'   => "Can't delete data scurve"
                ], 400);
			}
		}

        $data 	= [];

        $file = $request->file('file');
        try {
			$filetype 	= \PHPExcel_IOFactory::identify($file);
			$objReader 	= \PHPExcel_IOFactory::createReader($filetype);
			$objPHPExcel = $objReader->load($file);
		} catch (Exception $e) {
			die('Error loading file "' . pathinfo($file, PATHINFO_BASENAME) . '": ' . $e->getMessage());
		}
        $sheet = $objPHPExcel->getSheet(0);
		$highestRow = $sheet->getHighestRow();
		$no = 1;
        if($this->choose == 'all'){
            for ($row = 4; $row <= $highestRow; $row++) {
                $act 	   	= $sheet->getCellByColumnAndRow(0, $row)->getFormattedValue();
				$start  	= $sheet->getCellByColumnAndRow(1, $row)->getFormattedValue();
				$finish 	= $sheet->getCellByColumnAndRow(2, $row)->getFormattedValue();
				$w1 		= $sheet->getCellByColumnAndRow(4, $row)->getFormattedValue();
				$w2 		= $sheet->getCellByColumnAndRow(5, $row)->getFormattedValue();
				$w3 		= $sheet->getCellByColumnAndRow(6, $row)->getFormattedValue();
				$w4 		= $sheet->getCellByColumnAndRow(7, $row)->getFormattedValue();
				$w5 		= $sheet->getCellByColumnAndRow(8, $row)->getFormattedValue();

                if ($act) {
					$userid = Auth::guard('admin')->user()->id;
					if ($start) {
						$sdate = explode('-', $start);
						$start_date = $sdate[2] . '-' . getNumMonth($sdate[1]) . '-' . $sdate[0];
					} else { $start_date = '2019-10-01'; }
					if ($finish) {
						$fdate = explode('-', $finish);
						$finish_date = $fdate[2] . '-' . getNumMonth($fdate[1]) . '-' . $fdate[0];
					} else { $finish_date = '2025-04-01'; }
	
					if ($w1) { $w1 = explode('%', $w1); } 
					if ($w2) { $w2 = explode('%', $w2); } 
					if ($w3) { $w3 = explode('%', $w3); } 
					if ($w4) { $w4 = explode('%', $w4); } 
					if ($w5) { $w5 = explode('%', $w5); } 
	
					$data = array(
						'parent_id' => 0,
						'activity' => $act,
						'start_date' => ($start_date)?$start_date:null,
						'finish_date' => ($finish_date)?$finish_date:null,
						'type' => $type,
						'location' => $location,
						'w1' => ($w1)?$w1[0]:null,
						'w2' => ($w2)?$w2[0]:null,
						'w3' => ($w3)?$w3[0]:null,
						'w4' => ($w4)?$w4[0]:null,
						'w5' => ($w5)?$w5[0]:null,
						// 'start_update' => date('Y-m-d H:i:s'),
						'created_user' => $userid,
					);

                    $ok = ActivitiesCurva::create($data);
                    if($ok){
                        $ok->sort = $ok->id;
                        $ok->save();

                        for ($val=10; $val <= 76; $val++) { 
							$period 	= $sheet->getCellByColumnAndRow($val, 2)->getFormattedValue();
							$year     = explode('/', $period);
							$month 		= $sheet->getCellByColumnAndRow($val, 3)->getFormattedValue();
							$sval 		= $sheet->getCellByColumnAndRow($val, $row)->getFormattedValue();
							if($sval){
								$sval = explode('%', $sval);
								if($sval[0] > 0){
									$detil = array(
										'activities_id' => $ok->id,
										'month' => $month,
										'year' => $period,
										'progress' => $sval[0],
										'type' => $type,
										'date' => date('Y-m-d'),
										'created_user' => $userid,
									);
                                    $ok = ActivitiesCurvaDetail::create($detil);
									if(!$ok){
										DB::rollback();
                                        return response()->json([
                                            'success'    => false,
                                            'message'   => "Can't insert data progress"
                                        ], 400);
									}
								}
							}
						}
                    }else{
                        DB::rollback();
                        return response()->json([
                            'success'    => false,
                            'message'   => "Can't insert data progress"
                        ], 400);
                    }
                }
            }
        }else{
            $cek = ActivitiesCurva::where('type',$type)->get();
			if($cek <= 0){
                DB::rollback();
                return response()->json([
                    'success'    => false,
                    'message'   => "Can't delete data scurve"
                ], 400);
			}

            $aparent = 0;
            $achild1 = 0;
            $achild2 = 0;
            $achild3 = 0;
            $achild4 = 0;

            for ($row = 4; $row <= $highestRow; $row++) {
                $act 	   	= $sheet->getCellByColumnAndRow(0, $row)->getFormattedValue();

                if ($act) {
                    $getparent = ActivitiesCurva::where('lower(activity)',strtolower($act));
                    $getparent->where('type', $type);
                    $getparent->where('location', $location);
                    $getparent->where('parent_id', 0);
                    $getparent->first();
                    $new_aparent = false;
                    if($getparent){
                        $aparent = $getparent->id;
                        $achild1 = 0;
                        $achild2 = 0;
                        $achild3 = 0;
                        $achild4 = 0;
                        $new_aparent = true;
                    }
                    // cek child 1
                    $getparent = false;
                    if($aparent != 0 && !$new_aparent){
                        $getparent = ActivitiesCurva::where('lower(activity)',strtolower($act));
                        $getparent->where('type', $type);
                        $getparent->where('location', $location);
                        $getparent->where('parent_id', $aparent);
                        $getparent->first();
                    }
                    $new_achild1 = false;
                    if($getparent){
                        $achild1 = $getparent->id;
                        $achild2 = 0;
                        $achild3 = 0;
                        $achild4 = 0;
                        $new_achild1 = true;
                    }
                    // cek child 2
                    $getparent = false;
                    if($achild1 != 0 && !$new_achild1){
                        $getparent = ActivitiesCurva::where('lower(activity)',strtolower($act));
                        $getparent->where('type', $type);
                        $getparent->where('location', $location);
                        $getparent->where('parent_id', $achild1);
                        $getparent->first();
                    }
                    $new_achild2 = false;
                    if($getparent){
                        $achild2 = $getparent->id;
                        $achild3 = 0;
                        $achild4 = 0;
                        $new_achild2 = true;
                    }
                    // cek child 3
                    $getparent = false;
                    if($achild2 != 0 && !$new_achild2){
                        $getparent = ActivitiesCurva::where('lower(activity)',strtolower($act));
                        $getparent->where('type', $type);
                        $getparent->where('location', $location);
                        $getparent->where('parent_id', $achild2);
                        $getparent->first();
                    }
                    $new_achild3 = false;
                    if($getparent){
                        $achild3 = $getparent->id;
                        $achild4 = 0;
                        $new_achild3 = true;
                    }
                    // cek child 4
                    $getparent = false;
                    if($achild3 != 0 && !$new_achild3){
                        $getparent = ActivitiesCurva::where('lower(activity)',strtolower($act));
                        $getparent->where('type', $type);
                        $getparent->where('location', $location);
                        $getparent->where('parent_id', $achild3);
                        $getparent->first();
                    }
                    $new_achild4 = false;
                    if($getparent){
                        $achild4 = $getparent->id;
                        $new_achild4 = true;
                    }

                    // real parent
                    $real_parent = 0;
                    if($new_aparent){
                        $real_parent = 0;
                    }elseif($new_achild1){
                        $real_parent = $aparent;
                    }elseif($new_achild2){
                        $real_parent = $achild1;
                    }elseif($new_achild3){
                        $real_parent = $achild2;
                    }elseif($new_achild4){
                        $real_parent = $achild3;
                    }
                    // end get parent id

                    $userid = Auth::guard('admin')->user()->id;
                    $get = ActivitiesCurva::where('lower(activity)',strtolower($act));
                    $get->where('type', $type);
                    $get->where('parent_id', $real_parent);
                    $get->first();

                    if($get){
                        $del = ActivitiesCurvaDetail::where('activities_id', $get->id)->where('type', $type);
                        $del->delete();
						if (!$del) {
                            DB::rollback();
                            return response()->json([
                                'success'    => false,
                                'message'   => "Can't delete data scurve"
                            ], 400);
						}
                        for ($val=10; $val <= 76; $val++) { 
                            $period 	= $sheet->getCellByColumnAndRow($val, 2)->getFormattedValue();
							$year     = explode('/', $period);
							$month 		= $sheet->getCellByColumnAndRow($val, 3)->getFormattedValue();
							$sval 		= $sheet->getCellByColumnAndRow($val, $row)->getFormattedValue();
                            if($sval){
                                $sval = explode('%', $sval);
                                if($sval[0] > 0){
									$detil = array(
										'activities_id' => $get->id,
										'month' => $month,
										'year' => $period,
										'progress' => $sval[0],
										'type' => $type,
										'date' => date('Y-m-d'),
										'created_user' => $userid,
									);
                                    $ok = ActivitiesCurvaDetail::create($detil);
									if(!$ok){
										DB::rollback();
                                        return response()->json([
                                            'success'    => false,
                                            'message'   => "Can't insert data progress"
                                        ], 400);
									}
                                }
                            }
                        }
                    }
                }
            }
        }
        DB::commit();
        return response()->json([
            'success'     => true,
            'message'   => "Data has been saved",
        ], 200);
    }

    public function dieng(Request $request)
    {
        View::share('menu_active', url('activitie/scurve/dieng'));
        $filter = $request->query1;
		$get 	= $request;
        
        $location = 'dieng';
		$type = '';
		$table = 'activities_curva';
        $start = $this->daterange($location, $type);
		$start = sortDateArray($start);
        $start_date = $start[0];
		$finish_date = end($start);
        $range_data = getDatesFromRange($start_date, $finish_date, 'Y/m', '1 month');
		$range_year = getDatesFromRange($start_date, $finish_date, 'Y', '1 month');
        $ranges_data = $range_data;
        $ranges_year = array_unique($range_year);

        $act_parent = $this->get_actparent('dieng','plan');

        $real_name = Auth::guard('admin')->user()->name;

        $plan = "";
        $ranges = [];

        if ($filter) {
            if (!$get['startYear'] or !$get['startMonth'] or !$get['finishYear'] or !$get['finishMonth']) {
				return view('admin.activitie.scurve', compact('ranges_data','ranges_year','ranges','plan','type','filter','get','location', 'real_name','act_parent'));
			}

            $start_date = $get['startYear'] . '-' . $get['startMonth'] . '-1';
			$finish_date = $get['finishYear'] . '-' . $get['finishMonth'] . '-1';
			$range = getDatesFromRange($start_date, $finish_date, 'Y/m', '1 month');
            $ranges = $range;

            $location = 'dieng';
			$type = '';
			$range = $range;
			$act = strtolower($get['act']);
			$table = 'activities_curva_view';
            $plan = $this->rows($act, $location, $range);

            $type = $this->activity_type('dieng');
        }
        $filter = $filter;
        $get = $get;
        $location = 'dieng';

        return view('admin.activitie.scurve', compact('ranges_data','ranges_year','ranges','plan','type','filter','get','location','real_name', 'act_parent'));
    }

    public function patuha(Request $request){
        View::share('menu_active', url('activitie/scurve/patuha'));
        $filter = $request->query1;
		$get 	= $request;
        
        $location = 'patuha';
		$type = '';
		$table = 'activities_curva';
        $start = $this->daterange($location, $type);
		$start = sortDateArray($start);
        $start_date = $start[0];
		$finish_date = end($start);
        $range_data = getDatesFromRange($start_date, $finish_date, 'Y/m', '1 month');
		$range_year = getDatesFromRange($start_date, $finish_date, 'Y', '1 month');
        $ranges_data = $range_data;
        $ranges_year = array_unique($range_year);

        $act_parent = $this->get_actparent('patuha','plan');

        $real_name = Auth::guard('admin')->user()->name;

        $plan = "";
        $ranges = [];

        if ($filter) {
            if (!$get['startYear'] or !$get['startMonth'] or !$get['finishYear'] or !$get['finishMonth']) {
				return view('admin.activitie.scurve', compact('ranges_data','ranges_year','ranges','plan','type','filter','get','location', 'real_name','act_parent'));
			}

            $start_date = $get['startYear'] . '-' . $get['startMonth'] . '-1';
			$finish_date = $get['finishYear'] . '-' . $get['finishMonth'] . '-1';
			$range = getDatesFromRange($start_date, $finish_date, 'Y/m', '1 month');
            $ranges = $range;

            $location = 'patuha';
			$type = 'plan';
			$range = $range;
			$act = strtolower($get['act']);
			$table = 'activities_curva_view';
            $plan = $this->rows($act, $location, $range);

            $type = $this->activity_type('patuha');
        }
        $filter = $filter;
        $get = $get;
        $location = 'patuha';

        return view('admin.activitie.scurve', compact('ranges_data','ranges_year','ranges','plan','type','filter','get','location','real_name', 'act_parent'));
    }

    function daterange($location, $type)
	{
        $query = ActivitiesCurva::select("activities_curvas.*");
		if ($location) {
			$query->whereRaw("lower(location) = '".strtolower($location)."'");
		}
		if($type){
			$query->where('type', $type);
		}
		$query->orderBy('id', 'asc');
		$read = $query->get();
		$data = [];
		foreach ($read as $key => $value) {
			if ($value->start_date) {
				array_push($data, $value->start_date);
				array_push($data, $value->finish_date);
			}
		}
		$new = array_unique($data);
		return $new;
	}

    function get_actparent($location, $type)
	{
        $act = DB::table('activities_curva_view');
        $act->select('activities_curva_view.*');
        $act->where('location', $location);
        if($type){
            $act->where('type', $type);
        }
		$act->where('parent_id', 0);
		$act->orderBy('sort', 'asc');
		$read = $act->get();
		$data = [];
		foreach ($read as $key => $value) {
			$data[] = $value;
		}

		return $data;
	}

    function rows($act, $location, $range)
	{

		if ($act) {
			$get = $this->get_id($act, $location);
		}
        $view = DB::table('activities_curva_view');
        $view->selectRaw("activities_curva_view.*, 
                    (select count(*) from activities_curvas child where child.parent_id = activities_curva_view.id) as child ");
		$view->where("location", $location);
		if ($act) {
			$view->whereIn('id', $get);
		}
		$view->orderBy('sort', 'asc');
		$read = $view->get();
		$data = [];
		foreach ($read as $key => $value) {
			$value->start_date = ($value->start_date) ? date('d-M-Y', strtotime($value->start_date)) : '';
			$value->finish_date = ($value->finish_date) ? date('d-M-Y', strtotime($value->finish_date)) : '';
			$value->start_update = ($value->start_update) ? date('d-M-Y', strtotime($value->start_update)) : '';
			$value->last_update = ($value->last_update) ? date('d-M-Y', strtotime($value->last_update)) : '';
			$value->details = $this->detail($value->id, $range);
			$value->current = $this->currentProgress($value->id);
			$data[] = $value;
		}

		return $data;
	}

    function get_id($act, $loc)
	{
        $view = DB::table('activities_curva_view');
        $view->select("id");
		if ($loc) {
            $query->where("parent_id", 0);
            $query->whereRaw("lower(activity) = $act");
            $query->where("location = $loc");
            $menu = $query->get();
		} else {
            $query->where("parent_id", 0);
            $query->whereRaw("lower(activity) = $act");
            $menu = $query->get();
		}
		$data = [];
		foreach ($menu as $row) {
			array_push($data, $row->id);
			$data = $this->get_child_id($row->id, $data);
		}
		return $data;
	}

    function get_child_id($act_id, $data)
	{
        $view = DB::table('activities_curva_view');
        $view->select("id");
        $view->where("parent_id = $act_id");
        $menu = $view->get();
		if (count($menu) > 0) {
			foreach ($menu as $row) {
				array_push($data, $row->id);
				$data = $this->get_child_id($row->id, $data);
			}
		}

		return $data;
	}

    function detail($act_id, $ranges)
	{
		$dec = 14;
		$data = [];
		foreach ($ranges as $range) {
			$new = explode('/', $range);
			$year = (int) $new[0];
			$month = (int) $new[1];

            $query = ActivitiesCurvaDetail::select('*');
            $query->where("activities_id", $act_id);
            $query->where('month', $month);
            $query->where('year', $year);
            $read = $query->get();
			$count = count($read);

			if ($count > 0) {
				foreach ($read as $key => $value) {
					$data[] = [
						'date' => $range,
						'progress' => round($value->progress, $dec) . '%',
						'file' => json_encode($this->getFile($value->id)),
						'id' => $value->id,
						'fulldate' => ($value->date) ? date('d/m/Y', strtotime($value->date)) : '',
					];
				}
			} else {
				$data[] = [
					'date' => $range,
					'progress' => '',
					'file' => '',
					'id' => '',
					'fulldate' => '',
				];
			}
		}


		return $data;
	}

    function getFile($id)
	{
		$data = [];
        $query = ActivitiesCurvaFile::select('*');
        $query->where('detail_id', $id);
        $read = $query->get();
		foreach ($read as $key => $value) {
			array_push($data, $value->file);
		}
		return $data;
	}

    function currentProgress($act_id)
	{
		$dec = 10;
		$data = 0;

        $query = ActivitiesCurvaDetail::selectRaw('activities_curva_details.*,
			ac.w1, ac.w2, ac.w3, ac.w4, ac.w5,
			(select count(*) from activities_curvas child where child.parent_id = ac.id) child');
		$query->join('activities_curvas as ac', 'ac.id', '=', 'activities_curva_details.activities_id');
        $query->where('activities_id', $act_id);
		$read = $query->get();
		$count = count($read);
		$type = null;
		foreach ($read as $key => $val) {
			if ($val->child == 0) {
				$p_w1 = ($val->w1) ? ($val->w1 / 100) : 1;
				$p_w2 = ($val->w2) ? ($val->w2 / 100) : 1;
				$p_w3 = ($val->w3) ? ($val->w3 / 100) : 1;
				$p_w4 = ($val->w4) ? ($val->w4 / 100) : 1;
				$p_w5 = ($val->w5) ? ($val->w5 / 100) : 1;

				$p_prog = $val->progress;
				$data = $data + $p_prog;
				$type = 'child';
			} else {
				$type = 'parent';
			}
		}

		if ($type == 'child') {
			$current = ($data) ? $data : 0;
			return round(($current), $dec) . '%';
		} else {
			return '';
		}
	}

    public function chart(Request $request)
    {
        $get = $request;

        $location = $get['location'];
		$type = '';
		$table = 'activities_curva';
		$start = $this->daterange($location, $type);
        $start = sortDateArray($start);

        $first_date = $start[0];
		$start_date = $get['startYear'] . '-' . $get['startMonth'] . '-1';
		$finish_date = $get['finishYear'] . '-' . $get['finishMonth'] . '-1';
		if (!$get['startYear'] or !$get['startMonth'] or !$get['finishYear'] or !$get['finishMonth']) {
			return;
		}
		$range = getDatesFromRange($first_date, $finish_date, 'Y/m', '1 month');
		$range_s = getDatesFromRange($start_date, $finish_date, 'Y/m', '1 month');

		$data = $this->chartdata($get, $range, $range_s);

        return response()->json([
            'status' => true,
			'series' => $data
        ], 200);
    }

    function chartdata($get, $range, $range_s)
	{
		$dec = 5;

		$type = $this->activity_type($get['location']);
		$acti = $get['act'];
		if ($acti) {
			$get = $this->get_id(strtolower($acti), $get['location']);
		}

        $query = ActivitiesCurvaDetail::selectRaw("activities_curva_details.*,
			ac.w1, ac.w2, ac.w3, ac.w4, ac.w5,
			ac.activity,
			(select count(*) from activities_curvas child where child.parent_id = ac.id) child");
        $query->join('activities_curvas as ac', 'ac.id', '=', 'activities_curva_details.activities_id');
		$query->whereRaw("lower(ac.location) = '".strtolower($get['location'])."'");
		if ($acti) {
			$query->whereIn('ac.id', $get);
		}
		$query->orderBy('ac.id', 'asc');
		$query->orderBy('activities_curva_details.type', 'desc');
		$query->orderBy('activities_curva_details.month', 'asc');
		$query->orderBy('activities_curva_details.year', 'asc');
		$read = $query->get();

		foreach($type as $typ){
			${"_".$typ->type} = [];
			${"_".$typ->type."tud"} = 0;
		}
		
		foreach ($range as $date) {
			
			foreach($type as $typ){
				${"_".$typ->type."tod"} = 0;
			}

			$dates = explode('/', $date);
			foreach ($read as $val) {
				if ($val->child == 0) {
					if ($dates[0] == $val->year and $dates[1] == $val->month) {
						$p_w1 = ($val->w1) ? ($val->w1 / 100) : 1;
						$p_w2 = ($val->w2) ? ($val->w2 / 100) : 1;
						$p_w3 = ($val->w3) ? ($val->w3 / 100) : 1;
						$p_w4 = ($val->w4) ? ($val->w4 / 100) : 1;
						$p_w5 = ($val->w5) ? ($val->w5 / 100) : 1;

						foreach($type as $typ){
							if ($val->type == $typ->type) {
								if ($acti) {
									${"_".$typ->type."_prog"} = ($val->progress / 100) * $p_w5 * $p_w4 * $p_w3 * $p_w2;
								} else {
									${"_".$typ->type."_prog"} = ($val->progress / 100) * $p_w5 * $p_w4 * $p_w3 * $p_w2 * $p_w1;
								}
								${"_".$typ->type."tod"} = ${"_".$typ->type."tod"} + ${"_".$typ->type."_prog"};
							}	
						}
					}
				}
			}

			foreach($type as $typ){
				${"_sum".$typ->type} = (${"_".$typ->type."tod"} + ${"_".$typ->type."tud"}) * 100;
				array_push(${"_".$typ->type}, ['date' => $date, 'now' => round(${"_sum".$typ->type}, $dec), 'past' => round((${"_".$typ->type."tod"} * 100), $dec), 'type' => $typ->type]);

				${"_".$typ->type."tud"} = ${"_".$typ->type."tud"} + ${"_".$typ->type."tod"};
			}
		}

		foreach($type as $typ){
			${"_".$typ->type."_line"} = [];
			${"_".$typ->type."_fix"} = [];
		}

		$categories = [];
		foreach ($range_s as $filter) {
			$filt = explode('/', $filter);
			$newdate = $filt[0] . '-' . $filt[1] . '-01';
			$cat = date('M Y', strtotime($newdate));
			array_push($categories, $cat);

			foreach($type as $typ){
				foreach (${"_".$typ->type} as $act) {
					if($act['type'] == $typ->type){
						if ($act['date'] == $filter) {
							array_push(${"_".$typ->type."_line"}, $act['now']);
							array_push(${"_".$typ->type."_fix"}, $act);
						}
					}
				}
			}
		}

		$result['categories'] 	= $categories;
		foreach($type as $typ){
			$result[$typ->type] 		= ${"_".$typ->type."_fix"};
			$result[$typ->type.'_line'] 	= ${"_".$typ->type."_line"};
		}
		$result['type'] 	= $type;
		return $result;
	}
    
    public function get_progress(Request $request)
	{
        $query = ActivitiesCurvaDetail::select('activities_curva_details.*,
			parent.path');
        $query->join('activities_curva_view as parent', 'parent.id', '=', 'activities_curva_details.activities_id');
		$query->where('activities_curva_details.id', $request->id);
		$read = $query->get();
		foreach ($read as $row) {
			$row->date = ($row->date) ? date('d/m/Y', strtotime($row->date)) : '';
			$data = $row;
		}
        return response()->json([
            'status' => true,
			'series' => $data
        ], 200);
	}
}
