Conekta.setPublicKey("key_BpoqCZd5rqzFZXrxfjjFVQQ");

$(document).ready(()=> {
    if($('#ticket-value').val() != '') {
        $('html,body').animate({
            scrollTop: $("#div-tickets").offset().top
        }, 1000);
    }
});

$("#more-info").click(function () {
    $('html,body').animate({
        scrollTop: $("#div-info").offset().top
    }, 1000);
});

var map;
var marker;
var center;
var latitud = 0, longitud = 0;
var ths=this;
var cost_type="free";

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
    console.log(ths.cost_type+" > "+$('#cost_type').val());
    if($('#cost_type').val() == 'free' || (ths.cost_type === 'free')){
        $('#optionFree').show();
        $("#payment-method option[value=free]").attr("selected",true);
        $('#payment-method').prop('disabled', 'disabled');
        
        getFormCorreo();
    }else{
        $("#payment-method").val("");
        $("#divContentPayment").html("");
        $('#payment-method').removeAttr('disabled');
        $('#optionFree').hide();

    }
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
    if ($('#model_payment').val() == 'included') {
        $('#body-comisions').html('');
        if ($('#payment-method').val() == 'card') {
            var comision = (totalTickets * (0.03)) + 2.50;
            var tbody = '<tr><td colspan="3">Comisiones</td><td class="text-right">$'+formatMoney(comision)+' MXN</td></tr>';
        } else if ($('#payment-method').val() == 'oxxo') {
            var comision = totalTickets * (0.04);
            var tbody = '<tr><td colspan="3">Comisiones</td><td class="text-right">$'+formatMoney(comision)+' MXN</td></tr>';
        }
        var totalAux = totalTickets;
        totalAux = totalAux + comision;
        $('#body-comisions').html(tbody);
        $('#total-sale').html('$'+formatMoney(totalAux)+' MXN');
    }
    $.get( 
        $('#URL').val()+"payments/paymentMethod/"+$('#payment-method').val(),
        function (data) {
            $("#divContentPayment").html(data);
            $("#confirmEmail").on('paste', function(e){
                e.preventDefault();
            });
        }
    );
});

function getFormCorreo(){
    $.get( 
        $('#URL').val()+"payments/paymentMethod/"+$('#payment-method').val(),
        function (data) {
            $("#divContentPayment").html(data);
            $("#confirmEmail").on('paste', function(e){
                e.preventDefault();
            });
        }
    );
}

$('#formSale').submit((e)=> {
    e.preventDefault();
    if ($('#email').val() == $('#confirmEmail').val()) {
        if (sales == true) {
            var turnsVerified = true;
            for (var i = 0; i < turns.length; i++) {
                for (var j = 0; j < turns[i].length; j++) {
                    if (turns[i][j] == null) {
                        turnsVerified = false;
                    }
                }
            }
            if (turnsVerified == true) {
                if($('#payment-method').val() == 'card') {
                    var $form = $('#formSale');
                    Conekta.Token.create($form, conektaSuccessReponseHandler, conektaErrorReponseHandler);
                } else if ($('#payment-method').val() == 'oxxo') {
                    Swal.fire({
                        title: "¡Atención!",
                        html: "Tu referencia de pago se enviará a:<br><b>"+$('#email').val()+"</b><br> ¿Es correcta esta información?",
                        showCancelButton: true,
                        cancelButtonColor: '#3085d6',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Aceptar',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.value) {
                            var msgAlert = 'Creando referencia de pago, no cierre ni actualice esta página. Por favor espere!!';
                            var msgSuccess = 'Su referencia se genero con éxito, en breve la recibira en su correo electrónico';
                            jsPay(msgAlert, msgSuccess);
                        }
                    });
                }else if($('#payment-method').val() == 'free'){
                    Swal.fire({
                        title: "¡Atención!",
                        html: "<span>Sus boletos seran enviados al correo: </span><br><b>"+$('#email').val()+"</b><br><span>¿Es correcta esta información?</span>",
                        showCancelButton: true,
                        cancelButtonColor: '#3085d6',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Aceptar',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.value) {
                            var msgAlert = 'Procesando pago, no cierre ni actualice esta página. Por favor espere!!';
                            var msgSuccess = '<span>Su pago se realizo con éxito </span><br><span>Recibira sus boletos por correo electrónico</span>';
                            jsPay(msgAlert, msgSuccess);
                        }
                    });
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    text: 'Debe elegir los turnos para cada día de evento',
                });
            }
        } else {
            Swal.fire({
                icon: 'error',
                text: 'Debe elegir al menos un boleto',
            });
        }
    } else {
        Swal.fire({
            icon: 'error',
            text: 'Los correos no coinciden',
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
            var msgSuccess = '<span>Su pago se realizo con éxito </span><br><span>Recibira sus boletos por correo electrónico</span>';
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
var total = 0, totalTickets = 0;
var turns = new Array();
var indicatorTurns = new Array();
function calculateTotals(indicator = null) {
    var prices = [], names = [];
    var pos = 0, quantitytTickets = 0;
    var tbody = ''; 
    total = 0;
    $('#body-comisions').html('');
    $(".quantities").each(function (e) {
        quantities[pos] = parseInt($(this).val());
        pos++;
    });
    pos = 0;
    posTurns = 0;
    $(".prices").each(function (e) {
        prices[pos] = parseInt($(this).val());
        names[pos] = $(this).attr('data-name');
        idTickets[pos] = $(this).attr('data-idTicket');
        pos++;
    });
    tbody += '<tr><td class="bold">Producto</td><td class="text-right bold">Cantidad</td><td class="text-right bold">P/U</td><td class="text-right bold">Subtotal</td></tr>';
    for (var i = 0; i < quantities.length; i++) {
        posTurns = 0;
        turns[i] = new Array();
        indicatorTurns[i] = false;
        // if($(".selectTurn"+i).val() != undefined) {
            
        // }
        $(".selectTurn"+i).each(function (e) {
            turns[i][posTurns] = $(this).val();
            indicatorTurns[i] = true;
            posTurns++;
        });
        quantitytTickets = quantitytTickets + quantities[i];
        total = total + quantities[i] * prices[i];
        if (quantities[i] > 0) {
            sales = true;
            tbody += '<tr><td>'+names[i]+'</td><td class="text-right">'+quantities[i]+'</td><td class="text-right">$'+formatMoney(prices[i])+' MXN</td><td class="text-right">$'+formatMoney(quantities[i] * prices[i])+' MXN</td></tr>';
        }
    }
    if ($('#model_payment').val() == 'included') {
        var comision = 0;
        if ($('#payment-method').val() == 'card') {
            comision = (total * (0.03)) + 2.50;
            var tbodyComisions = '<tr><td colspan="3">Comisiones</td><td class="text-right">$'+formatMoney(comision)+' MXN</td></tr>';
        } else if ($('#payment-method').val() == 'oxxo') {
            comision = total * (0.04);
            var tbodyComisions = '<tr><td colspan="3">Comisiones</td><td class="text-right">$'+formatMoney(comision)+' MXN</td></tr>';
        }
        totalTickets = total;
        total = total + comision;
        $('#body-comisions').html(tbodyComisions);
    }
    if (indicator == null) {
        $('#quantityTickets').text(quantitytTickets);
        $('#total').text('$'+formatMoney(total)+' MXN');
        if(total>0){
            ths.cost_type="paid";
        }else{
            ths.cost_type="free";
        }
        
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
            phone: $('#phone').val(),
            card: $('#card').val(),
            quantities: quantities,
            tickets: idTickets,
            payment_method: $('#payment-method').val(),
            event_id: $('#idEvent').val(),
            turns : turns,
            indicatorTurns: indicatorTurns
        },
        success: function(response) {
            if (response.status == true) {
                // $('#modalSale').modal('hide');
                jsRemoveWindowLoad();
                Swal.fire({
                    icon: 'success',
                    title: 'Correcto',
                    html: msgSuccess,
                });
                // setTimeout(function(){
                //     location.reload();
                // },3000);
            } else {
                jsRemoveWindowLoad();
                if(response.error == 'exceeded') {
                    Swal.fire({
                        icon: 'warning',
                        title: '¡Atención!',
                        html: response.msj,
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: response.error,
                    });
                }
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Lo sentimos ocurrio un error',
                html: '<span>Si el problema persiste intente con otro navegador</span><br><span>o contacte a soporte</span>',
            });
            jsRemoveWindowLoad();
        },
    });
}