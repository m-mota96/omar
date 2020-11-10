<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Compra boletos para {{$event->name}} - Boletos</title>
    <link rel="stylesheet" href="{{asset('fontawesome5.12.1/css/all.css')}}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/public.css') }}" rel="stylesheet">
</head>
<body >
    <nav class="navbar navbar-expand navbar-light topbar static-top shadow bg-white">
        <div class="container">
            <!-- Topbar Navbar -->
            <ul class="navbar-nav pt-1 pb-1">
                {{-- <div class="topbar-divider d-none d-sm-block"></div> --}}
                <!-- Nav Item - User Information -->
                <li class="mr-4">
                    <span class="text-white pointer"><i class="fas fa-chart-line"></i> Estadísticas</span>
                </li>
                <li class="mr-4">
                    <a class="text-white pointer" href=""><i class="fas fa-cog"></i> Configuración</a>
                </li>
                <li class="mr-4">
                    <a class="text-white pointer" href=""><i class="fas fa-tag"></i> Boletos</a>
                </li>
                <li class="mr-4">
                    <span class="text-white pointer"><i class="fas fa-shopping-cart"></i> Reservaciones</span>
                </li>
                <li class="mr-4">
                    <span class="text-white pointer"><i class="fas fa-list-ul"></i> Registro</span>
                </li>
                <li class="mr-4">
                    <span class="text-white pointer"><i class="fas fa-star"></i> Promociones</span>
                </li>
            </ul>
        </div>
    </nav>
    <div class="row content-head p-r">
        <div class="col-xl-12 p-a opacy">
            <img class="h-100 w-100 img-transparent" src="{{asset('media/events/'.$event->id.'/'.$event->profile->name.'')}}">
        </div>
        <img class="col-xl-6 offset-xl-3 p-a img-event p-0" src="{{asset('media/events/'.$event->id.'/'.$event->profile->name.'')}}">
    </div>
    <div class="container mt-5">
        <div class="row">
            <div class="col-xl-10 offset-xl-1 pb-5">
                <div class="row">
                    <div class="col-xl-9 pl-0">
                        <h2 class="bold mb-3">{{$event->name}}</h2>
                        <h5><i class="fas fa-map-marked-alt"></i> {{$event->location->name}}</h5>
                        <h5 class="mb-5">
                            <i class="fas fa-calendar-alt"></i>
                            <span>
                                <b class="ml-1">Inicio:</b> {{$event->initial_date}} hrs
                                <br>
                                <b class="ml-4">Fin:</b> {{$event->final_date}} hrs
                            </span>
                        </h5>
                        <a class="text-blue pointer" id="more-info"><i class="fas fa-plus"></i> Más información del evento</a>
                    </div>
                    <div class="col-xl-3 border-left border-secondary pl-4 pr-0">
                        <p class="bold">COMPARTE ESTE EVENTO</p>
                        <a class="btn bg-blue-600 text-white rounded-circle mr-3" href=""><i class="fab fa-facebook-f"></i></a>
                        <a class="btn bg-blue-400 text-white rounded-circle mr-3" href=""><i class="fab fa-twitter"></i></a>
                        <a class="btn bg-green-400 text-white rounded-circle" href="https://api.whatsapp.com/send?text=Voy a asistir al evento {{$event->name}} https://boletos.com/{{$event->url}}" target="_blank"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row bg-white">
        <div class="container">
            <div class="row">
                <input type="hidden" id="ticket-value" value="{{$ticket}}">
                <div class="col-xl-10 offset-xl-1 pt-5 pb-4" id="div-tickets">
                    <div class="row">
                        <h2 class="bold w-100">Selecciona tus boletos</h2>
                        <h4 class="text-gray-600 w-100 mb-5">Máximo 10 boletos por orden</h4>
                    </div>
                    @foreach ($event->tickets as $t)
                        @if ($t->name == $ticket)
                        <div class="row mb-3 card-tickets p-2">  
                        @else
                        <div class="row mb-3 p-2">
                        @endif
                            <div class="col-xl-9 pl-0">
                                <h4>{{$t->name}}</h4>
                                <h5 class="text-blue">${{number_format($t->price, 2)}} MXN</h5>
                                <input class="prices" type="hidden" value="{{$t->price}}" data-name="{{$t->name}}" data-idTicket="{{$t->id}}" id="price-{{$t->id}}">
                            </div>
                            <div class="col-xl-3">
                                <div class="input-group input-group-lg pt-1 mb-2">
                                    <div class="input-group-prepend">
                                    <span class="btn bg-blue text-white bold btn-minus" data-id="{{$t->id}}">-</span>
                                    </div>
                                    <input type="text" class="form-control text-center bg-white border border-primary quantities" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-lg" value="0" disabled id="quantity-{{$t->id}}">
                                    <div class="input-group-append">
                                        <span class="btn bg-blue text-white bold btn-more" data-id="{{$t->id}}" data-limit="{{$t->max_reservation}}">+</span>
                                    </div>
                                </div>
                                <h5 class="hidden" id="text-subtotal-{{$t->id}}">TOTAL: <b id="subtotal-{{$t->id}}"></b></h5>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        <div class="dropdown-divider w-100"></div>
    </div>
    <div class="row bg-white">
        <div class="container">
            <div class="row">
                <div class="col-xl-10 offset-xl-1 pt-4 pb-4">
                    <div class="row">
                        <div class="col-xl-7 pl-0 pr-5">
                            <h4 class="w-100">Tienes <b id="quantityTickets">0</b> boletos seleccionados</h4>
                            <h4 class="bold text-blue d-i" id="total">$0.00 MXN</h4><span class="text-gray-600">&nbsp;+ CARGOS</span>
                        </div>
                        <div class="col-xl-5">
                            <span class="btn bg-orange text-white bold btn-lg pt-3 pb-3 w-100" id="btnSale">Comprar Boletos</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="dropdown-divider w-100"></div>
    </div>
    <div class="row bg-white" id="div-info">
        <div class="container">
            <div class="row">
                <div class="col-xl-10 offset-xl-1 pt-5 pb-5">
                    <div class="row">
                        <div class="col-xl-7 pl-0 pr-5 text-justify">
                            <h2 class="text-gray-dark-300 mb-3">Sobre el evento</h2>
                            <h5>{{$event->description}}</h5>
                        </div>
                        <div class="col-xl-5 text-justify">
                            <h2 class="text-gray-dark-300 mb-3">Lugar</h2>
                            <h5 class="bold mb-3">{{$event->location->name}}</h5>
                            <h5 class="mb-3">{{$event->location->address}}</h5>
                            <div class="w-100 map mb-3" id="map">

                            </div>
                            <div class="dropdown-divider w-100"></div>
                            <h2 class="text-gray-dark-300 mb-3 text-left mt-4">Contacta al Organizador</h2>
                            @if (!empty($event->email))
                                <a class="text-dark font-small"><i class="fas fa-envelope"></i> {{$event->email}}</a><br>
                            @endif
                            @if (!empty($event->phone))
                                <a class="text-dark font-small"><i class="fas fa-phone"></i> {{$event->phone}}</a><br>
                            @endif
                            @if (!empty($event->twitter))
                                <a class="text-dark font-small" href="https://twitter.com/{{$event->twitter}}" target="_blank"><i class="fab fa-twitter"></i> {{'@'.$event->twitter}}</a><br>
                            @endif
                            @if (!empty($event->facebook))
                                <a class="text-dark font-small" href="{{$event->facebook}}" target="_blank"><i class="fab fa-facebook-f"></i> Facebook</a><br>
                            @endif
                            @if (!empty($event->instagram))
                                <a class="text-dark font-small" href="{{$event->instagram}}" target="_blank"><i class="fab fa-instagram"></i> Instagram</a><br>
                            @endif
                            @if (!empty($event->website))
                                <a class="text-dark font-small" href="{{$event->website}}" target="_blank"><i class="fas fa-link"></i> {{$event->website}}</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row footer-top" style="height: 150px;">

    </div>
    <div class="row footer-bottom" style="height: 170px;">

    </div>
    <input type="hidden" id="URL" value="{{URL::asset('')}}">
    <input type="hidden" id="idEvent" value="{{$event->id}}">
    @include('modals.public.sale')
<script src="{{asset('js/jquery-3.4.1.js')}}"></script>
<script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('js/sweetalert2.js')}}"></script>
<script type="text/javascript" src="https://cdn.conekta.io/js/latest/conekta.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAOmdO7d-Hf7ZA7sVJpwICf1fWx-aQYzo4" type="text/javascript"></script>
<script src="{{asset('js/charging.js')}}"></script>
<script src="{{asset('js/public.js')}}"></script>
<script>
    initMap("<?= $event->location->latitude ?>", "<?= $event->location->longitude ?>");
</script>
</body>
</html>