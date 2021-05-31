@extends('customers.layout')
@section('heads')
    <title>Turnos</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/dataTables.bootstrap4.min.css">
    <style>.btn-sm { width: unset !important; }</style>
@endsection
@section('content')
    @include('customers.topbarEdit')
    <div class="container">
        <form class="row" id="formTurns">
            <input type="hidden" id="eventId" name="eventId" value="{{$event_id}}">
            {{ csrf_field() }}
            <div class="col-xl-12 pl-0 pr-4" id="content-tickets">
                <div class="card pl-5 pr-5 pt-3 pb-5" id="contentTurns">

                </div>
            </div>
        </form>
    </div>
@endsection
@section('scripts')
    <script src="{{asset('datatables/datatables.min.js')}}"></script>
    <script src="https://cdn.datatables.net/1.10.22/js/dataTables.bootstrap4.min.js"></script>
    {{-- <script src="{{asset('js/charging.js')}}"></script> --}}
    <script src="{{asset('js/customers/turns.js')}}"></script>
    <script>
        var dates = @json($event->eventDates);
        createTurnsContent(dates);
    </script>
@endsection