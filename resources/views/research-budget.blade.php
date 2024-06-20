@extends('layouts.master')
@section('title')
  งบประมาณด้านการวิจัย
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
    @slot('li_1')  งบประมาณด้านการวิจัย @endslot
    @slot('title') งบประมาณด้านการวิจัย @endslot
@endcomponent
<div class="row">
  <div class="col">
    <div class="h-100">
        <div class="row mb-3 pb-1">
          <div class="row row-cols-lg-6 g-3 align-items-center">
            <div class="col-12">
              <select class="form-control" id="year" name="year">
                <option value="">เลือกปี</option>
                @for($i = 0; $i <= 4;$i++)
                  <option value="{{ date('Y') - $i }}">{{ date('Y') - $i }}</option>
                @endfor
              </select>
            </div>
            <div class="col-12">
              <select class="form-control" id="type" name="type">
                <option value="">เลือกประเภททุน</option>
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
          <div class="col-xl-6 col-sm-12">
            <div class="card">
              <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">จำนวนเงินสนับสนุนย้อนหลัง 5 ปี</h4>
              </div>
              <div class="card-body p-2">
                <div class="w-100">
                <div id="chartY" class="apex-charts" dir="ltr"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-xl-6 col-sm-12">
            <div class="card">
              <div class="card-header align-items-center d-flex">
                <h4 class="card-title mb-0 flex-grow-1">จำนวนเงินสนับสนุนตามประเภทย้อนหลัง 5 ปี</h4>
              </div>
              <div class="card-body p-2">
                <div class="w-100">
                <div id="chartT" class="apex-charts" dir="ltr"></div>
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
                              <th>ชื่อโครงการวิจัย</th>
                              <th>ชื่อหัวหน้าโครงการ</th>
                              <th>ชื่อผู้ร่วมวิจัย</th>
                              <th>ชื่อแหล่งทุน/หน่วยงาน</th>
                              <th>ปี</th>
                              <th>ประเภท</th>
                              <th>สถานะ</th>
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

      let choices_year = new Choices('#year', option_choices)
      let choices_type = new Choices('#type', option_choices)

      let chartY = new ApexCharts(document.querySelector("#chartY"), options_bar);
      chartY.render()

      let chartT = new ApexCharts(document.querySelector("#chartT"), options_bar);
      chartT.render()

      reloadChart()

      let tb = $('#tb').DataTable({
        scrollX: true,
        processing: true,
        searching: true,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        ajax: {
          url: "research-budget/list",
          type: "POST",
          data: function (d) {
            d.year = $("#year").val()
            d.type = $("#type").val()
          }
        },
        columns: [
          { "data": "project_name" },
          { "data": "head_project_name" },
          { "data": "co_project_name" },
          { "data": "department" },
          { "data": "year" },
          { "data": "type" },
          { "data": "status" },
        ]
      })

      function getChart(chartType){

        $.ajax({
            url: "/research-budget/chart",
            method: "POST",
            data: {
              year: $("#year").val(),
              type: $("#type").val(),
              chart_type: chartType
            }
          }).done(function(response) {

            if(chartType === 'year'){
              chartY.updateSeries([{
                name: 'Budgets',
                data: response.data
              }])

            }else{
              chartT.updateSeries([{
                name: 'Budgets',
                data: response.data
              }])

            }

          })

      }

      $('#btn_search').click(function(){
        reloadData()
      })

      $('#btn_clear').click(function(){
        choices_year.setChoiceByValue("")
        choices_type.setChoiceByValue("")
        reloadData()
      })

      function reloadData(){
        tb.ajax.reload()
        reloadChart()
      }

      function reloadChart(){
        getChart('year')
        getChart('type')
      }

    })
  </script>
@endsection
