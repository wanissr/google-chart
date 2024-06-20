@extends('layouts.master')
@section('title')
  ค่าสนับสนุนการตีพิมพ์
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
    @slot('li_1')  ค่าสนับสนุนการตีพิมพ์ @endslot
    @slot('title') ค่าสนับสนุนการตีพิมพ์ @endslot
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
          <div class="col-sm-12">
            <button class="btn btn-info" id="btn_search">ค้นหา</button>
            <button class="btn btn-outline-warning waves-effect waves-light material-shadow-none" id="btn_clear">ล้างการค้นหา</button>
          </div>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-6">
          <div class="card">
            <div class="card-header align-items-center d-flex">
              <h4 class="card-title mb-0 flex-grow-1">จำนวนเงินสนับสนุนย้อนหลัง 5 ปี</h4>
            </div>
            <div class="card-body p-2">
              <div class="w-100">
              <div id="chart" class="apex-charts" dir="ltr"></div>
              </div>
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

      let chart = new ApexCharts(document.querySelector("#chart"), options_bar);
      chart.render()

      getChart()

      $('#btn_search').click(function(){
        getChart()
      })

      $('#btn_clear').click(function(){
        choices_year.setChoiceByValue("")
        getChart()
      })

      function getChart(){

        $.ajax({
            url: "/publishing-support/chart",
            method: "POST",
            data: {
              year: $("#year").val()
            }
          }).done(function(response) {

            chart.updateSeries([{
              name: 'Budgets',
              data: response.data
            }])

          })

      }

    })
  </script>
@endsection
