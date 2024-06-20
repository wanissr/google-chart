<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Revolution\Google\Sheets\Facades\Sheets;

class IntellectualController extends Controller
{
  public function index()
  {
    $types = $this->getDataType();
    $years = $this->getDataYear();
    return view('intellectual', compact('types', 'years'));
  }

  public function list(Request $request)
  {
    try {

      $year = $request->year;
      $type = $request->type;
      $date_type = $request->date_type;

      $rows = Sheets::spreadsheet(config('google.post_spreadsheet_id'))->sheet('INTELLECTUAL')->get();

      $header = $rows->pull(0);
      $values = Sheets::collection(header: $header, rows: $rows);
      $values->toArray();

      $base_data = [];
      foreach ($values as $key => $value) {
        $link = '';
        if($value['link']){
          $link = '<a href="'.$value['link'].'" target="_blank" class="btn btn-sm btn-outline-secondary waves-effect waves-light material-shadow-none">Link</a>';
        }

        $check_filter = $this->checkFilterDate($date_type, $value, $year, $type);
        if($check_filter){
          $base_data[] = [
            'row_no' => $key,
            'invention_name' => $value['invention_name'] ?? '-',
            'inventor' => $value['inventor'] ?? '-',
            'coinventor' => $value['co_inventor'] ?? '-',
            'major' => $value['department'] ?? '-',
            'submission_date' => $value['submission_date'] ? $this->coverDate($value['submission_date']) : '-',
            'request_date' => $value['request_date'] ? $this->coverDate($value['request_date']) : '-',
            'type' => $value['type'] ?? '-',
            'no' => $value['no'] ?? '-',
            'registration_date' => $value['registration_date'] ? $this->coverDate($value['registration_date']) : '-',
            'expires_date' => $value['expires_date'] ? $this->coverDate($value['expires_date']) : '-',
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

      $year = $request->year;
      $type = $request->type;
      $date_type = $request->date_type;

      $rows = Sheets::spreadsheet(config('google.post_spreadsheet_id'))->sheet('INTELLECTUAL')->get();

      $header = $rows->pull(0);
      $values = Sheets::collection(header: $header, rows: $rows);
      $values->toArray();

      $data = [];
      foreach ($values as $key => $value) {

        $check_filter = $this->checkFilterDate($date_type, $value, $year, $type);
        if($check_filter){

          if($value[$date_type]){
            $year_date = date('Y', strtotime($value[$date_type]));

            if(isset($data[$year_date])){
              $data[$year_date] = $data[$year_date] + 1;
            }else{
              $data[$year_date] = 1;
            }
          }

        }

      }

      ksort($data);

      $base_data = [];
      $total = 0;
      foreach ($data as $key => $value) {
        $base_data['label'][] = $key;
        $base_data['data'][] = $value;
        $total += $value;
      }

      $base_data['total'] = number_format($total);

      return $base_data;

    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public function checkFilterDate($date_type, $data = null, $year = null, $type = null)
  {

    if(!$year && !$type){
      return true;

    }else{

      if($data){
        $filter2 = $year && $type;
        $filter1 = !$filter2 && ($year || $type);

        $v_type = trim($data['type']);

        if($filter2){
          if($date_type){
            $year_date = date('Y', strtotime($data[$date_type]));
            if($year_date == $year && $data[$date_type] != '' && $type ==  $v_type){
              return true;
            }

          }else{
            $year1 = date('Y', strtotime($data['submission_date']));
            $year2 = date('Y', strtotime($data['request_date']));
            $year3 = date('Y', strtotime($data['registration_date']));

            if(($year1 == $year || $year2 == $year || $year3 == $year) && $type ==  $v_type){
              return true;
            }
          }

        }else if($filter1){

          if($date_type){
            $year_date = date('Y', strtotime($data[$date_type]));
            if(($year_date == $year || $type ==  $v_type) && $data[$date_type] != ''){
              return true;
            }

          }else{
            $year1 = date('Y', strtotime($data['submission_date']));
            $year2 = date('Y', strtotime($data['request_date']));
            $year3 = date('Y', strtotime($data['registration_date']));

            if(($year1 == $year || $year2 == $year || $year3 == $year) || $type ==  $v_type){
              return true;
            }
          }
        }
      }

    }

    return false;

  }

  public function getDataType()
  {
    try {
      $rows = Sheets::spreadsheet(config('google.post_spreadsheet_id'))->sheet('INTELLECTUAL')->get();

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

  public function getDataYear()
  {
    try {
      $rows = Sheets::spreadsheet(config('google.post_spreadsheet_id'))->sheet('INTELLECTUAL')->get();

      $header = $rows->pull(0);
      $values = Sheets::collection(header: $header, rows: $rows);
      $values->toArray();

      $base = [];
      foreach ($values as $key => $value) {

        if($value['submission_date']){
          $year1 = date('Y', strtotime($value['submission_date']));
          if(!in_array($year1, $base)){
            $base[] = trim($year1, '');
          }
        }

        if($value['request_date']){
          $year2 = date('Y', strtotime($value['request_date']));
          if(!in_array($year2, $base)){
            $base[] = trim($year2, '');
          }
        }

        if($value['registration_date']){
          $year3 = date('Y', strtotime($value['registration_date']));
          if(!in_array($year3, $base)){
            $base[] = trim($year3, '');
          }
        }
      }
      arsort($base);

      return $base;

    } catch (\Throwable $th) {
      throw $th;
    }
  }

  public function coverDate($date)
  {
    $arr_month = ['มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
    $arr_date = explode('-', $date);
    $year = $arr_date[0];
    $month = $arr_month[$arr_date[1]-1];

    return $arr_date[2].' '.$month.' '.$year;

  }
}
