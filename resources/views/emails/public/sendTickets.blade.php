<!DOCTYPE html>
<!-- saved from url=(0050)http://bigelephant.mx/_sitios/japy/bienvenido.html -->
<html lang="en" dir="ltr">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <title>.:: Bienvenido ::.</title>
    </head>
    <body style=" margin: 0; padding: 0;">
        <table style="height: 100vh;" align="center" border="0" cellpadding="0" cellspacing="0" width="100%" height="100%">
            <tbody>
            <tr>
                <!-- MARCO -->
                <td align="center" style="background-color: white;">
                    <table border="0" cellpadding="0" cellspacing="0" width="600" style="width:600px;max-width:600px">
                        <!-- ENCABEZADO -->
                        <tbody>
                        <tr>
                            <td>
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tbody>
                                    <tr>
                                        <td bgcolor="#fff" style="padding:0px 0px 0px 0px;">
                                            <img src="{{URL::asset('media/events/'.$event->id.'/'.$event->profile->name.'')}}" width="100%"  style="display: block;">
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <!-- FIN ENCABEZADO -->
                        <!-- CUERPO CONTENIDO -->
                        <tr>
                            <td bgcolor="#f9f9f9" height="20px" width="40">
                            </td>
                        </tr>
                        <tr bgcolor="#f9f9f9" valing="center" style="font-family: &#39;Raleway&#39;, sans-serif; font-weight: 300;text-align: center; font-size: 14px; color:#fff; margin-top:50;">
                            <td>
                                <div style="text-align: center;">
                                    <p style="color:#013f68; font-size:20px; font-weight:bold; text-align:center;">RESUMEN DE TU COMPRA</p>
                                    <p style="color:grey; font-size:25px; font-weight:bold; text-align:center;">{{$buyer}}</p>
                                </div>
                            </td>
                        </tr>
                        <tr bgcolor="#f9f9f9" valing="center" style="font-family: &#39;Raleway&#39;, sans-serif; font-weight: 300;text-align: center; font-size: 14px; color:#fff; margin-top:50;">
                            <td>
                                <div style="width: 100%; padding-left: 5vh; padding-right: 5vh;">
                                    <table style="width: 100%;">
                                        <thead>
                                            <tr style="padding-top: 5px; padding-bottom: 5px;">
                                                <td style="color: black; font-weight: bold; text-align: left;">Producto</td>
                                                <td style="color: black; font-weight: bold; text-align: right;">Cantidad</td>
                                                <td style="color: black; font-weight: bold; text-align: right;">P/U</td>
                                                <td style="color: black; font-weight: bold; text-align: right;">Subtotal</td>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @for($i = 0; $i < sizeof($quantities); $i++)
                                                @if ($quantities[$i] > 0)
                                                    <tr style="padding-top: 5px; padding-bottom: 5px;">
                                                        <td style="color: black; text-align: left;">{{$tickets[$i]['name']}}</td>
                                                        <td style="color: black; text-align: right;">{{$quantities[$i]}}</td>
                                                        <td style="color: black; text-align: right;">${{number_format($tickets[$i]['price'], 2)}} MXN</td>
                                                        <td style="color: black; text-align: right;">${{number_format(($tickets[$i]['price'] * $quantities[$i]), 2)}} MXN</td>
                                                    </tr>
                                                @endif
                                            @endfor
                                            <tr style="padding-top: 5px; padding-bottom: 5px;">
                                                <td colspan="3" style="color: black; text-align: right; font-weight: bold;">TOTAL</td>
                                                <td style="color: black; text-align: right; font-weight: bold;">${{number_format($total, 2)}} MXN</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </td>
                        </tr>
                        {{-- <tr bgcolor="#f9f9f9" valing="center" style="font-family: &#39;Raleway&#39;, sans-serif; font-weight: 300;text-align: center; font-size: 14px; color:#fff; margin-top:50;">
                            <td>
                                <div style="width: 190px; padding-left: 32vh; text-align: center;">
                                    <p style="color:grey; font-size:30px; font-weight:bold; margin 0;">{{ $nombre }}</p>
                                </div>
                            </td>
                        </tr>
                        <tr bgcolor="#ffffff" valing="center" style="font-family: &#39;Raleway&#39;, sans-serif; font-weight: 300;text-align: center; font-size: 14px; color:#fff; margin-top:50;">
                            <td>
                                <div style="width: 230px; padding-left: 32vh; text-align: right;">
                                    <p style="color:grey; font-size:20px; margin 0;">{{ $cantidad }} BOLETOS GENERAL</p>
                                </div>
                            </td>
                        </tr>
                        <tr bgcolor="#ffffff" valing="center" style="font-family: &#39;Raleway&#39;, sans-serif; font-weight: 300;text-align: center; font-size: 14px; color:#fff; margin-top:50;">
                            <td>
                                <div style="width: 230px; padding-left: 32vh; text-align: right;">
                                    <p style="color:grey; font-size:20px; margin 0;">SUB-TOTAL $ {{ $total }}</p>
                                </div>
                            </td>
                        </tr>
                        <tr bgcolor="#ffffff" valing="center" style="font-family: &#39;Raleway&#39;, sans-serif; font-weight: 300;text-align: center; font-size: 14px; color:#fff; margin-top:50;">
                            <td>
                                <div style="width: 230px; padding-left: 32vh; text-align: right;">
                                    <p style="color:grey; font-size:20px; margin 0;">TOTAL $ {{ $total }}</p>
                                </div>
                            </td>
                        </tr> --}}
                        <tr bgcolor="#f9f9f9">
                            <td>
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tbody>
                                    <tr>
                                        <td width="290">
                                        </td>
                                        <td>
                                            
                                        </td>
                                        <td width="290">
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td bgcolor="#f9f9f9" height="10px" width="40">
                            </td>
                        </tr>
                        <tr bgcolor="#f9f9f9" style="font-family: &#39;Raleway&#39;, sans-serif; text-align: center; font-size: 12px; color:#444242; margin-top:50;">
                            <!-- <td>
                                Continua con tu planificación de ensueño!
                            </td> -->
                        </tr>
                        <!-- FIN CUERPO CONTENIDO -->
                        <!-- FOOTER-->
                        <tr>
                            <td bgcolor="#f9f9f9" height="20px" width="40">
                            </td>
                        </tr>
                        <tr bgcolor="#f9f9f9" style=" font-family: &#39;Raleway&#39;, sans-serif; text-align: center; font-size: 12px; color:#f9f9f9; margin-top:50;">
                            <!-- <td>
                                <a href="https://www.facebook.com/Japy-177297236307569/" style="color:#cf1e66;">Facebook</a> | <a href="https://twitter.com/japymx/" style="color:#cf1e66;">Twitter</a> |
                                <a href="https://www.instagram.com/japymx/" style="color:#cf1e66;">Instagram</a> | <a href="https://co.pinterest.com/japymx/" style="color:#cf1e66;">Pinterest</a>
                            </td> -->
                        </tr>
                        <tr>
                            <td bgcolor="#f9f9f9" height="10px" width="40">
                            </td>
                        </tr>
                        <tr bgcolor="#f9f9f9" style=" font-family: &#39;Raleway&#39;, sans-serif; text-align: center; font-size: 12px; color:#f9f9f9; margin-top:50;">
                            <td>
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tbody>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <!-- FIN FOOTER-->
                        <tr>
                            <td>
                                <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                    <tbody>
                                    <tr>
                                        <td bgcolor="#f9f9f9" style="padding:0px 0px 0px 0px;">
                                            
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </td>

                        </tr>
                        <!-- <tr>
                            <td bgcolor="#ffffff" height="20px">
                            </td>
                        </tr> -->
                        </tbody>
                    </table>
                </td>
            </tr>
            </tbody>
        </table>
    </body>
</html>