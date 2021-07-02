@extends('customers.layout')
@section('heads')
    <title>Forma por boleto</title>
    <link rel="stylesheet" href="{{asset('css/bootstrap-multiselect.css')}}" type="text/css"/>
@endsection
@section('content')
@include('customers.topbarEdit')
<div class="container">
    <div class="row">
        <div class="card w-100 pb-5">
            <div class="row p-4" id="contentFormTicket">
                <div class="col-xl-3 text-center">
                    <h1 class="mt-4"><i class="fas fa-clipboard-list text-info font-super-large"></i></h1>
                </div>
                <div class="col-xl-9">
                    <h2 class="bold mt-5">Forma por boleto</h2>
                    <ul class="pl-4" style= "list-style-type: square">
                        <li><h4>Pide información por cada asistente.</h4></li>
                        <li><h4><span class="bold">Ejemplo:</span> Edad, talla de camiseta, etc.</h4></li>
                    </ul>
                    <h2 class="bold text-primary pointer subrayed" id="createQuestion"><a>Crear forma por boleto</a></h2>
                </div>
            </div>
        </div>
    </div>
</div>
@include('modals.customers.editQuestions')
@endsection
@section('scripts')
    <script>var tickets = @json($tickets);</script>
    <script src="{{asset('js/bootstrap-multiselect.js')}}"></script>
    <script src="{{asset('js/customers/formTicket.js')}}"></script>
@endsection