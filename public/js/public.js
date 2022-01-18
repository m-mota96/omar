var ths=this;
const ticketsComplete=[];
Conekta.setPublicKey("key_BpoqCZd5rqzFZXrxfjjFVQQ");

window.onload = function() {
    var myInput = document.getElementById('confirmEmail_orderData');
    myInput.onpaste = function(e) {
        e.preventDefault();
    }

    myInput.oncopy = function(e) {
        e.preventDefault();
    }
}

$(document).ready(()=> {
    if($('#ticket-value').val() != '') {
        $('html,body').animate({
            scrollTop: $("#div-tickets").offset().top
        }, 1000);
    }
    
    ths.ticketsComplete=JSON.parse($("#ticketsComplete").val());

    /*Logisca para funcionamiento de boton siguiente*/
    $("#content-payment").css('display','none');

    $("#showContentData").click(function(){
        if(validateGlobalFields()==true){
            if(getGlobalDataOrder()==true){
                $("#content-data").animate({left:-800},500,function(){
                    $("#content-data").hide();
                    $("#content-payment").show();
                    });
            }
        }
        
        
    })

    $("#showContentPayment").click(function(){
        $("#content-payment").hide();
        $("#content-data").show();
        $("#content-data").animate({left:0},500,function(){
        });
        
    })
    //Fin de logica

});



$("#more-info").click(function () {
    $('html,body').animate({
        scrollTop: $("#div-info").offset().top
    }, 1000);
});

var map;
var marker;
var center;
var latitud = 0, longitud = 0;
var cost_type="free";

function initMap(lat = null, long = null) {
    var elem = document.getElementById("map");
    if (lat == null || long == null) {
        center = {lat: 20.676580, lng: -103.34785};
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (pos) {
                center = {lat: pos.coords.latitude, lng: pos.coords.longitude};
                drawMap(elem, center);
                setPosicion(center);
            }, function () {
                drawMap(elem, center);
            });
        } else {
            drawMap(elem, center);
        }
    } else {
        center = getPosicion(lat, long);
        drawMap(elem, center);
    }
}

function drawMap(elem, center) {
    map = new google.maps.Map(elem, {
        center: center,
        zoom: 14,
    });
    marker = new google.maps.Marker({
        position: center,
        map: map,
        animation: google.maps.Animation.DROP,
        title: 'Mueve el mapa',
    });
    map.addListener('center_changed', function () {
        var p = map.getCenter();
        marker.setPosition({lat: p.lat(), lng: p.lng()});
        setPosicion({lat: p.lat(), lng: p.lng()});
    });
}

function setPosicion(center) {
    $("#latitude").val(center.lat);
    $("#longitude").val(center.lng);
    latitud = center.lat;
    longitud = center.lng;
}

function getPosicion(lat, long) {
    return {lat: parseFloat(lat), lng: parseFloat(long)};
}

$('.btn-more').click(function(e) {
    var id = $(this).attr('data-id');
    if (parseInt($('#quantity-'+id).val()) < parseInt($(this).attr('data-limit'))) {
        var quantity = parseInt($('#quantity-'+id).val()) + 1;
        $('#quantity-'+id).val(quantity);
        $('#subtotal-'+id).text('$'+formatMoney(quantity*$('#price-'+id).val())+' MXN');
        $('#text-subtotal-'+id).removeClass('hidden');
        calculateTotals();
    }
});

$('#btnSale').click(function() {
    $("#content-payment").hide();
        $("#content-data").show();
        $("#content-data").animate({left:0},500,function(){
        });
    calculateTotals('sale');
    if($('#cost_type').val() == 'free' || (ths.cost_type === 'free')){
        $('#optionFree').show();
        $("#payment-method option[value=free]").attr("selected",true);
        $('#payment-method').prop('disabled', 'disabled');
        
        getFormCorreo();
    }else{
        $("#payment-method").val("");
        $("#divContentPayment").html("");
        $('#payment-method').removeAttr('disabled');
        $('#optionFree').hide();

    }
    
    ticketsGeneratedHtml();

    $('#modalSale').modal('show');
    
});

/* Inicio -- Relacionado a los pasos de modal Para realizar el pago*/
function ticketsGeneratedHtml(){
    var container_tickets="";
    $("#container-tickets").html("");
    var contTicket=0;
    var posTickets=0;
    ths.ticketsGenerated.forEach(ticket => {

        for (let index = 0; index < ticket.quanties; index++) {
            container_tickets+= `
                <div class="container rounded border" id="container_${contTicket}">
                    <div class="row p-2 border-bottom">
                        <h5 class="bold w-10">Boleto ${contTicket+1} - ${ticket.name}</h5>
                    </div>
                    <div class="row p-2">
                        <div class="form-check">
                            <input class="form-check-input" onchange="completeOrderData(${posTickets},${contTicket})" type="checkbox" value="" id="autocompleted_${contTicket}">
                            <label class="form-check-label pointer" for="autocompleted_${contTicket}">
                                Autocompletar este boleto con datos de la orden.
                            </label>
                        </div>
                    </div>

                    <div class="row p-2">
                        <div class="col-xl-4">
                            <label for="name">Nombre *</label>
                            <input type="text" class="form-control" id="name_${contTicket}" name="name[${contTicket}]" required placeholder="Nombre">
                        </div>
                        <div class="col-xl-4">
                            <label for="name">Correo *</label>
                            <input type="email"  class="form-control" id="email_${contTicket}" name="email[${contTicket}]" required placeholder="Correo">
                        </div>
                        <div class="col-xl-4">
                            <label for="name">Teléfono *</label>
                            <input type="tel" pattern="[0-9]{9}" class="form-control" id="phone_${contTicket}" required name="phone[${contTicket}]" placeholder="Teléfono">
                        </div>
                    </div>
                    <br>
                    ${ths.generateQuestionsHtml(ticket.id,contTicket)}

                </div>
                <br>
                `;
                
                contTicket++;
        }
        posTickets++;
    });
    
    $("#container-tickets").html(container_tickets);

    

}

function generateQuestionsHtml(_idTicket,_contTicket){
    console.log("generateQuestionsHtml> "+_idTicket+"  > "+_contTicket);
    var container_questions=``;

    var ticket=ths.ticketsComplete.filter(ticket =>{
        return ticket.id == _idTicket
    });
    
    console.log(ticket);

    if(ticket[0].questions.length>0){
        var contQuestion=0;
        var element=`<div class="row p-2">`;
        var contElement=1;
        ticket[0].questions.forEach(question => {
            if(contElement == 4){
                element+="</div>";
                element+=`<div class="row p-2">`;
                contElement=1;
            }
            
            if(question.type == 0){
                //Input Text
                element+=`
                        <div class="col-xl-4">
                            <label class="bold mb-0">${question.title}`; question.required==1 ? element+=` (requerido) </label>` : element+= `</label>`;
                                element+=`<input class="form-control"`; question.required == 1 ? element+="required ": ""; element+=`type="text" value="" id="question_${_contTicket}_${contQuestion}"  name="question[${_contTicket}][${contQuestion}]">
                                <i>${question.information}</i>
                        </div>`;

            }else if(question.type == 1){
                //TextArea

                element+=`
                        <div class="col-xl-4">
                            <label class="bold mb-0">${question.title}`; question.required==1 ? element+=` (requerido) </label>` : element+= `</label>`;
                            element+=`<textarea class="form-control" id="question_${_contTicket}_${contQuestion}" name="question[${_contTicket}][${contQuestion}]" rows="5"`; question.required == 1 ? element+=`required></textarea>`: element+="></textarea>"; 
                            element+=`<i>${question.information}</i>
                        </div>`;

            }else if(question.type == 2){
                //Select
                
                var options=question.options.split(",");
                element+=`
                        <div class="col-xl-4">
                            <label class="bold mb-0">${question.title}`; question.required==1 ? element+=` (requerido) </label>` : element+= `</label>`;
                            element+=`<select class="form-control" id="question_${_contTicket}_${contQuestion}" name="question[${_contTicket}][${contQuestion}]" `; question.required == 1 ? element+="required> ":  element+=">";
                                element+=`<option value="">Selecciona una opción</option>`;
                                options.forEach(option=>{
                                    element+=`<option value="${option}">${option}</option>`;
                                })
                            element+=`</select>
                            <i>${question.information}</i>
                        </div>
                `;
                
            }else if(question.type == 3){
                //Input file

                element+=`
                        <div class="col-xl-4">
                            <label class="bold mb-0">${question.title}`; question.required==1 ? element+=` (requerido) </label>` : element+= `</label>`;
                            element+=`<input onchange="validarArchivo(event,'question_${_contTicket}_${contQuestion}')" type="file" id="question_${_contTicket}_${contQuestion}" name="question[${_contTicket}][${contQuestion}]"`; question.required == 1 ? element+="required> ": element+=">";
                            element+=`<i>${question.information}</i>
                        </div>`

            }

            contElement++;
            contQuestion++;
            
        });

        if(contElement < 4){
            element+="</div>";
        }
        container_questions+=element;
    }
    return container_questions;
}

function completeOrderData(_posTickets,_contTicket){
    if($("#autocompleted_"+_contTicket).is(':checked')){
        $('#name_'+_contTicket).val($('#name_orderData').val());
        $('#email_'+_contTicket).val($('#email_orderData').val());
        $('#phone_'+_contTicket).val($('#phone_orderData').val());
    }else{
        $('#name_'+_contTicket).val('');
        $('#email_'+_contTicket).val('');
        $('#phone_'+_contTicket).val('');
    }

} 

function completeDataPaymet(){
    if($("#autocompleted_DataPayment").is(':checked')){
        $('#name').val($('#name_orderData').val());
        $('#email').val($('#email_orderData').val());
        $('#phone').val($('#phone_orderData').val());
        $('#confirmEmail').val($('#confirmEmail_orderData').val());
    }else{
        $('#name').val('');
        $('#email').val('');
        $('#phone').val('');
        $('#confirmEmail').val('');
    }
}

function validateGlobalFields(){
    var cont=0;

    if(document.querySelector(`#name_orderData`).reportValidity()){
        cont++;
    }else{
        return false;
    }
    if(document.querySelector(`#email_orderData`).reportValidity()){
        cont++;
        if(document.querySelector(`#confirmEmail_orderData`).reportValidity()){
            if($('#email_orderData').val() == $('#confirmEmail_orderData').val()){
                $('#txt_confirmeEmail_alert').css('display','none');
                cont++;
            }else{
                $('#txt_confirmeEmail_alert').css('display','block');
                return false;
            }
            
        }else{
            return false;
        }
        
    }else{
        return false;
    }
    if(document.querySelector(`#phone_orderData`).reportValidity()){
        cont++;
    }else{
        return false;
    }
    
    if(cont == 4){
        return true;
    }
    
    
}

$('#confirmEmail_orderData').change(()=>{
    if($('#email_orderData').val() == $('#confirmEmail_orderData').val()){
        $('#txt_confirmeEmail_alert').css('display','none');
    }else{
        $('#txt_confirmeEmail_alert').css('display','block');
    }
    
})

function getGlobalDataOrder(){
    //obtener campos del encabezado (nombre, correo, telefono)
    ths.globlaDataOrder=[];
    var banDataComplete=true;

    ths.globlaDataOrder={
        name:$('#name_orderData').val(),
        emal:$('#email_orderData').val(),
        phone:$('#phone_orderData').val(),
        infoTickets:[]
    }

    
    var infoTicket=[];
    var contTicket=0;
    var arrayRequest=[];

    ths.ticketsGenerated.forEach(ticketG =>{
        //Obtener campos de cada boleto (nombre, correo, telefono)
        infoTicket=[];
        var ticket=ths.ticketsComplete.filter(item =>{
            return item.id == ticketG.id
        });
        
        for (let x = 0; x < ticketG.quanties; x++) {

            // Se realiza la validacion del required a los headers(nombre, correo y phone) del boleto
    
            if(!document.querySelector(`#name_${contTicket}`).reportValidity()){
                return banDataComplete=false;
            }
            if(!document.querySelector(`#email_${contTicket}`).reportValidity()){
                return banDataComplete=false;
            }
            if(!document.querySelector(`#phone_${contTicket}`).reportValidity()){
                return banDataComplete=false;
            }

            if(ticket[0].questions.length > 0){
    
                // Se realiza la validacion del required a las questions
                var typeQuestion="";
                var valueQuestion="";
                var contaQuestion=0;
                
                for (let i = 0; i < ticket[0].questions.length; i++) {
                    if(document.querySelector(`#question_${contTicket}_${i}`).reportValidity()){
                        if(document.getElementById(`question_${contTicket}_${i}`).nodeName == 'INPUT'){
                            if($(`#question_${contTicket}_${i}`).attr('type') == 'text'){
                                typeQuestion="text";
                                valueQuestion=$(`#question_${contTicket}_${i}`).val();

                            }else{
                                // File
                                var file= new FormData();
                                file.append('file',document.querySelector(`#question_${contTicket}_${i}`).files[0]);
                                typeQuestion="file";
                                valueQuestion=file;

                            }
                            
                        }else if(document.getElementById(`question_${contTicket}_${i}`).nodeName == 'SELECT'){
                            typeQuestion="select";
                            valueQuestion=$(`#question_${contTicket}_${i} option:selected`).text();

                        }else if(document.getElementById(`question_${contTicket}_${i}`).nodeName == 'TEXTAREA'){
                            typeQuestion="textarea";
                            valueQuestion=$(`#question_${contTicket}_${i}`).val();

                        }

                        arrayRequest[contaQuestion]={
                            type:typeQuestion,
                            value:valueQuestion,
                            question_id:ticket[0].questions[i]['id'],
                            title:ticket[0].questions[i]['title']
                        };
                        contaQuestion++;

                    }else{
                        return banDataComplete=false;
                    }
                }
            
                
                infoTicket={
                    name:$(`#name_${contTicket}`).val(),
                    email:$(`#email_${contTicket}`).val(),
                    phone:$(`#phone_${contTicket}`).val(),
                    idTicket:ticket[0].id,
                    event_id:ticket[0].event_id,
                    requestQuestion:arrayRequest
                }
                arrayRequest=[];
                
    
            }else{
                infoTicket={
                    name:$(`#name_${contTicket}`).val(),
                    email:$(`#email_${contTicket}`).val(),
                    phone:$(`#phone_${contTicket}`).val(),
                    idTicket:ticket[0].id,
                    event_id:ticket[0].event_id,
                    requestQuestion:arrayRequest
                }
                arrayRequest=[];
            }
    
            contTicket++;
            ths.globlaDataOrder.infoTickets.push(infoTicket);
            infoTicket=[];
        }
        

    });
    
    if(banDataComplete == true){
        return banDataComplete;
    }else{
        return banDataComplete;
    }
    

}

function validarArchivo(_evt,_idFileElement){

    var archivo=_evt.target.files;
    
    if(archivo.length == 1){
        if(archivo[0].type=="image/jpeg" || archivo[0].type=="image/jpg" || archivo[0].type=="application/pdf" || archivo[0].type=="image/png"){
            if(archivo[0].size < ((1024*1024)*5)){
                console.log('archivo correcto');

            }else{
                console.log("El archivo supera los 5 MB");
                $("#"+_idFileElement).val('');
            }
        }else{
            console.log("El tipo de archivo no es permitido");
            $("#"+_idFileElement).val('');
        }
    }else{
        console.log("Seleccione un archivo para subir");
        $("#"+_idFileElement).val('');
    }
}

/** Fin */


$('.btn-minus').click(function() {
    var id = $(this).attr('data-id');
    if (parseInt($('#quantity-'+id).val()) > 0) {
        $('#quantity-'+id).val(parseInt($('#quantity-'+id).val())-1);
        if ($('#quantity-'+id).val() == 0) {
            $('#subtotal-'+id).text('');
            $('#text-subtotal-'+id).addClass('hidden');
        }
        calculateTotals();
    }
});

$('#payment-method').change(()=> {
    if ($('#model_payment').val() == 'separated') {
        $('#body-comisions').html('');
        var comision = (totalTickets * (0.12));
        var tbody = '<tr><td colspan="3">Comisiones</td><td class="text-right">$'+formatMoney(comision)+' MXN</td></tr>';
        var totalAux = totalTickets;
        totalAux = totalAux + comision;
        $('#body-comisions').html(tbody);
        $('#total-sale').html('$'+formatMoney(totalAux)+' MXN');
    }
    $.get( 
        $('#URL').val()+"payments/paymentMethod/"+$('#payment-method').val(),
        function (data) {
            $("#divContentPayment").html(data);
            $("#confirmEmail").on('paste', function(e){
                e.preventDefault();
            });
        }
    );
});

function getFormCorreo(){
    $.get( 
        $('#URL').val()+"payments/paymentMethod/"+$('#payment-method').val(),
        function (data) {
            $("#divContentPayment").html(data);
            $("#confirmEmail").on('paste', function(e){
                e.preventDefault();
            });
        }
    );
}

$('#formSale').submit((e)=> {
    e.preventDefault();
    if ($('#email').val() == $('#confirmEmail').val()) {
        if (sales == true) {
            var turnsVerified = true;
            for (var i = 0; i < turns.length; i++) {
                for (var j = 0; j < turns[i].length; j++) {
                    if (turns[i][j] == null) {
                        turnsVerified = false;
                    }
                }
            }
            if (turnsVerified == true) {
                if($('#payment-method').val() == 'card') {
                    var $form = $('#formSale');
                    Conekta.Token.create($form, conektaSuccessReponseHandler, conektaErrorReponseHandler);
                } else if ($('#payment-method').val() == 'oxxo') {
                    Swal.fire({
                        title: "¡Atención!",
                        html: "Tu referencia de pago se enviará a:<br><b>"+$('#email').val()+"</b><br> ¿Es correcta esta información?",
                        showCancelButton: true,
                        cancelButtonColor: '#3085d6',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Aceptar',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.value) {
                            var msgAlert = 'Creando referencia de pago, no cierre ni actualice esta página. Por favor espere!!';
                            var msgSuccess = 'Su referencia se genero con éxito, en breve la recibira en su correo electrónico';
                            jsPay(msgAlert, msgSuccess);
                        }
                    });
                }else if($('#payment-method').val() == 'free'){
                    Swal.fire({
                        title: "¡Atención!",
                        html: "<span>Sus boletos seran enviados al correo: </span><br><b>"+$('#email').val()+"</b><br><span>¿Es correcta esta información?</span>",
                        showCancelButton: true,
                        cancelButtonColor: '#3085d6',
                        cancelButtonText: 'Cancelar',
                        confirmButtonColor: '#d33',
                        confirmButtonText: 'Aceptar',
                        reverseButtons: true
                    }).then((result) => {
                        if (result.value) {
                            var msgAlert = 'Procesando pago, no cierre ni actualice esta página. Por favor espere!!';
                            var msgSuccess = '<span>Su pago se realizo con éxito </span><br><span>Recibira sus boletos por correo electrónico</span>';
                            jsPay(msgAlert, msgSuccess);
                        }
                    });
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    text: 'Debe elegir los turnos para cada día de evento',
                });
            }
        } else {
            Swal.fire({
                icon: 'error',
                text: 'Debe elegir al menos un boleto',
            });
        }
    } else {
        Swal.fire({
            icon: 'error',
            text: 'Los correos no coinciden',
        });
    }
});

var conektaSuccessReponseHandler = function(token) {
    $('#conektaTokenId').val(token.id);
    Swal.fire({
        title: 'Atención',
        html: "<span>Sus boletos seran enviados al correo: </span><br><b>"+$('#email').val()+"</b><br><span>¿Es correcta esta información?</span>",
        icon: 'warning',
        showCancelButton: true,
        cancelButtonColor: '#3085d6',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#d33',
        confirmButtonText: 'Aceptar',
        reverseButtons: true
    }).then((result) => {
        if (result.value) {
            var msgAlert = 'Procesando pago, no cierre ni actualice esta página. Por favor espere!!';
            var msgSuccess = '<span>Su pago se realizo con éxito </span><br><span>Recibira sus boletos por correo electrónico</span>';
            jsPay(msgAlert, msgSuccess);
        }
    });
};

var conektaErrorReponseHandler = function(response) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: response.message_to_purchaser
    });
};

var sales = false;
var idTickets = [], quantities = [];
var total = 0, totalTickets = 0;
var turns = new Array();
var indicatorTurns = new Array();
var ticketsGenerated=[];

function calculateTotals(indicator = null) {
    var prices = [], names = [];
    var pos = 0, quantitytTickets = 0;
    var tbody = ''; 
    ths.ticketsGenerated=[];
    total = 0;
    $('#body-comisions').html('');
    $(".quantities").each(function (e) {
        quantities[pos] = parseInt($(this).val());
        pos++;
    });
    pos = 0;
    posTurns = 0;
    $(".prices").each(function (e) {
        prices[pos] = parseInt($(this).val());
        names[pos] = $(this).attr('data-name');
        idTickets[pos] = $(this).attr('data-idTicket');
        pos++;
    });
    tbody += '<tr><td class="bold">Producto</td><td class="text-right bold">Cantidad</td><td class="text-right bold">P/U</td><td class="text-right bold">Subtotal</td></tr>';
    for (var i = 0; i < quantities.length; i++) {
        posTurns = 0;
        turns[i] = new Array();
        indicatorTurns[i] = false;
        // if($(".selectTurn"+i).val() != undefined) {
            
        // }
        $(".selectTurn"+i).each(function (e) {
            turns[i][posTurns] = $(this).val();
            indicatorTurns[i] = true;
            posTurns++;
        });
        quantitytTickets = quantitytTickets + quantities[i];
        total = total + quantities[i] * prices[i];
        if (quantities[i] > 0) {
            sales = true;
            ths.ticketsGenerated.push({
                name:names[i],
                quanties:quantities[i],
                id:idTickets[i]
            });
            tbody += '<tr><td>'+names[i]+'</td><td class="text-right">'+quantities[i]+'</td><td class="text-right">$'+formatMoney(prices[i])+' MXN</td><td class="text-right">$'+formatMoney(quantities[i] * prices[i])+' MXN</td></tr>';
        }
    }
    if ($('#model_payment').val() == 'separated') {
        var comision = (total * (0.12));
        var tbodyComisions = '<tr><td colspan="3">Comisiones</td><td class="text-right">$'+formatMoney(comision)+' MXN</td></tr>';
        totalTickets = total;
        total = total + comision;
        $('#body-comisions').html(tbodyComisions);
    }
    if (indicator == null) {
        $('#quantityTickets').text(quantitytTickets);
        $('#total').text('$'+formatMoney(total)+' MXN');
        if(total>0){
            ths.cost_type="paid";
        }else{
            ths.cost_type="free";
        }
        
    } else {
        $('#body-sale').html(tbody);
        $('#total-sale').html('$'+formatMoney(total)+' MXN');
    }
}

function formatMoney(number, decPlaces, decSep, thouSep) {
    decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
    decSep = typeof decSep === "undefined" ? "." : decSep;
    thouSep = typeof thouSep === "undefined" ? "," : thouSep;
    var sign = number < 0 ? "-" : "";
    var i = String(parseInt(number = Math.abs(Number(number) || 0).toFixed(decPlaces)));
    var j = (j = i.length) > 3 ? j % 3 : 0;
    
    return sign +
        (j ? i.substr(0, j) + thouSep : "") +
        i.substr(j).replace(/(\decSep{3})(?=\decSep)/g, "$1" + thouSep) +
        (decPlaces ? decSep + Math.abs(number - i).toFixed(decPlaces).slice(2) : "");
}

function jsPay(msgAlert, msgSuccess) {
    jsShowWindowLoad(msgAlert);
    // console.log("jsPay");
    
    // var formDataComplete= new FormData();
    
    // formDataComplete.append("_token",$("meta[name='csrf-token']").attr("content"));
    // formDataComplete.append("conektaTokenId",$('#conektaTokenId').val());
    // formDataComplete.append("name",$('#name').val());
    // formDataComplete.append("email",$('#email').val());
    // formDataComplete.append("phone",$('#phone').val());
    // formDataComplete.append("card",$('#card').val());
    // formDataComplete.append("quantities",JSON.stringify(quantities));
    // formDataComplete.append("tickets",JSON.stringify(idTickets));
    // formDataComplete.append("payment_method",$('#payment-method').val());
    // formDataComplete.append("event_id",$('#idEvent').val());
    // formDataComplete.append("turns",JSON.stringify(turns));
    // formDataComplete.append("indicatorTurns",indicatorTurns);
    // formDataComplete.append("globlaDataOrder",ths.globlaDataOrder);
   
   //console.log(turns);
    $.ajax({
        dataType: 'json',
        url: $('#URL').val()+'makePayment',
        method: 'post',
        // processData: false,
        // contentType: false, 
        data: {
            "_token": $("meta[name='csrf-token']").attr("content"),
            conektaTokenId: $('#conektaTokenId').val(),
            name: $('#name').val(),
            email: $('#email').val(),
            phone: $('#phone').val(),
            card: $('#card').val(),
            quantities: quantities,
            tickets: idTickets,
            payment_method: $('#payment-method').val(),
            event_id: $('#idEvent').val(),
            turns : turns,
            indicatorTurns: indicatorTurns,
            globlaDataOrder:ths.globlaDataOrder
        },
        // data:formDataComplete,
        success: function(response) {
            if (response.status == true) {
                // $('#modalSale').modal('hide');
                jsRemoveWindowLoad();
                Swal.fire({
                    icon: 'success',
                    title: 'Correcto',
                    html: msgSuccess,
                });
                // setTimeout(function(){
                //     location.reload();
                // },3000);
            } else {
                jsRemoveWindowLoad();
                if(response.error == 'exceeded') {
                    Swal.fire({
                        icon: 'warning',
                        title: '¡Atención!',
                        html: response.msj,
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        html: response.error,
                    });
                }
            }
        },
        error: function() {
            Swal.fire({
                icon: 'error',
                title: 'Lo sentimos ocurrio un error',
                html: '<span>Si el problema persiste intente con otro navegador</span><br><span>o contacte a soporte</span>',
            });
            jsRemoveWindowLoad();
        },
    });
    

}



