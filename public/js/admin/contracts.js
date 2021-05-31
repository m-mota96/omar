$(document).ready(()=> {
    tableContracts();
    $('[data-toggle="tooltip"]').tooltip();
});

function tableContracts() {
    $('#contracts').dataTable().fnDestroy();
    var table = $('#contracts').DataTable({
        "order": [[0, 'desc']],
        "lengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "Todos"]],
        "ajax": {
            url: $('#URL').val()+'extractUsersInfo',
            method: 'post',
            data: {
                "_token": $("meta[name='csrf-token']").attr("content")
            }
        },
        "columns": [
            {data: 'id', "width": "1%", "className": "text-center"},
            {data: 'name', "width": "4%", "className": "text-center"},
            {data: 'email', "width": "4%", "className": "text-center"},
            {data: 'phone', "width": "2%", "className": "text-center"},
            {
                "width": "7%",
                "className": "text-center",
                "render": (data, type, row, meta) => {
                    var taxData = JSON.stringify(row.tax_data).replace(/\"/g,"'");
                    var documents = "<span class=\"btn btn-primary btn-sm mb-1 ml-2 pointer buttons-medium\" onclick=\"viewTaxData("+taxData+")\">Ver información fiscal</span>";
                    taxData = JSON.stringify(row.bank_data).replace(/\"/g,"'");
                    documents += "<span class=\"btn btn-success btn-sm mb-1 ml-2 pointer buttons-medium\" onclick=\"viewBankData("+taxData+")\">Ver información bancaria</span>";
                    return documents;
                }
            },
            {
                "width": "2%",
                "className": "text-center",
                "render": (data, type, row, meta) => {
                    if (row.contract == null) {
                        var documents = "<span class=\"btn btn-warning btn-sm mb-1 ml-2 pointer\" onclick=\"modalContract("+row.id+")\">Subir contrato</span>";
                    } else {
                        var documents = '<a class="btn btn-info btn-sm buttons-small" href="'+$('#URL').val()+'media/pdf/contracts/'+row.contract+'" target="_blank" data-toggle="tooltip" data-placement="top" title="Ver contrato"><i class="fas fa-eye"></i></a> &nbsp;<span class="btn btn-danger btn-sm pointer buttons-small" data-toggle="tooltip" data-placement="top" title="Eliminar contrato" onclick="deleteContract('+row.id+')"><i class="fas fa-trash-alt"></i></span>';
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

function deleteContract(user_id) {
    Swal.fire({
        title: 'Atención',
        text: "¿Seguro que desea eliminar el contrato?",
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
                url: $('#URL').val()+'deleteContract',
                method: 'post',
                data: {
                    "_token": $("meta[name='csrf-token']").attr("content"),
                    'user_id': user_id
                },
                success: (res)=> {
                    if (res.status == true) {
                        tableContracts();
                        Swal.fire({
                            position: 'bottom-end',
                            icon: 'success',
                            text: 'Contrato eliminado exitosamente',
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

function modalContract(user_id) {
    $('#user_id').val(user_id);
    $('#modalContract').modal('show');
}

function viewTaxData(taxData) {
    $('#modalInformation #modalInformationLabel').text('Información fiscal');
    var text = '';
    text += '<div class="col-xl-6"><small class="mb-1 text-gray-dark-300">Representante legal:</small><p class="bold text-dark">'+taxData.legal_representative+'</p></div>';
    text += '<div class="col-xl-6"><small class="mb-1 text-gray-dark-300">Razón social:</small><p class="bold text-dark">'+taxData.business_name+'</p></div>';
    text += '<div class="col-xl-6"><small class="mb-1 text-gray-dark-300">RFC:</small><p class="bold text-dark">'+taxData.rfc+'</p></div>';
    text += '<div class="col-xl-6"><small class="mb-1 text-gray-dark-300">Calle:</small><p class="bold text-dark">'+taxData.address+'</p></div>';
    text += '<div class="col-xl-6"><small class="mb-1 text-gray-dark-300">No. exterior:</small><p class="bold text-dark">'+taxData.external_number+'</p></div>';
    var internal_number = (taxData.external_number != null)? "N/A" : taxData.internal_number;
    text += '<div class="col-xl-6"><small class="mb-1 text-gray-dark-300">No. interior:</small><p class="bold text-dark">'+internal_number+'</p></div>';
    text += '<div class="col-xl-6"><small class="mb-1 text-gray-dark-300">Colonia:</small><p class="bold text-dark">'+taxData.colony+'</p></div>';
    text += '<div class="col-xl-6"><small class="mb-1 text-gray-dark-300">Código postal:</small><p class="bold text-dark">'+taxData.postal_code+'</p></div>';
    text += '<div class="col-xl-6"><small class="mb-1 text-gray-dark-300">Ciudad:</small><p class="bold text-dark">'+taxData.city+'</p></div>';
    text += '<div class="col-xl-6"><small class="mb-1 text-gray-dark-300">Estado:</small><p class="bold text-dark">'+taxData.state+'</p></div>';
    $('#modalInformation .modal-body .row').html(text);
    $('#modalInformation').modal('show');
}

function viewBankData(bankData) {
    $('#modalInformation #modalInformationLabel').text('Datos bancarios');
    var text = '';
    text += '<div class="col-xl-6"><small class="mb-1 text-gray-dark-300">Banco:</small><p class="bold text-dark">'+bankData.bank+'</p></div>';
    text += '<div class="col-xl-6"><small class="mb-1 text-gray-dark-300">Clave:</small><p class="bold text-dark">'+bankData.key+'</p></div>';
    text += '<div class="col-xl-6"><small class="mb-1 text-gray-dark-300">No. de cuenta:</small><p class="bold text-dark">'+bankData.number_account+'</p></div>';
    text += '<div class="col-xl-6"><small class="mb-1 text-gray-dark-300">Propietario de la cuenta:</small><p class="bold text-dark">'+bankData.name_propietary+'</p></div>';
    $('#modalInformation .modal-body .row').html(text);
    $('#modalInformation').modal('show');
}

$('#formContract').submit((e)=> {
    e.preventDefault();
    var formData = new FormData(document.getElementById("formContract"));
    formData.append('file', $('#fileContract')[0].files[0]);
    formData.append('user_id', $('#user_id').val());
    formData.append('_token', $("meta[name='csrf-token']").attr("content"));
    $.ajax({
        url: $('#URL').val()+'uploadContract',
        method: 'post',
        enctype: 'multipart/form-data',
        processData: false,
        contentType: false,
        cache: false,
        data: formData,
        success: (res)=> {
            if (res.status == true) {
                tableContracts();
                $('#modalContract').modal('hide');
                $('#user_id').val('');
                $('#fileContract').val('');
                Swal.fire({
                    position: 'bottom-end',
                    icon: 'success',
                    text: 'Archivo guardado exitosamente',
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