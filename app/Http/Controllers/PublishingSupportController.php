<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Revolution\Google\Sheets\Facades\Sheets;

class PublishingSupportController extends Controller
{
  public function index()
  {
    return view('publishing-support');
  }

  public function list(Request $request)
  {
    try {
      $year = $request->year;
      $type = $request->type;

      $filter2 = $year && $type;
      $filter1 = !$filter2 && ($year || $type);

      $rows = Sheets::spreadsheet(config('google.post_spreadsheet_id'))->sheet('PUBLISHING_SUPPORT')->get();

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
            'row_no' => $key,
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

      $year = $request->year;

      $rows = Sheets::spreadsheet(config('google.post_spreadsheet_id'))->sheet('PUBLISHING_SUPPORT')->get();

      $header = $rows->pull(0);
      $values = Sheets::collection(header: $header, rows: $rows);
      $values->toArray();

      $data = [];
      foreach ($values as $key => $value) {
        $set_data = false;
        $year_date = date('Y', strtotime($value['support_request_date']));

        if($year){
          if($year_date == $year && $year_date != ''){
            $set_data = true;
          }
        }else{
          $set_data = true;
        }

        if($set_data){

          $budget = (float)$value['budget'];

          if(isset($data[$year_date])){
            $data[$year_date] = $data[$year_date] + $budget;
          }else{
            $data[$year_date] = $budget;
          }
        }
      }

      $base_data['data'] = [];
      foreach ($data as $key => $value) {

        $base_data['data'][] = [
          'x' => (string)$key,
          'y' => round($value, 2, PHP_ROUND_HALF_UP)
        ];

      }

      return response()->json($base_data);

    } catch (\Throwable $th) {
      throw $th;
    }
  }
}
