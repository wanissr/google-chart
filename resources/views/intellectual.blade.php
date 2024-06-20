@extends('layouts.master')
@section('title')
ผลงานทรัพย์สินทางปัญญา
@endsection
@section('css')
    <link href="{{ URL::asset('build/libs/jsvectormap/css/jsvectormap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/libs/swiper/swiper-bundle.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/css/datatable/dataTables.bootstrap5.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/css/datatable/responsive.bootstrap.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ URL::asset('build/css/datatable/buttons.dataTables.min.css') }}" rel="stylesheet" type="text/css" />

@endsection
@section('content')
@component('components.breadcrumb')
    @slot('li_1')  ผลงานทรัพย์สินทางปัญญา @endslot
    @slot('title') ผลงานทรัพย์สินทางปัญญา @endslot
@endcomponent
<div class="row">
  <div class="col">
    <div class="h-100">
      <div class="row mb-3 pb-1">
        <div class="row row-cols-lg-6 g-3 align-items-center">
        <div class="col-12">
            <select class="form-control" id="date_type" name="date_type">
              <option value="">เลือกประเภทวัน</option>
              <option value="submission_date">ยื่นคำขอแล้ว</option>
              <option value="request_date">รับคำขอแล้ว</option>
              <option value="registration_date">จดทะเบียนแล้ว</option>
            </select>
          </div>
          <div class="col-12">
            <select class="form-control" id="year" name="year">
              <option value="">เลือกปี</option>
              @foreach($years as $year)
                <option value="{{ $year }}">{{ $year }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-12">
            <select class="form-control" id="type" name="type">
              <option value="">เลือกประเภท</option>
              @foreach($types as $type)
                <option value="{{ $type }}">{{ $type }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-sm-12">
            <button class="btn btn-info" id="btn_search">ค้นหา</button>
            <button class="btn btn-outline-warning waves-effect waves-light material-shadow-none" id="btn_clear">ล้างการค้นหา</button>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-xl-4 col-sm-12">
          <div class="card">
            <div class="card-header align-items-center d-flex">
              <h4 class="card-title mb-0 flex-grow-1">จำนวนผลงานที่ยื่นคำขอแล้ว</h4>
            </div>
            <div class="card-body p-2">
              <p class="text-end fs-5">จำนวนผลงานทั้งหมด <span class="badge bg-primary-subtle text-primary" id="total_chart_1">0</span></p>
              <div class="w-100">
                <div id="chart1" class="apex-charts" dir="ltr"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-4 col-sm-12">
          <div class="card">
            <div class="card-header align-items-center d-flex">
              <h4 class="card-title mb-0 flex-grow-1">จำนวนผลงานที่รับคำขอแล้ว</h4>
            </div>
            <div class="card-body p-2">
              <p class="text-end fs-5">จำนวนผลงานทั้งหมด <span class="badge bg-primary-subtle text-primary " id="total_chart_2">0</span></p>
              <div class="w-100">
              <div id="chart2" class="apex-charts" dir="ltr"></div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-xl-4 col-sm-12">
          <div class="card">
            <div class="card-header align-items-center d-flex">
              <h4 class="card-title mb-0 flex-grow-1">จำนวนผลงานที่จดทะเบียนแล้ว</h4>
            </div>
            <div class="card-body p-2">
              <p class="text-end fs-5">จำนวนผลงานทั้งหมด <span class="badge bg-primary-subtle text-primary" id="total_chart_3">0</span></p>
              <div class="w-100">
              <div id="chart3" class="apex-charts" dir="ltr"></div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
          <div class="col-sm-12">
              <div class="card">
                <div class="card-body">
                  <table id="tb" class="display table table-bordered dt-responsive" style="width:100%">
                    <thead class="table-info">
                        <tr class="text-center">
                            <th>ชื่อการประดิษฐ์</th>
                            <th>ผู้ประดิษฐ์</th>
                            <th>ผู้ร่วมประดิษฐ์</th>
                            <th>หลักสูตร/สาขาวิชา</th>
                            <th>วันยื่นคำขอ</th>
                            <th>วันรับคำขอ</th>
                            <th>ประเภท</th>
                            <th>เลขที่</th>
                            <th>วันที่จดทะเบียน</th>
                            <th>หมดอายุวันที่</th>
                            <th>link</th>
                        </tr>
                    </thead>
                  </table>
                </div>
              </div>
          </div>
      </div>
    </div>
  </div>
</div>

@endsection
@section('script')
  <script src="{{ URL::asset('build/libs/apexcharts/apexcharts.min.js') }}"></script>
  <script src="{{ URL::asset('build/libs/swiper/swiper-bundle.min.js') }}"></script>
  <script src="{{ URL::asset('build/js/app.js') }}"></script>
  <script src="{{ URL::asset('build/js/datatable/jquery.dataTables.min.js') }}"></script>
  <script src="{{ URL::asset('build/js/datatable/dataTables.bootstrap5.min.js') }}"></script>
  <script src="{{ URL::asset('build/js/datatable/dataTables.responsive.min.js') }}"></script>
  <script src="{{ URL::asset('build/js/datatable/dataTables.buttons.min.js') }}"></script>
  <script>
    $( document ).ready(function() {

      const option_choices = {
        shouldSort: false
      }

      let choices_date_type = new Choices('#date_type', option_choices)
      let choices_year = new Choices('#year', option_choices)
      let choices_type = new Choices('#type', option_choices)

      let chart1 = new ApexCharts(document.querySelector("#chart1"), options_chart);
      chart1.render()

      let chart2 = new ApexCharts(document.querySelector("#chart2"), options_chart);
      chart2.render()

      let chart3 = new ApexCharts(document.querySelector("#chart3"), options_chart);
      chart3.render()

      reloadChart()

      let tb = $('#tb').DataTable({
        scrollX: true,
        processing: true,
        searching: true,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        ajax: {
          url: "intellectual/list",
          type: "POST",
          data: function (d) {
            d.year = $("#year").val()
            d.type = $("#type").val()
            d.date_type = $("#date_type").val()
          }
        },
        columns: [
          { "data": "invention_name" },
          { "data": "inventor" },
          { "data": "coinventor" },
          { "data": "major" },
          { "data": "submission_date" },
          { "data": "request_date" },
          { "data": "type" },
          { "data": "no" },
          { "data": "registration_date" },
          { "data": "expires_date" },
          { "data": "link" },
        ]
      })

      $('#btn_search').click(function(){
        reloadData()
      })

      $('#btn_clear').click(function(){
        choices_date_type.setChoiceByValue("")
        choices_year.setChoiceByValue("")
        choices_type.setChoiceByValue("")
        reloadData()
      })

      function getChart(chartType, reloadAll = true){

        $.ajax({
          url: "/intellectual/chart",
          method: "POST",
          data: {
            date_type: chartType,
            year: $("#year").val(),
            type: $("#type").val()
          }
        }).done(function(response) {

          let not_reload_c1 = true;
          let not_reload_c2 = true;
          let not_reload_c3 = true;

          if(chartType === 'submission_date'){
            not_reload_c1 = false
            updateChart(chart1, response.data, response.label)
            updateTotal("#total_chart_1", response.total)
          }

          if(chartType === 'request_date'){
            not_reload_c2 = false
            updateChart(chart2, response.data, response.label)
            updateTotal("#total_chart_2", response.total)

          }

          if(chartType === 'registration_date'){
            not_reload_c3 = false
            updateChart(chart3, response.data, response.label)
            updateTotal("#total_chart_3", response.total)

          }

          if(!reloadAll){
            if(not_reload_c1){
              updateChart(chart1)
              updateTotal("#total_chart_1", 0)

            }

            if(not_reload_c2){
              updateChart(chart2)
              updateTotal("#total_chart_2", 0)

            }

            if(not_reload_c3){
              updateChart(chart3)
              updateTotal("#total_chart_3", 0)

            }
          }

        })

      }

      function reloadData(){
        tb.ajax.reload()

        const date_type = $("#date_type").val()
        if(date_type){
          getChart(date_type, false)

        }else{
          reloadChart()

        }

      }

      function reloadChart(){
        getChart('submission_date')
        getChart('request_date')
        getChart('registration_date')
      }

      function updateChart(chart, data = [], label = []){
        chart.updateOptions({
          labels: label
        })

        chart.updateSeries(data)
      }

      function updateTotal(ele, total){
        $(ele).html(total)
      }

    })
  </script>
@endsection
