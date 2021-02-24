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
            {data: 'id', "width": "5%", "className": "text-center"},
            {data: 'name', "width": "5%", "className": "text-center"},
            {data: 'email', "width": "5%", "className": "text-center"},
            {
                "width": "5%",
                "render": (data, type, row, meta) => {
                    console.log(row.tax_data);
                    var documents = "<span class=\"btn btn-primary btn-sm mb-1 ml-2 pointer\" onclick=\"viewTaxData("+row.tax_data+")\">Ver información</span>";
                    return documents;
                }
            },
        ],
        language: {
            "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
        }
    });
}

function viewTaxData(taxData) {
    console.log(taxData);
}