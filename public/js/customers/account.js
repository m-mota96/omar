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

$('#formTaxData').submit((e)=> {
    e.preventDefault();
    $.ajax({
        url: $('#URL').val()+'saveTaxData',
        method: 'post',
        data: {
            "_token": $("meta[name='csrf-token']").attr("content"),
            legal_representative: $('#legal_representative').val(),
            business_name: $('#business_name').val(),
            rfc: $('#rfc').val(),
            address: $('#address').val(),
            external_number: $('#external_number').val(),
            internal_number: $('#internal_number').val(),
            colony: $('#colony').val(),
            postal_code: $('#postal_code').val(),
            state: $('#state').val(),
            city: $('#city').val()
        },
        success: (res)=> {
            Swal.fire({
                icon: 'success',
                title: 'Correcto',
                text: 'La información fiscal se guardo con exito'
            });
            var content = '';
            content += '<div class="row">';
                content += '<div class="col-xl-12 mt-2">';
                    content += '<p class="mb-1 bold">INFORMACIÓN FISCAL</p>';
                content += '</div>';
                content += '<div class="col-xl-4">';
                    content += '<small class="mb-1 text-gray-dark-300">Representante legal:</small>';
                    content += '<p class="bold">'+res.taxData.legal_representative+'</p>';
                content += '</div>';
                content += '<div class="col-xl-4">';
                    content += '<small class="mb-1 text-gray-dark-300">Razón social:</small>';
                    content += '<p class="bold">'+res.taxData.business_name+'</p>';
                content += '</div>';
                content += '<div class="col-xl-4">';
                    content += '<small class="mb-1 text-gray-dark-300">RFC:</small>';
                    content += '<p class="bold">'+res.taxData.rfc+'</p>';
                content += '</div>';
                content += '<div class="col-xl-4">';
                    content += '<small class="mb-1 text-gray-dark-300">Calle:</small>';
                    content += '<p class="bold">'+res.taxData.address+'</p>';
                content += '</div>';
                content += '<div class="col-xl-4">';
                    content += '<small class="mb-1 text-gray-dark-300">Número exterior:</small>';
                    content += '<p class="bold">'+res.taxData.external_number+'</p>';
                content += '</div>';
                content += '<div class="col-xl-4">';
                    content += '<small class="mb-1 text-gray-dark-300">Número interior:</small>';
                    if (res.taxData.internal_number != null) {
                        content += '<p class="bold">'+res.taxData.internal_number+'</p>';
                    } else {
                        content += '<p class="bold">N/A</p>';
                    }
                content += '</div>';
                content += '<div class="col-xl-4">';
                    content += '<small class="mb-1 text-gray-dark-300">Colonia:</small>';
                    content += '<p class="bold">'+res.taxData.colony+'</p>';
                content += '</div>';
                content += '<div class="col-xl-4">';
                    content += '<small class="mb-1 text-gray-dark-300">Código postal:</small>';
                    content += '<p class="bold">'+res.taxData.postal_code+'</p>';
                content += '</div>';
                content += '<div class="col-xl-4">';
                    content += '<small class="mb-1 text-gray-dark-300">Estado:</small>';
                    content += '<p class="bold">'+res.taxData.state+'</p>';
                content += '</div>';
                content += '<div class="col-xl-4">';
                    content += '<small class="mb-1 text-gray-dark-300">Ciudad:</small>';
                    content += '<p class="bold">'+res.taxData.city+'</p>';
                content += '</div>';
            content += '</div>';
            $('#contentTaxData').html(content);
        },
        error: ()=> {
            console.log('ERROR');
        }
    });
});

$('#formBankData').submit((e)=> {
    e.preventDefault();
    $.ajax({
        url: $('#URL').val()+'saveBankData',
        method: 'post',
        data: {
            "_token": $("meta[name='csrf-token']").attr("content"),
            bank: $('#bank').val(),
            name_propietary: $('#name_propietary').val(),
            key: $('#key').val(),
            number_account: $('#number_account').val()
        },
        success: (res)=> {
            Swal.fire({
                icon: 'success',
                title: 'Correcto',
                text: 'La información bancaria se guardo con exito'
            });
            var content = '';
            content += '<div class="row">';
                content += '<div class="col-xl-12 mt-2">';
                    content += '<p class="mb-1 bold">DATOS BANCARIOS</p>';
                content += '</div>';
                content += '<div class="col-xl-4">';
                    content += '<small class="mb-1 text-gray-dark-300">Banco:</small>';
                    content += '<p class="bold">'+res.bankData.bank+'</p>';
                content += '</div>';
                content += '<div class="col-xl-4">';
                    content += '<small class="mb-1 text-gray-dark-300">Clave:</small>';
                    content += '<p class="bold">'+res.bankData.key+'</p>';
                content += '</div>';
                content += '<div class="col-xl-4">';
                    content += '<small class="mb-1 text-gray-dark-300">Número de cuenta:</small>';
                    content += '<p class="bold">'+res.bankData.number_account+'</p>';
                content += '</div>';
                content += '<div class="col-xl-4">';
                    content += '<small class="mb-1 text-gray-dark-300">Nombre del tarjetahabiente:</small>';
                    content += '<p class="bold">'+res.bankData.name_propietary+'</p>';
                content += '</div>';
            content += '</div>';
            $('#contentBankData').html(content);
        },
        error: ()=> {
            console.log('ERROR');
        }
    });
});

function processDocuments(docs) {
    // if (docs.length == 0) {
        var acta = '';
        var documents = ['Acta constitutiva', 'Comprobante de domicilio', 'Comprobante bancario', 'Identificación del representante legal'];
        var types = ['acta', 'comprobante_domicilio', 'comprobante_bancario', 'identificacion'];
        for (var i = 0; i < 4; i++) {
            if (docs[i] != undefined) {
                acta += '<div class="col-xl-6 pr-0">';
                    acta += '<div class="row w-100">';
                    if (docs[i].status == 2) {
                        acta += '<div class="col-xl-12 upload-documents pt-3 pb-3 mb-4" id="upload'+(i+1)+'">';
                    } else {
                        acta += '<div class="col-xl-12 upload-documents pt-3 pb-3 mb-4">';
                    }
                            acta += '<div class="row dz-message needsclick">';
                                acta += '<div class="col-xl-1" id="icon_'+(i+1)+'">';
                                    if (docs[i].status == 3) { // DOCUMENTO CORRECTO
                                        acta += '<h2 class="text-gray-dark-300"><i class="fas fa-check-square text-green"></i></h2>';
                                    } else if(docs[i].status == 2) { // DOCUMENTO RECHAZADO
                                        acta += '<h2 class="text-gray-dark-300"><i class="fas fa-window-close text-red"></i></h2>';
                                    } else if(docs[i].status == 1) { // DOCUMENTO EN REVISION
                                        acta += '<h2 class="text-gray-dark-300"><i class="fas fa-exclamation-circle text-orange"></i></h2>';
                                    }
                                acta += '</div>';
                                acta += '<div class="col-xl-11 pb-4">';
                                    // acta += '<i class="fa fa-upload fa-2x valign "></i><br>';
                                    acta += '<h4 class="text-gray-dark-300 mb-3">'+documents[i]+'</h4>';
                                    acta += '<small class="text-gray-dark-300">Máx 50 MB,  .jpeg, .png, .pdf</small><br>';
                                    if (docs[i].status == 3) { // DOCUMENTO CORRECTO
                                        acta += '<small class="mb-4 pointer text-green">Su documento es correcto</small>';
                                    } else if(docs[i].status == 2) { // DOCUMENTO RECHAZADO
                                        acta += '<small class="mb-4 pointer text-red" id="info_'+(i+1)+'">Documento incorrecto <b class="text-blue">haz click en el recuadro</b> <span class="text-dark">para subir uno nuevo</span></small>';
                                    } else if(docs[i].status == 1) { // DOCUMENTO EN REVISION
                                        acta += '<small class="mb-4 pointer text-orange">Su documento se encuentra en revisión</small>';
                                    }
                                acta += '</div>';
                            acta += '</div>';
                            if (docs[i].status == 3 || docs[i].status == 1) {
                                acta += '<small class="col-xl-11 offset-xl-1 pl-0 mb-4 pointer" onclick="viewDocument(\''+docs[i].document+'\')"><b>Ver documento ></b></small><br><br>';
                            } else {
                                acta += '<small class="col-xl-11 offset-xl-1 pl-0 mb-4 pointer" onclick="examples(\''+types[i]+'\')" id="viewDoc_'+(i+1)+'"><b>Ver ejemplo ></b></small><br><br>';
                            }
                        acta += '</div>';
                    acta += '</div>';
                acta += '</div>';
            } else {
                acta += '<div class="col-xl-6 pr-0">';
                    acta += '<div class="row w-100">';
                        acta += '<div class="col-xl-12 upload-documents pt-3 pb-3 mb-4" id="upload'+(i+1)+'">';
                            acta += '<div class="row dz-message needsclick">';
                                acta += '<div class="col-xl-1" id="icon_'+(i+1)+'">';
                                    acta += '<h2 class="text-gray-dark-300"><i class="fas fa-cloud-upload-alt"></i></h2>';
                                acta += '</div>';
                                acta += '<div class="col-xl-11 pb-4">';
                                    // acta += '<i class="fa fa-upload fa-2x valign "></i><br>';
                                    acta += '<h4 class="text-gray-dark-300 mb-3">'+documents[i]+'</h4>';
                                    acta += '<small class="text-gray-dark-300">Máx 50 MB,  .jpeg, .png, .pdf</small><br>';
                                    acta += '<small class="mb-4 pointer" id="info_'+(i+1)+'">Arrastra tu archivo o <b class="text-blue">haz click en el recuadro</b></small>';
                                acta += '</div>';
                            acta += '</div>';
                            acta += '<small class="col-xl-11 offset-xl-1 pl-0 mb-4 pointer" onclick="examples(\''+types[i]+'\')" id="viewDoc_'+(i+1)+'"><b>Ver ejemplo ></b></small><br><br>';
                        acta += '</div>';
                    acta += '</div>';
                acta += '</div>';
            }
        }
        $('#contentDocuments').html(acta);
    // }
    for (var i = 0; i < 4; i++) {
        if (docs[i] == undefined) {
            dropzoneActa(i+1, types[i]);
        } else {
            if (docs[i].status == 2 ) {
                dropzoneActa(i+1, types[i]);
            }
        }
    }
}

function examples(type) {
    // alert(type);
    window.open($('#URL').val()+'media/pdf/documents/examples/'+type+'.pdf', '_blank');
}

function viewDocument(nameDocument) {
    // location.href = $('#URL').val()+'media/pdf/documents/user_1/'+nameDocument;
    window.open($('#URL').val()+'media/pdf/documents/user_'+$('#USER_ID').val()+'/'+nameDocument, '_blank');
}

var myDropzone = '';
function dropzoneActa(numberDocument, type) {
    // console.log(numberDocument);
    $('#upload'+numberDocument).dropzone({
        url: $('#URL').val()+'uploadDocuments',
        method: 'post',
        paramName: 'files', // The name that will be used to transfer the file
        maxFilesize: 50, // MB
        uploadMultiple: false,
        createImageThumbnails: true,
        thumbnailWidth: 400,
        thumbnailMethod: 'contain',
        acceptedFiles: '.pdf',
        // autoProcessQueue: false,
        dataType: 'json',
        accept: function(file, done) {
            $('.dz-success-mark').hide();
            $('.dz-error-mark').hide();
            $('.text-upload').hide();
            done();
        },
        error: function(data, xhr) {
            if(data.size > 51200) {
                this.removeAllFiles();
                Swal.fire({
                    title: 'El pdf debe pesar menos de 50MB.',
                    icon: 'error'
                });
            }
        },
        init: function() {
            // var submitButton = document.querySelector("#save");
            // myDropzone = this;
            // submitButton.addEventListener("click", function() {
            //     myDropzone.processQueue();
            // });
            this.on("sending", function(file, xhr, formData) {
                formData.append("_token", $("meta[name='csrf-token']").attr("content"));
                formData.append("type", type);
            });
            this.on('success', function(file, response) {
                // $('#modalEditImage .text-upload').show();
                // $('#modalEditImage').modal('hide');
                Swal.fire({
                    position: 'bottom-end',
                    icon: 'success',
                    text: 'Archivo enviado exitosamente',
                    showConfirmButton: false,
                    timer: 1500
                });
                // $("#imageEvent").attr("src", $('#URL').val()+"media/events/"+response.event_id+"/"+response.image+"");
                this.removeFile(file);
                $('#info_'+numberDocument).addClass('text-orange');
                $('#info_'+numberDocument).text('Su documento se encuentra en revisión');
                $('#viewDoc_'+numberDocument).html('<b>Ver documento ></b>');
                $("#viewDoc_"+numberDocument).attr("onclick", "viewDocument(\'"+response.nameDocument+"\')");
                $('#icon_'+numberDocument).html('<h2 class="text-gray-dark-300"><i class="fas fa-exclamation-circle text-orange"></i></h2>');
                this.removeEventListeners();
            });
        },
    });
}