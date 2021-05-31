@extends('customers.layout')
@section('heads')
    <title>Mis tipos de boleto</title>
    <link href="{{asset('css/nouislider.css')}}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
@endsection
@section('content')
@include('customers.topbarEdit')
<div class="container">
    <div class="row mt-5">
        <input type="hidden" id="days_event" value="{{sizeof($event->eventDates)}}">
        <input type="hidden" id="url_event" value="{{$event->url}}">
        <div class="col-xl-9 pl-0 pr-4" id="content-tickets">
            
        </div>
        <div class="col-xl-3 pt-0">
            <div class="card mt-0">
                <div class="card-body text-center">
                    <h5 class="card-title text-center mb-4">OPCIONES</h5>
                    <input type="hidden" id="event_id" value="{{$event_id}}">
                    <input type="hidden" id="model_payment" value="{{$event->model_payment}}">
                    <input type="hidden" id="quantity_payments" value="{{$quantityPayments}}">
                    <p class="btn bg-orange-400 text-white w-100" onclick="saveTicket()"><i class="fas fa-plus"></i> Nuevo tipo de boleto</p>
                </div>
            </div>
            <div class="card mt-3">
                <div class="card-body text-center">
                    <h5 class="card-title text-center mb-4">MODELO DE COBRO</h5>
                    <div class="row card-inline pl-3 pr-3">
                        <p class="btn btn-outline-primary w-50 btn-br" onclick="modelPayment(0)" id="separated">SEPARADO</p>
                        <p class="btn btn-outline-primary w-50 btn-bl"  onclick="modelPayment(1)" id="included">INCLUIDO</p>
                    </div>
                </div>
                <div class="card-footer text-center">
                    <span class="text-blue pointer"><i class="fas fa-question-circle text-dark"></i> ¿Qué es esto?</span>
                </div>
            </div>
        </div>
    </div>
</div>
@include('modals.customers.tickets')
@endsection
@section('scripts')
    <script src="{{asset('js/nouislider.js')}}"></script>
    <script src="{{asset('js/wNumb.js')}}"></script>
    <script src="{{asset('js/customers/tickets.js')}}"></script>
    <script>
        chargingDom('<?= $tickets; ?>');
    </script>
@endsection