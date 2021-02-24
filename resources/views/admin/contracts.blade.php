@extends('admin.layout')
@section('heads')
    
@endsection
@section('contect')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Subir contratos</h1>
        </div>
        <!-- Content Row -->
        <div class="row">
            <div class="col-lg-12 card p-5">
                <table class="table table-striped" id="contracts">
                    <thead>
                        <th>#</th>
                        <th>Cliente</th>
                        <th>Correo</th>
                        <th>Información fiscal</th>
                    </thead>
                    
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{asset('js/admin/contracts.js')}}"></script>
@endsection