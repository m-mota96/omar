$(document).ready(()=> {
    $('[data-toggle="tooltip"]').tooltip();
    if ($('#model_payment').val() != 'separated') {
        typeModel = 1;
        $('#separated').removeClass('btn-primary');
        $('#separated').addClass('btn-outline-primary');
        $('#included').addClass('btn-primary');
        $('#included').removeClass('btn-outline-primary');
    } else {
        typeModel = 0;
        $('#separated').addClass('btn-primary');
        $('#separated').removeClass('btn-outline-primary');
        $('#included').removeClass('btn-primary');
        $('#included').addClass('btn-outline-primary');
    }
});

$('input:radio[name=price]').click(()=> {	 
    if ($('input:radio[name=price]:checked').val() == 1) {
        $('#priceTicket').css('display', 'block');
        $('#priceTicket').prop("required", true);
        $('#priceTicket').val('');
    } else {
        $('#priceTicket').val('50');
        $('#priceTicket').css('display', 'none');
        $('#priceTicket').removeAttr("required");
    }
});

$("#modalTickets").on("hidden.bs.modal", function () {
    $('#modalTickets .form-control').val('');
    $('#priceTicket').css('display', 'block');
    $('#slider-step').remove();
    $('#divPromotions').addClass('hidden');
    $('#promotion').prop('checked', false);
});

$('#promotion').click(()=> {
    if ($('#promotion').is(':checked') == true) {
        $('#discount').attr('required', true);
        $('#date_promotion').attr('required', true);
        $('#divPromotions').slideDown(900);
    } else {
        $('#discount').val('');
        $('#date_promotion').val('');
        $('#discount').attr('required', false);
        $('#date_promotion').attr('required', false);
        $('#divPromotions').slideUp(900);
    }
});

function saveTicket(id = null, name = null, description = '', min_reservation = null, max_reservation = null, quantity = null, start_sale = null, stop_sale = null, price = null, status = null, access, promotion = null, date_promotion = null, turns) {
    $('#divSlider').append('<div class="col-xl-12" id="slider-step"></div>');
    var days = parseInt($('#days_event').val());
    if (id != null) {
        if (description == 'null') {
            description = null;
        }
        if (promotion != null && date_promotion != 'null') {
            $('#promotion').prop('checked', true);
            $('#divPromotions').removeClass('hidden');
            $('#divPromotions').removeAttr('style');
            $('#discount').val(promotion);
            $('#date_promotion').val(date_promotion);
        }
        $('#modalTicketsLabel').text('Edita un tipo de boleto');
        $('#ticket_id').val(id);
        $('#nameTicket').val(name);
        $('#descriptionTicket').val(description);
        $('#min_reservation').val(min_reservation);
        $('#max_reservation').val(max_reservation);
        $('#quantity').val(quantity);
        $('#start_sale').val(start_sale);
        $('#stop_sale').val(stop_sale);
        if (turns == 0) {
            $("#turnActive").prop("checked", false);
            $("#turnInactive").prop("checked", true);
        } else {
            $("#turnInactive").prop("checked", false);
            $("#turnActive").prop("checked", true);
        }
        if(price != null && price > 0) {
            $('#cover').prop('checked', true);
            $('#free').prop('checked', false);
            $('#priceTicket').val(price);
        } else {
            $('#free').prop('checked', true);
            $('#priceTicket').css('display', 'none');
            $('#priceTicket').val('50');
        }
        $('#submitTickets').text('Guardar cambios');
        var msj = 'El boleto se modifico correctamente';
        var stepSlider = document.getElementById('slider-step');

        noUiSlider.create(stepSlider, {
            start: [access],
            step: 1,
            // tooltips: [true],
            // tooltips: wNumb({decimals: 0}),
            range: {
                'min': [1],
                'max': [days]
            },
            pips: {
                mode: 'steps',
                density: 100,
            }
        });
    } else {
        console.log("Else");
        var stepSlider = document.getElementById('slider-step');

        noUiSlider.create(stepSlider, {
            start: [days],
            step: 1,
            // tooltips: [true],
            // tooltips: wNumb({decimals: 0}),
            range: {
                'min': [1],
                'max': [days]
            },
            pips: {
                mode: 'steps',
                density: 100,
            }
        });
        
        

        $('#modalTicketsLabel').text('Agrega un tipo de boleto');
        $('#submitTickets').text('Guardar boleto');
        var msj = 'El tipo de boleto se guardo correctamente';

        
    }
    $('#modalTickets').modal('show');
}

function deleteTicket(id) {
    Swal.fire({
        title: '¿Seguro que que desea eliminar este boleto?',
        text: "Los datos no se podrán recuperar",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#3085d6',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33',
        confirmButtonText: 'Eliminar',
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            $.ajax({
                method: 'POST',
                url: $('#URL').val()+'deleteTicket',
                dataType: 'json',
                async: false,
                data: {
                    "_token": $("meta[name='csrf-token']").attr("content"),
                    ticket_id: id
                },
                success: (response)=> {
                    if(response.status == true) {
                        $('#card-ticket-'+id).remove();
                        Swal.fire({
                            position: 'bottom-end',
                            icon: 'success',
                            text: 'El boleto se elimino correctamente',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    } else {
                        Swal.fire({
                            position: 'bottom-end',
                            icon: 'error',
                            text: 'Lo sentimos ocurrio un error',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                },
                error: ()=> {
                    console.log('ERROR');
                }
            });
        }
    });
}

function chargingDom(tickets) {
    tickets = JSON.parse(tickets);
    var content = '';
    for (var i = 0; i < tickets.length; i++) {
        content += '<div class="col-xl-12 bg-white p-4 mb-4" id="card-ticket-'+tickets[i].id+'">';
            content += '<div class="row">';
                content += '<div class="col-xl-8">';
                    content += '<h4 class="bold mb-2 name">'+tickets[i].name+'</h4>';
                    content += '<h4 class="bold text-gray-600 mb-2 price">$'+tickets[i].price+'.00 MXN</h4>';
                    content += '<span class="mb-2 pointer text-blue" onclick="copyToClipboard(\'#linkTicket'+i+'\')" data-toggle="tooltip" data-placement="right" title="Copiar enlace"><i class="fas fa-link"></i> <span class="linkTicket" id="linkTicket'+i+'">'+$('#URL').val()+''+tickets[i].event.url+'/'+tickets[i].name+'</span></span>';
                    content += '<p></p>';
                    content += '<span class="font-small mr-4 pointer edit" onclick="saveTicket('+tickets[i].id+', \''+tickets[i].name+'\', \''+tickets[i].description+'\', '+tickets[i].min_reservation+', '+tickets[i].max_reservation+', '+tickets[i].quantity+', \''+tickets[i].start_sale+'\', \''+tickets[i].stop_sale+'\', '+tickets[i].price+', '+tickets[i].status+', '+tickets[i].valid+', '+tickets[i].promotion+', \''+tickets[i].date_promotion+'\', '+tickets[i].use_turns+')"><i class="fas fa-pen"></i> EDITAR</span>';
                    content += '<span class="font-small mr-4 pointer delete" onclick="deleteTicket('+tickets[i].id+')"><i class="fas fa-trash-alt"></i> ELIMINAR</span>';
                content += '</div>';
                content += '<div class="col-xl-4 text-right">';
                    content += '<h3 class="mb-0"><span class="text-blue-400">'+tickets[i].access.length+'/</span><span class="text-blue-300 quantity">'+tickets[i].quantity+'</span></h3>';
                    content += '<span class="font-small mt-0">BOLETOS RESERVADOS</span>';
                content += '</div>';
            content += '</div>';
        content += '</div>';
    }
    $('#content-tickets').html(content);
}

$('#formTickets').submit((e)=> {
    console.log("event");
    e.preventDefault();
    var slider = document.getElementById('slider-step');
    var daysValid = parseInt(slider.noUiSlider.get());
    var ban = true;
    if ($('#promotion').is(':checked') == true) {
        if ($('#date_promotion').val() <= $('#start_sale').val() || $('#date_promotion').val() > $('#stop_sale').val()) {
            ban = false;
        }
    }
    if (ban == true) {
        var auxTurns=0;
        var price=0;
        if(!$('input:radio[name=turns]:checked').val()){
            auxTurns=0;
        }else{
            auxTurns=$('input:radio[name=turns]:checked').val();
        }
        

        if($('input:radio[name=price]:checked').val() == 0){
            price=0;
        }else{
            price=$('#priceTicket').val();
        }
        
        $.ajax({
            method: 'POST',
            url: $('#URL').val()+'saveTicket',
            dataType: 'json',
            data: {
                "_token": $("meta[name='csrf-token']").attr("content"),
                event_id: $('#event_id').val(),
                ticket_id: $('#ticket_id').val(),
                name: $('#nameTicket').val(),
                description: $('#descriptionTicket').val(),
                min_reservation: $('#min_reservation').val(),
                max_reservation: $('#max_reservation').val(),
                quantity: $('#quantity').val(),
                promotion: $('#discount').val(),
                date_promotion: $('#date_promotion').val(),
                start_sale: $('#start_sale').val(),
                stop_sale: $('#stop_sale').val(),
                turns: ($('input:radio[name=turns]:checked').val() == undefined) ? 0: $('input:radio[name=turns]:checked').val(),
                price: price,
                daysValid: daysValid
            },
            success: (response)=> {
                if(response.status == true) {
                    if (response.operation == 'save') {
                        chargingDom(JSON.stringify(response.ticket));
                        var msj = 'El boleto se guardo correctamente';
                    } else {
                        $('#card-ticket-'+response.ticket.id+' .name').text(response.ticket.name);
                        $('#card-ticket-'+response.ticket.id+' .price').text('$'+response.ticket.price+'.00 MXN');
                        $('#card-ticket-'+response.ticket.id+' .edit').attr('onclick', 'saveTicket('+response.ticket.id+', \''+response.ticket.name+'\', \''+response.ticket.description+'\', '+response.ticket.min_reservation+', '+response.ticket.max_reservation+', '+response.ticket.quantity+', \''+response.ticket.start_sale+'\', \''+response.ticket.stop_sale+'\', '+response.ticket.price+', '+response.ticket.status+', '+response.ticket.valid+', '+response.ticket.promotion+', \''+response.ticket.date_promotion+'\', '+response.ticket.use_turns+')');
                        $('#card-ticket-'+response.ticket.id+' .quantity').text(response.ticket.quantity);
                        $('#card-ticket-'+response.ticket.id+' .linkTicket').text($('#URL').val()+''+$('#url_event').val()+'/'+response.ticket.name);
                        var msj = 'El boleto se modifico correctamente';
                    }
                    $('#modalTickets').modal('hide');
                    Swal.fire({
                        position: 'bottom-end',
                        icon: 'success',
                        text: msj,
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    Swal.fire({
                        position: 'bottom-end',
                        icon: 'error',
                        text: 'Lo sentimos ocurrio un error',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            },
            error: ()=> {
                console.log('ERROR');
            }
        });
    } else {
        Swal.fire({
            icon: 'error',
            text: 'La fecha de la promoción debe estar entre el rango de venta del boleto'
        });
    }
});

var typeModel = 0;
function modelPayment(val) {
    if (typeModel != val) {
        if ($('#quantity_payments').val() == 0) {
            Swal.fire({
                title: 'Atención',
                html: "<span>¿Seguro que deseas continuar? <br> Una vez hecha la primera reservación, <br>ya no se podrá cambiar el modelo de cobro.</span>",
                icon: 'warning',
                showCancelButton: true,
                cancelButtonColor: '#d33',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#3085d6',
                confirmButtonText: 'Aceptar',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    $.ajax({
                        url: $('#URL').val()+'model_payment',
                        type: 'post',
                        data: {
                            "_token": $("meta[name='csrf-token']").attr("content"),
                            event_id: $('#event_id').val(),
                            model_payment: (val == 1) ? 'included' : 'separated',
                        },
                        success: (res)=> {
                            if (res.status == true) {
                                Swal.fire({
                                    position: 'bottom-end',
                                    icon: 'success',
                                    text: 'El modelo de cobro se cambio correctamente',
                                    showConfirmButton: false,
                                    timer: 2000
                                });
                                if (val == 1) {
                                    typeModel = 1;
                                    $('#separated').removeClass('btn-primary');
                                    $('#separated').addClass('btn-outline-primary');
                                    $('#included').addClass('btn-primary');
                                    $('#included').removeClass('btn-outline-primary');
                                } else {
                                    typeModel = 0;
                                    $('#separated').addClass('btn-primary');
                                    $('#separated').removeClass('btn-outline-primary');
                                    $('#included').removeClass('btn-primary');
                                    $('#included').addClass('btn-outline-primary');
                                }
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Acción denegada',
                                    html: 'Ya no es posible cambiar el modelo de cobro<br>por que ya se efectuo al menos una reservación',
                                });
                            }
                        },
                        error: ()=> {
                            console.log('ERROR');
                        }
                    });
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Acción denegada',
                html: 'Ya no es posible cambiar el modelo de cobro<br>por que ya se efectuo al menos una reservación',
            });
        }
    }
}

function copyToClipboard(elemento) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(elemento).text()).select();
    document.execCommand("copy");
    $temp.remove();
}