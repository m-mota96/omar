@extends('customers.layout')
@section('heads')
    <title>Reservaciones</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
@endsection
@section('content')
    @include('customers.topbarEdit')
    <div class="container">
        <input type="hidden" id="eventId" value="{{$event_id}}">
        <div class="card">
            <div class="col-xl-12 p-5">
                <table class="table table-striped w-100" id="sales">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Cliente</th>
                            <th>Correo</th>
                            <th>Teléfono</th>
                            <th>Método de pago</th>
                            <th>Estatus</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                </table>
                <tbody>
    
                </tbody>
            </div>
        </div>
    </div>
    @include('modals.customers.detailsSale')
@endsection
@section('scripts')
    <script src="{{asset('datatables/datatables.min.js')}}"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{asset('js/charging.js')}}"></script>
    <script src="{{asset('js/customers/reservations.js')}}"></script>
@endsection