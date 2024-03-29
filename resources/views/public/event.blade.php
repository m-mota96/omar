<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="og:title" content="{{$event->name}}"/>
    <meta property="og:type" content="article"/>
    <meta property="og:url" content="https://ticketland.mx/{{$event->url}}"/>
    @if (isset($event->profile->name))
        <meta property="og:image" content="{{asset('media/events/'.$event->id.'/'.$event->profile->name.'')}}"/>
    @else
        <meta property="og:image" content="{{asset('media/general/not_image.png')}}"/>
    @endif
    <meta property="og:description" content="{{$event->description}}"/>
    <title>Compra boletos para {{$event->name}} - Ticketland</title>
    <link rel="stylesheet" href="{{asset('fontawesome5.12.1/css/all.css')}}">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/public.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{asset('media/general/ticketland.png')}}" type="image/x-icon">
</head>
<body>
    <div class="row content-head p-r">
        <input type="hidden" id="model_payment" value="{{$event->model_payment}}">
        <input type="hidden" id="cost_type" value="{{$event->cost_type}}">
        <div class="col-xl-12 p-a opacy">
            @if (isset($event->profile->name))
                <img class="h-100 w-100 img-transparent" src="{{asset('media/events/'.$event->id.'/'.$event->profile->name.'')}}" alt="{{$event->name}}">
            @else
                <img class="h-100 w-100 img-transparent" src="{{asset('media/general/not_image.png')}}">
            @endif
        </div>
        @if (isset($event->profile->name))
            <img class="col-xl-6 offset-xl-3 p-a img-event p-0" src="{{asset('media/events/'.$event->id.'/'.$event->profile->name.'')}}" alt="{{$event->name}}">
        @else
            <img class="col-xl-6 offset-xl-3 p-a img-event p-0 cover" src="{{asset('media/general/not_image.png')}}">
        @endif
    </div>
    <div class="container mt-5">
        <div class="row">
            <div class="col-xl-10 offset-xl-1 pb-5">
                <div class="row">
                    <div class="col-xl-9 pl-0">
                        <h2 class="bold mb-3">{{$event->name}}</h2>
                        @if (isset($event->location->name)) <h5><i class="fas fa-map-marked-alt"></i> {{$event->location->name}}</h5> @endif
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
                        <a class="btn bg-green-400 text-white rounded-circle" href="https://api.whatsapp.com/send?text=Voy a asistir al evento {{$event->name.':'}} https://ticketland.mx/{{$event->url}}" target="_blank"><i class="fab fa-whatsapp"></i></a>
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
                    <input type="hidden" id="ticketsComplete" value="{{$event->tickets}}">
                    @foreach ($event->tickets as $key => $t)
                        @if ($t->name == $ticket)
                        <div class="row mb-3 card-tickets p-2">  
                        @else
                        <div class="row mb-3 p-2">
                        @endif
                            <div class="col-xl-4 pl-0">
                                <h4>{{$t->name}} @if(!empty($t->promotion) && date('Y-m-d') <= $t->date_promotion) <sup class="badge badge-danger"> -{{$t->promotion}}% Descuento</sup> @endif</h4>
                                @if (!empty($t->promotion) && date('Y-m-d') <= $t->date_promotion)
                                    <h6 class="text-gray line">${{number_format($t->price, 2)}} MXN</h6>
                                    <h5 class="text-blue">${{number_format($t->price - ($t->price*($t->promotion/100)), 2)}} MXN</h5>
                                    <input class="prices" type="hidden" value="{{$t->price - ($t->price*($t->promotion/100))}}" data-name="{{$t->name}}" data-idTicket="{{$t->id}}" id="price-{{$t->id}}">
                                @else
                                    <h5 class="text-blue">${{number_format($t->price, 2)}} MXN</h5>
                                    <input class="prices" type="hidden" value="{{$t->price}}" data-name="{{$t->name}}" data-idTicket="{{$t->id}}" id="price-{{$t->id}}">
                                @endif
                            </div>
                            <div class="col-xl-5 pl-0">
                                <input type="hidden" id="turns" value="{{$t->use_turns}}">
                                @if ($t->use_turns == 1)
                                    <h6><b>Seleccione un turno para cada día del evento</b></h6>
                                    @for ($i = 0; $i < sizeof($event->eventDates); $i++)
                                        <h6>
                                            <b>Día {{$i+1}}: </b>
                                            <span class="text-primary bold">{{date_format(new DateTime($event->eventDates[$i]->date), "d-M-Y")}}</span>
                                            <select class="custom-select custom-select-sm w-75 selectTurn{{$key}}" id="turno{{$key.'-'.$i}}">
                                                <option value="" selected disabled>Seleccione un turno</option>
                                                @foreach ($event->eventDates[$i]->turns as $turn)
                                                    @if ($turn->used < $turn->quantity)
                                                        <option value="{{$turn->id}}">{{$turn->name.": (".substr($turn->initial_hour, 0, 5)."hrs - ".substr($turn->final_hour, 0, 5)."hrs.)"}}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        </h6>
                                    @endfor
                                @endif
                            </div>
                            <div class="col-10 col-xl-3 pl-0">
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
                        <div class="col-xl-7 pl-0 pr-5 mb-4">
                            <h4 class="w-100">Tienes <b id="quantityTickets">0</b> boletos seleccionados</h4>
                            <h4 class="bold text-blue d-i" id="total">$0.00 MXN</h4><span class="text-gray-600">&nbsp;+ CARGOS</span>
                        </div>
                        <div class="col-xl-5">
                            @if($event->cost_type == 'free')
                                <span class="btn bg-orange text-white bold btn-lg pt-3 pb-3 w-100" id="btnSale">Registrar Boletos</span>
                            @else
                                <span class="btn bg-orange text-white bold btn-lg pt-3 pb-3 w-100" id="btnSale">Comprar Boletos</span>
                            @endif
                            
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
                        <div class="col-xl-7 pl-0 pr-5 text-justify mb-4">
                            <h2 class="text-gray-dark-300 mb-3">Sobre el evento</h2>
                            <h5>{{$event->description}}</h5>
                        </div>
                        <div class="col-xl-5 text-justify location">
                            @if (isset($event->location->name) && isset($event->location->address))
                                <h2 class="text-gray-dark-300 mb-3">Lugar</h2>
                                <h5 class="bold mb-3">{{$event->location->name}}</h5>
                                <h5 class="mb-3">{{$event->location->address}}</h5>
                                <div class="w-100 map mb-3" id="map">

                                </div>
                            @endif
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
    {{-- <div class="row footer-bottom" style="height: 170px;">

    </div> --}}
    <input type="hidden" id="URL" value="{{URL::asset('')}}">
    <input type="hidden" id="idEvent" value="{{$event->id}}">
    
    @include('modals.public.sale')
<script src="{{asset('js/jquery-3.4.1.js')}}"></script>
<script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('js/sweetalert2.js')}}"></script>
<script type="text/javascript" src="https://cdn.conekta.io/js/latest/conekta.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDmxWjrQMl9hyuxzaoCm0_Ma03a92eu2b4" type="text/javascript"></script>
<script src="{{asset('js/charging.js')}}"></script>
<script src="{{asset('js/public.js')}}"></script>
<script>
    var lat = @json(isset($event->location->latitude) ? $event->location->latitude : null);
    var lon = @json(isset($event->location->longitude) ? $event->location->longitude : null);
    if (lat != null && lon != null) {
        initMap(lat, lon);
    }
</script>
</body>
</html>