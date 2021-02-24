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
        <img class="w-100" src="{{$img_event}}">
        {{-- <div class="container justify-content-center text-center"> --}}
            {{-- <h1 class="mt-5 mb-5">{{$name}}</h1> --}}
            {{-- <img class="mt-2 w-50 mb-0" src="data:image/png;base64,{{$qr}}">
            <p>Código de acceso</p> --}}
        {{-- </div> --}}
        <table style="width: 100%; background: #ddd8d6; padding: 20px;">
            <tbody>
                <tr>
                    <td>
                        <h5>Fechas del evento: <br><span style="font-weight: normal;">{{$initial_date}} a {{$final_date}}</span></h5>
                    </td>
                    <td>
                        <h5>Fecha de compra: <br><span style="font-weight: normal;">{{date('d-m-Y')}}</span></h5>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h5>Nombre del evento: <br><span style="font-weight: normal;">{{$eventName}}</span></h5>
                    </td>
                    <td>
                        <h5>Lugar de evento: <br><span style="font-weight: normal;">{{$address}}</span></h5>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h5>Tipo de boleto: <br><span style="font-weight: normal;">{{$name}}</span></h5>
                    </td>
                    <td>
                        <h5>Precio del boleto: <br><span style="font-weight: normal;">${{$price}}.00 MXN</span></h5>
                    </td>
                </tr>
                <tr>
                    
                    <td style="text-align: left;">
                        <br>
                        <img style="width: 20%;" src="data:image/png;base64,{{$qr}}">
                        {{-- <img style="width: 20%;" src="{{$qr}}"> --}}
                    </td>
                </tr>
            </tbody>
        </table>
        {{-- <div class="row" style="margin-bottom: 0px !important; padding: 0px !important;">
            <div style="width: 50%; margin-bottom: 0px;">
                <h5>Fechas del evento: <br><span style="font-weight: normal;">{{$initial_date}} a {{$final_date}}</span></h5>
            </div>
            <div style="width: 50%; margin-left:50%; margin-bottom: 0px;">
                <h5>Fecha de compra: <br><span style="font-weight: normal;">{{date('d-m-Y')}}</span></h5>
            </div>
            <div style="width: 50%;">
                <h5>Tipo de boleto: <br><span style="font-weight: normal;">{{$name}}</span></h5>
            </div>
            <div style="width: 50%; margin-left: 50%;">
                <h5>Lugar de evento: <br><span style="font-weight: normal;">{{$address}}</span></h5>
            </div>
        </div>
        <div class="row" style="margin-top: 0px !important; padding: 0px !important;">
            
        </div> --}}
        
        
        
        
            {{-- <div class="col-xl-6 text-right justify-content-right">
                <h5>Fecha de compra: <br><span style="font-weight: normal;">{{date('d/m/Y')}}</span></h5>
            </div> --}}
        </div>
    </body>
</html>