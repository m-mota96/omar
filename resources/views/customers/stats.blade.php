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
                <input type="hidden" id="event_id" value="{{$event_id}}">
                <div class="col-xl-6">
                    <label class="bold">Rango de fechas:</label>
                    <div class="input-group mb-3 input-daterange">
                        <div class="input-group-prepend">
                          <span class="input-group-text">De:</span>
                        </div>
                        <input type="date" class="form-control" id="start_date">
                        <div class="input-group-prepend">
                            <span class="input-group-text">a:</span>
                        </div>
                        <input type="date" class="form-control" id="end_date">
                    </div>
                </div>
            </div>
            <div class="row p-4">
                <div class="col-xl-12" id="graphic">
            
                </div>
            </div>
            <div class="row pl-5 pr-5">
                <div class="col-xl-6 text-center offset-xl-1">
                    <div class="col-xl-12 pt-3 pb-2 w-100 btn-totalSales">
                        <div class="row">
                            <div class="col-xl-4">
                                <h1 class="bold mt-2 text-btn-totalSales" id="totalNotDiscount"></h1>
                                <p class="text-btn-totalSales">S/Descuento</p>
                            </div>
                            <div class="col-xl-4">
                                <h1 class="bold mt-2 text-btn-totalSales" id="totalDiscount"></h1>
                                <p class="text-btn-totalSales">C/Descuento</p>
                            </div>
                            <div class="col-xl-4">
                                <h1 class="bold mt-2 text-btn-totalSales" id="totalSales"></h1>
                                <p class="text-btn-totalSales">Total</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xl-2 text-center">
                    <div class="col-xl-12 pt-3 pb-2 w-100 btn-totalPending">
                        <h1 class="bold mt-2 text-btn-totalPending" id="totalPending"></h1>
                        <p class="text-btn-totalPending">Pendientes</p>
                    </div>
                </div>
                <div class="col-xl-2 text-center">
                    <div class="col-xl-12 pt-3 pb-2 w-100 btn-totalExpired">
                        <h1 class="bold mt-2 text-btn-totalExpired" id="totalExpired"></h1>
                        <p class="text-btn-totalExpired">Expirados</p>
                    </div>
                </div>
                <div class="col-xl-12 text-center mt-5">
                    <h4 class="bold">Ingresos totales</h4>
                </div>
                <div class="col-xl-12">
                    <table class="table table-striped">
                        <thead>
                            <th>METODO DE PAGO</th>
                            <th>VENTAS TOTALES</th>
                            <th>CARGO POR SERVICIO</th>
                            <th>INGRESO NETO</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="bold">Tarjeta de Crédito / Débito</td>
                                <td>${{(isset($payments[0]->total)) ? number_format($payments[0]->total, 2) : "0.00"}} MXN</td>
                                <td>${{(isset($payments[0]->total)) ? number_format($payments[0]->total * 0.12, 2) : "0.00"}} MXN</td>
                                <td class="text-green bold">${{(isset($payments[0]->total)) ? number_format($payments[0]->total - ($payments[0]->total * 0.12), 2) : "0.00"}} MXN</td>
                            </tr>
                            <tr>
                                <td class="bold">Oxxo</td>
                                <td>${{(isset($payments[1]->total)) ? number_format($payments[1]->total, 2) : "0.00"}} MXN</td>
                                <td>${{(isset($payments[1]->total)) ? number_format($payments[1]->total * 0.12, 2) : "0.00"}} MXN</td>
                                <td class="text-green bold">${{(isset($payments[1]->total)) ? number_format($payments[1]->total - ($payments[1]->total * 0.12), 2) : "0.00"}} MXN</td>
                            </tr>
                            <tr>
                                <td colspan="2"></td>
                                <td class="bold">Total</td>
                                <td class="bold text-orange">
                                    @if (isset($payments[0]->total) && !isset($payments[1]->total))
                                        ${{number_format(($payments[0]->total - ($payments[0]->total * 0.12)), 2)}}
                                    @elseif (!isset($payments[0]->total) && isset($payments[1]->total))
                                        ${{number_format(($payments[1]->total - ($payments[1]->total * 0.12)), 2)}}
                                    @elseif (isset($payments[0]->total) && isset($payments[1]->total))
                                        ${{number_format(($payments[0]->total - ($payments[0]->total * 0.12)) + ($payments[1]->total - ($payments[1]->total * 0.12)), 2)}}
                                    @else
                                        $0.00
                                    @endif
                                    MXN
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            {{-- <div class="row">
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
            </div> --}}

        </div>
    </div>
</div>
@endsection
@section('scripts')
    <script src="{{asset('js/highcharts.js')}}"></script>
    <script src="{{asset('datatables/datatables.min.js')}}"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <script src="{{asset('js/datepicker_es.js')}}"></script>
    <script src="{{asset('js/customers/stats.js')}}"></script>
@endsection