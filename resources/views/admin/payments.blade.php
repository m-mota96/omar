@extends('admin.layout')
@section('heads')
    
@endsection
@section('contect')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Pagos /@if ($status == 0) pendientes @else pagados @endif</h1>
            <input type="hidden" id="status" value="{{$status}}">
        </div>
        <!-- Content Row -->
        <div class="row">
            <div class="col-lg-12 card p-5">
                <table class="table table-striped" id="payments">
                    <thead>
                        <th>#</th>
                        <th>Usuario</th>
                        <th>Correo</th>
                        <th>Evento</th>
                        <th>@if ($status == 0) Fecha de corte @else Fecha de pago @endif</th>
                        <th>Monto total</th>
                        <th>Monto a pagar</th>
                        <th>Ganancia</th>
                        @if ($status == 0)
                        <th>Acciones</th>
                        @endif
                    </thead>
                    
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{asset('js/admin/payments.js')}}"></script>
@endsection