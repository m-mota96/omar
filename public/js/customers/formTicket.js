$(document).ready(()=> {
    chargingIndex();
});

var questionsGlobal = [];

function chargingIndex() {
    if (initialQuestions.length == 0) {
        indicatorIndex = false;
        var html ='<div class="col-xl-3 text-center">';
            html += '<h1 class="mt-4"><i class="fas fa-clipboard-list text-info font-super-large"></i></h1>';
        html += '</div>';
        html += '<div class="col-xl-9">';
            html += '<h2 class="bold mt-5">Forma por boleto</h2>';
            html += '<ul class="pl-4" style= "list-style-type: square">';
                html += '<li><h4>Pide información por cada asistente.</h4></li>';
                html += '<li><h4><span class="bold">Ejemplo:</span> Edad, talla de camiseta, etc.</h4></li>';
            html += '</ul>';
            html += '<h2 class="bold text-primary pointer subrayed" onclick="createDom()"><a>Crear forma por boleto</a></h2>';
        html += '</div>';
        $('#contentFormTicket').html(html);
    } else {
        createDom();
        for (var i = 0; i < initialQuestions.length; i++) {
            addQuestion(initialQuestions[i], 'createExisting');
        }
        indicatorIndex = false;
    }
}

function createDom() {
    var html = '';
    html += '<div class="col-xl-8 offset-xl-2 text-center detectChanges pt-3 pb-3 mb-4 hidden" id="detectChanges" onclick="saveChangesQuesntions()">';
        html += '<span>Hay cambios pendientes por publicar en tu forma de registro. <button class="btn btn-warning ml-3 text-white btn-questions">Guardar cambios</button></span>';
    html += '</div>';
    html += '<div class="col-xl-8 offset-xl-2"><h2 class="bold">Forma de registro por boleto</h2></div>';
    html += '<div class="col-xl-8 offset-xl-2">';
        html += '<div class="col-xl-12 pt-4 pb-3 bg-info-form-ticket mb-5">';
            html += '<h5><i class="fas fa-info-circle text-info"></i> <strong>Nombre y Correo </strong> son campos por defecto, no es necesario que los agregues.</h5>';
        html += '</div>';
    html += '</div>';
    html += '<div class="col-xl-8 offset-xl-2 mb-5" id="previewQuestions">';

    html += '</div>';
    html += '<div class="col-xl-8 offset-xl-2 mt-3" id="contentQuestions">';
        html += createInputs('new');
    html += '</div>';
    $('#contentFormTicket').html(html);
    $('#tickets').multiselect({
        includeSelectAllOption: true,
    });
}

function createInputs(action, values = null) {
    var idModal = '';
    if (values == null) {
        var values = [];
        values.push({
            'title': '',
            'required': '',
            'type': 0,
            'tickets': '',
            'info': '',
            'options': ''
        });
        values = values[0];
    } else {
        idModal = 'modal-';
    }
    var html = '';
    if (action != 'edit') {
        html += '<h5>AGREGA HASTA <strong>'+(20 - questionsGlobal.length)+'</strong> PREGUNTAS</h5><hr class="sidebar-divider">';
    }
    html += '<div class="row">';
        html += '<div class="col-xl-3 mb-4">';
            html += '<label class="bold">Título de la pregunta</label>';
        html += '</div>';
        html += '<div class="col-xl-9">';
            html += '<input class="form-control" type="text" id="'+idModal+'title" value="'+values.title+'">';
        html += '</div>';
        html += '<div class="col-xl-3 mb-4">';
            html += '<label class="bold">Tipos de boleto</label>';
        html += '</div>';
        html += '<div class="col-xl-9">';
            html += '<select class="form-control" multiple="multiple" id="'+idModal+'tickets">';
                if (action == 'new' || action == 'reset') {
                    for (var i = 0; i < tickets.length; i++) {
                        html += '<option value="'+tickets[i].id+'">'+tickets[i].name+'</option>';
                    }
                } else if (action == 'edit') {
                    var ticketSselected = false;
                    for (var i = 0; i < tickets.length; i++) {
                        ticketSselected = false;
                        for (var j = 0; j < values.tickets.length; j++) {
                            if (tickets[i].id == values.tickets[j]) {
                                ticketSselected = true;
                            }
                        }
                        if (ticketSselected == true) {
                            html += '<option value="'+tickets[i].id+'" selected="selected">'+tickets[i].name+'</option>';
                        } else {
                            html += '<option value="'+tickets[i].id+'">'+tickets[i].name+'</option>';
                        }
                    }
                }
            html += '</select>';
        html += '</div>';
        html += '<div class="col-xl-3 mb-4">';
            html += '<label class="bold">Texto de ayuda</label>';
        html += '</div>';
        html += '<div class="col-xl-9">';
            html += '<input class="form-control" type="text" id="'+idModal+'information" value="'+values.info+'">';
        html += '</div>';
        html += '<div class="col-xl-3 mb-4">';
            html += '<label class="bold">Tipo de pregunta</label>';
        html += '</div>';
        html += '<div class="col-xl-9">';
            html += '<select class="form-control" onchange="changeInput(this.value, \''+idModal+'\')" id="'+idModal+'type">';
                html += '<option value="0">Texto</option>';
                html += '<option value="1">Un párrafo de texto</option>';
                html += '<option value="2">Selecciona de una lista</option>';
                html += '<option value="3">Sube un archivo</option>';
            html += '</select>';
        html += '</div>';
        html += '<div class="col-xl-9 pl-5 mb-4 offset-xl-3" id="'+idModal+'typeInput">';
                if (action == 'edit') {
                    switch (values.type) {
                        case "0": //input text
                            html += '<input class="form-control" type="text" placeholder="Su respuesta">';
                            break;
                        case "1": //textarea
                            html += '<textarea class="form-control" placeholder="Su párrafo" rows="5"></textarea>';
                            break;
                        case "2": //select
                            countOptions = 0;
                            html += '<label>Opciones:</label><br>';
                            for (var i = 0; i < values.options.length; i++) {
                                html += '<input class="form-control inputs-questions w-75 mb-3 mr-3" type="text" placeholder="Click para agregar opcion" onclick="addOption(\''+idModal+'\')" id="'+idModal+'inputOption'+countOptions+'" value="'+values.options[i]+'"> <i class="fas fa-times pointer" id="'+idModal+'delete'+countOptions+'" onclick="deleteOption('+countOptions+', \''+idModal+'\')"></i>';
                                countOptions++;
                            }
                            html += '<input class="form-control inputs-questions w-75 mb-3 mr-3" type="text" placeholder="Click para agregar opcion" onclick="addOption(\''+idModal+'\')" id="'+idModal+'inputOption'+countOptions+'"> <i class="fas fa-times hidden pointer" id="'+idModal+'delete'+countOptions+'" onclick="deleteOption('+countOptions+', \''+idModal+'\')"></i>';
                            break;
                        case "3": //input file
                            html += '<input type="file">';
                            break;
                    }
                } else {
                    html += '<input class="form-control" type="text" placeholder="Su respuesta">';
                }
        html += '</div>';
        html += '<div class="col-xl-9 mb-5 offset-xl-3">';
            html += '<div class="form-check">';
                html += '<input class="form-check-input pointer" type="checkbox" value="0" id="'+idModal+'checkRequired"/>';
                html += '<label class="form-check-label pointer" for="'+idModal+'checkRequired">Marcar como obligatoria</label>';
            html += '</div>';
        html += '</div>';
        if (action != 'edit') {
            html += '<div class="col-xl-12 text-center">';
                html += '<button class="btn btn-primary" onclick="addQuestion()">Agregar pregunta</button>';
            html += '</div>';
        }
    html += '</div>';
    switch (action) {
        case 'new':
            return html;
            break;
        case 'reset':
            $('#contentQuestions').html(html);
            break;
        case 'edit':
            $('#modalContentQuestions').html(html);
            var valuesTemp = JSON.stringify(values).replace(/\"/g,"'");
            $('#modalQuestions #updateDataQuestion').attr("onclick", "updateDataQuestion("+valuesTemp+")");
            $('#modal-tickets').multiselect({
                includeSelectAllOption: true,
            });
            $('#modal-type').val(values.type);
            $('#modal-checkRequired').prop('checked', values.required);
            $('#modalQuestions').modal('show');
            break;
    }
}

var countOptions = 0;
function changeInput(type, idDomCharging) {
    var input = '';
    switch (type) {
        case "0": //input text
            input = '<input class="form-control" type="text" placeholder="Su respuesta">';
            break;
        case "1": //textarea
            input = '<textarea class="form-control" placeholder="Su párrafo" rows="5"></textarea>';
            break;
        case "2": //select
            countOptions = 0;
            input = '<label>Opciones:</label><br><input class="form-control inputs-questions w-75 mb-3 mr-3" type="text" placeholder="Click para agregar opcion" onclick="addOption(\''+idDomCharging+'\')" id="'+idDomCharging+'inputOption'+countOptions+'"> <i class="fas fa-times hidden pointer" id="'+idDomCharging+'delete'+countOptions+'" onclick="deleteOption('+countOptions+', \''+idDomCharging+'\')"></i>';
            break;
        case "3": //input file
            input = '<input type="file">';
            break;
    }
    $('#'+idDomCharging+'typeInput').html(input);
}

function addOption(idDomCharging = '') {
    $('#'+idDomCharging+'inputOption'+countOptions).removeAttr('placeholder');
    $('#'+idDomCharging+'inputOption'+countOptions).removeAttr('onclick');
    $('#'+idDomCharging+'delete'+countOptions).removeClass('hidden');
    countOptions++;
    if (idDomCharging == '') {
        var input = '<input class="form-control inputs-questions w-75 mb-3 mr-3" type="text" placeholder="Click para agregar opcion" onclick="addOption()" id="'+idDomCharging+'inputOption'+countOptions+'"> <i class="fas fa-times hidden pointer" id="'+idDomCharging+'delete'+countOptions+'" onclick="deleteOption('+countOptions+', \''+idDomCharging+'\')"></i>';
    } else {
        var input = '<input class="form-control inputs-questions w-75 mb-3 mr-3" type="text" placeholder="Click para agregar opcion" onclick="addOption(\''+idDomCharging+'\')" id="'+idDomCharging+'inputOption'+countOptions+'"> <i class="fas fa-times hidden pointer" id="'+idDomCharging+'delete'+countOptions+'" onclick="deleteOption('+countOptions+', \''+idDomCharging+'\')"></i>';
    }
    $('#'+idDomCharging+'typeInput').append(input);
}

function deleteOption(idDom, idDomDelete = '') {
    $('#'+idDomDelete+'delete'+idDom).remove();
    $('#'+idDomDelete+'inputOption'+idDom).remove();
}

function addQuestion(valuesModified = null, action = null) {
    // console.log(valuesModified);
    var html = '';
    var values = [];
    if (valuesModified == null && action == null) {
        var id = Math.floor(Math.random() * 1000000);
        values.push({
            'id' : id,
            'status': 'new',
            'title': $('#title').val(),
            'required': $('#checkRequired').prop('checked'),
            'type': $('#type').val(),
            'tickets': $('#tickets').val(),
            'info': $('#information').val(),
            'options': null
        });
    } else {
        var id = valuesModified.id;
        values[0] = valuesModified;
    }
    if (valuesModified == null || action != null) {
        html += '<div class="row mt-2 pb-2 pt-1 bg-info-form-ticket-questions" id="'+id+'">';
    }
            html += '<div class="col-xl-8">';
            if (values[0].required) {
                html += '<label class="bold mb-0">'+values[0].title+' (requerido)</label>';
            } else {
                html += '<label class="bold mb-0">'+values[0].title+'</label>';
            }
            switch (values[0].type) {
                case "0": // input text
                    html += '<input class="form-control" type="text">';
                    break;
                case "1": // textarea
                    html += '<textarea class="form-control" rows="5"></textarea>';
                    break;
                case "2": //select
                    html += '<select class="form-control">';
                    if (valuesModified == null && action == null) {
                        values[0].options = [];
                        var pos = 0;
                        $('.inputs-questions').each(function (e) {
                            if ($(this).val() != '') {
                                html += '<option value="'+$(this).val()+'">'+$(this).val()+'</option>';
                                values[0].options[pos] = $(this).val();
                                pos++;
                            }
                        });
                    } else {
                        for (var i = 0; i < values[0].options.length; i++) {
                            html += '<option value="'+values[0].options[i]+'">'+values[0].options[i]+'</option>';
                        }
                    }
                    html += '</select>';
                    break;
                case "3": //input file
                    html += '<input type="file">';
                    break;
            }
            if (values[0].info != '') {
                html += '<i>'+values[0].info+'</i>';
            }
            html += '</div>';
            html += '<div class="col-xl-4 text-right">';
                if (valuesModified == null || action != null) {
                    questionsGlobal.push(values[0]);
                }
                var idQuestionDelete = values[0].id;
                values = JSON.stringify(values[0]).replace(/\"/g,"'");
                html += '<span class="bold pointer" onclick="editQuestion('+values+')"><i class="fas fa-pencil-alt"></i> Editar</span><span class="bold ml-3 pointer" onclick="deleteQuestion('+idQuestionDelete+')"><i class="fas fa-trash"></i> Eliminar</span>';
            html += '</div>';
    if (valuesModified == null || action != null) {
        html += '</div>';
    }
    if (valuesModified == null || action != null) {
        $('#previewQuestions').append(html);
        createInputs('reset');
    } else if (valuesModified != null) {
        $('#'+valuesModified.id).html(html);
        $('#modalQuestions').modal('hide');
    }
    countOptions = 0;
    $('#tickets').multiselect({
        includeSelectAllOption: true,
    });
    if (indicatorIndex == false) {
        $('#detectChanges').removeClass('hidden');
        $('html,body').animate({
            scrollTop: $("#card-content-form_questions").offset().top - 65
        }, 400);
    }
}

function editQuestion(values) {
    createInputs('edit', values);
}

var questionsDeleted = [];
function deleteQuestion(idQuestion) {
    for (var i = 0; i < questionsGlobal.length; i++) {
        if (questionsGlobal[i].id == idQuestion) {
            questionsGlobal.splice(i, 1);
            questionsDeleted.push(idQuestion);
            $('#'+idQuestion).remove();
            $('#detectChanges').removeClass('hidden');
            $('html,body').animate({
                scrollTop: $("#card-content-form_questions").offset().top - 65
            }, 400);
        }
    }
}

function updateDataQuestion(values) {
    var id = 0;
    var options = [];
    for (var i = 0; i < questionsGlobal.length; i++) {
        if (questionsGlobal[i].id == values.id) {
            id = values.id;
            questionsGlobal[i].title = $('#modal-title').val();
            questionsGlobal[i].tickets = $('#modal-tickets').val();
            questionsGlobal[i].info = $('#modal-information').val();
            questionsGlobal[i].type = $('#modal-type').val();
            questionsGlobal[i].required = $('#modal-checkRequired').prop('checked');
            questionsGlobal[i].options = null;
            if ($('#modal-type').val() == 2) {
                $('.inputs-questions').each(function (e) {
                    if ($(this).val() != '') {
                        options.push($(this).val());
                    }
                });
                questionsGlobal[i].options = options;
            }
            addQuestion(questionsGlobal[i]);
        }
    }
}

function saveChangesQuesntions() {
    console.log(questionsGlobal);
    $.ajax({
        url: $('#URL').val()+'saveChangesQuesntions',
        method: 'post',
        data: {
            "_token": $("meta[name='csrf-token']").attr("content"),
            event_id: $('#event_id').val(),
            data: questionsGlobal,
            dataDeleted: questionsDeleted
        },
        success: (res)=> {
            if (res.status == true) {
                questionsDeleted = [];
                questionsGlobal = [];
                $('#previewQuestions').html('');
                for (var i = 0; i < res.questions.length; i++) {
                    addQuestion(res.questions[i], 'createExisting');
                }
                $('#detectChanges').addClass('hidden');
            }
        },
        error: ()=> {

        }
    });
}