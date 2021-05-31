@extends('customers.layout')
@section('heads')
    <title>Asistecia</title>
@endsection
@section('content')
@include('customers.topbarEdit')
<div class="container">
    <div class="row">
        <div class="card w-100 pb-5">
            <div class="row p-4">
                <input type="hidden" id="event_id" value="{{$event_id}}">
                <div class="col-xl-2 text-center">
                    <div class="col-xl-12 pt-3 pb-2 w-100 btn-totalSales">
                        <h1 class="bold mt-2 text-btn-totalSales" id="spectators"></h1>
                        <p class="text-btn-totalSales">Público dentro del evento</p>
                    </div>
                    <div class="col-xl-12 pt-3 pb-2 w-100 btn-totalPending mt-2">
                        <h1 class="bold mt-2 text-btn-totalPending" id="exhibitors"></h1>
                        <p class="text-btn-totalPending">Empresarios</p>
                    </div>
                    <div class="col-xl-12 pt-3 pb-2 w-100 btn-totalExpired mt-2">
                        <h1 class="bold mt-2 text-btn-totalExpired" id="courtesies"></h1>
                        <p class="text-btn-totalExpired">Cortesías</p>
                    </div>
                    <button class="btn btn-primary mt-3" id="refresh">Actualizar</button>
                </div>
                <div class="col-xl-10">
                    <div class="col-xl-12" id="graphic">
            
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="{{asset('js/highcharts.js')}}"></script>
<script src="{{asset('js/customers/assistance.js')}}"></script>
@endsection