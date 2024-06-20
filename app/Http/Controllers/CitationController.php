<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Revolution\Google\Sheets\Facades\Sheets;

class CitationController extends Controller
{
  public function index()
  {
    $departments = $this->getDepartment();
    return view('citation.index', compact('departments'));
  }

  public function international()
  {
    $departments = $this->getDepartmentInternational();
    return view('citation.international', compact('departments'));
  }

  public function list(Request $request)
  {
    try {

      $year = $request->year;
      $department = $request->department;
      $quartile = $request->quartile;

      $filter2 = $year && $department;
      $filter1 = !$filter2 && ($year || $department);

      $rows = Sheets::spreadsheet(config('google.post_spreadsheet_id'))->sheet('PUBLISHED_NATIONAL')->get();

      $header = $rows->pull(0);
      $values = Sheets::collection(header: $header, rows: $rows);
      $values->toArray();

      $base_data = [];
      foreach ($values as $key => $value) {
        // $set_data = false;
        $link = '';
        if($value['link']){
          $link = '<a href="'.$value['link'].'" target="_blank" class="btn btn-sm btn-outline-secondary waves-effect waves-light material-shadow-none">Link</a>';
        }

        // $filed_name = 'citation_'.$year;

        // if($filter2){
        //   if(!empty($value[$filed_name]) && $department == $value['department']){
            // $set_data = true;
        //   }

        // }else if($filter1){
        //   if(!empty($value[$filed_name]) || ($department == $value['department'] && $value['department'] != '')){
        //     $set_data = true;
        //   }

        // }else{
        //   $set_data = true;

        // }

        // if($set_data){
          $base_data[] = [
            'row_no' => $key,
            'title' => $value['title'] ?? '-',
            'author' => $value['author'] ?? '-',
            'name' => $value['fullname'] ?? '-',
            'department' => $value['department'] ?? '-',
            'year' => $value['year'] ?? '-',
            'database' => $value['database'] ?? '-',
            'link' => $link
          ];
        // }
      }

      $list = [
        "data" => $base_data
      ];

      return response()->json($list);


    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public function listInternational(Request $request)
  {
    try {

      $year = $request->year;
      $department = $request->department;
      $quartile = $request->quartile;

      $filter2 = $year && $department;
      $filter1 = !$filter2 && ($year || $department);

      $rows = Sheets::spreadsheet(config('google.post_spreadsheet_id'))->sheet('PUBLISHED_INTER')->get();

      $header = $rows->pull(0);
      $values = Sheets::collection(header: $header, rows: $rows);
      $values->toArray();

      $base_data = [];
      foreach ($values as $key => $value) {
        // $set_data = false;
        $link = '';
        if($value['link']){
          $link = '<a href="'.$value['link'].'" target="_blank" class="btn btn-sm btn-outline-secondary waves-effect waves-light material-shadow-none">Link</a>';
        }

        // $filed_name = 'citation_'.$year;

        // if($filter2){
        //   if(!empty($value[$filed_name]) && $department == $value['department']){
        //     $set_data = true;
        //   }

        // }else if($filter1){
        //   if(!empty($value[$filed_name]) || ($department == $value['department'] && $value['department'] != '')){
        //     $set_data = true;
        //   }

        // }else{
        //   $set_data = true;

        // }

        // if($set_data){
          $base_data[] = [
            'row_no' => $key,
            'title' => $value['title'] ?? '-',
            'author' => $value['author'] ?? '-',
            'name' => $value['fullname'] ?? '-',
            'department' => $value['department'] ?? '-',
            'year' => $value['year'] ?? '-',
            'database' => $value['database'] ?? '-',
            'link' => $link
          ];
        // }
      }

      $list = [
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
      $year = $request->year ? [$request->year] : ['2024', '2023', '2022', '2021', '2020'];
      $department = $request->department;
      $type = $request->type;

      $filter2 = $year && $department;
      $filter1 = !$filter2 && ($year || $department);

      $rows = Sheets::spreadsheet(config('google.post_spreadsheet_id'))->sheet('PUBLISHED_NATIONAL')->get();

      $header = $rows->pull(0);
      $values = Sheets::collection(header: $header, rows: $rows);
      $values->toArray();

      $base_data = [];
      if($type == 'year'){
        $base_data = $this->getDataChartYear($values, $filter2, $filter1, $year, $department);

      }else if($type == 'department'){
        $base_data = $this->getDataChartDepartment($values, $filter2, $filter1, $year, $department, 'normal');

      }

      return response()->json($base_data);

    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public function getChartInternational(Request $request)
  {

    try {
      $year = $request->year ? [$request->year] : ['2024', '2023', '2022', '2021', '2020'];
      $department = $request->department;
      $type = $request->type;

      $filter2 = $year && $department;
      $filter1 = !$filter2 && ($year || $department);

      $rows = Sheets::spreadsheet(config('google.post_spreadsheet_id'))->sheet('PUBLISHED_INTER')->get();

      $header = $rows->pull(0);
      $values = Sheets::collection(header: $header, rows: $rows);
      $values->toArray();

      $base_data = [];
      if($type == 'year'){
        $base_data = $this->getDataChartYear($values, $filter2, $filter1, $year, $department);

      }else if($type == 'department'){
        $base_data = $this->getDataChartDepartment($values, $filter2, $filter1, $year, $department, 'inter');

      }

      return response()->json($base_data);

    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public function getDataChartYear($values, $filter2, $filter1, $year, $department = '')
  {
    try {
      $data = [];
      $title_data = [];

      foreach ($year as $key => $y) {
        $filed_name = 'citation_'.$y;

        foreach ($values as $key => $value) {
          $set_data = false;

          if(!empty($value[$filed_name])){
            $department_v = trim($value['department'], '');
            if($department != ''){
              if($department == $department_v){
                $set_data = true;
              }

            }else{
              $set_data = true;
            }

          }

          if($set_data){
            $title = trim($value['title'], '');
            if(!$this->checkDuplicate($title_data, $title)){

              // set title for check duplicate
              $title_data[] = [
                'title' => $title
              ];

              if(isset($data[$y])){
                $data[$y] = $data[$y] + 1;
              }else{
                $data[$y] = 1;
              }
            }
          }
        }
      }

      ksort($data);

      $base_data = [];
      foreach ($data as $key => $value) {
        $base_data['label'][] = $key;
        $base_data['data'][] = $value;
      }

      return $base_data;
    } catch (\Throwable $th) {
      throw $th;
    }

  }

  public function getDataChartDepartment($values, $filter2, $filter1, $year, $department = '', $type_data)
  {
    try {
      $data = [];
      $title_data = [];
      $arr_depart = $type_data == 'normal' ? $this->getDepartment() : $this->getDepartmentInternational();

      foreach ($year as $key => $y) {
        $filed_name = 'citation_'.$y;

        foreach ($values as $key => $value) {
          $set_data = false;
          $department_v = trim($value['department'], '');
          $index_depart = array_search($department_v, $arr_depart);

          if(!empty($value[$filed_name])){
            if($department != ''){
              if($department == $department_v){
                $set_data = true;
              }

            }else{
              $set_data = true;
            }

          }

          if($set_data){
            $title = trim($value['title'], '');
            if(!$this->checkDuplicate($title_data,  $title)){

              // set title for check duplicate
              $title_data[] = [
                'title' => $title
              ];

              if(isset($data[$index_depart])){
                $data[$index_depart] = $data[$index_depart] + 1;
              }else{
                $data[$index_depart] = 1;
              }
            }
          }
        }
      }

      $base_data = [];
      foreach ($data as $key => $value) {
        $base_data['label'][] = $arr_depart[$key];
        $base_data['data'][] = $value;
      }

      return $base_data;

    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public function checkDuplicate($datas, $search){
    try {
      foreach ($datas as $key => $data) {
        if($data['title'] == $search){
          return true;
        }
      }

      return false;
    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public function getDatabaseType()
  {
    $rows = Sheets::spreadsheet(config('google.post_spreadsheet_id'))->sheet('PUBLISHED_NATIONAL')->get();
    $header = $rows->pull(0);
    $values = Sheets::collection(header: $header, rows: $rows);
    $values->toArray();

    $base = [];
    foreach ($values as $key => $value) {

      if($value['database']){

        if(!in_array($value['database'], $base)){
          $base[] = $value['database'];
        }

      }

    }

    return $base;
  }

  public function getDepartment()
  {
    $rows = Sheets::spreadsheet(config('google.post_spreadsheet_id'))->sheet('PUBLISHED_NATIONAL')->get();
    $header = $rows->pull(0);
    $values = Sheets::collection(header: $header, rows: $rows);
    $values->toArray();

    $base = [];
    foreach ($values as $key => $value) {

      if($value['department']){

        if(!in_array($value['department'], $base)){
          $base[] = trim($value['department'], '');
        }

      }

    }

    return $base;
  }

  public function getDepartmentInternational()
  {
    $rows = Sheets::spreadsheet(config('google.post_spreadsheet_id'))->sheet('PUBLISHED_INTER')->get();
    $header = $rows->pull(0);
    $values = Sheets::collection(header: $header, rows: $rows);
    $values->toArray();

    $base = [];
    foreach ($values as $key => $value) {

      if($value['department']){

        if(!in_array($value['department'], $base)){
          $base[] = trim($value['department'], '');
        }

      }

    }

    return $base;
  }

}
