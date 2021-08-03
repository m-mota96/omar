@extends('admin.layout')
@section('heads')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
@endsection
@section('contect')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Eventos / categorías</h1>
        </div>
        <!-- Content Row -->
        <div class="row">
            <div class="col-lg-12 card p-5">
                <form class="row mb-5" id="formCategory">
                    {{ csrf_field() }}
                    <div class="col-xl-12">
                        <h4 class="bold">Registrar categoría</h4>
                    </div>
                    <div class="col-xl-3">
                        <label>Nombre:</label>
                        <input type="hidden" name="id_category">
                        <input class="form-control" type="text" name="name" required>
                    </div>
                    <div class="col-xl-3 pt-4">
                        <button class="btn btn-primary mt-2" type="submit">Guardar</button>
                    </div>
                </form>
                <table class="table table-striped w-100" id="categories">
                    <thead>
                        <th>#</th>
                        <th>Categoría</th>
                        <th>Acción</th>
                    </thead>
                    
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{asset('js/admin/categories.js')}}"></script>
@endsection