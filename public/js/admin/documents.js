$(document).ready(()=> {
    tableUsers();
    $('[data-toggle="tooltip"]').tooltip();
});

function tableUsers() {
    $('#customers').dataTable().fnDestroy();
    var table = $('#customers').DataTable({
        "order": [[0, 'desc']],
        "lengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "Todos"]],
        "ajax": {
            url: $('#URL').val()+'extractUsersDocuments',
            method: 'post',
            data: {
                "_token": $("meta[name='csrf-token']").attr("content")
            }
        },
        "columns": [
            {data: 'id', "width": "5%", "className": "text-center"},
            {data: 'name', "width": "5%", "className": "text-center"},
            {data: 'email', "width": "5%", "className": "text-center"},
            // {data: 'name'},
            // {data: 'email'},
            // {data: 'phone'},
            {
                "width": "5%",
                "render": (data, type, row, meta) => {
                    var documents = '';
                    for (var i = 0; i < row.documents.length; i++) {
                        if (row.documents[i].status == 1) {
                            if (row.documents[i].type == "comprobante_domicilio") {
                                var txt = 'Comprobante de domicilio';
                            } else if (row.documents[i].type == "identificacion") {
                                var txt = 'Identificacion';
                            } else if (row.documents[i].type == "acta") {
                                var txt = 'Acta constitutiva';
                            } else if (row.documents[i].type == "comprobante_bancario") {
                                var txt = 'Comprobante bancario';
                            }
                            documents += '<label style="width: 195px">'+txt+'</label>';
                            documents += '<span class="btn btn-primary btn-sm mb-1 ml-2 buttons-small pointer" data-toggle="tooltip" data-placement="top" title="Ver documento" onclick="viewDocument(\''+row.documents[i].document+'\', '+row.documents[i].user_id+')">';
                            documents += '<i class="fas fa-eye"></i></span>';
                            documents += '<span class="btn btn-success btn-sm mb-1 ml-2 buttons-small pointer" data-toggle="tooltip" data-placement="top" title="Aceptar documento" onclick="statusDocument('+row.documents[i].id+', 3)"><i class="fas fa-check"></i></span>';
                            documents += '<span class="btn btn-danger btn-sm mb-1 ml-2 buttons-small pointer" data-toggle="tooltip" data-placement="top" title="Rechazar documento" onclick="statusDocument('+row.documents[i].id+', 2)"><i class="fas fa-times"></i></span>';
                            documents += '<br>';
                        }
                    }
                    $('[data-toggle="tooltip"]').tooltip();
                    return documents;
                }
            },
        ],
        language: {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        }
    });
}

function viewDocument(document, user_id) {
    window.open($('#URL').val()+'media/pdf/documents/user_'+user_id+'/'+document);
}

function statusDocument(idDocument, status) {
    if (status == 3) {
        var txt = 'validar';
        var txt2 = 'valido';
    } else {
        var txt = 'rechazar';
        var txt2 = 'rechazo';
    }
    Swal.fire({
        title: 'Atención',
        text: "¿Seguro que desea "+txt+" el documento?",
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
                url: $('#URL').val()+'statusDocument',
                method: 'post',
                data: {
                    "_token": $("meta[name='csrf-token']").attr("content"),
                    idDocument: idDocument,
                    status: status
                },
                success: (res) => {
                    tableUsers();
                    Swal.fire(
                        'Correcto',
                        'El documento se '+txt2+' con éxito',
                        'success'
                    );
                },
                error: () => {
                    console.log('ERROR');
                }
            });
        }
    })
}