<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Tickets</title>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    </head>
    <body>
        <img class="w-100 mb-5" src="{{$img_event}}">
        <div class="container justify-content-center text-center">
            <h1 class="mt-5 mb-5">{{$name}}</h1>
            <img class="mt-2 w-50 mb-0" src="data:image/png;base64,{{$qr}}">
            <p>Código de acceso</p>
        </div>
    </body>
</html>