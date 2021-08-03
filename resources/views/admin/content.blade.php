@extends('admin.layout')
@section('heads')
    
@endsection
@section('contect')
    <div class="container-fluid">
        <!-- Page Heading -->
        <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">Index/contenido</h1>
        </div>
        <!-- Content Row -->
        <div class="row">
            <div class="col-lg-12 card p-5">
                <div class="row">
                    <div class="col-xl-4 upload rounded pt-5 pb-5 mb-4" id="upload">
                        <text class="text-upload dz-message needsclick" div="drop">
                            <i class="fa fa-upload fa-2x valign "></i><br>
                            Suelte la imagen o haga click en el recuadro para cargar.
                        </text>
                    </div>
                    <div class="col-xl-6">
                        <p><b>NOTA: </b> cargue todas lás imágenes que desea reemplazar en el recuadro con el nombre correspondiente a cada lugar en el sitio web por ejemplo img1.png, img2.png...</p>
                    </div>
                </div>
            </div>
        </div>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
    <script src="{{asset('js/dropzone.js')}}"></script>
    <script src="{{asset('js/admin/content.js')}}"></script>
@endsection