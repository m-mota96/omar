var status = 3;
$(document).ready(()=> {
    if ($('#type').val() == 'paid') {
        tableEventsPaid();
    } else {
        tableEventsFree();
    }
    $('[data-toggle="tooltip"]').tooltip();
});

function changeStatus() {
    status = parseInt($(this).attr('data-status'));
    switch (status) {
        case "0":
            $('#all').removeClass('btn-dark');
            $('#all').addClass('btn-outline-dark');
            $('#active').removeClass('btn-success');
            $('#active').addClass('btn-outline-success');
            $('#past').removeClass('btn-danger');
            $('#past').addClass('btn-outline-danger');
            $(this).removeClass('btn-outline-warning');
            $(this).addClass('btn-warning');
            break;
        case "1":
            $('#all').removeClass('btn-dark');
            $('#all').addClass('btn-outline-dark');
            $(this).removeClass('btn-outline-success');
            $(this).addClass('btn-success');
            $('#past').removeClass('btn-danger');
            $('#past').addClass('btn-outline-danger');
            $('#inactive').removeClass('btn-warning');
            $('#inactive').addClass('btn-outline-warning');
            break;
        case "2":
            $('#all').removeClass('btn-dark');
            $('#all').addClass('btn-outline-dark');
            $('#active').removeClass('btn-success');
            $('#active').addClass('btn-outline-success');
            $(this).removeClass('btn-outline-danger');
            $(this).addClass('btn-danger');
            $('#inactive').removeClass('btn-warning');
            $('#inactive').addClass('btn-outline-warning');
            break;
        case "3":
            $(this).removeClass('btn-outline-dark');
            $(this).addClass('btn-dark');
            $('#active').removeClass('btn-success');
            $('#active').addClass('btn-outline-success');
            $('#past').removeClass('btn-danger');
            $('#past').addClass('btn-outline-danger');
            $('#inactive').removeClass('btn-warning');
            $('#inactive').addClass('btn-outline-warning');
            break;
    }
    if ($('#type').val() == 'paid') {
        tableEventsPaid();
    } else {
        tableEventsFree();
    }
}

function tableEventsPaid() {
    $('#events').dataTable().fnDestroy();
    var table = $('#events').DataTable({
        "order": [[0, 'desc']],
        "lengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "Todos"]],
        "ajax": {
            url: $('#URL').val()+'extractEvents',
            method: 'post',
            data: {
                "_token": $("meta[name='csrf-token']").attr("content"),
                'status': status,
                'type': $('#type').val()
            }
        },
        "columns": [
            {data: 'id', "width": "1%", "className": "text-center text-dark"},
            {data: 'name', "width": "5%", "className": "text-center text-dark"},
            {
                "width": "5%",
                "className": "text-dark",
                "render": (data, type, row, meta) => {
                    return '<a href="'+$('#URL').val()+''+row.url+'" target="_blank">'+$('#URL').val()+''+row.url+'</a>';
                }
            },
            {
                "width": "5%",
                "className": "text-dark",
                "render": (data, type, row, meta) => {
                    return '<span>Del: <br><b>'+row.event_dates[0].date+'</b><br>al: <br><b>'+row.event_dates[row.event_dates.length-1].date+'</b></span>';
                }
            },
            {
                "width": "5%",
                "className": "text-dark bold",
                "render": (data, type, row, meta) => {
                    if (row.payments_agruped != null) {
                        return '<h5>$'+row.payments_agruped.total.toFixed(2)+'</h5>';
                    } else {
                        return '<h5>$0.00</h5>';
                    }
                }
            },
            {
                "width": "5%",
                "className": "text-dark bold",
                "render": (data, type, row, meta) => {
                    if (row.payments_agruped != null) {
                        var commission = row.payments_agruped.total * .05;
                        var total = (row.payments_agruped.total - commission).toFixed(2);
                        return '<h5>$'+total+'</h5>';
                    } else {
                        return '<h5>$0.00</h5>';
                    }
                }
            },
            {
                "width": "5%",
                "className": "text-dark bold",
                "render": (data, type, row, meta) => {
                    if (row.payments_agruped != null) {
                        var commission = row.payments_agruped.total * .05;
                        return '<h5>$'+commission.toFixed(2)+'</h5>';
                    } else {
                        return '<h5>$0.00</h5>';
                    }
                }
            },
            {
                "width": "5%",
                "className": "text-dark bold text-center",
                "render": (data, type, row, meta) => {
                    return '<h5>'+row.sales+'</h5>';
                }
            },
            {
                "width": "5%",
                "className": "text-dark bold text-center",
                "render": (data, type, row, meta) => {
                    if (row.assistance != null) {
                        return '<h5>'+row.assistance.assistance+'</h5>';
                    } else {
                        return '<h5>0</h5>';
                    }
                }
            },
            {
                "width": "5%",
                "className": "text-dark bold text-center",
                "render": (data, type, row, meta) => {
                    if (row.status == 0) {
                        return '<h5 class="text-warning bold">Inactivo</h5>';
                    } else if (row.status == 1) {
                        return '<h5 class="text-success bold">Activo</h5>';
                    } else if (row.status == 2) {
                        return '<h5 class="text-danger bold">Finalizado</h5>';
                    }
                }
            },
        ],
        language: {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        }
    });
}

function tableEventsFree() {
    $('#events').dataTable().fnDestroy();
    var table = $('#events').DataTable({
        "order": [[0, 'desc']],
        "lengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "Todos"]],
        "ajax": {
            url: $('#URL').val()+'extractEvents',
            method: 'post',
            data: {
                "_token": $("meta[name='csrf-token']").attr("content"),
                'status': status,
                'type': $('#type').val()
            }
        },
        "columns": [
            {data: 'id', "width": "1%", "className": "text-center text-dark"},
            {data: 'name', "width": "5%", "className": "text-center text-dark"},
            {
                "width": "5%",
                "className": "text-dark",
                "render": (data, type, row, meta) => {
                    return '<a href="'+$('#URL').val()+''+row.url+'" target="_blank">'+$('#URL').val()+''+row.url+'</a>';
                }
            },
            {
                "width": "5%",
                "className": "text-dark",
                "render": (data, type, row, meta) => {
                    return '<span>Del: <br><b>'+row.event_dates[0].date+'</b><br>al: <br><b>'+row.event_dates[row.event_dates.length-1].date+'</b></span>';
                }
            },
            {
                "width": "5%",
                "className": "text-dark bold text-center",
                "render": (data, type, row, meta) => {
                    return '<h5>'+row.sales+'</h5>';
                }
            },
            {
                "width": "5%",
                "className": "text-dark bold text-center",
                "render": (data, type, row, meta) => {
                    return '<h5>'+row.sales+'</h5>';
                }
            },
            {
                "width": "5%",
                "className": "text-dark bold text-center",
                "render": (data, type, row, meta) => {
                    if (row.status == 0) {
                        return '<h5 class="text-warning bold">Inactivo</h5>';
                    } else if (row.status == 1) {
                        return '<h5 class="text-success bold">Activo</h5>';
                    } else if (row.status == 2) {
                        return '<h5 class="text-danger bold">Finalizado</h5>';
                    }
                }
            },
        ],
        language: {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        }
    });
}