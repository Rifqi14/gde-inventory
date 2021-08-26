<?php

use App\Models\SalaryReport;
use Illuminate\Support\Facades\DB;

if (!function_exists('recalculateTotal')) {
  function recalculateTotal($id)
  {
      $salary     = SalaryReport::find($id);
      $additional = 0.0;
      $deduction  = 0.0;

      DB::beginTransaction();
      if ($salary) {
          foreach ($salary->details as $key => $detail) {
              if ($detail->type == 1) {
                  $additional+=$detail->total;
              } else {
                  $deduction+=$detail->total;
              }
          }
  
          $salary->gross      = $additional;
          $salary->deduction  = $deduction;
          $salary->net_salary = $additional - $deduction;
          $salary->save();

          if (!$salary) {
              DB::rollBack();
              return response()->json([
                  'status'    => false,
                  'message'   => "Error recalculate total salary"
              ], 400);
          }
      } else {
          DB::rollBack();
          return response()->json([
              'status'    => false,
              'message'   => "Error recalculate total salary"
          ], 400);
      }
      DB::commit();
  }
}