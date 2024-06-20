<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Revolution\Google\Sheets\Facades\Sheets;

class PublishedController extends Controller
{

  public function index()
  {
    $departments = $this->getDepartment();
    return view('index', compact('departments'));
  }

  public function international()
  {
    $departments = $this->getDepartmentInternational();
    return view('international', compact('departments'));
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
        $set_data = false;
        $link = '';
        if($value['link']){
          $link = '<a href="'.$value['link'].'" target="_blank" class="btn btn-sm btn-outline-secondary waves-effect waves-light material-shadow-none">Link</a>';
        }

        if($filter2){
          if($value['year'] == $year && $department == $value['department']){
            $set_data = true;
          }

        }else if($filter1){
          if($value['year'] == $year || ($department == $value['department'] && $value['department'] != '')){
            $set_data = true;
          }

        }else{
          $set_data = true;

        }

        if($set_data){
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
        }
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
        $base_data = $this->getDataChartDepartment($values, $filter2, $filter1, $year, $department);

      }

      return response()->json($base_data);

    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public function listInternational(Request $request)
  {
    try {
      $match_data = 0;
      $year = $request->year;
      $quartile = $request->quartile;
      $department = $request->department;

      !empty($year) ? $match_data++ : '';
      !empty($quartile) ? $match_data++ : '';
      !empty($department) ? $match_data++ : '';

      $rows = Sheets::spreadsheet(config('google.post_spreadsheet_id'))->sheet('PUBLISHED_INTER')->get();

      $header = $rows->pull(0);
      $values = Sheets::collection(header: $header, rows: $rows);
      $values->toArray();

      $base_data = [];
      foreach ($values as $key => $value) {
        $count_match = 0;
        $set_data = false;
        $link = '';
        if($value['link']){
          $link = '<a href="'.$value['link'].'" target="_blank" class="btn btn-sm btn-outline-secondary waves-effect waves-light material-shadow-none">Link</a>';
        }

        $v_quartile = trim($value['quartile'], '');
        $v_department = trim($value['department'], '');

        if($match_data == 3){
          if($value['year'] == $year && $quartile == $v_quartile && $department == $v_department){
            $set_data = true;
          }
        }else if($match_data == 0){
          $set_data = true;

        }else{
          ($year != '' && $value['year'] == $year) ? $count_match++ : '';
          ($quartile != '' && $quartile == $v_quartile) ? $count_match++ : '';
          ($department != '' && $department == $v_department) ? $count_match++ : '';

          $set_data = ($count_match == $match_data) ? true : false;
        }

        if($set_data){
          $base_data[] = [
            'row_no' => $key,
            'title' => $value['title'] ?? '-',
            'author' => $value['author'] ?? '-',
            'name' => $value['fullname'] ?? '-',
            'department' => $value['department'] ?? '-',
            'source' => $value['source'] ?? '-',
            'year' => $value['year'] ?? '-',
            'quartile' => $value['quartile'] ?? '-',
            'link' => $link
          ];
        }

      }

      $list = [
        "data" => $base_data
      ];

      return response()->json($list);


    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public function getChartInternational(Request $request)
  {
    try {
      $match_data = 0;
      $year = $request->year ? [$request->year] : ['2024', '2023', '2022', '2021', '2020'];
      $quartile = $request->quartile;
      $department = $request->department;
      $chart_type = $request->chart_type;
      $data = [];
      $title_data = [];

      !empty($year) ? $match_data++ : '';
      !empty($quartile) ? $match_data++ : '';
      !empty($department) ? $match_data++ : '';

      $arr_depart = $this->getDepartmentInternational();
      $arr_quartile = ['Q1', 'Q2', 'Q3', 'Q4'];

      $rows = Sheets::spreadsheet(config('google.post_spreadsheet_id'))->sheet('PUBLISHED_INTER')->get();

      $header = $rows->pull(0);
      $values = Sheets::collection(header: $header, rows: $rows);
      $values->toArray();

      foreach ($values as $key => $value) {
        $count_match = 0;
        $set_data = false;

        $v_quartile = trim($value['quartile'], '');
        $v_department = trim($value['department'], '');

        if($match_data == 3){
          if(in_array($value['year'], $year) && $quartile == $v_quartile  && $department == $v_department){
            $set_data = true;
          }
        }else if($match_data == 0){
          $set_data = true;

        }else{
          ($year != '' && in_array($value['year'], $year)) ? $count_match++ : '';
          ($quartile != '' && $quartile == $v_quartile) ? $count_match++ : '';
          ($department != '' && $department == $v_department) ? $count_match++ : '';

          $set_data = ($count_match == $match_data) ? true : false;
        }

        if($set_data){
          if(!$this->checkDuplicate($title_data, $value['title'])){

            // set title for check duplicate
            $title_data[] = [
              'title' => $value['title']
            ];

            if($chart_type == 'year'){

              if(isset($data[$value['year']])){
                $data[$value['year']] = $data[$value['year']] + 1;
              }else{
                $data[$value['year']] = 1;
              }

            }else if($chart_type == 'quartile'){

              $index_quartile = array_search($v_quartile, $arr_quartile);

              if(isset($data[$index_quartile])){
                $data[$index_quartile] = $data[$index_quartile] + 1;
              }else{
                $data[$index_quartile] = 1;
              }

            }else if($chart_type == 'department'){

              $index_depart = array_search($v_department, $arr_depart);

              if(isset($data[$index_depart])){
                $data[$index_depart] = $data[$index_depart] + 1;
              }else{
                $data[$index_depart] = 1;
              }

            }
          }
        }

      }

      ksort($data);

      $base_data = [];
      foreach ($data as $key => $value) {

        if($chart_type == 'year'){
          $base_data['label'][] = $key;

        }else if($chart_type == 'quartile'){
          $base_data['label'][] = $arr_quartile[$key];

        }else if($chart_type == 'department'){
          $base_data['label'][] = $arr_depart[$key];

        }

        $base_data['data'][] = $value;
      }

      return response()->json($base_data);

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
          $base[] = trim($value['database'], '');
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

  public function getDataChartYear($values, $filter2, $filter1, $year, $department = '')
  {
    try {
      $data = [];
      $title_data = [];
      foreach ($values as $key => $value) {
        $set_data = false;
        $department_v = trim($value['department'], '');
        if($filter2){
          if(in_array($value['year'], $year) && $department == $department_v){
            $set_data = true;
          }

        }else if($filter1){
          if(in_array($value['year'], $year) || ($department == $department_v && $department_v != '')){
            $set_data = true;
          }

        }else{
          $set_data = true;
        }

        if($set_data){
          if(!$this->checkDuplicate($title_data, $value['title'])){

            // set title for check duplicate
            $title_data[] = [
              'title' => $value['title']
            ];

            if(isset($data[$value['year']])){
              $data[$value['year']] = $data[$value['year']] + 1;
            }else{
              $data[$value['year']] = 1;
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

  public function getDataChartDepartment($values, $filter2, $filter1, $year, $department = '')
  {
    try {
      $data = [];
      $title_data = [];
      $arr_depart = $this->getDepartment();

      foreach ($values as $key => $value) {
        $set_data = false;
        $department_v = trim($value['department'], '');
        $index_depart = array_search($department_v, $arr_depart);

        if($filter2){
          if(in_array($value['year'], $year) && $department == $department_v){
            $set_data = true;
          }

        }else if($filter1){
          if(in_array($value['year'], $year) || ($department == $department_v && $department_v != '')){
            $set_data = true;
          }

        }else{
          $set_data = true;
        }

        if($set_data){
          if(!$this->checkDuplicate($title_data, $value['title'])){

            // set title for check duplicate
            $title_data[] = [
              'title' => $value['title']
            ];

            if(isset($data[$index_depart])){
              $data[$index_depart] = $data[$index_depart] + 1;
            }else{
              $data[$index_depart] = 1;
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

}
