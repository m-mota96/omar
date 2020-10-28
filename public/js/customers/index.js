$(document).ready(()=> {
    addEvent();
});

function addEvent() {
    $('.show').unbind();
    $('.card-reveal .close').unbind();
    $('.show').on('click', function() {
        var id = $(this).attr("data-id");
        $('#options'+id).slideToggle('slow');
    });
    $('.card-reveal .close').on('click', function() {
        var id = $(this).attr("data-id");
        $('#options'+id).slideToggle('slow');
    }); 
}

$('#createEvent').on('click', ()=> {
    $('#modalCreateEvent #modalCreateEventLabel').text('Crear evento');
    $('#modalCreateEvent #submitCreateEvent').text('Crear evento');
    $('#modalCreateEvent').modal('show');
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
$('#formCreateEvent').submit((e)=> {
    e.preventDefault();
    if($('#nameEvent').val().length > 3) {
        createEvent();
    } else {
        Swal.fire({
            icon: 'error',
            text: 'El nombre de su evento debe contener mas de 3 letras'
        });
        return false;
    }
});

$("#modalCreateEvent").on("hidden.bs.modal", function () {
    $('#modalCreateEvent .form-control').val('');
    $('#modalCreateEvent #daysEvent').val(1);
    $('#modalCreateEvent #txt_name_alert').css('display', 'none');
    $('#modalCreateEvent #txt_website_alert').css('display', 'none');
    $('#modalCreateEvent #txt_name_success').css('display', 'none');
    $('#modalCreateEvent #txt_website_success').css('display', 'none');
    createDates(1);
});

$('#daysEvent').on('change', function() {
    createDates($(this).val());
});

$('#searchEvents').keyup(()=> {
    $.ajax({
        method: 'POST',
        url: $('#URL').val()+'searchEvents',
        dataType: 'json',
        data: {
            "_token": $("meta[name='csrf-token']").attr("content"),
            name: $('#searchEvents').val(),
        },
        success: (response)=> {
            if(response.events.length > 0) {
                createEventDom(response.events);
            } else {
                $('#divEvents').html('<h2 class="mt-5 text-center w-100 text-gray-600">No se encontraron resultados</h2>');
            }
        },
        error: ()=> {
            console.log('ERROR');
        }
    });
});

function createDates(quantity) {
    var options = '';
    for (var i = 0; i < quantity; i++) {
        options += '<label>Día '+(i+1)+':</label>';
        options += '<div class="input-group mb-2">';
            options += '<input class="form-control inputs-dates-create-event" type="date" id="date_'+i+'" required>';
            options += '<div class="input-group-prepend">';
                options += '<div class="input-group-text bold"> De:</div>';
            options += '</div>';
            options += '<select class="form-control" id="initial_hour_'+i+'">';
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
            options += '<select class="form-control" id="initial_minute_'+i+'">';
                options += '<option value="00">00</option>';
                options += '<option value="15">15</option>';
                options += '<option value="30">30</option>';
                options += '<option value="45">45</option>';
            options += '</select>';
            options += '<div class="input-group-prepend">';
                options += '<div class="input-group-text bold">a: </div>';
            options += '</div>';
            options += '<select class="form-control" id="final_hour_'+i+'">';
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
            options += '<select class="form-control" id="final_minute_'+i+'">';
                options += '<option value="00">00</option>';
                options += '<option value="15">15</option>';
                options += '<option value="30">30</option>';
                options += '<option value="45">45</option>';
            options += '</select>';
        options += '</div>';
    }
    $('#modalCreateEvent #dates').html(options);
}

function createEvent() {
    var dates = [];
    var initial_times = [];
    var final_times = [];
    for (var i = 0; i < $('#daysEvent').val(); i++) {
        dates[i] = $('#date_'+i).val();
        initial_times[i] = $('#initial_hour_'+i).val()+':'+$('#initial_minute_'+i).val();
        final_times[i] = $('#final_hour_'+i).val()+':'+$('#final_minute_'+i).val();
    }
    $.ajax({
        method: 'POST',
        url: $('#URL').val()+'createEvent',
        dataType: 'json',
        data: {
            "_token": $("meta[name='csrf-token']").attr("content"),
            name: $('#nameEvent').val(),
            website: $('#website').val(),
            quantity: $('#quantity').val(),
            dates: dates,
            initial_times: initial_times,
            final_times: final_times,
            description: $('#description').val(),
            location: $('#location').val(),
        },
        success: (response)=> {
            if(response.status == true) {
                // createEventDom(response.event);
                // $('#modalCreateEvent').modal('hide');
                // $('#modalCreateEvent .form-control').val('');
                // $('#modalCreateEvent #daysEvent').val(1);
                // $('#modalCreateEvent #txt_name_success').css('display', 'none');
                // $('#modalCreateEvent #txt_website_success').css('display', 'none');
                // createDates(1);
                location.href = $('#URL').val()+'admin/edit/'+response.event.id;
                // Swal.fire({
                //     position: 'bottom-end',
                //     icon: 'success',
                //     text: 'El evento se creo correctamente',
                //     showConfirmButton: false,
                //     timer: 1500
                // });
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

function createEventDom(events) {
    var content = '';
    for (var i = 0; i < events.length; i++) {
        content += '<div class="col-xl-4 pr-0 mb-4">';
            if (events[i].profile !== null) {
                content += '<img class="w-100 h-100 img-index" src="'+$("#URL").val()+'/media/'+events[i].profile.name+'" alt="'+events[i].name+'">';
            } else {
                content += '<img class="w-100" src="'+$("#URL").val()+'media/general/not_image.png" alt="'+events[i].name+'">';
            }
        content += '</div>';
        content += '<div class="col-xl-8 bg-white p-4 mb-4">';
            content += '<div class="row">';
                content += '<div class="col-xl-8">';
                    content += '<h4 class="bold mb-0"><a class="text-dark" href="'+$("#URL").val()+'admin/edit/'+events[i].id+'">'+events[i].name+'</a></h4>';
                    content += '<span>';
                        content += events[i].initial_date+' a '+events[i].final_date;
                    content += '</span>';
                    content += '<p></p>';
                    content += '<span class="font-small mr-4"><a class="text-dark" href="'+$("#URL").val()+'admin/edit/'+events[i].id+'">EDITAR</a></span>';
                    content += '<span class="font-small mr-4"><a class="text-dark" href="">ELIMINAR</a></span>';
                content += '</div>';
                content += '<div class="col-xl-4 text-right">';
                    content += '<h3 class="mb-0"><span class="text-blue-400">0/</span><span class="text-blue-300">'+events[i].quantity_tickets+'</span></h3>';
                    content += '<span class="font-small mt-0">BOLETOS RESERVADOS</span>';
                content += '</div>';
            content += '</div>';
        content += '</div>';
    }
    $('#divEvents').html(content);
    // addEvent();
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
                    $('#modalCreateEvent #txt_name_alert').css('display', 'unset');
                    $('#modalCreateEvent #txt_name_success').css('display', 'none');
                    statusName = false;
                } else {
                    $('#modalCreateEvent #txt_name_alert').css('display', 'none');
                    $('#modalCreateEvent #txt_name_success').css('display', 'unset');
                    statusName = true;
                }
                if(response.website_available != true) {
                    $('#modalCreateEvent #txt_website_alert').css('display', 'unset');
                    $('#modalCreateEvent #txt_website_success').css('display', 'none');
                    statusWebsite = false;
                } else {
                    $('#modalCreateEvent #txt_website_alert').css('display', 'none');
                    $('#modalCreateEvent #txt_website_success').css('display', 'unset');
                    statusWebsite = true;
                }
                if(statusName == true && statusWebsite == true) {
                    $('#modalCreateEvent #submitCreateEvent').attr('disabled', false);
                    // createEvent();
                } else {
                    $('#modalCreateEvent #submitCreateEvent').attr('disabled', true);
                }
            },
            error: ()=> {
                console.log('ERROR');
            }
        });
    }
}

// function editEventDom(event) {
//     $('#cardEvent-'+event.id+' .card-body .card-title').text(event.name);
//     $('#cardEvent-'+event.id+' .card-body .card-initial-date').text(event.initial_date+' ('+event.initial_time.substring(0, 5)+' a '+event.initial_time.substring(6, 12)+')');
//     $('#cardEvent-'+event.id+' .card-body .card-final-date').text(event.final_date+' ('+event.final_time.substring(0, 5)+' a '+event.final_time.substring(6, 12)+')');
//     $('#cardEvent-'+event.id+' .card-body .card-text').text(event.description);
//     $('#options'+event.id).slideToggle('slow');
// }

// function editEvent(event_id) {
//     $.ajax({
//         method: 'POST',
//         url: $('#URL').val()+'extractEvent',
//         dataType: 'json',
//         data: {
//             "_token": $("meta[name='csrf-token']").attr("content"),
//             event_id: event_id
//         },
//         success: (response)=> {
//             $('#modalCreateEvent #nameEvent').val(response.event.name);
//             $('#modalCreateEvent #website').val(response.event.url);
//             $('#modalCreateEvent #quantity').val(response.event.quantity);
//             $('#modalCreateEvent #price').val(response.event.price);
//             $('#modalCreateEvent #initial_date').val(response.event.initial_date);
//             $('#modalCreateEvent #final_date').val(response.event.final_date);
//             $('#modalCreateEvent #initial_time_start').val(response.event.initial_time.substring(0, 5));
//             $('#modalCreateEvent #final_time_start').val(response.event.initial_time.substring(6, 12));
//             $('#modalCreateEvent #initial_time_end').val(response.event.final_time.substring(0, 5));
//             $('#modalCreateEvent #final_time_end').val(response.event.final_time.substring(6, 12));
//             // $('#modalCreateEvent #location').val(response.event.final_time.substring(6, 12));
//             $('#modalCreateEvent #description').val(response.event.description);
//             $('#modalCreateEvent #event_id').val(response.event.id);
//             $('#modalCreateEvent #modalCreateEventLabel').text('Editar evento');
//             $('#modalCreateEvent #submitCreateEvent').text('Editar evento');
//             $('#modalCreateEvent').modal('show');
//         },
//         error: ()=> {
//             console.log('ERROR');
//         }
//     });
// }

function deleteEvent(id) {
    Swal.fire({
        title: '¿Seguro que que desea eliminar este evento?',
        text: "Los datos no se podrán recuperar",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#3085d6',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33',
        confirmButtonText: 'Eliminar',
        reverseButtons: true
      }).then((result) => {
        if (result.value) {
            Swal.fire(
                'Deleted!',
                'Your file has been deleted.',
                'success'
            );
        }
      })
}

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

// google.maps.event.addDomListener(window, 'load', function() {
//     var autocomplete = document.getElementById('location');
//     const search = new google.maps.places.Autocomplete(autocomplete);
//     // search.bindTo('bounds', )
// });