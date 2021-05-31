let scanner = new Instascan.Scanner({
    video: document.getElementById('preview'),
    mirror: false
});

scanner.addListener('scan', function (content) {
    $.ajax({
        url: $('#URL').val()+'searchAccess',
        method: 'post',
        data: {
            "_token": $("meta[name='csrf-token']").attr("content"),
            folio: content
        },
        success: function(response) {
            if (response.status == true) {
                $('#folio').text(response.access.folio);
                $('#folioOculto').val(response.access.folio);
                $('#btnValidate').removeClass('hidden');
            } else {
                if (response.error == 'date_not_found') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'La fecha actual no coincide con las fechas del evento',
                    });
                } else if (response.error == 'horary_incorrect') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'La hora actual no esta dentro del rango de horario del turno seleccionado',
                    });
                } else if (response. error == 'access_not_found') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Este código no existe en nuestros registros',
                    });
                } else if (response. error == 'verified') {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Este código ya fue utilizado',
                    });
                }
            }
        },
        error: function() {
            console.log('error');
        },
    });
});

Instascan.Camera.getCameras().then(function (cameras) {
    if (cameras.length > 0) {
        if (cameras.length > 1) {
            scanner.start(cameras[1]);
        } else {
            scanner.start(cameras[0]);
        }
    } else {
        console.error('No cameras found.');
    }
}).catch(function (e) {
    console.error(e);
});

function validateFolio() {
    $.ajax({
        url: $('#URL').val()+'validateFolio',
        method: 'post',
        data: {
            "_token": $("meta[name='csrf-token']").attr("content"),
            folio: $('#folioOculto').val()
        },
        success: function(response) {
            if (response.status == true) {
                $('#folio').text('');
                $('#btnValidate').addClass('hidden');
                Swal.fire({
                    icon: 'success',
                    text: 'El boleto se desactivo correctamente',
                });
            }
        },
        error: function() {
            console.log('error');
        },
    });
}