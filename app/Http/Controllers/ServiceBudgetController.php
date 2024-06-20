<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Revolution\Google\Sheets\Facades\Sheets;

class ServiceBudgetController extends Controller
{
  public function index()
  {
    return view('service-budget');
  }

  public function list(Request $request)
  {
    try {
      $year = $request->year;
      $type = $request->type;

      $filter2 = $year && $type;
      $filter1 = !$filter2 && ($year || $type);

      $rows = Sheets::spreadsheet(config('google.post_spreadsheet_id'))->sheet('SERVICE_BUDGET')->get();

      $header = $rows->pull(0);
      $values = Sheets::collection(header: $header, rows: $rows);
      $values->toArray();

      $base_data = [];
      foreach ($values as $key => $value) {
        $set_data = false;
        if($filter2){
          if($value['year'] == $year && $value['type'] == $type){
            $set_data = true;
          }
        }else if($filter1){
          if($value['year'] == $year || $value['type'] == $type){
            $set_data = true;
          }
        }else{
          $set_data = true;
        }

        if($set_data){
          $base_data[] = [
            'project_name' => $value['project_name'],
            'head_project_name' => $value['head_project_name'],
            'co_project_name' => $value['co_project_name'] ?? '-',
            'department' => $value['department'],
            'year' => $value['year'],
            'type' => $value['type'],
            'status' =>  $value['status']
          ];
        }
      }

      $list = [
        "recordsTotal" => count($values),
        "recordsFiltered" => count($base_data),
        "data" => $base_data
      ];

      return response()->json($list);

    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public function getChart(Request $request)
  {
    try {

      $arr_type = $this->getDataType();

      $rows = Sheets::spreadsheet(config('google.post_spreadsheet_id'))->sheet('SERVICE_BUDGET')->get();

      $header = $rows->pull(0);
      $values = Sheets::collection(header: $header, rows: $rows);
      $values->toArray();

      $data = [];
      foreach ($values as $key => $value) {
        $set_data = false;

        $budget = (float)$value['budget'];

        $type_v = trim($value['type'], '');
        $index_type = array_search($type_v, $arr_type);

        if(isset($data[$index_type])){
          $data[$index_type] = $data[$index_type] + $budget;
        }else{
          $data[$index_type] = $budget;
        }
      }

      $base_data['data'] = [];
      foreach ($data as $key => $value) {

        $base_data['data'][] = [
          'x' => $arr_type[$key],
          'y' => round($value, 2, PHP_ROUND_HALF_UP)
        ];

      }

      return response()->json($base_data);

    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public function getDataType()
  {
    try {
      $rows = Sheets::spreadsheet(config('google.post_spreadsheet_id'))->sheet('SERVICE_BUDGET')->get();

      $header = $rows->pull(0);
      $values = Sheets::collection(header: $header, rows: $rows);
      $values->toArray();

      $base = [];
      foreach ($values as $key => $value) {

        if($value['type']){
          if(!in_array($value['type'], $base)){
            $base[] = trim($value['type'], '');
          }

        }

      }

    return $base;

    } catch (\Throwable $th) {
      throw $th;
    }
  }
}
