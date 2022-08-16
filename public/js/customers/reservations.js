$(document).ready(()=> {
    tableSales();
});

function tableSales() {
    $('#sales').dataTable().fnDestroy();
    var table = $('#sales').DataTable({
        "order": [[0, 'desc']],
        "processing": true,
        "serverSide": true,
        "lengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "Todos"]],
        "ajax": {
            url: $('#URL').val()+'extractSales',
            method: 'post',
            data: {
                "_token": $("meta[name='csrf-token']").attr("content"),
                event_id: $('#eventId').val()
            }
        },
        "columns": [
            {data: 'id', "width": "5%", "className": "text-center"},
            {data: 'name'},
            {data: 'email'},
            {data: 'phone'},
            {
                "width": "10%",
                "render": (data, type, row, meta) => {
                    if (row.reference.length == 4) {
                        return '<span>Tarjeta</span>';
                    } else {
                        return '<span>Efectivo</span>';
                    }
                }
            },
            {
                "render": (data, type, row, meta) => {
                    return '<span>$'+row.amount+'</span>';
                }
            },
            {
                "className": "text-center",
                "render": (data, type, row, meta) => {
                    var span = '', codeAux = '';
                    for (var i = 0; i < row.accesses.length; i++) {
                        if ((row.accesses[i].code != '' && row.accesses[i].code != null) && codeAux != row.accesses[i].code.code) {
                            span += row.accesses[i].code.code+', ';
                            codeAux = row.accesses[i].code.code;
                        }
                    }
                    span += '';
                    if (span == '') {
                        span = 'N/A';
                    } else {
                        span = span.substr(0, span.length - 2);
                    }
                    return '<span>'+span+'</span>';
                }
            },
            {
                "render": (data, type, row, meta) => {
                    if (row.status == 'payed') {
                        return '<span class="text-green bold">Pagado</span>';
                    } else if (row.status == 'pending') {
                        return '<span class="text-orange bold">Pendiente</span>';
                    } else if (row.status == 'expired') {
                        return '<span class="text-red bold">Expirado</span>';
                    }
                }
            },
            {
                "render": (data, type, row, meta) => {
                    return '<span>'+row.created_at.display+'</span>';
                }
            },
            {
                "width": "20%",
                "className": "text-center",
                "render": (data, type, row, meta) => {
                    var btn = '<button type="button" class="btn btn-success btn-sm mr-2" data-toggle="tooltip" data-placement="right" title="Detalles de la compra" onclick="detailsSale('+row.id+')"><i class="fas fa-info"></i></button>';
                    if (row.status == 'payed') {
                        btn += '<button type="button" class="btn btn-primary btn-sm mr-2" data-toggle="tooltip" data-placement="right" title="Reenviar boletos" onclick="resendTickets('+row.id+', \''+row.email+'\')"><i class="fas fa-envelope"></i></button>';
                        btn += '<button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="right" title="Descargar boletos" onclick="downloadFiles('+row.id+')"><i class="fas fa-file-download"></i></button>';
                        // $('[data-toggle="tooltip"]').tooltip();
                        return btn;
                    }
                    return btn;
                }
            },
        ],
        language: {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        }
    });
}

function detailsSale(payment_id) {
    $.ajax({
        url: $('#URL').val()+'detailsSale',
        method: 'post',
        data: {
            "_token": $("meta[name='csrf-token']").attr("content"),
            payment_id: payment_id
        },
        success: (response)=> {
            var tr = '';
            for (var i = 0; i < response.data.length; i++) {
                tr += '<tr>';
                    tr += '<td>'+response.data[i].ticket.name+'</td>';
                    tr += '<td>$'+formatMoney(response.data[i].ticket.price)+'</td>';
                    tr += (response.data[i].status == 1) ? '<td class="text-green">Activo</td>' : '<td class="text-orange">Escaneado</td>';
                tr += '</tr>';
            }
            $('#tbodyDetails').html(tr);
            $('#modaldetailsSale').modal('show');
        },
        error: ()=> {
            console.log('ERROR');
        }
    });
}

function resendTickets(payment_id, email) {
    Swal.fire({
        icon: 'warning',
        html: '<span>Los boletos seran enviados al siguiente correo:</span><br><span><b>Nota: </b>si el correo es incorrecto ingrese el nuevo</span>',
        input: 'email',
        inputAttributes: {
          autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Reenviar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true,
        inputValue : email,
    }).then((result) => {
        if (result.value) {
            jsShowWindowLoad('Reenviando boletos, por favor espere!!');
            $.ajax({
                url: $('#URL').val()+'resendTickets',
                method: 'post',
                data: {
                    "_token": $("meta[name='csrf-token']").attr("content"),
                    payment_id: payment_id,
                    email: result.value
                },
                success: (res)=> {
                    if(res.status == true) {
                        jsRemoveWindowLoad();
                        tableSales();
                        Swal.fire({
                            icon: 'success',
                            title: 'Correcto',
                            text: 'Los boletos se reenviaron con éxito'
                        });
                    }
                },
                error: ()=> {
                    jsRemoveWindowLoad();
                    console.log('ERROR');
                }
            })
        }
    })
}

function downloadFiles(payment_id) {
    $.ajax({
        url: $('#URL').val()+'downloadTickets',
        method: 'post',
        data: {
            "_token": $("meta[name='csrf-token']").attr("content"),
            'payment_id': payment_id
        },
        success: (res)=> {
            if(res.status == true) {
                window.location.href = $('#URL').val()+'media/zips/'+res.nameZip;
            }
        },
        error: ()=> {
            console.log('ERROR');
        }
    });
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