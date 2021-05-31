<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Ticketland</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/index.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{asset('media/general/ticketland.png')}}" type="image/x-icon">
</head>
<body>
    <div class="row topbar pt-3 pb-2 pl-5 pr-5 form-inline">
        <div class="col-xl-6 text-left content-topbar">
            <h5 class="text-white"><a class="text-white mr-3" href="{{URL::asset('')}}">Ticketland</a> <input class="form-control w-50" type="text" placeholder="Busque por nombre del evento"></h5>
        </div>
        <div class="col-xl-6 text-right content-topbar">
            <h5 class="text-white"><a class="text-white mr-3" href="{{route('login')}}">Inicia sesión</a> <a class="text-white" href="{{route('register')}}">Registrarse</a></h5>
        </div>
    </div>
    {{-- <nav class="navbar navbar-expand navbar-light topbar static-top shadow pt-3 pb-2 pl-5 pr-5">
            <ul class="navbar-nav pt-1 pb-1">
                <li class="mr-4">
                    <h5><a class="text-white pointer" href="">Ticketland</a></h5>
                </li>
                <li class="mr-4">
                    <h5><input class="form-control" type="text" placeholder="Busca por nombre del evento"></h5>
                </li>
            </ul>
    </nav> --}}
    <div class="row">
            <div id="carouselExampleCaptions" class="carousel slide w-100" data-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="{{asset('media/sliderIndex/prueba1.jpg')}}" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>First slide label</h5>
                        <p>Some representative placeholder content for the first slide.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{asset('media/sliderIndex/prueba2.jpg')}}" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Second slide label</h5>
                        <p>Some representative placeholder content for the second slide.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{asset('media/sliderIndex/prueba3.jpg')}}" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Third slide label</h5>
                        <p>Some representative placeholder content for the third slide.</p>
                    </div>
                </div>
                <div class="carousel-item">
                    <img src="{{asset('media/sliderIndex/prueba4.jpg')}}" class="d-block w-100" alt="...">
                    <div class="carousel-caption d-none d-md-block">
                        <h5>Third slide label</h5>
                        <p>Some representative placeholder content for the third slide.</p>
                    </div>
                </div>
            </div>
            <ol class="carousel-indicators">
                <div class="col-xl-3 bg-gray-dark-700 active pointer items" data-slide-to="0" data-target="#carouselExampleCaptions">
                    <p>PRUEBA</p><span>Fechas del evento</span>
                </div>
                <div class="col-xl-3 bg-gray-dark-700 pointer items" data-slide-to="1" data-target="#carouselExampleCaptions">
                    <p>PRUEBA</p><span>Fechas del evento</span>
                </div>
                <div class="col-xl-3 bg-gray-dark-700 pointer items" data-slide-to="2" data-target="#carouselExampleCaptions">
                    <p>PRUEBA</p><span>Fechas del evento</span>
                </div>
                <div class="col-xl-3 bg-gray-dark-700 pointer items" data-slide-to="3" data-target="#carouselExampleCaptions">
                    <p>PRUEBA</p><span>Fechas del evento</span>
                </div>
            </ol>
            <a class="carousel-control-prev" href="#carouselExampleCaptions" role="button" data-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="sr-only">Previous</span>
            </a>
            <a class="carousel-control-next" href="#carouselExampleCaptions" role="button" data-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="sr-only">Next</span>
            </a>
        </div>
    </div>
    <script src="{{URL::asset('js/jquery-3.4.1.js')}}"></script>
    <script src="{{URL::asset('js/bootstrap.js')}}"></script>
    <script src="{{URL::asset('js/index.js')}}"></script>
</body>
</html>
