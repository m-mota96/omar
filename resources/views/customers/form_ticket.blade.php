@extends('customers.layout')
@section('heads')
    <title>Forma por boleto</title>
    <link rel="stylesheet" href="{{asset('css/bootstrap-multiselect.css')}}" type="text/css"/>
@endsection
@section('content')
@include('customers.topbarEdit')
<div class="container">
    <div class="row" id="card-content-form_questions">
        <div class="card w-100 pb-5">
            <input type="hidden" id="event_id" value="{{$event->id}}">
            <div class="row p-4" id="contentFormTicket">
                
            </div>
        </div>
    </div>
</div>
@include('modals.customers.editQuestions')
@endsection
@section('scripts')
    <script src="{{asset('js/bootstrap-multiselect.js')}}"></script>
    <script>
        var tickets = @json($tickets);
        var initialQuestions = @json($questions);
        var indicatorIndex = true;
    </script>
    <script src="{{asset('js/customers/formTicket.js')}}"></script>
@endsection