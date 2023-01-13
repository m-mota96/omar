<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Accesos</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css" integrity="sha384-zCbKRCUGaJDkqS1kPbPd7TveP5iyJE0EjAuZQTgFLD2ylzuqKfdKlfG/eSrtxUkn" crossorigin="anonymous">
</head>
<body>
    <div class="container pt-5">
        {{-- <div class="row">
            <div class="col-xl-12"> --}}
                @if ($type == 'tickets')
                    <h3>Agradecenemos su compra, recuerde no compartir sus boletos.</h3>
                    @foreach ($access as $key => $a)
                        <h5 class="mt-5">
                            Tipo de boleto: <b>{{$a->ticket->name}}</b>
                            <br>Nombre: <b>{{$a->name}} </b>
                            <br>Para descargar este boleto haga <a href="{{asset('media/pdf/events/'.$a->payment->event_id.'/'.$a->folio.'.pdf')}}">click aquí</a>
                        </h5>
                        {{-- <iframe src="{{asset('media/pdf/events/'.$a->payment->event_id.'/'.$a->folio.'.pdf')}}" type="application/pdf" width="100%" style="min-height: 50vh;"></iframe> --}}
                        {{-- <embed src="{{asset('media/pdf/events/'.$a->payment->event_id.'/'.$a->folio.'.pdf')}}" type="application/pdf" width="100%" style="min-height: 60vh;"> --}}
                    @endforeach
                @else
                    <h3>Le recordamos que tiene 2 días para hacer su pago, de lo contrario esta referencia quedará dada de baja.</h3>
                    {{-- <iframe src="{{asset('media/pdf/events/'.$a->payment->event_id.'/'.$a->folio.'.pdf')}}" type="application/pdf" width="100%" height="800vh"></iframe> --}}
                    <h5 class="mt-5">Haga <a href="{{asset('media/pdf/events/'.$payment->event_id.'/reference'.$payment->id.'.pdf')}}">click aquí</a> para descargar su referencia de pago.</h5>
                @endif
            {{-- </div>
        </div> --}}
    </div>
</body>
</html>