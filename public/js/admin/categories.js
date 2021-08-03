$(document).ready(()=> {
    tableCategories();
});

$('#formCategory').submit((e)=> {
    e.preventDefault();
    $.ajax({
        url: $('#URL').val()+'actionsCategories',
        method: 'post',
        data: $("#formCategory").serialize(),
        success: (res)=> {
            $('#formCategory .form-control').val('');
            tableCategories();
        },
        error: ()=> {

        }
    });
});

function tableCategories() {
    $('#categories').dataTable().fnDestroy();
    var table = $('#categories').DataTable({
        "order": [[1, 'asc']],
        "lengthMenu": [[25, 50, 75, 100, -1], [25, 50, 75, 100, "Todos"]],
        "ajax": {
            url: $('#URL').val()+'extractCategories',
            method: 'post',
            data: {
                "_token": $("meta[name='csrf-token']").attr("content"),
            }
        },
        "columns": [
            {data: 'id', "width": "1%", "className": "text-center text-dark"},
            {data: 'name', "width": "5%", "className": "text-center text-dark"},
            {
                "width": "1%",
                "className": "text-dark text-center",
                "render": (data, type, row, meta) => {
                    var buttons = '<span class="btn btn-success btn-sm buttons-small pointer" data-toggle="tooltip" data-placement="top" title="Editar categoria" onclick="editCategory('+row.id+', \''+row.name+'\')"><i class="fas fa-edit"></i></span>';
                    buttons += '<span class="btn btn-danger btn-sm buttons-small pointer ml-2" data-toggle="tooltip" data-placement="top" title="Eliminar categoria" onclick="deleteCategory('+row.id+')"><i class="fas fa-trash-alt"></i></span>';
                    $('[data-toggle="tooltip"]').tooltip();
                    return buttons;
                }
            },
        ],
        language: {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        }
    });
}

function editCategory(id_category, name) {
    Swal.fire({
        title: 'Editar categoría',
        input: 'text',
        inputAttributes: {
          autocapitalize: 'off'
        },
        showCancelButton: true,
        confirmButtonText: 'Editar',
        cancelButtonText: 'Cancelar',
        reverseButtons: true,
        inputValue : name,
    }).then((result) => {
        if (result.value) {
            $.ajax({
                url: $('#URL').val()+'actionsCategories',
                method: 'post',
                data: {
                    "_token": $("meta[name='csrf-token']").attr("content"),
                    id_category: id_category,
                    name: result.value
                },
                success: (res)=> {
                    if(res.status == true) {
                        tableCategories();
                    }
                },
                error: ()=> {
                    console.log('ERROR');
                }
            })
        }
    })
}

function deleteCategory(id_category) {
    Swal.fire({
        title: 'Atención',
        text: "¿Seguro que desea eliminar la categoría?",
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
                url: $('#URL').val()+'actionsCategories',
                method: 'post',
                data: {
                    "_token": $("meta[name='csrf-token']").attr("content"),
                    id_category: id_category
                },
                success: (res) => {
                    tableCategories();
                },
                error: () => {
                    console.log('ERROR');
                }
            });
        }
    })
}