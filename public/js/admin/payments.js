$(document).ready(()=> {
    if ($('#status').val() == 0) {
        tablePaymentsPending();
    } else {
        tablePaymentsPayed();
    }
});

function tablePaymentsPending() {
    $('#payments').dataTable().fnDestroy();
    var table = $('#payments').DataTable({
        "order": [[0, 'desc']],
        "lengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "Todos"]],
        "ajax": {
            url: $('#URL').val()+'extractPayments',
            method: 'post',
            data: {
                "_token": $("meta[name='csrf-token']").attr("content"),
                status: $('#status').val()
            }
        },
        "columns": [
            {data: 'id', "width": "1%", "className": "text-center text-dark"},
            {data: 'user.name', "width": "5%", "className": "text-center text-dark"},
            {data: 'user.email', "width": "5%", "className": "text-center text-dark"},
            {data: 'event.name', "width": "5%", "className": "text-center text-dark"},
            {data: 'created_at.date', "width": "5%", "className": "text-center text-dark"},
            {
                "width": "5%",
                "className": "text-dark",
                "render": (data, type, row, meta) => {
                    return '<span>$'+row.amount+' MXN</span>';
                }
            },
            {
                "width": "5%",
                "className": "text-dark",
                "render": (data, type, row, meta) => {
                    var commission = row.commission / 100;
                    var total = row.amount - (commission * row.amount);
                    return '<span>$'+total+' MXN</span>';
                }
            },
            {
                "width": "5%",
                "className": "text-dark",
                "render": (data, type, row, meta) => {
                    var commission = row.commission / 100;
                    var total = commission * row.amount;
                    return '<span>$'+total+' MXN</span>';
                }
            },
            {
                "width": "2%",
                "className": "text-dark text-center",
                "render": (data, type, row, meta) => {
                    $('[data-toggle="tooltip"]').tooltip();
                    return '<span class="btn btn-success btn-sm mb-1 ml-2 buttons-small pointer" data-toggle="tooltip" data-placement="top" title="Marcar como pagado" onclick="changeStatus('+row.id+')"><i class="fas fa-check"></i></span>';
                }
            },
        ],
        language: {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        }
    });
}

function tablePaymentsPayed() {
    $('#payments').dataTable().fnDestroy();
    var table = $('#payments').DataTable({
        "order": [[0, 'desc']],
        "lengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "Todos"]],
        "ajax": {
            url: $('#URL').val()+'extractPayments',
            method: 'post',
            data: {
                "_token": $("meta[name='csrf-token']").attr("content"),
                status: $('#status').val()
            }
        },
        "columns": [
            {data: 'id', "width": "1%", "className": "text-center text-dark"},
            {data: 'user.name', "width": "5%", "className": "text-center text-dark"},
            {data: 'user.email', "width": "5%", "className": "text-center text-dark"},
            {data: 'event.name', "width": "5%", "className": "text-center text-dark"},
            {data: 'created_at.update', "width": "5%", "className": "text-center text-dark"},
            {
                "width": "5%",
                "className": "text-dark",
                "render": (data, type, row, meta) => {
                    return '<span>$'+row.amount+' MXN</span>';
                }
            },
            {
                "width": "5%",
                "className": "text-dark",
                "render": (data, type, row, meta) => {
                    var commission = row.commission / 100;
                    var total = row.amount - (commission * row.amount);
                    return '<span>$'+total+' MXN</span>';
                }
            },
            {
                "width": "5%",
                "className": "text-dark",
                "render": (data, type, row, meta) => {
                    var commission = row.commission / 100;
                    var total = commission * row.amount;
                    return '<span>$'+total+' MXN</span>';
                }
            }
        ],
        language: {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        }
    });
}

function changeStatus(payment_id) {
    Swal.fire({
        title: 'Atención',
        text: "¿Seguro que quiere marcar este pago como pagado?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Aceptar',
        cancelButtonText: 'Cerrar',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: $('#URL').val()+'changeStatusAdminPayment',
                method: 'post',
                data: {
                    "_token": $("meta[name='csrf-token']").attr("content"),
                    payment_id: payment_id
                },
                success: (res)=> {
                    tablePayments();
                    Swal.fire(
                        'Correcto',
                        'El pago ha sido marcado como pagado',
                        'success'
                    );
                },
                error: ()=> {
                    console.log('ERROR');
                }
            });
        }
    });
}