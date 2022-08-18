$(document).ready(()=> {
    $('#modalCodes #ticket_id').multiselect({
        includeSelectAllOption: true,
        nonSelectedText: "Elija uno o más boletos",
        allSelectedText: "Todo seleccionado",
        selectAllText: 'Seleccionar todo'
    });
});

function newCode() {
    $('#modalCodes #code_id').val('');
    $('#modalCodes .form-control').val('');
    $('#modalCodes #ticket_id').multiselect('refresh');
    $('#modalCodesLabel').text('Agrega un un código de descuento');
    $('#modalCodes').modal('show');
}

function generateCode() {
    code = '';
    chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    lon = 6;
    for (x = 0; x < lon; x++) {
        rand = Math.floor(Math.random() * chars.length);
        code += chars.substr(rand, 1);
    }
    $('#modalCodes #code').val(code);
}

$('#formCodes').submit((e)=> {
    e.preventDefault();
    if ($('#formCodes #expiration').val() > $('#formCodes #current_date').val() || $('#modalCodes #code_id').val() != '') {
        $.ajax({
            method: 'POST',
            url: $('#URL').val()+'saveCode',
            dataType: 'json',
            // data: $('#formCodes').serialize(),
            data: {
                _token: $("meta[name='csrf-token']").attr("content"),
                code_id: $('#modalCodes #code_id').val(),
                code: $('#modalCodes #code').val(),
                quantity: $('#modalCodes #quantity').val(),
                expiration: $('#modalCodes #expiration').val(),
                discount: $('#modalCodes #discount').val(),
                ticket_id: $('#modalCodes #ticket_id').val(),
                customer_name: $('#modalCodes #customer_name').val(),
                email: $('#modalCodes #email').val(),
                password: $('#modalCodes #password').val(),
                password_confirm: $('#modalCodes #password_confirm').val(),

            },
            success: (res)=> {
                if (res.status == true) {
                    Swal.fire({
                        position: 'bottom-end',
                        icon: 'success',
                        text: 'El código se guardo correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    if ($('#modalCodes #code_id').val() != '') {
                        var card_id = '#card-code-'+$('#modalCodes #code_id').val();
                        $(card_id+' .code').text(res.code.code);
                        $(card_id+' .discount').text('Descuento: '+res.code.discount+'%');
                        var ticketsName = '';
                        for (let j = 0; j < res.code.tickets.length; j++) {
                            ticketsName += res.code.tickets[j].name+', ';
                        }
                        ticketsName = ticketsName.substring(0, ticketsName.length - 2);
                        $(card_id+' .ticket').text('['+ticketsName+']');
                        $(card_id+' .quantity').text(res.code.quantity);
                        var codeParsed = JSON.stringify(res.code);
                        codeParsed = codeParsed.replace(/['"]+/g, "'");
                        $(card_id+' .edit').attr('onclick', 'editCode('+codeParsed+')');
                    } else {
                        var code = [];
                        code.push(res.code);
                        chargingDom(code, 'add');
                    }
                    $('#modalCodes .form-control').val('');
                    $('#modalCodes').modal('hide');
                } else {
                    if (res.error == 'passwords_incorrect') {
                        Swal.fire({
                            title: '¡Error!',
                            icon: 'error',
                            text: 'Las contraseñas no coinciden'
                        });
                    }
                }
            },
            error: ()=> {
                console.log('ERROR');
            }
        });
    } else {
        Swal.fire({
            title: '¡Error!',
            icon: 'error',
            text: 'La fecha de expiración debe ser mayor que la actual'
        });
    }
});

function chargingDom(codes, action = '') {
    var content = '';
    for (var i = 0; i < codes.length; i++) {
        var ticketsName = '';
        var sales = 0;
        if (codes[i].tickets.length > 0) {
            for (let j = 0; j < codes[i].tickets.length; j++) {
                sales = sales + codes[i].tickets[j].pivot.used;
                ticketsName += codes[i].tickets[j].name+', ';
            }
            ticketsName = ticketsName.substring(0, ticketsName.length - 2);
        }
        content += '<div class="col-xl-12 bg-white p-4 mb-4" id="card-code-'+codes[i].id+'">';
            content += '<div class="row">';
                content += '<div class="col-xl-8">';
                    content += '<h4 class="bold mb-2 code">'+codes[i].code+'</h4>';
                    content += '<h4 class="bold text-gray-600 mb-2 discount">Descuento: '+codes[i].discount+'%</h4>';
                    content += '<span class="mb-2 text-blue ticket">['+ticketsName+']</span>';
                    content += '<p></p>';
                    var codeParsed = JSON.stringify(codes[i]);
                    codeParsed = codeParsed.replace(/['"]+/g, "'");
                    content += '<span class="font-small mr-4 pointer edit" onclick="editCode('+codeParsed+')"><i class="fas fa-pen"></i> EDITAR</span>';
                    if (codes[i].used == 0) {
                        content += '<span class="font-small mr-4 pointer delete" onclick="deleteCode('+codes[i].id+')"><i class="fas fa-trash-alt"></i> ELIMINAR</span>';
                    }
                content += '</div>';
                content += '<div class="col-xl-4 text-right">';
                    content += '<h3 class="mb-0"><span class="text-blue-400">'+sales+'/</span><span class="text-blue-300 quantity">'+codes[i].quantity+'</span></h3>';
                    content += '<span class="font-small mt-0">CANTIDAD UTILIZADA</span>';
                content += '</div>';
            content += '</div>';
        content += '</div>';
    }
    if (action == '') {
        $('#content-codes').html(content);
    } else {
        $('#content-codes').append(content);
    }
}

function deleteCode(codeId) {
    Swal.fire({
        title: '¿Seguro que que desea eliminar este código?',
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
                url: $('#URL').val()+'deleteCode',
                dataType: 'json',
                async: false,
                data: {
                    "_token": $("meta[name='csrf-token']").attr("content"),
                    code_id: codeId
                },
                success: (response)=> {
                    if(response.status == true) {
                        $('#card-code-'+codeId).remove();
                        Swal.fire({
                            position: 'bottom-end',
                            icon: 'success',
                            text: 'El código se elimino correctamente',
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

function editCode(code) {
    var tickets = [];
    for (let i = 0; i < code.tickets.length; i++) {
        tickets[i] = code.tickets[i].id;
    }
    $('#modalCodesLabel').text('Editar código de descuento');
    $('#modalCodes #code').val(code.code);
    $('#modalCodes #quantity').val(code.quantity);
    $('#modalCodes #discount').val(code.discount);
    $('#modalCodes #email').val(code.email);
    $('#modalCodes #customer_name').val(code.customer_name);
    $('#modalCodes #expiration').val(code.expiration);
    $('#modalCodes #ticket_id').val(tickets);
    $('#modalCodes #code_id').val(code.id);
    $('#modalCodes #ticket_id').multiselect('refresh');
    $('#modalCodes').modal('show');
}