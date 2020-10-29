Conekta.setPublicKey("key_BpoqCZd5rqzFZXrxfjjFVQQ");
$("#more-info").click(function () {
    $('html,body').animate({
        scrollTop: $("#div-info").offset().top
    }, 1000);
});

var map;
var marker;
var center;
var latitud = 0, longitud = 0;

function initMap(lat = null, long = null) {
    var elem = document.getElementById("map");
    if (lat == null || long == null) {
        center = {lat: 20.676580, lng: -103.34785};
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (pos) {
                center = {lat: pos.coords.latitude, lng: pos.coords.longitude};
                drawMap(elem, center);
                setPosicion(center);
            }, function () {
                drawMap(elem, center);
            });
        } else {
            drawMap(elem, center);
        }
    } else {
        center = getPosicion(lat, long);
        drawMap(elem, center);
    }
}

function drawMap(elem, center) {
    map = new google.maps.Map(elem, {
        center: center,
        zoom: 14,
    });
    marker = new google.maps.Marker({
        position: center,
        map: map,
        animation: google.maps.Animation.DROP,
        title: 'Mueve el mapa',
    });
    map.addListener('center_changed', function () {
        var p = map.getCenter();
        marker.setPosition({lat: p.lat(), lng: p.lng()});
        setPosicion({lat: p.lat(), lng: p.lng()});
    });
}

function setPosicion(center) {
    $("#latitude").val(center.lat);
    $("#longitude").val(center.lng);
    latitud = center.lat;
    longitud = center.lng;
}

function getPosicion(lat, long) {
    return {lat: parseFloat(lat), lng: parseFloat(long)};
}

$('.btn-more').click(function(e) {
    var id = $(this).attr('data-id');
    if (parseInt($('#quantity-'+id).val()) < parseInt($(this).attr('data-limit'))) {
        var quantity = parseInt($('#quantity-'+id).val()) + 1;
        $('#quantity-'+id).val(quantity);
        $('#subtotal-'+id).text('$'+formatMoney(quantity*$('#price-'+id).val())+' MXN');
        $('#text-subtotal-'+id).removeClass('hidden');
        calculateTotals();
    }
});

$('#btnSale').click(function() {
    calculateTotals('sale');
    $('#modalSale').modal('show');
});

$('.btn-minus').click(function() {
    var id = $(this).attr('data-id');
    if (parseInt($('#quantity-'+id).val()) > 0) {
        $('#quantity-'+id).val(parseInt($('#quantity-'+id).val())-1);
        if ($('#quantity-'+id).val() == 0) {
            $('#subtotal-'+id).text('');
            $('#text-subtotal-'+id).addClass('hidden');
        }
        calculateTotals();
    }
});

$('#payment-method').change(()=> {
    $.get(
        $('#URL').val()+"paymentMethod/"+$('#payment-method').val(),
        function (data) {
            $("#divContentPayment").html(data);
        }
    );
});

$('#formSale').submit((e)=> {
    e.preventDefault();
    if (sales == true) {
        if($('#payment-method').val()=='card') {
            var $form = $('#formSale');
            Conekta.Token.create($form, conektaSuccessReponseHandler, conektaErrorReponseHandler);
        }
    } else {
        Swal.fire({
            icon: 'error',
            text: 'Debe elegir al menos un boleto',
        });
    }
});

var conektaSuccessReponseHandler = function(token) {
    $('#conektaTokenId').val(token.id);
    Swal.fire({
        title: 'Atención',
        html: "<span>Sus boletos seran enviados al correo: </span><br><b>"+$('#email').val()+"</b><br><span>¿Es correcta esta información?</span>",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#3085d6',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33',
        confirmButtonText: 'Aceptar',
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            var msgAlert = 'Procesando pago, no cierre ni actualice esta página. Por favor espere!!';
            var msgSuccess = 'Su pago se realizó con éxito, recibirá un correo electrónico con sus boletos';
            jsPay(msgAlert, msgSuccess);
        }
    });
};

var conektaErrorReponseHandler = function(response) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: response.message_to_purchaser
    });
};

var sales = false;
var idTickets = [], quantities = [];
function calculateTotals(indicator = null) {
    var prices = [], names = [];
    var pos = 0,  total = 0, quantitytTickets = 0;
    var tbody = '';
    $(".quantities").each(function (e) {
        quantities[pos] = parseInt($(this).val());
        pos++;
    });
    pos = 0;
    $(".prices").each(function (e) {
        prices[pos] = parseInt($(this).val());
        names[pos] = $(this).attr('data-name');
        idTickets[pos] = $(this).attr('data-idTicket');
        pos++;
    });
    tbody += '<tr><td class="bold">Producto</td><td class="text-right bold">Cantidad</td><td class="text-right bold">P/U</td><td class="text-right bold">Subtotal</td></tr>';
    for (var i = 0; i < quantities.length; i++) {
        quantitytTickets = quantitytTickets + quantities[i];
        total = total + quantities[i] * prices[i];
        if (quantities[i] > 0) {
            sales = true;
            tbody += '<tr><td>'+names[i]+'</td><td class="text-right">'+quantities[i]+'</td><td class="text-right">$'+formatMoney(prices[i])+' MXN</td><td class="text-right">$'+formatMoney(quantities[i] * prices[i])+' MXN</td></tr>';
        }
    }
    if (indicator == null) {
        $('#quantityTickets').text(quantitytTickets);
        $('#total').text('$'+formatMoney(total)+' MXN'); 
    } else {
        $('#body-sale').html(tbody);
        $('#total-sale').html('$'+formatMoney(total)+' MXN');
    }
}

function formatMoney(number, decPlaces, decSep, thouSep) {
    decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
    decSep = typeof decSep === "undefined" ? "." : decSep;
    thouSep = typeof thouSep === "undefined" ? "," : thouSep;
    var sign = number < 0 ? "-" : "";
    var i = String(parseInt(number = Math.abs(Number(number) || 0).toFixed(decPlaces)));
    var j = (j = i.length) > 3 ? j % 3 : 0;
    
    return sign +
        (j ? i.substr(0, j) + thouSep : "") +
        i.substr(j).replace(/(\decSep{3})(?=\decSep)/g, "$1" + thouSep) +
        (decPlaces ? decSep + Math.abs(number - i).toFixed(decPlaces).slice(2) : "");
}

function jsPay(msgAlert, msgSuccess) {
    jsShowWindowLoad(msgAlert);
    $.ajax({
        dataType: 'json',
        url: $('#URL').val()+'makePayment',
        method: 'post',
        data: {
            "_token": $("meta[name='csrf-token']").attr("content"),
            conektaTokenId: $('#conektaTokenId').val(),
            name: $('#name').val(),
            email: $('#email').val(),
            card: $('#card').val(),
            quantities: quantities,
            tickets: idTickets,
            payment_method: $('#payment-method').val(),
            event_id: $('#idEvent').val()
        },
        success: function(response) {
            if(response.status=='success') {
                jsRemoveWindowLoad();
                // swal('¡Correcto!', msgSuccess, 'success');
                // setTimeout(function(){
                //     location.reload();
                // },2000);
            } else {
                jsRemoveWindowLoad();
                // swal('¡Error!', response.error, 'error');
            }
        },
        error: function() {
            // swal('Error!', 'Lo sentimos ocurrio un error. Si el problema persiste intente con otro navegador', 'error');
            jsRemoveWindowLoad();
        },
    });
}