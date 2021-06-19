$(document).ready(()=> {
    addEvent();
    // var cont = 1;
    // $('.upload').each(function(e) {
    //     createDropzone($(this).attr('id'), cont);
    //     cont++;
    // });
});

google.maps.event.addDomListener(window, 'load', function() {
    
    const myLang = {lat: 20.653056, lng: -103.391389}
    // search.bindTo('bounds', )
    const options = {
        center: myLang,
        zoom: 15
    }

    var map = document.getElementById('map');

    const mapa = new google.maps.Map(map, options);

    const marcador = new google.maps.Marker({
        position: myLang,
        map: mapa
    });

    var informacion = new google.maps.InfoWindow();

    marcador.addListener('click', function() {
        informacion.open(mapa, marcador);
    });

    var autocomplete = document.getElementById('addressEvent');
    const search = new google.maps.places.Autocomplete(autocomplete);

    search.addListener('place_changed', function() {
        informacion.close();
        marcador.setVisible(false);
        var place = search.getPlace();
        if(!place.geometry.viewport) {
            Swal.fire({
                position: 'bottom-end',
                icon: 'error',
                text: 'No se encontró la dirección',
                showConfirmButton: false,
                timer: 1500
            });
            return;
        }

        if(place.geometry.viewport) {
            mapa.fitBounds(place.geometry.viewport);
        } else {
            mapa.setCenter(place.geometry.location);
            mapa.setZoom(16);
        }

        $('#latitude').val(place.geometry.location.lat());
        $('#longitude').val(place.geometry.location.lng());

        marcador.setPosition(place.geometry.location);
        marcador.setVisible(true);
    });
});

function addEvent() {
    $('.btnEditLogo').unbind();
    $('#btnDeleteLogo').unbind();
    $('#moreDays').unbind();
    $('.btnEditLogo').click(()=> {
        $('#modalEditLogo').modal('show');
    });
    $('#btnDeleteLogo').click(()=> {
        $.ajax({
            method: 'POST',
            url: $('#URL').val()+'deleteLogo',
            dataType: 'json',
            async: false,
            data: {
                "_token": $("meta[name='csrf-token']").attr("content"),
                event_id: $("#eventId").val()
            },
            success: (response)=> {
                if(response.status == true) {
                    var logo = '';
                    logo += '<div class="col-xl-12 text-center border-logo-edit h-100 pt-3">';
                        logo += '<br>';
                        logo += '<h4 class="bold"><a class="text-gray-dark-400 pointer btnEditLogo">Agregar Logo</a></h4>';
                    logo += '</div>';
                    $('#contentLogo').html(logo);
                    addEvent();
                } else {
                    Swal.fire({
                        position: 'bottom-end',
                        icon: 'error',
                        text: 'Error al eliminar el logo',
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
    $('#moreDays').click(function() {
        createDates($(this).attr('data-finalId'));
    });
}

$('#btnEditImage').click(()=> {
    $('#modalEditImage').modal('show');
});

$('#deletePreview').click(()=> {
    $('#modalEditImage .text-upload').show();
    myDropzone.removeAllFiles();
});

$('#deletePreviewLogo').click(()=> {
    $('#modalEditLogo .text-upload').show();
    myDropzone2.removeAllFiles();
});

$('#formEditImage').submit((e)=> {
    e.preventDefault();
    myDropzone.processQueue();
});

$('#formEditLogo').submit((e)=> {
    e.preventDefault();
    myDropzone2.processQueue();
});

$('#formEditLocation').submit((e)=> {
    e.preventDefault();
    $.ajax({
        method: 'POST',
        url: $('#URL').val()+'addLocation',
        dataType: 'json',
        async: false,
        data: {
            "_token": $("meta[name='csrf-token']").attr("content"),
            event_id: $("#eventId").val(),
            location: $('#locationEvent').val(),
            address: $('#addressEvent').val(),
            latitude: $('#latitude').val(),
            longitude: $('#longitude').val()
        },
        success: (response)=> {
            if(response.status == true) {
                $('#content-map').addClass('content-map');
                const myLang2 = {lat: parseFloat($('#latitude').val()), lng: parseFloat($('#longitude').val())}
                
                const options2 = {
                    center: myLang2,
                    zoom: 14
                }

                $('#content-map').html('');
                var map2 = document.getElementById('content-map');

                const mapa2 = new google.maps.Map(map2, options2);

                const marcador2 = new google.maps.Marker({
                    position: myLang2,
                    map: mapa2
                });
                $('#modalEditLocation').modal('hide');
                Swal.fire({
                    position: 'bottom-end',
                    icon: 'success',
                    text: 'La dirección se guardo correctamente',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                Swal.fire({
                    position: 'bottom-end',
                    icon: 'error',
                    text: 'Lo sentimos ocurrio un error',
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
$('#formEditNameWebsite').submit((e)=> {
    e.preventDefault();
    $.ajax({
        method: 'POST',
        url: $('#URL').val()+'updateNameWebsite',
        dataType: 'json',
        async: false,
        data: {
            "_token": $("meta[name='csrf-token']").attr("content"),
            event_id: $("#eventId").val(),
            name_event: $('#nameEvent').val(),
            website: $('#website').val(),
            quantity: $('#quantity').val()
        },
        success: (response)=> {
            if(response.status == true) {
                $('#titleEvent').text($('#nameEvent').val());
                $('#viewWebsite').attr('href', $('#URL').val()+response.website);
                $('#topbarWebsite').attr('href', $('#URL').val()+response.website);
                $('#topbarWebsite').html('<span class="mr-2 d-none d-lg-inline text-white"><i class="fas fa-link"></i> '+$('#URL').val()+response.website+'</span>');
                $('#modalEditNameWebsite #txt_name_success').css('display', 'none');
                $('#modalEditNameWebsite #txt_website_success').css('display', 'none');
                $('#modalEditNameWebsite').modal('hide');
                Swal.fire({
                    position: 'bottom-end',
                    icon: 'success',
                    text: 'Datos guardados correctamente',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                Swal.fire({
                    position: 'bottom-end',
                    icon: 'error',
                    text: 'Lo sentimos ocurrio un error',
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

$("#modalEditImage").on("hidden.bs.modal", function () {
    $('.text-upload').show();
    myDropzone.removeAllFiles();
});

$('#btnNameAndSite').click(()=> {
    $('#modalEditNameWebsite').modal('show');
});
$('#nameEvent').keyup(function() {
    var name = $(this).val().toLowerCase();
    var nameFilter = filterNonAphaNumeric(name);
    $('#website').val(nameFilter);
    checkEventAvailable(nameFilter);
});
$('#website').keyup(function() {
    var name = $(this).val().toLowerCase();
    var nameFilter = filterNonAphaNumeric(name);
    $('#website').val(nameFilter);
    checkEventAvailable(nameFilter);
});
$('#btnEditDescription').click(function() {
    $('#modalEditDescription').modal('show');
});

var ths = this;
var categories=[];
$('#btnEditCategory').click(function() {
    if(ths.categories.length == 0){
        $.ajax({
            url:$('#URL').val()+'getCategories',
            method:"POST",
            async: false,
            data:{
                "_token": $("meta[name='csrf-token']").attr("content"),
            },
            dataType: 'json',
            success:(response)=>{
                ths.categories=response.categories;
                console.log(ths.categories);
                ths.categories.forEach( category =>{
                    $('#categorySelect').append('<option value="'+category.id+'" >'+category.name+'</option>');
                });
                
            },
            error:()=>{
                console.log("ERROR");
            }
        });
    }
    $('#modalEditCategory').modal('show');
    

});

$('#btnEditDates').click(function() {
    $('#modalEditDates').modal('show');
});
$('#btnEditAddress').click(function() {
    $('#modalEditLocation').modal('show');
});
$('#btnAddContact').click(function() {
    $('#modalAddContact').modal('show');
});
$('#formAddContact').submit((e)=> {
    e.preventDefault();
    $.ajax({
        method: 'POST',
        url: $('#URL').val()+'addContact',
        dataType: 'json',
        async: false,
        data: {
            "_token": $("meta[name='csrf-token']").attr("content"),
            event_id: $("#eventId").val(),
            email: $('#emailContact').val(),
            phone: $('#phoneContact').val(),
            twitter: $('#twitterContact').val(),
            facebook: $('#facebookContact').val(),
            instagram: $('#instagramContact').val(),
            website: $('#websiteContact').val()
        },
        success: (response)=> {
            if(response.status == true) {
                $('#modalAddContact').modal('hide');
                var contact = '';
                if ($('#emailContact').val() != '') {
                    contact += '<a class="text-dark font-small"><i class="fas fa-envelope"></i> '+$('#emailContact').val()+'</a><br>';
                }
                if ($('#phoneContact').val() != '') {
                    contact += '<a class="text-dark font-small"><i class="fas fa-phone"></i> '+$('#phoneContact').val()+'</a><br>';
                }
                if ($('#twitterContact').val() != '') {
                    contact += '<a class="text-dark font-small" href="https://twitter.com/'+$('#twitterContact').val()+'" target="_blank"><i class="fab fa-twitter"></i> @'+$('#twitterContact').val()+'</a><br>';
                }
                if ($('#facebookContact').val() != '') {
                    contact += '<a class="text-dark font-small" href="'+$('#facebookContact').val()+'" target="_blank"><i class="fab fa-facebook-f"></i> Facebook</a><br>';
                }
                if ($('#instagramContact').val() != '') {
                    contact += '<a class="text-dark font-small" href="'+$('#instagramContact').val()+'" target="_blank"><i class="fab fa-instagram"></i> Instagram</a><br>';
                }
                if ($('#websiteContact').val() != '') {
                    contact += '<a class="text-dark font-small" href="'+$('#websiteContact').val()+'" target="_blank"><i class="fas fa-link"></i> '+$('#websiteContact').val()+'</a>';
                }
                $('#content-contact').html(contact);
                Swal.fire({
                    position: 'bottom-end',
                    icon: 'success',
                    text: 'Datos de contacto guardados correctamente',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                Swal.fire({
                    position: 'bottom-end',
                    icon: 'error',
                    text: 'Lo sentimos ocurrio un error',
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
$('#formEditDescription').submit((e)=> {
    e.preventDefault();
    $.ajax({
        method: 'POST',
        url: $('#URL').val()+'editDescription',
        dataType: 'json',
        async: false,
        data: {
            "_token": $("meta[name='csrf-token']").attr("content"),
            event_id: $("#eventId").val(),
            description: $('#description').val()
        },
        success: (response)=> {
            if(response.status == true) {
                $('#modalEditDescription').modal('hide');
                $('#content-description').text($('#description').val());
                Swal.fire({
                    position: 'bottom-end',
                    icon: 'success',
                    text: 'La descripción se modifico correctamente',
                    showConfirmButton: false,
                    timer: 1500
                });
            } else {
                Swal.fire({
                    position: 'bottom-end',
                    icon: 'error',
                    text: 'Lo sentimos ocurrio un error',
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

$('#formEditCategory').submit((e)=> {
    e.preventDefault();

    if($('#categorySelect option:selected').val() > 0){
        $.ajax({
            method: 'POST',
            url: $('#URL').val()+'editCategory',
            dataType: 'json',
            async: false,
            data: {
                "_token": $("meta[name='csrf-token']").attr("content"),
                event_id: $("#eventId").val(),
                category_id: $('#categorySelect').val()
            },
            success: (response)=> {
                if(response.status == true) {
                    $('#modalEditCategory').modal('hide');
                    $('#content-category').text($('#categorySelect option:selected').text());
                    Swal.fire({
                        position: 'bottom-end',
                        icon: 'success',
                        text: 'La categoría se modifico correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    Swal.fire({
                        position: 'bottom-end',
                        icon: 'error',
                        text: 'Lo sentimos ocurrio un error',
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

$('#formEditDates').submit((e)=> {
    e.preventDefault();
    var dates = [];
    var initial_times = [];
    var final_times = [];
    var ban = true;
    for (var i = 0; i < daysEvent; i++) {
        dates[i] = $('#date_'+i).val();
        initial_times[i] = $('#initial_hour_'+i).val()+':'+$('#initial_minute_'+i).val();
        final_times[i] = $('#final_hour_'+i).val()+':'+$('#final_minute_'+i).val();
        if (parseInt($('#initial_hour_'+i).val()) > parseInt($('#final_hour_'+i).val())) {
            $('#initial_hour_'+i).addClass('border border-danger');
            $('#initial_minute_'+i).addClass('border border-danger');
            $('#final_hour_'+i).addClass('border border-danger');
            $('#final_minute_'+i).addClass('border border-danger');
            ban = false;
        }
    }
    if (ban == true) {
        $.ajax({
            method: 'POST',
            url: $('#URL').val()+'editDates',
            dataType: 'json',
            async: false,
            data: {
                "_token": $("meta[name='csrf-token']").attr("content"),
                event_id: $("#eventId").val(),
                dates: dates,
                initial_times: initial_times,
                final_times: final_times
            },
            success: (response)=> {
                if(response.status == true) {
                    $('#modalEditDates').modal('hide');
                    $('#content-initial_date').text(response.initial_date);
                    $('#content-final_date').text(response.final_date);
                    Swal.fire({
                        position: 'bottom-end',
                        icon: 'success',
                        text: 'Las fechas se modificaron correctamente',
                        showConfirmButton: false,
                        timer: 1500
                    });
                } else {
                    Swal.fire({
                        position: 'bottom-end',
                        icon: 'error',
                        text: 'Lo sentimos ocurrio un error',
                        showConfirmButton: false,
                        timer: 1500
                    });
                }
            },
            error: ()=> {
                console.log('ERROR');
            }
        });
    } else {
        $('#submitEditDates').attr('disabled', true);
    }
});

var myDropzone = '';
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

var myDropzone2 = '';
$('#upload2').dropzone({
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
        $('#modalEditLogo .dz-success-mark').hide();
        $('#modalEditLogo .dz-error-mark').hide();
        $('#modalEditLogo .text-upload').hide();
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
        myDropzone2 = this;
        // submitButton.addEventListener("click", function() {
        //     myDropzone.processQueue();
        // });
        this.on("sending", function(file, xhr, formData) {
            formData.append("_token", $("meta[name='csrf-token']").attr("content"));
            formData.append("event_id", $("#eventId").val());
            formData.append("type", "logo");
        });
        this.on('success', function(file, response) {
            $('#modalEditLogo .text-upload').show();
            $('#modalEditLogo').modal('hide');
            Swal.fire({
                position: 'bottom-end',
                icon: 'success',
                text: 'Imagen guardada exitosamente',
                showConfirmButton: false,
                timer: 1500
            });
            var logo = '';
            $("#logoEvent").attr("src", $('#URL').val()+"media/events/"+response.event_id+"/"+response.image+"");
            logo += '<div class="col-xl-12 text-center h-100 pt-3">';
                logo += '<img class="w-100 h-100 p-a logotype" src="'+$("#URL").val()+'media/events/'+response.event_id+'/'+response.image+'">';
            logo += '</div>';
            logo += '<span class="bg-gray-dark-900 text-white p-1 p-a font-small text-right btnEditLogo editLogo pointer"><i class="fas fa-pen"></i> &nbsp;Editar logo</span>';
            logo += '<span class="btn btn-danger p-a pt-1 pb-1 pr-2 pl-2 font-small btnDeleteLogo" id="btnDeleteLogo"><i class="fas fa-trash-alt"></i></span>';
            $('#contentLogo').html(logo);
            myDropzone2.removeFile(file);
            addEvent();
        });
    },
});

function filterNonAphaNumeric(str) {
    let code, i, len, result='';
    for (i = 0, len = str.length; i < len; i++) {
        code = str.charCodeAt(i);
        if ((code > 47 && code < 58) || // numeric (0-9)
            (code > 64 && code < 91) || // upper alpha (A-Z)
            (code > 96 && code < 123)) { // lower alpha (a-z)
                result += str.charAt(i);
        }
    }
    return result;
}

function checkEventAvailable() {
    if($('#nameEvent').val().length > 3) {
        $.ajax({
            method: 'POST',
            url: $('#URL').val()+'checkEvent',
            dataType: 'json',
            async: false,
            data: {
                "_token": $("meta[name='csrf-token']").attr("content"),
                name_event: $('#nameEvent').val(),
                website: $('#website').val()
            },
            success: (response)=> {
                var statusName = true;
                var statusWebsite = true;
                if(response.name_available != true) {
                    $('#modalEditNameWebsite #txt_name_alert').css('display', 'unset');
                    $('#modalEditNameWebsite #txt_name_success').css('display', 'none');
                    statusName = false;
                } else {
                    $('#modalEditNameWebsite #txt_name_alert').css('display', 'none');
                    $('#modalEditNameWebsite #txt_name_success').css('display', 'unset');
                    statusName = true;
                }
                if(response.website_available != true) {
                    $('#modalEditNameWebsite #txt_website_alert').css('display', 'unset');
                    $('#modalEditNameWebsite #txt_website_success').css('display', 'none');
                    statusWebsite = false;
                } else {
                    $('#modalEditNameWebsite #txt_website_alert').css('display', 'none');
                    $('#modalEditNameWebsite #txt_website_success').css('display', 'unset');
                    statusWebsite = true;
                }
                if(statusName == true && statusWebsite == true) {
                    $('#modalEditNameWebsite #submitEditNameWebsite').attr('disabled', false);
                    // createEvent();
                } else {
                    $('#modalEditNameWebsite #submitEditNameWebsite').attr('disabled', true);
                }
            },
            error: ()=> {
                console.log('ERROR');
            }
        });
    }
}

$('#initial_date').change(function() {
    calculateDates();
});

$('#final_date').change(function() {
    calculateDates();
});

$('#indicatorSchedule').click(function() {
    if ($(this).is(':checked')) {
        chargeSchedules();
    } else {
        $('#contentSchedules').remove();
        calculateDates();
    }
});


var daysEvent = 0;
function calculateDates() {
    var initial_date = $("#initial_date").val();
    var final_date = $("#final_date").val();
    var validateSchedules = true;
    if (initial_date != '' && final_date != '') {
        if (initial_date <= final_date) {
            $('#incorrectDates').addClass('hidden');
            if ($('#indicatorSchedule').is(':checked') == true) {
                var scheduleStart = $('#initialScheduleHour').val()+':'+$('#initialScheduleMinute').val();
                var scheduleEnd = $('#finalScheduleHour').val()+':'+$('#finalScheduleMinute').val();
                if (scheduleStart > scheduleEnd) {
                    validateSchedules = false;
                }
            }
            if (validateSchedules == true) {
                $('#submitEditDates').attr('disabled', false);
                $('#incorrectSchedules').addClass('hidden');
                var dateStart = new Date(initial_date);
                var dateEnd    = new Date(final_date);
                var finalId = 0;
                $('#modalEditDates #divDates').html('');
                while(dateEnd.getTime() >= dateStart.getTime()) {
                    dateStart.setDate(dateStart.getDate() + 1);
                    var month = dateStart.getMonth() + 1;
                    var day = dateStart.getDate();
                    if (month < 10) {
                        month = '0' + month;
                    }
                    if (day < 10) {
                        day = '0' + day;
                    }
                    var dateParse = dateStart.getFullYear() + '-' + month + '-' + day;
                    createDates(finalId, dateParse, $('#indicatorSchedule').is(':checked'));
                    finalId++;
                    daysEvent++;
                }
            } else {
                $('#incorrectSchedules').removeClass('hidden');
                $('#submitEditDates').attr('disabled', true);
            }
        } else {
            $('#incorrectDates').removeClass('hidden');
            $('#submitEditDates').attr('disabled', true);
        }
    }
}

function createDates(finalId, dateValue, indicatorSchedule) {
    var options = '';
    options += '<label id="infoDay-'+finalId+'">Día '+(parseInt(finalId)+1)+':</label>';
    options += '<div class="input-group mb-2" id="groupDate-'+finalId+'">';
        options += '<input class="form-control inputs-dates-create-event bg-white" type="date" id="date_'+finalId+'" value="'+dateValue+'" required disabled>';
        options += '<div class="input-group-prepend">';
            options += '<div class="input-group-text bold"> De:</div>';
        options += '</div>';
        options += '<select class="form-control" id="initial_hour_'+finalId+'" onchange="checkSchedules()">';
            for (var j = 0; j < 24; j++) {
                if (j < 10) {
                    options += '<option value="0'+j+'">0'+j+'</option>';
                } else {
                    options += '<option value="'+j+'">'+j+'</option>';
                }
            }
        options += '</select>';
        options += '<div class="input-group-prepend">';
            options += '<div class="input-group-text">:</div>';
        options += '</div>';
        options += '<select class="form-control" id="initial_minute_'+finalId+'" onchange="checkSchedules()">';
            options += '<option value="00">00</option>';
            options += '<option value="15">15</option>';
            options += '<option value="30">30</option>';
            options += '<option value="45">45</option>';
        options += '</select>';
        options += '<div class="input-group-prepend">';
            options += '<div class="input-group-text bold">a: </div>';
        options += '</div>';
        options += '<select class="form-control" id="final_hour_'+finalId+'" onchange="checkSchedules()">';
            for (var j = 0; j < 24; j++) {
                if (j < 10) {
                    options += '<option value="0'+j+'">0'+j+'</option>';
                } else {
                    options += '<option value="'+j+'">'+j+'</option>';
                }
            }
        options += '</select>';
        options += '<div class="input-group-prepend">';
            options += '<div class="input-group-text">:</div>';
        options += '</div>';
        options += '<select class="form-control" id="final_minute_'+finalId+'" onchange="checkSchedules()">';
            options += '<option value="00">00</option>';
            options += '<option value="15">15</option>';
            options += '<option value="30">30</option>';
            options += '<option value="45">45</option>';
        options += '</select>';
        // options += '<span class="btn btn-danger ml-3" onclick="deleteDate('+finalId+')"><i class="fas fa-trash-alt"></i></span>';
    options += '</div>';
    $('#modalEditDates #divDates').append(options);
    if (indicatorSchedule == true) {
        $('#initial_hour_'+finalId).val($('#initialScheduleHour').val());
        $('#initial_minute_'+finalId).val($('#initialScheduleMinute').val());
        $('#final_hour_'+finalId).val($('#finalScheduleHour').val());
        $('#final_minute_'+finalId).val($('#finalScheduleMinute').val());
    }
}

function chargingSchedules(schedules) {
    var ban = true;
    for (var i = 0; i < schedules.length; i++) {
        if ((i + 1) < schedules.length) {
            if (schedules[i].initial_time.substring(0, 2) != schedules[i+1].initial_time.substring(0, 2) && schedules[i].final_time.substring(0, 2) != schedules[i+1].final_time.substring(0, 2)) {
                ban = false;
            }
        }
    }

    if (ban == true) {
        $('#indicatorSchedule').prop('checked', true);
        chargeSchedules(null, schedules[0].initial_time.substring(0, 2), schedules[0].initial_time.substring(3, 5), schedules[0].final_time.substring(0, 2), schedules[0].final_time.substring(3, 5));
        calculateDates();
    } else {
        calculateDates();
        for (var i = 0; i < schedules.length; i++) {
            $('#initial_hour_'+i).val(schedules[i].initial_time.substring(0, 2));
            $('#initial_minute_'+i).val(schedules[i].initial_time.substring(3, 5));
            $('#final_hour_'+i).val(schedules[i].final_time.substring(0, 2));
            $('#final_minute_'+i).val(schedules[i].final_time.substring(3, 5));
        }
    }
}

function checkSchedules() {
    var ban = true;
    for (var i = 0; i < daysEvent; i++) {
        if (parseInt($('#initial_hour_'+i).val()) > parseInt($('#final_hour_'+i).val())) {
            $('#initial_hour_'+i).addClass('border border-danger');
            $('#initial_minute_'+i).addClass('border border-danger');
            $('#final_hour_'+i).addClass('border border-danger');
            $('#final_minute_'+i).addClass('border border-danger');
            ban = false;
        } else {
            $('#initial_hour_'+i).removeClass('border border-danger');
            $('#initial_minute_'+i).removeClass('border border-danger');
            $('#final_hour_'+i).removeClass('border border-danger');
            $('#final_minute_'+i).removeClass('border border-danger');
            $('#initial_hour_'+i).addClass('border border-success');
            $('#initial_minute_'+i).addClass('border border-success');
            $('#final_hour_'+i).addClass('border border-success');
            $('#final_minute_'+i).addClass('border border-success');
        }
    }
    if (ban == true) {
        $('#submitEditDates').attr('disabled', false);
    } else {
        $('#submitEditDates').attr('disabled', true);
    }
}

function chargeSchedules(schedules = null, initial_hour = null, initial_minute = null, final_hour = null, final_minute = null) {
    var schedules = '';
    schedules += '<div class="input-group mb-2" id="contentSchedules">';
        schedules += '<div class="input-group-prepend">';
            schedules += '<div class="input-group-text">De:</div>';
        schedules += '</div>';
        schedules += '<select class="form-control" id="initialScheduleHour" onChange="calculateDates()">';
            for (var i = 0; i < 24; i++) {
                if (i < 10) {
                    schedules += '<option value="0'+i+'">0'+i+'</option>';
                } else {
                    schedules += '<option value="'+i+'">'+i+'</option>';
                }
            }
        schedules += '</select>';
        schedules += '<div class="input-group-prepend">';
            schedules += '<div class="input-group-text">:</div>';
        schedules += '</div>';
        schedules += '<select class="form-control" id="initialScheduleMinute" onChange="calculateDates()">';
            schedules += '<option value="00">00</option>';
            schedules += '<option value="15">15</option>';
            schedules += '<option value="30">30</option>';
            schedules += '<option value="45">45</option>';
        schedules += '</select>';
        schedules += '<div class="input-group-prepend">';
            schedules += '<div class="input-group-text bold">a: </div>';
        schedules += '</div>';
        schedules += '<select class="form-control" id="finalScheduleHour" onChange="calculateDates()">';
            for (var i = 0; i < 24; i++) {
                if (i < 10) {
                    schedules += '<option value="0'+i+'">0'+i+'</option>';
                } else {
                    schedules += '<option value="'+i+'">'+i+'</option>';
                }
            }
        schedules += '</select>';
        schedules += '<div class="input-group-prepend">';
            schedules += '<div class="input-group-text">:</div>';
        schedules += '</div>';
        schedules += '<select class="form-control" id="finalScheduleMinute" onChange="calculateDates()">';
            schedules += '<option value="00">00</option>';
            schedules += '<option value="15">15</option>';
            schedules += '<option value="30">30</option>';
            schedules += '<option value="45">45</option>';
        schedules += '</select>';
    schedules += '</div>';
    schedules += '<span class="text-red hidden" id="incorrectSchedules">La hora inicial debe ser menor que la final</span>';
    $('#divSchedules').append(schedules);
    if (initial_hour != null && initial_minute != null && final_hour != null && final_minute != null) {
        $('#initialScheduleHour').val(initial_hour);
        $('#initialScheduleMinute').val(initial_minute);
        $('#finalScheduleHour').val(final_hour);
        $('#finalScheduleMinute').val(final_minute);
    }
}

function chargingMap(latitude = '', longitude = '') {
    if (latitude != '' && longitude != '') {
        const myLang3 = {lat: parseFloat(latitude), lng: parseFloat(longitude)}
                
        const options3 = {
            center: myLang3,
            zoom: 14
        }

        var map3 = document.getElementById('content-map');

        const mapa3 = new google.maps.Map(map3, options3);

        const marcador3 = new google.maps.Marker({
            position: myLang3,
            map: mapa3
        });
    }
}