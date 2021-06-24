$(document).ready(()=> {
    $('[data-toggle="tooltip"]').tooltip();
});

var idDomGlobal = 0;
function openModal(idDom, id = null, title = null, date = null) {
    idDomGlobal = idDom;
    if (id != null) {
        $('#image').prop('required', false);
    } else {
        $('#image').prop('required', true);
    }
    $('#idSlider').val(id);
    $('#title').val(title);
    $('#initial_date').val(date);
    $('#modalSlider').modal('show');
}

function deleteInfo(idDom, id) {
    Swal.fire({
        title: 'Atención',
        text: "¿Seguro que desea eliminar la información?",
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
                url: $('#URL').val()+'deleteInfoSlider',
                method: 'post',
                data: {
                    "_token": $("meta[name='csrf-token']").attr("content"),
                    'id': id
                },
                success: (res)=> {
                    if (res.status == true) {
                        var num = parseInt(idDom) + parseInt(1);
                        $('#image'+idDom).attr('src', $('#URL').val()+'media/general/not_image.png');
                        $('#save'+idDom).attr('onclick', 'openModal('+idDom+')');
                        $('#title'+idDom).text('Imagen '+num);
                        $('#date'+idDom).addClass('text-white');
                        $('#date'+idDom).text('...');
                    }
                },
                error: ()=> {
                    console.log('ERROR');
                }
            });
        }
    });
}

$('#formModalSlider').submit((e)=> {
    e.preventDefault();
    var formData = new FormData(document.getElementById("formModalSlider"));
    formData.append('file', $('#image')[0].files[0]);
    formData.append('title', $('#title').val());
    formData.append('initial_date', $('#initial_date').val());
    formData.append('id', $('#idSlider').val());
    formData.append('_token', $("meta[name='csrf-token']").attr("content"));
    $.ajax({
        url: $('#URL').val()+'saveInfoSlider',
        method: 'post',
        enctype: 'multipart/form-data',
        processData: false,
        contentType: false,
        cache: false,
        data: formData,
        success: (res)=> {
            if (res.status == true) {
                $('#image'+idDomGlobal).attr('src', $('#URL').val()+'media/sliderIndex/'+res.data.image);
                $('#delete'+idDomGlobal).attr('onclick', 'deleteInfo('+idDomGlobal+', '+res.data.id+')');
                $('#title'+idDomGlobal).text(res.data.title);
                $('#date'+idDomGlobal).removeClass('text-white');
                $('#date'+idDomGlobal).text(res.data.date);
                $('#modalSlider .form-control').val("");
                $('#modalSlider .image').val("");
                $('#modalSlider').modal('hide');
            }
        },
        error: ()=> {
            console.log('ERROR');
        }
    });
});