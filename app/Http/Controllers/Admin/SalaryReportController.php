<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\CalendarException;
use App\Models\Employee;
use App\Models\Menu;
use App\Models\Role;
use App\Models\SalaryReport;
use App\Models\SalaryReportDetail;
use Carbon\Carbon;
use Doctrine\DBAL\Query\QueryBuilder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use NumberFormatter;

class SalaryReportController extends Controller
{
    function __construct() {
        $menu       = Menu::GetByRoute('salaryreport')->first();
        $parent     = Menu::parent($menu->parent_id)->first();
        View::share('menu_name', $menu->menu_name);
        View::share('parent_name', $parent->menu_name);
        View::share('menu_active', url('admin/salaryreport'));
        $this->middleware('accessmenu', ['except' => ['select']]);
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.salaryreport.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator      = Validator::make($request->all(), [
            'type'      => 'required',
            'periode'   => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        switch ($request->type) {
            case 'employee':
                return $this->generateByEmployee($request);
                break;
            case 'role':
                return $this->generateByRole($request);
                break;
            
            default:
                return $this->generateAll($request);
                break;
        }
    }

    public function generateByEmployee(Request $request)
    {
        $employee       = Employee::find($request->employee_generate);
        $month          = Carbon::parse($request->periode)->month;
        $year           = Carbon::parse($request->periode)->year;
        $calException   = CalendarException::whereMonth('date_exception', $month)->whereYear('date_exception', $year)->count();
        $dayInMonth     = Carbon::parse($request->periode)->daysInMonth;
        $workingDay     = $dayInMonth - $calException;
        
        DB::beginTransaction();
        $dailySalary    = $employee->salary / $workingDay;
        $attend         = Attendance::getByEmployee($employee->id)->whereMonth('attendance_date', $month)->whereYear('attendance_date', $year)->status('APPROVED')->sum('day_work');

        $checkExistingSalary = SalaryReport::employeeById($employee->id)->whereMonth('period', $month)->whereYear('period', $year)->first();
        if (!$checkExistingSalary) {
            $salaryReport   = SalaryReport::create([
                'employee_id'   => $employee->id,
                'user_id'       => Auth::user()->id,
                'gross'         => 0.0,
                'deduction'     => 0.0,
                'net_salary'    => 0.0,
                'period'        => Carbon::parse($request->periode)->toDateString(),
                'status'        => 'WAITING',
                'print_status'  => 0,
            ]);

            if ($salaryReport) {
                $salaryItem     = SalaryReportDetail::create([
                    'salary_report_id'      => $salaryReport->id,
                    'description'           => 'Salary',
                    'total'                 => $dailySalary * $attend,
                    'type'                  => 1,
                    'is_added'              => 0,
                    'currency_id'           => $employee->salary_currency_id,
                ]);

                if ($salaryItem) {
                    $salaryReport->gross        = $salaryItem->total;
                    $salaryReport->deduction    = 0;
                    $salaryReport->net_salary   = $salaryReport->gross - $salaryReport->deduction;
                    $salaryReport->save();
                } else {
                    DB::rollBack();
                    return response()->json([
                        'status'    => false,
                        'message'   => "Error generate salary",
                    ], 400);
                }
                
            } else {
                DB::rollBack();
                return response()->json([
                    'status'    => false,
                    'message'   => "Error generate salary",
                ], 400);
            }
        } else {
            $updateItem = SalaryReportDetail::where('salary_report_id', $checkExistingSalary->id)->where('description', 'Salary')->first();

            $updateItem->total  = $dailySalary * $attend;
            $updateItem->save();

            $checkExistingSalary->gross = $updateItem->total;
            $checkExistingSalary->net_salary = $checkExistingSalary->gross - $checkExistingSalary->deduction;
            $checkExistingSalary->save();
        }

        DB::commit();
        return response()->json([
            'status'    => true,
            'message'   => "Success generate salary",
        ], 200);
    }

    public function generateByRole(Request $request)
    {
        $role           = $request->role_id_generate;
        $employees      = Employee::whereHas('user.roles', function($q) use ($role) {
            $q->where('role_id', $role);
        })->get();
        $month          = Carbon::parse($request->periode)->month;
        $year           = Carbon::parse($request->periode)->year;
        $calException   = CalendarException::whereMonth('date_exception', $month)->whereYear('date_exception', $year)->count();
        $dayInMonth     = Carbon::parse($request->periode)->daysInMonth;
        $workingDay     = $dayInMonth - $calException;
        
        DB::beginTransaction();
        foreach ($employees as $key => $employee) {
            $dailySalary    = $employee->salary / $workingDay;
            $attend         = Attendance::getByEmployee($employee->id)->whereMonth('attendance_date', $month)->whereYear('attendance_date', $year)->status('APPROVED')->sum('day_work');

            $checkExistingSalary = SalaryReport::employeeById($employee->id)->whereMonth('period', $month)->whereYear('period', $year)->first();
            if (!$checkExistingSalary) {
                $salaryReport   = SalaryReport::create([
                    'employee_id'   => $employee->id,
                    'user_id'       => Auth::user()->id,
                    'gross'         => 0.0,
                    'deduction'     => 0.0,
                    'net_salary'    => 0.0,
                    'period'        => Carbon::parse($request->periode)->toDateString(),
                    'status'        => 'WAITING',
                    'print_status'  => 0,
                ]);
    
                if ($salaryReport) {
                    $salaryItem     = SalaryReportDetail::create([
                        'salary_report_id'      => $salaryReport->id,
                        'description'           => 'Salary',
                        'total'                 => $dailySalary * $attend,
                        'type'                  => 1,
                        'is_added'              => 0,
                        'currency_id'           => $employee->salary_currency_id,
                    ]);
    
                    if ($salaryItem) {
                        $salaryReport->gross        = $salaryItem->total;
                        $salaryReport->deduction    = 0;
                        $salaryReport->net_salary   = $salaryReport->gross - $salaryReport->deduction;
                        $salaryReport->save();
                    } else {
                        DB::rollBack();
                        return response()->json([
                            'status'    => false,
                            'message'   => "Error generate salary",
                        ], 400);
                    }
                    
                } else {
                    DB::rollBack();
                    return response()->json([
                        'status'    => false,
                        'message'   => "Error generate salary",
                    ], 400);
                }
            } else {
                $updateItem = SalaryReportDetail::where('salary_report_id', $checkExistingSalary->id)->where('description', 'Salary')->first();

                $updateItem->total  = $dailySalary * $attend;
                $updateItem->save();

                $checkExistingSalary->gross = $updateItem->total;
                $checkExistingSalary->net_salary = $checkExistingSalary->gross - $checkExistingSalary->deduction;
                $checkExistingSalary->save();
            }
        }

        DB::commit();
        return response()->json([
            'status'    => true,
            'message'   => "Success generate salary",
        ], 200);
    }

    public function generateAll(Request $request)
    {
        $employees      = Employee::payrollYes()->get();
        $month          = Carbon::parse($request->periode)->month;
        $year           = Carbon::parse($request->periode)->year;
        $calException   = CalendarException::whereMonth('date_exception', $month)->whereYear('date_exception', $year)->count();
        $dayInMonth     = Carbon::parse($request->periode)->daysInMonth;
        $workingDay     = $dayInMonth - $calException;
        
        DB::beginTransaction();
        foreach ($employees as $key => $employee) {
            $dailySalary    = $employee->salary / $workingDay;
            $attend         = Attendance::getByEmployee($employee->id)->whereMonth('attendance_date', $month)->whereYear('attendance_date', $year)->status('APPROVED')->sum('day_work');

            $checkExistingSalary = SalaryReport::employeeById($employee->id)->whereMonth('period', $month)->whereYear('period', $year)->first();
            if (!$checkExistingSalary) {
                $salaryReport   = SalaryReport::create([
                    'employee_id'   => $employee->id,
                    'user_id'       => Auth::user()->id,
                    'gross'         => 0.0,
                    'deduction'     => 0.0,
                    'net_salary'    => 0.0,
                    'period'        => Carbon::parse($request->periode)->toDateString(),
                    'status'        => 'WAITING',
                    'print_status'  => 0,
                ]);
    
                if ($salaryReport) {
                    $salaryItem     = SalaryReportDetail::create([
                        'salary_report_id'      => $salaryReport->id,
                        'description'           => 'Salary',
                        'total'                 => $dailySalary * $attend,
                        'type'                  => 1,
                        'is_added'              => 0,
                        'currency_id'           => $employee->salary_currency_id,
                    ]);
    
                    if ($salaryItem) {
                        $salaryReport->gross        = $salaryItem->total;
                        $salaryReport->deduction    = 0;
                        $salaryReport->net_salary   = $salaryReport->gross - $salaryReport->deduction;
                        $salaryReport->save();
                    } else {
                        DB::rollBack();
                        return response()->json([
                            'status'    => false,
                            'message'   => "Error generate salary",
                        ], 400);
                    }
                    
                } else {
                    DB::rollBack();
                    return response()->json([
                        'status'    => false,
                        'message'   => "Error generate salary",
                    ], 400);
                }
            } else {
                $updateItem = SalaryReportDetail::where('salary_report_id', $checkExistingSalary->id)->where('description', 'Salary')->first();

                $updateItem->total  = $dailySalary * $attend;
                $updateItem->save();

                $checkExistingSalary->gross = $updateItem->total;
                $checkExistingSalary->net_salary = $checkExistingSalary->gross - $checkExistingSalary->deduction;
                $checkExistingSalary->save();
            }
        }

        DB::commit();
        return response()->json([
            'status'    => true,
            'message'   => "Success generate salary",
        ], 200);
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
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request,$id)
    {
        if (in_array('update', $request->actionmenu)) {
            $salary     = SalaryReport::with(['employee'])->find($id);
            if ($salary) {
                $period = Carbon::parse($salary->period)->locale('id')->isoFormat('MMMM Y');
                return view('admin.salaryreport.edit', compact('salary', 'period'));
            } else {
                abort(404);
            }
        } else {
            abort(403);
        }
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
        $validator      = Validator::make($request->all(), [
            'status'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'    => false,
                'message'   => $validator->errors()->first()
            ], 400);
        }

        DB::beginTransaction();
        try {
            $salary     = SalaryReport::find($id);
            switch ($request->status) {
                case 'approved':
                    $salary->status = 'APPROVED';
                    $salary->approved_reason    = $request->request_reason;
                    $salary->approval_by        = Auth::user()->id;
                    $salary->approval_date      = Carbon::now();
                    break;
                case 'rejected':
                    $salary->status = 'REJECTED';
                    $salary->reject_reason      = $request->request_reason;
                    $salary->approval_by        = Auth::user()->id;
                    $salary->approval_date      = Carbon::now();
                    break;
                
                default:
                    $salary->status = 'REJECTED';
                    break;
            }
            $salary->save();
        } catch (\Illuminate\Database\QueryException $th) {
            DB::rollBack();
            return response()->json([
                'status'    => false,
                'message'   => "Error update data {$th->errorInfo[2]}"
            ], 400);
        }
        DB::commit();
        return response()->json([
            'status'    => true,
            'results'   => route('salaryreport.index'),
        ], 200);
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
            $salary = SalaryReport::find($id)->delete();
        } catch (\Illuminate\Database\QueryException $th) {
            return response()->json([
                'status'    => false,
                'message'   => "Error delete data {$th->errorInfo[2]}",
            ], 400);
        }
        return response()->json([
            'status'    => true,
            'message'   => "Success delete data"
        ], 200);
    }

    public function read(Request $request)
    {
        $start      = $request->start;
        $length     = $request->length;
        $query      = $request->search['value'];
        $sort       = $request->columns[$request->order[0]['column']]['data'];
        $dir        = $request->order[0]['dir'];
        $employee   = $request->employee_id;
        $role       = $request->role_id;
        $status     = $request->status;

        $queryData  = SalaryReport::with(['employee', 'details', 'employee.user.roles']);
        if ($role) {
            $queryData->whereHas('employee.user.roles', function($q) use ($role) {
                $q->where('role_id', $role);
            })->get();
        }
        if ($employee) {
            $queryData->employeeById($employee);
        }
        if ($status) {
            $queryData->status($status);
        }

        $row        = clone $queryData;
        $recordsTotal = $row->count();

        $queryData->offset($start);
        $queryData->limit($length);
        $queryData->orderBy($sort, $dir);
        $salaries   = $queryData->get();

        $data       = [];
        foreach ($salaries as $key => $salary) {
            $fmt = new NumberFormatter('id_ID', NumberFormatter::CURRENCY);
            $fmt->setAttribute($fmt::FRACTION_DIGITS, 0);
            $salary->no     = ++$start;
            $salary->period = Carbon::parse($salary->period)->locale('id')->isoFormat('MMMM Y');
            $salary->total  = $fmt->formatCurrency($salary->net_salary, $salary->details()->first()->currencies->code);
            $data[]         = $salary;
        }
        return response()->json([
            'draw'              => $request->draw,
            'recordsTotal'      => $recordsTotal,
            'recordsFiltered'   => $recordsTotal,
            'data'              => $data
        ], 200);
    }
}