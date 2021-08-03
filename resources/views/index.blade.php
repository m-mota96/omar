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
        <input type="hidden" id="URL" value="{{URL::asset('')}}">
        <div class="col-12 col-xl-6 text-left content-topbar">
            <h5 class="text-white"><a class="text-white mr-3" href="{{URL::asset('')}}">Ticketland</a> <input class="form-control w-50" type="text" placeholder="Busque por nombre del evento"></h5>
        </div>
        <div class="col-xl-6 text-right content-topbar">
            <h5 class="text-white"><a class="text-white mr-3" href="{{route('login')}}">Inicia sesión</a> <a class="text-white" href="{{route('register')}}">Registrarse</a></h5>
        </div>
    </div>
    <div class="row">
            <div id="carouselExampleCaptions" class="carousel slide w-100" data-ride="carousel">
            <div class="carousel-inner">
                @foreach ($slider as $key => $s)
                    <div class="carousel-item @if($key==0) active @endif">
                        <img src="{{asset('media/sliderIndex/'.$s->image)}}" class="d-block w-100" alt="{{$s->title}}">
                        <div class="carousel-caption d-none d-md-block">
                            <h5>First slide label</h5>
                            <p>Some representative placeholder content for the first slide.</p>
                        </div>
                    </div>
                @endforeach
            </div>
            <ol class="carousel-indicators justify-content-start">
                @foreach ($slider as $key => $s)
                    <div class="@if(sizeof($slider) < 5) col-xl-3 @else col-xl-2 @endif bg-gray-dark-700 @if($key==0) active @endif pointer items" data-slide-to="{{$key}}" data-target="#carouselExampleCaptions">
                        <p>{{$s->title}}</p><span>{{$s->date}}</span>
                    </div>
                @endforeach
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
    <div class="container mt-5">
        <div class="row">
            <div class="col-xl-4 mb-2">
                <img class="w-100" src="{{asset('../media/index/img1.png')}}">
            </div>
            <div class="col-xl-4 mb-2">
                <img class="w-100" src="{{asset('../media/index/img2.png')}}">
            </div>
            <div class="col-xl-4 mb-2">
                <img class="w-100" src="{{asset('../media/index/img3.png')}}">
            </div>
        </div>
    </div>
    <div class="row mt-5 mb-5">
        <div class="card col-xl-10 offset-xl-1 p-4">
            <div class="row w-100">
                <div class="col-xl-12 text-center mb-4">
                    <h1>¿Quienes somos?</h1>
                </div>
                <div class="col-xl-3 offset-xl-2 mb-2 text-center">
                    <img class="w-75" src="{{asset('../media/index/img4.png')}}">
                </div>
                <div class="col-xl-5 text-justify vertical-align-center">
                    <p>
                        It is a long established fact that a reader will be distracted 
                        by the readable content of a page when looking at its layout. 
                        The point of using Lorem Ipsum is that it has a more-or-less 
                        normal distribution of letters, as opposed to using 'Content here, 
                        content here', making it look like readable English. 
                        Many desktop publishing packages and web page editors now use Lorem 
                        Ipsum as their default model text, and a search for 'lorem ipsum' 
                        will uncover many web sites still in their infancy.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="container mt-5">
        <div class="row">
            <div class="col-xl-8 mb-2">
                <img class="w-100" src="{{asset('../media/index/img5.png')}}">
            </div>
            <div class="col-xl-4 mb-2">
                <img class="w-100" src="{{asset('../media/index/img6.png')}}">
            </div>
        </div>
    </div>
    <div class="container mt-5 mb-5">
        <div class="card bg-gray-100 p-4">
            <form class="row w-100" id="form-contact">
                {{ csrf_field() }}
                <div class="col-xl-12 text-center">
                    <h1>Contacto</h1>
                </div>
                <div class="col-xl-1 offset-xl-2">
                    <span>Nombre: </span>
                </div>
                <div class="col-xl-7 mb-3">
                    <input class="form-control" type="text" name="name" required>
                </div>
                <div class="col-xl-1 offset-xl-2">
                    <span>E-mail: </span>
                </div>
                <div class="col-xl-7 mb-3">
                    <input class="form-control" type="email" name="email" required>
                </div>
                <div class="col-xl-1 offset-xl-2">
                    <span>Asunto: </span>
                </div>
                <div class="col-xl-7 mb-3">
                    <textarea class="form-control" rows="5" name="message" required></textarea>
                </div>
                <div class="col-xl-1 offset-xl-2">
                    
                </div>
                <div class="col-xl-7 mb-3">
                    <div class="g-recaptcha" data-sitekey="6Lfb88sbAAAAAFkKgK4fONJM0dpLmguNIhbXdoOI"></div>
                </div>
                <div class="col-xl-8 offset-xl-2 mt-4 text-center">
                    <button class="btn btn-primary" type="submit">Enviar</button>
                </div>
            </form>
        </div>
    </div>
    <div class="row bg-footer p-4">
        <div class="col-xl-4 text-center vertical-align-end">
            <span class="text-white">Ticketland {{date('Y')}}</span>
        </div>
        <div class="col-xl-4 text-center">
            <img src="{{asset('../media/general/logo-footer-index.png')}}">
        </div>
        <div class="col-xl-4 text-center">
            
        </div>
    </div>
    <script src="{{URL::asset('js/jquery-3.4.1.js')}}"></script>
    <script src="{{URL::asset('js/bootstrap.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <script src="{{URL::asset('js/index.js')}}"></script>
</body>
</html>
