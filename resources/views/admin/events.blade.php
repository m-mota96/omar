@extends('admin.layout')
@section('heads')
    
@endsection
@section('contect')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Eventos /@if ($type == 'paid') de consumo @else gratuitos @endif</h1>
        </div>
        <!-- Content Row -->
        <div class="row">
            <div class="col-lg-12 card p-5">
                <div class="row mb-5">
                    <input type="hidden" id="type" value="{{$type}}">
                    <button type="button" class="col-xl-1 btn btn-dark" data-status="3" onclick="changeStatus.call(this)" id="all">Todos</button>
                    <button type="button" class="col-xl-1 ml-2 btn btn-outline-success" data-status="1" onclick="changeStatus.call(this)" id="active">Activos</button>
                    <button type="button" class="col-xl-1 ml-2 btn btn-outline-warning" data-status="0" onclick="changeStatus.call(this)" id="inactive">Inactivos</button>
                    <button type="button" class="col-xl-1 ml-2 btn btn-outline-danger" data-status="2" onclick="changeStatus.call(this)" id="past">Finalizados</button>
                </div>
                <table class="table table-striped" id="events">
                    <thead>
                        <th>#</th>
                        <th>Nombre del evento</th>
                        <th>Sitio web</th>
                        <th>Fechas</th>
                        @if ($type == 'paid')
                            <th>Ingresos</th>
                            <th>Ganancia</th>
                            <th>Comisión</th>
                        @endif
                        <th>Boletos @if ($type == 'paid') vendidos @else registrados @endif</th>
                        <th>Asistencia</th>
                        <th>Estatus</th>
                    </thead>
                    
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    {{-- <script src="{{asset('js/dropzone.js')}}"></script> --}}
    <script src="{{asset('js/admin/events.js')}}"></script>
@endsection