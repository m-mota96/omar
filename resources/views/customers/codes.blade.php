@extends('customers.layout')
@section('heads')
    <title>Mis códigos de descuento</title>
    <link href="{{asset('css/nouislider.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@endsection
@section('content')
@include('customers.topbarEdit')
<div class="container">
    <div class="row mt-5">
        <div class="col-xl-9 pl-0 pr-4" id="content-codes">
            
        </div>
        <div class="col-xl-3 pt-0">
            <div class="card mt-0">
                <div class="card-body text-center">
                    <h5 class="card-title text-center mb-4">OPCIONES</h5>
                    <input type="hidden" id="event_id" value="{{$event_id}}">
                    <p class="btn bg-orange-400 text-white w-100" onclick="newCode()"><i class="fas fa-plus"></i> Nuevo código</p>
                </div>
            </div>
        </div>
    </div>
</div>
@include('modals.customers.codes')
@endsection
@section('scripts')
    {{-- <script src="{{asset('js/nouislider.js')}}"></script>
    <script src="{{asset('js/wNumb.js')}}"></script> --}}
    <script src="{{asset('js/customers/codes.js')}}"></script>
    <script>
        var codes = @json($codes);
        chargingDom(codes);
    </script>
@endsection