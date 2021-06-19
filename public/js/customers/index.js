$(document).ready(()=> {
    addEvent();
});

var ths = this;
var categories=[];

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
    $('#modalCreateEvent #divDates').html('');
});

$('#moreDays').click(function() {
    createDates($(this).attr('data-finalId'));
});

$('#initial_date').change(function() {
    calculateDates();
});

$('#final_date').change(function() {
    calculateDates();
});

$('#indicatorSchedule').click(function() {
    if ($(this).is(':checked')) {
        var schedules = '';
        schedules += '<div class="input-group mb-2" id="contentSchedules">';
            schedules += '<div class="input-group-prepend">';
                schedules += '<div class="input-group-text">De:</div>';
            schedules += '</div>';
            schedules += '<select class="form-control" id="initialScheduleHour" onChange="calculateDates()">';
                schedules += '<option value="00">00</option>';
                schedules += '<option value="01">01</option>';
                schedules += '<option value="02">02</option>';
                schedules += '<option value="03">03</option>';
                schedules += '<option value="04">04</option>';
                schedules += '<option value="05">05</option>';
                schedules += '<option value="06">06</option>';
                schedules += '<option value="07">07</option>';
                schedules += '<option value="08">08</option>';
                schedules += '<option value="09">09</option>';
                schedules += '<option value="10">10</option>';
                schedules += '<option value="11">11</option>';
                schedules += '<option value="12">12</option>';
                schedules += '<option value="13">13</option>';
                schedules += '<option value="14">14</option>';
                schedules += '<option value="15">15</option>';
                schedules += '<option value="16">16</option>';
                schedules += '<option value="17">17</option>';
                schedules += '<option value="18">18</option>';
                schedules += '<option value="19">19</option>';
                schedules += '<option value="20">20</option>';
                schedules += '<option value="21">21</option>';
                schedules += '<option value="22">22</option>';
                schedules += '<option value="23">23</option>';
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
                schedules += '<option value="00">00</option>';
                schedules += '<option value="01">01</option>';
                schedules += '<option value="02">02</option>';
                schedules += '<option value="03">03</option>';
                schedules += '<option value="04">04</option>';
                schedules += '<option value="05">05</option>';
                schedules += '<option value="06">06</option>';
                schedules += '<option value="07">07</option>';
                schedules += '<option value="08">08</option>';
                schedules += '<option value="09">09</option>';
                schedules += '<option value="10">10</option>';
                schedules += '<option value="11">11</option>';
                schedules += '<option value="12">12</option>';
                schedules += '<option value="13">13</option>';
                schedules += '<option value="14">14</option>';
                schedules += '<option value="15">15</option>';
                schedules += '<option value="16">16</option>';
                schedules += '<option value="17">17</option>';
                schedules += '<option value="18">18</option>';
                schedules += '<option value="19">19</option>';
                schedules += '<option value="20">20</option>';
                schedules += '<option value="21">21</option>';
                schedules += '<option value="22">22</option>';
                schedules += '<option value="23">23</option>';
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
    } else {
        $('#contentSchedules').remove();
        calculateDates();
    }
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

$('#statusEvent').change(function() {
    var status = 0;
    if ($('#statusEvent').prop('checked')) {
        status = 1;
        var txt = 'activo';
        var action = 'inicara';
    } else {
        status = 0;
        var txt = 'desactivo';
        var action = 'detendra';
    }
    // Swal.fire({
    //     icon: 'warning',
    //     title: 'Atención',
    //     html: '<span>Esta acción '+action+' la venta de boletos para su evento</span><br><span>¿Desea continuar?</span>',
    //     showCancelButton: true,
    //     confirmButtonColor: '#3085d6',
    //     cancelButtonColor: '#d33',
    //     confirmButtonText: 'Aceptar',
    //     cancelButtonText: 'Cancelar',
    //     reverseButtons: true
    // }).then((result) => {
    //     if (result.isConfirmed) {
            $.ajax({
                url: $('#URL').val()+'changeStatusEvent',
                method: 'post',
                data: {
                    "_token": $("meta[name='csrf-token']").attr("content"),
                    eventId: $(this).attr('data-eventId'),
                    status: status
                },
                success: (res)=> {
                    if (res.status == true) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Correcto',
                            text: 'Su evento se '+txt+' con éxito'
                        });
                    }
                },
                error: ()=> {
                    console.log('ERROR');
                }
            });
    //     } else {
    //         $('#statusEvent').bootstrapToggle('off');
    //     }
    // });
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
                $('#submitCreateEvent').attr('disabled', false);
                $('#incorrectSchedules').addClass('hidden');
                var dateStart = new Date(initial_date);
                var dateEnd    = new Date(final_date);
                var finalId = 0;
                $('#modalCreateEvent #divDates').html('');
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
                $('#submitCreateEvent').attr('disabled', true);
            }
        } else {
            $('#incorrectDates').removeClass('hidden');
            $('#submitCreateEvent').attr('disabled', true);
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
    $('#modalCreateEvent #divDates').append(options);
    if (indicatorSchedule == true) {
        $('#initial_hour_'+finalId).val($('#initialScheduleHour').val());
        $('#initial_minute_'+finalId).val($('#initialScheduleMinute').val());
        $('#final_hour_'+finalId).val($('#finalScheduleHour').val());
        $('#final_minute_'+finalId).val($('#finalScheduleMinute').val());
    }
    // var cont = 0;
    // $("#modalCreateEvent .inputs-dates-create-event").each(function (e) {
    //     cont++;
    // });
    // $('#modalCreateEvent #moreDays').attr('data-finalId', cont);
}

function deleteDate(id) {
    if ((id + 1) >= $('#modalCreateEvent #moreDays').attr('data-finalId')) {
        $('#groupDate-'+id).remove();
        $('#infoDay-'+id).remove();
        var cont = 0;
        $("#modalCreateEvent .inputs-dates-create-event").each(function (e) {
            cont++;
        });
        $('#modalCreateEvent #moreDays').attr('data-finalId', cont);
    } else {
        Swal.fire({
            icon: 'error',
            text: 'Debe eliminar en orden las fechas de su evento'
        });
    }
}

function createEvent() {
    $('#submitCreateEvent').attr('disabled', true);
    var dates = [];
    var initial_times = [];
    var final_times = [];
    var ban = true;
    for (var i = 0; i < daysEvent; i++) {
        dates[i] = $('#date_'+i).val();
        initial_times[i] = $('#initial_hour_'+i).val()+':'+$('#initial_minute_'+i).val();
        final_times[i] = $('#final_hour_'+i).val()+':'+$('#final_minute_'+i).val();
        if (parseInt($('#initial_hour_'+i).val()) > parseInt($('#final_hour_'+i).val())) {
            $('#initial_hour_'+i).removeClass('border border-danger');
            $('#initial_minute_'+i).addClass('border border-danger');
            $('#final_hour_'+i).addClass('border border-danger');
            $('#final_minute_'+i).addClass('border border-danger');
            ban = false;
        }
    }

    banCategory = false;
    if($('#categorySelect option:selected').val() > 0){
        banCategory = true;
        $('#categorySelect').removeClass('border border-danger');
    }else{
        banCategory = false;
        $('#categorySelect').addClass('border border-danger');
    }

    if (ban == true && banCategory == true) {
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
                cost_type: $('input:radio[name=cost_type]:checked').val(),
                category_id:$("#categorySelect option:selected").val()
            },
            success: (response)=> {
                if(response.status == true) {
                    location.href = $('#URL').val()+'customer/edit/'+response.event.id;
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
        $('#submitCreateEvent').attr('disabled', false);
    } else {
        $('#submitCreateEvent').attr('disabled', true);
    }
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
        $('#submitCreateEvent').attr('disabled', true);
        $.ajax({
            method: 'POST',
            url: $('#URL').val()+'checkEvent',
            dataType: 'json',
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