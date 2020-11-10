$(document).ready(()=> {
    $('[data-toggle="tooltip"]').tooltip();
});

$('input:radio[name=price]').click(()=> {	 
    if ($('input:radio[name=price]:checked').val() == 1) {
        $('#priceTicket').css('display', 'block');
    } else {
        $('#priceTicket').val('');
        $('#priceTicket').css('display', 'none');
    }
});

$("#modalTickets").on("hidden.bs.modal", function () {
    $('#modalTickets .form-control').val('');
    $('#cover').prop('checked', true);
    $('#free').prop('checked', false);
    $('#priceTicket').css('display', 'block');
});

function saveTicket(id = null, name = null, description = '', min_reservation = null, max_reservation = null, quantity = null, start_sale = null, stop_sale = null, price = null, status = null) {
    if (id != null) {
        if (description == 'null') {
            description = null;
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
        if(price != null) {
            $('#cover').prop('checked', true);
            $('#free').prop('checked', false);
            $('#priceTicket').val(price);
        } else {
            $('#free').prop('checked', true);
            $('#cover').prop('checked', false);
            $('#priceTicket').val('');
        }
        $('#submitTickets').text('Guardar cambios');
        var msj = 'El boleto se modifico correctamente';
    } else {
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

function chargingDom(tickets, action = null) {
    tickets = JSON.parse(tickets);
    var content = '';
    for (var i = 0; i < tickets.length; i++) {
        content += '<div class="col-xl-12 bg-white p-4 mb-4" id="card-ticket-'+tickets[i].id+'">';
            content += '<div class="row">';
                content += '<div class="col-xl-8">';
                    content += '<h4 class="bold mb-2 name">'+tickets[i].name+'</h4>';
                    content += '<h4 class="bold text-gray-600 mb-2 price">$'+tickets[i].price+'.00 MXN</h4>';
                    content += '<span class="mb-2 pointer text-blue" onclick="copyToClipboard(\'#linkTicket'+i+'\')" data-toggle="tooltip" data-placement="right" title="Copiar enlace"><i class="fas fa-link"></i> <span id="linkTicket'+i+'">'+$('#URL').val()+''+tickets[i].event.url+'/'+tickets[i].name+'</span></span>';
                    content += '<p></p>';
                    content += '<span class="font-small mr-4 pointer edit" onclick="saveTicket('+tickets[i].id+', \''+tickets[i].name+'\', \''+tickets[i].description+'\', '+tickets[i].min_reservation+', '+tickets[i].max_reservation+', '+tickets[i].quantity+', \''+tickets[i].start_sale+'\', \''+tickets[i].stop_sale+'\', '+tickets[i].price+', '+tickets[i].status+')"><i class="fas fa-pen"></i> EDITAR</span>';
                    content += '<span class="font-small mr-4 pointer delete" onclick="deleteTicket('+tickets[i].id+')"><i class="fas fa-trash-alt"></i> ELIMINAR</span>';
                content += '</div>';
                content += '<div class="col-xl-4 text-right">';
                    content += '<h3 class="mb-0"><span class="text-blue-400">0/</span><span class="text-blue-300 quantity">'+tickets[i].quantity+'</span></h3>';
                    content += '<span class="font-small mt-0">BOLETOS RESERVADOS</span>';
                content += '</div>';
            content += '</div>';
        content += '</div>';
    }
    if (action == null) {
        $('#content-tickets').html(content);
    } else {
        $('#content-tickets').append(content);
    }
}

$('#formTickets').submit((e)=> {
    e.preventDefault();
    $.ajax({
        method: 'POST',
        url: $('#URL').val()+'saveTicket',
        dataType: 'json',
        async: false,
        data: {
            "_token": $("meta[name='csrf-token']").attr("content"),
            event_id: $('#event_id').val(),
            ticket_id: $('#ticket_id').val(),
            name: $('#nameTicket').val(),
            description: $('#descriptionTicket').val(),
            min_reservation: $('#min_reservation').val(),
            max_reservation: $('#max_reservation').val(),
            quantity: $('#quantity').val(),
            start_sale: $('#start_sale').val(),
            stop_sale: $('#stop_sale').val(),
            price: $('#priceTicket').val()
        },
        success: (response)=> {
            if(response.status == true) {
                if (response.operation == 'save') {
                    var ticket = [];
                    ticket[0] = response.ticket;
                    chargingDom(JSON.stringify(ticket), 'add');
                    var msj = 'El boleto se guardo correctamente';
                } else {
                    $('#card-ticket-'+response.ticket.id+' .name').text(response.ticket.name);
                    $('#card-ticket-'+response.ticket.id+' .price').text('$'+response.ticket.price+'.00 MXN');
                    $('#card-ticket-'+response.ticket.id+' .edit').attr('onclick', 'saveTicket('+response.ticket.id+', \''+response.ticket.name+'\', \''+response.ticket.description+'\', '+response.ticket.min_reservation+', '+response.ticket.max_reservation+', '+response.ticket.quantity+', \''+response.ticket.start_sale+'\', \''+response.ticket.stop_sale+'\', '+response.ticket.price+', '+response.ticket.status+')');
                    $('#card-ticket-'+response.ticket.id+' .quantity').text(response.ticket.quantity);
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
});

function copyToClipboard(elemento) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(elemento).text()).select();
    document.execCommand("copy");
    $temp.remove();
}