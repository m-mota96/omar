@extends('customers.layout')
@section('heads')
    <title>Escaner</title>
@endsection
@section('content')
@include('customers.topbarEdit')
<div class="container">
    <div class="row">
        <div class="card w-100 pb-5">
            <div class="row p-4">
                <div class="col-12 col-xl-6">
                    <video class="w-100 h-mb" id="preview"></video>
                </div>
                <div class="col-12 col-xl-6">
                    <h5><b>Folio:</b> <span id="folio"></span></h5>
                    <input type="hidden" id="folioOculto">
                    <button class="btn btn-warning hidden" id="btnValidate" onclick="validateFolio()">Desactivar</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="{{asset('js/instascan.min.js')}}"></script>
<script src="{{asset('js/customers/scan.js')}}"></script>
@endsection