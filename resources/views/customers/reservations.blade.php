@extends('customers.layout')
@section('heads')
    <title>Reservaciones</title>
    <link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
@endsection
@section('content')
    @include('customers.topbarEdit')
    <div class="col-xl-12">
        <input type="hidden" id="eventId" value="{{$event_id}}">
        <div class="card">
            <div class="row p-5">
                <div class="col-xl-12 justify-content-end text-left mb-5">
                    <a class="btn btn-warning" href="{{route('excel/downloadPayments', $event_id)}}">Descargar base de datos</a>
                </div>
                <div class="col-xl-12">
                    <table class="table table-striped w-100" id="sales">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Cliente</th>
                                <th>Correo</th>
                                <th>Teléfono</th>
                                <th>Método de pago</th>
                                <th>Monto</th>
                                <th>Código de descuento</th>
                                <th>Estatus</th>
                                <th>Fecha</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
        
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('modals.customers.detailsSale')
@endsection
@section('scripts')
    <script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
    <script src="{{asset('js/charging.js')}}"></script>
    <script src="{{asset('js/customers/reservations2.js')}}"></script>
@endsection