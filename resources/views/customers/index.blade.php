@extends('customers.layout')
@section('heads')
    <link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
@endsection
@section('content')
    @include('customers.topbar')
    <div class="container">
        <div class="row mt-5 mb-4">
            <h2 class="bold">Listado de eventos</h2>
        </div>
        <div class="row">
            <div class="col-xl-9 pl-0 pr-4">
                <input class="form-control mb-4" type="search" placeholder="Buscar por nombre de evento" id="searchEvents" autocomplete="off">
                <div class="row mb-4">
                    <div class="col-xl-3">
                        <a class="bold text-green-400" href="{{route('home')}}">ACTIVOS ({{(!empty($quantities['actives'])) ? $quantities['actives'] : 0}})</a>
                    </div>
                    <div class="col-xl-3">
                        <a class="bold text-green-400" href="{{route('home', 'inactive')}}">INACTIVOS ({{(!empty($quantities['inactives'])) ? $quantities['inactives'] : 0}})</a>
                    </div>
                    <div class="col-xl-3">
                        <a class="bold text-green-400" href="{{route('home', 'past')}}">PASADOS ({{(!empty($quantities['past'])) ? $quantities['past'] : 0}})</a>
                    </div>
                    <div class="col-xl-3">
                        <a class="bold text-green-400" href="{{route('home', 'all')}}">TODOS ({{(!empty($quantities['all'])) ? $quantities['all'] : 0}})</a>
                    </div>
                </div>
                <div class="row pr-3" id="divEvents">
                    @foreach ($events as $e)
                        <div class="col-xl-4 pr-0 mb-4">
                            @if (isset($e->profile->name))
                                <img class="w-100 h-100 img-index" src="{{asset('media/'.$e->profile->name)}}" alt="{{$e->name}}" id="imageEvent-{{$e->id}}">
                            @else
                                <img class="w-100" src="{{asset('media/'.$e->imageGeneral)}}" alt="{{$e->name}}" id="imageEvent-{{$e->id}}">
                            @endif
                        </div>
                        <div class="col-xl-8 bg-white p-4 mb-4">
                            <div class="row">
                                <div class="col-xl-8">
                                    <h4 class="bold mb-0"><a class="text-dark" href="{{route('customer.edit', $e->id)}}">{{$e->name}}</a></h4>
                                    <span>
                                        {{$e->initial_date}} a 
                                        {{$e->final_date}}
                                    </span>
                                    <p></p>
                                    <span class="font-small mr-4"><a class="text-dark" href="{{route('customer.edit', $e->id)}}">EDITAR</a></span>
                                    @if ($e->sales == 0)
                                        <span class="font-small mr-4"><a class="text-dark" href="">ELIMINAR</a></span>
                                    @endif
                                    @if ($e->status == 1 || $e->status == 0)
                                        <input class="statusEvent" type="checkbox" {{($e->status == 1) ? "checked" : ""}} {{(empty(auth()->user()->contract) && auth()->user()->email != 'miguel.angel9603@hotmail.com') ? "disabled" : ""}} data-toggle="toggle" data-width="100" data-on="Activo" data-off="Inactivo" data-onstyle="success" data-eventId="{{$e->id}}">
                                    @endif
                                </div>
                                <div class="col-xl-4 text-right">
                                    <h3 class="mb-0"><span class="text-blue-400">{{$e->sales}}/</span><span class="text-blue-300">{{$e->quantity_tickets}}</span></h3>
                                    <span class="font-small mt-0">BOLETOS RESERVADOS</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    @if (sizeof($events) == 0)
                        <h2 class="mt-5 text-center w-100 text-gray-600">Lo sentimos, no hay eventos con este filtro.</h2>
                    @endif
                </div>
                <div class="row text-center justify-content-center">
                    {{ $events->links() }}
                </div>
            </div>
            <div class="col-xl-3 pt-0">
                <div class="card mt-0">
                    <div class="card-body text-center">
                    <h5 class="card-title text-center mb-4">OPCIONES</h5>
                    <p class="btn bg-orange-400 text-white w-100" id="createEvent"><i class="fas fa-plus"></i> Crear nuevo evento</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('modals.customers.createEvent')
@endsection
@section('scripts')
    {{-- <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAOmdO7d-Hf7ZA7sVJpwICf1fWx-aQYzo4&libraries=places"></script> --}}
    {{-- <script src="{{asset('js/jquery.imgareaselect.js')}}"></script> --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.19.3/moment.min.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
    <script src="{{asset('js/customers/index.js')}}"></script>
@endsection