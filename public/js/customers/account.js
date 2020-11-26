$('#editPassword').click(()=> {
    $('#modalResetPassword').modal('show');
});

$('#editProfile').click(()=> {
    $('#modalAccount').modal('show');
});

$('#formResetPassword').submit((e)=> {
    e.preventDefault();
    if ($('#password').val() == $('#passwordConfirm').val()) {
        var regExp = /^(?=\w*\d)(?=\w*[A-Z])(?=\w*[a-z])\S{8,16}$/;
        if (regExp.test($('#password').val())) {
            $('#instructions').addClass('hidden');
            $.ajax({
                url: $('#URL').val()+'resetPassword',
                method: 'post',
                data: {
                    "_token": $("meta[name='csrf-token']").attr("content"),
                    password: $('#password').val(),
                    last_password: $('#lastPassword').val()
                },
                success: (response)=> {
                    if (response.msj == 'password_incorrect' && response.status == false) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'La contraseña actual es incorrecta'
                        });
                    } else if (response.status == true) {
                        $('#modalResetPassword .form-control').val('');
                        $('#modalResetPassword').modal('hide');
                        Swal.fire({
                            icon: 'success',
                            title: 'Correcto',
                            text: 'La contraseña se modificó correctamente'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Lo sentimos ocurrio un error'
                        });
                    }
                },
                error: ()=> {
                    console.log('ERROR');
                }
            });
        } else {
            $('#instructions').removeClass('hidden');
        }
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Las contraseñas no coinciden'
        });
    }
});

$('#formAccount').submit((e)=> {
    e.preventDefault();
    $.ajax({
        url: $('#URL').val()+'editAccount',
        method: 'post',
        data: {
            "_token": $("meta[name='csrf-token']").attr("content"),
            name: $('#name').val(),
            phone: $('#phone').val()
        },
        success: (response)=> {
            if (response.status == true) {
                $('#modalAccount').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Correcto',
                    text: 'Los datos se modificaron correctamente'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Lo sentimos ocurrio un error'
                });
            }
        },
        error: ()=> {
            console.log('ERROR');
        }
    });
});

function processDocuments(docs) {
    if (docs.length == 0) {
        var acta = '';
        var documents = ['Acta constitutiva', 'Comprobante de domicilio', 'Carta del recinto'];
        for (var i = 0; i < 3; i++) {
            acta += '<div class="col-xl-6">';
                acta += '<div class="row w-100">';
                    acta += '<div class="col-xl-12 upload-documents pt-3 pb-3 mb-4" id="upload">';
                        acta += '<div class="row dz-message needsclick">';
                            acta += '<div class="col-xl-1">';
                                acta += '<h2 class="text-gray-dark-300"><i class="fas fa-cloud-upload-alt"></i></h2>';
                            acta += '</div>';
                            acta += '<div class="col-xl-11">';
                                // acta += '<i class="fa fa-upload fa-2x valign "></i><br>';
                                acta += '<h4 class="text-gray-dark-300 mb-3">'+documents[i]+'</h4>';
                                acta += '<small class="text-gray-dark-300">Máx 50 MB,  .jpeg, .png, .pdf</small><br>';
                                acta += '<small class="mb-4 pointer" onclick="alert(\"ENTRE\")"><b>Ver ejemplo ></b></small><br><br>';
                                acta += '<small class="mb-4 pointer">Arrastra tu archivo o <b class="text-blue">haz click en el recuadro</b></small>';
                            acta += '</div>';
                        acta += '</div>';
                    acta += '</div>';
                acta += '</div>';
            acta += '</div>';
        }
        // acta += '<div class="col-xl-6 upload-documents pt-3 pb-3 mb-4" id="upload2">';
        //     acta += '<div class="row dz-message needsclick">';
        //         acta += '<div class="col-xl-1">';
        //             acta += '<h2 class="text-gray-dark-300"><i class="fas fa-cloud-upload-alt"></i></h2>';
        //         acta += '</div>';
        //         acta += '<div class="col-xl-11">';
        //             // acta += '<i class="fa fa-upload fa-2x valign "></i><br>';
        //             acta += '<h4 class="text-gray-dark-300 mb-3">Comprobante de domicilio</h4>';
        //             acta += '<small class="text-gray-dark-300">Máx 50 MB,  .jpeg, .png, .pdf</small><br>';
        //             acta += '<small class="mb-4 pointer"><b>Ver ejemplo ></b></small><br><br>';
        //             acta += '<small class="mb-4 pointer">Arrastra tu archivo o <b class="text-blue">haz click en el recuadro</b></small>';
        //         acta += '</div>';
        //     acta += '</div>';
        // acta += '</div>';
        $('#contentDocuments').html(acta);
    }
    dropzoneActa();
}

var myDropzone = '';
function dropzoneActa() {
    $('#upload').dropzone({
        url: $('#URL').val()+'uploadImage',
        method: 'post',
        paramName: 'files', // The name that will be used to transfer the file
        maxFilesize: 1, // MB
        uploadMultiple: false,
        createImageThumbnails: true,
        thumbnailWidth: 400,
        thumbnailMethod: 'contain',
        acceptedFiles: '.jpg, .png',
        autoProcessQueue: false,
        dataType: 'json',
        accept: function(file, done) {
            $('#modalEditImage .dz-success-mark').hide();
            $('#modalEditImage .dz-error-mark').hide();
            $('#modalEditImage .text-upload').hide();
            done();
        },
        error: function(data, xhr) {
            if(data.size > 1024) {
                this.removeAllFiles();
                Swal.fire({
                    title: 'La imágen debe pesar menos de 1MB.',
                    icon: 'error'
                });
            }
        },
        init: function() {
            // var submitButton = document.querySelector("#save");
            myDropzone = this;
            // submitButton.addEventListener("click", function() {
            //     myDropzone.processQueue();
            // });
            this.on("sending", function(file, xhr, formData) {
                formData.append("_token", $("meta[name='csrf-token']").attr("content"));
                formData.append("event_id", $("#eventId").val());
                formData.append("type", "index");
            });
            this.on('success', function(file, response) {
                $('#modalEditImage .text-upload').show();
                $('#modalEditImage').modal('hide');
                Swal.fire({
                    position: 'bottom-end',
                    icon: 'success',
                    text: 'Imagen guardada exitosamente',
                    showConfirmButton: false,
                    timer: 1500
                });
                $("#imageEvent").attr("src", $('#URL').val()+"media/events/"+response.event_id+"/"+response.image+"");
                myDropzone.removeFile(file);
            });
        },
    });
}