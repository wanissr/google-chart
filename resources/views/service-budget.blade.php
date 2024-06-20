@extends('layouts.master')
@section('title')
  งบประมาณด้านบริการวิชาการ
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
    @slot('li_1')  งบประมาณด้านบริการวิชาการ @endslot
    @slot('title') งบประมาณด้านบริการวิชาการ @endslot
@endcomponent
<div class="row">
  <div class="col">
    <div class="h-100">
      <div class="row justify-content-center">
        <div class="col-md-6 col-sm-12">
          <div class="card">
            <div class="card-header align-items-center d-flex">
              <h4 class="card-title mb-0 flex-grow-1">จำนวนเงินสนับสนุนตามประเภทย้อนหลัง 5 ปี</h4>
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

      let chart = new ApexCharts(document.querySelector("#chart"), options_bar);
      chart.render()

      getChart()

      function getChart(){

        $.ajax({
            url: "/service-budget/chart",
            method: "POST",
            data: {}
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
