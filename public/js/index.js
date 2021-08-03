$('.carousel').carousel({
    interval: 6000
});

$('#form-contact').submit((e)=> {
    e.preventDefault();
    $.ajax({
        url: $('#URL').val()+'sendEmailContact',
        method: 'post',
        data: $('#form-contact').serialize(),
        success: (res)=> {
            if (res.status == true) {
                Swal.fire({
                    icon: 'success',
                    text: res.msj,
                    showConfirmButton: true,
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    text: res.msj,
                    showConfirmButton: true,
                });
            }
        },
        error: ()=> {
            
        }
    });
});