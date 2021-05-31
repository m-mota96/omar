<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ticketland</title>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{asset('fontawesome5.12.1/css/all.css')}}">
    <link href="{{ asset('css/index.css') }}" rel="stylesheet">
    <link rel="shortcut icon" href="{{asset('media/general/ticketland.png')}}" type="image/x-icon">
</head>
<body>
    <div class="container text-center">
        <input type="hidden" id="URL" value="{{URL::asset('')}}">
        <div class="col-8 col-xl-2 offset-2 offset-xl-4 input-group mb-3 mt-5 justify-content-center">
            <div class="input-group-prepend">
                <span class="btn btn-primary" onclick="calculate('minus')"><i class="fas fa-minus"></i></span>
            </div>
            <input type="text" class="form-control text-center" id="quantity" value="1" disabled>
            <div class="input-group-append">
                <span class="btn btn-primary" onclick="calculate('more')"><i class="fas fa-plus"></i></span>
            </div>
        </div>
        <div class="col-8 col-xl-2 offset-2 offset-xl-4 mb-3 mt-2 justify-content-center">
            <button class="btn btn-warning mt-3" id="btnDiscount">Descontar</button>
        </div>
    </div>
    <script src="{{URL::asset('js/jquery-3.4.1.js')}}"></script>
    <script src="{{URL::asset('js/bootstrap.js')}}"></script>
    <script src="{{URL::asset('js/discount.js')}}"></script>
</body>
</html>
