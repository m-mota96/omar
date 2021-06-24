@extends('admin.layout')
@section('heads')
    
@endsection
@section('contect')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Vista general</h1>
        </div>
        <!-- Content Row -->
        <div class="row">
            <div class="col-lg-12 card p-5">
                <div class="card p-4 mb-5 bg-gray">
                    <label>Seleccione el año: </label>
                    <select class="col-xl-1 form-control mb-4" id="">
                        <option value="2021">2021</option>
                    </select>
                    <div class="" id="graphicMonths"></div>
                </div>
                <div class="card p-4 bg-gray" id="graphicYears"></div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script src="{{asset('js/highcharts.js')}}"></script>
<script src="{{asset('js/admin/index.js')}}"></script>
@endsection