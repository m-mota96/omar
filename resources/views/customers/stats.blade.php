@extends('customers.layout')
@section('heads')
    <title>Estadísticas</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/css/bootstrap-datepicker3.css"/>
@endsection
@section('content')
@include('customers.topbarEdit')
<div class="container">
    <div class="row">
        <div class="card w-100 pb-5">
            <div class="row p-5">
                <div class="col-xl-6">
                    <div class="input-group mb-3 input-daterange">
                        <div class="input-group-prepend">
                          <span class="input-group-text">De:</span>
                        </div>
                        <input type="text" class="form-control" placeholder="1/{{date('m/Y')}}">
                        <div class="input-group-prepend">
                            <span class="input-group-text">a:</span>
                        </div>
                        <input type="text" class="form-control" placeholder="{{date('d/m/Y')}}">
                    </div>
                </div>
            </div>
            <div class="row p-4">
                <div class="col-xl-12" id="graphic">
            
                </div>
            </div>
            <div class="row">
                <div class="col-xl-4 text-center">
                    <h5 class="mb-3">Boletos pagados: <b>{{$total_sales}}</b></h5>
                    <h5>Ingresos: <b>${{number_format($moneySales, 2)}} MXN</b></h5>
                </div>
                <div class="col-xl-4 text-center">
                    <h5 class="mb-3">Boletos pendientes: <b>{{$total_pending}}</b></h5>
                    <h5>Posibles ingresos: <b>${{number_format($moneyPending, 2)}} MXN</b></h5>
                </div>
                <div class="col-xl-4 text-center">
                    <h5>Boletos vencidos: <b>0</b></h5>
                </div>
            </div>
        </div>
    </div>
</div>
@include('modals.customers.tickets')
@endsection
@section('scripts')
    <script src="{{asset('js/highcharts.js')}}"></script>
    <script src="{{asset('datatables/datatables.min.js')}}"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <script src="{{asset('js/datepicker_es.js')}}"></script>
    <script src="{{asset('js/customers/stats.js')}}"></script>
    <script>
        var sales = @json($sales);
        var pending = @json($pending);
        chart('<?= $final_day; ?>', sales, pending);
    </script>
@endsection