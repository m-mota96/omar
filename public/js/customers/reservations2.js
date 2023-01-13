$(document).ready(()=> {
    $('#sales thead th').each(function (i) {
        var title = $(this).text();
        if (i == 1 || i == 2 || i == 3 || i == 6) {
            $(this).html('<input class="form-control form-control-sm" type="text" placeholder="Buscar ' + title + '" />');
        }

        if (i == 4) {
            var select = '<select class="form-control form-control-sm">';
                select += '<option value="">'+title+'</otpion>';
                select += '<option value="Tarjeta">Tarjeta</otpion>';
                select += '<option value="Efectivo">Efectivo</otpion>';
            select += '</select>';
            $(this).html(select);
        }

        if (i == 7) {
            var select = '<select class="form-control form-control-sm">';
                select += '<option value="">'+title+'</otpion>';
                select += '<option value="Pagado">Pagado</otpion>';
                select += '<option value="Pendiente">Pendiente</otpion>';
                select += '<option value="Expirado">Expirado</otpion>';
            select += '</select>';
            $(this).html(select);
        }
    });
    tableSales();
});

function tableSales() {
    $('#sales').dataTable().fnDestroy();
    var table = $('#sales').DataTable({
        "order": [[0, 'desc']],
        "processing": true,
        "serverSide": true,
        "dom": 'lrtip',
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
            {data: 'name', orderable: false},
            {data: 'email', orderable: false},
            {data: 'phone', orderable: false},
            {data: 'type_payment', orderable: false},
            {data: 'amount'},
            {data: 'codes', orderable: false},
            {
                orderable: false,
                render: (data, type, row, meta) => {
                    if (row.status_payment == 'Pagado') {
                        return '<span class="text-success">Pagado</span>';
                    } else if (row.status_payment == 'Pendiente') {
                        return '<span class="text-warning">Pendiente</span>';
                    } else if (row.status_payment == 'Expirado') {
                        return '<span class="text-danger">Expirado</span>';
                    }
                }
            },
            {data: 'created_at.display'},
            {
                orderable: false,
                searchable:false,
                "width": "20%",
                "className": "text-center",
                "render": (data, type, row, meta) => {
                    var btn = '<button type="button" class="btn btn-success btn-sm mr-2" data-toggle="tooltip" data-placement="top" title="Detalles de la compra" onclick="detailsSale('+row.id+')"><i class="fas fa-info"></i></button>';
                    if (row.status_payment == 'Pagado') {
                        btn += '<button type="button" class="btn btn-primary btn-sm mr-2" data-toggle="tooltip" data-placement="top" title="Reenviar boletos" onclick="resendTickets('+row.id+', \''+row.email+'\')"><i class="fas fa-envelope"></i></button>';
                        btn += '<button type="button" class="btn btn-info btn-sm" data-toggle="tooltip" data-placement="top" title="Descargar boletos" onclick="downloadFiles('+row.id+')"><i class="fas fa-file-download"></i></button>';
                        // $('[data-toggle="tooltip"]').tooltip();
                        return btn;
                    }
                    return btn;
                }
            },
        ],
        initComplete: function () {
            // Apply the search
            this.api()
                .columns()
                .every(function () {
                    var that = this;
 
                    $('input', this.header()).on('keyup change clear', function () {
                        if (that.search() !== this.value) {
                            that.search(this.value).draw();
                        }
                    });

                    $('select', this.header()).on('change', function () {
                        if (that.search() !== this.value) {
                            that.search(this.value).draw();
                        }
                    });
                });
        },
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
                    tr += '<td>'+((response.data[i].code != null) ? response.data[i].code.code : 'N/A')+'</td>';
                    var amount = response.data[i].ticket.price;
                    if (response.data[i].code != null) {
                        amount = response.data[i].ticket.price - (response.data[i].ticket.price * (response.data[i].code.discount / 100));
                    }
                    tr += '<td>$'+formatMoney(amount)+'</td>';
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