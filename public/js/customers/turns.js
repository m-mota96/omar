var numberTurns=0;
var dayEvent=0;
var ths=this;
this.turnsEliminated=[];
this.turnsNews=[];
this.complementTr=0;

function createTurnsContent(dates) {

   console.log(dates);
   

   var indicator = false;

   for (var i = 0; i < dates.length; i++) {
      if (dates[i].turns.length > 0) {
         indicator = true;
      }
   }

   

   if (indicator == false) {
      var contentTurns = '<table class="w-100">';
      for (var i = 0; i < dates.length; i++) {
         this.complementTr=Date.now();
         contentTurns += '<tbody id="tbody'+i+'">';
         contentTurns += '<tr><td class="pl-1" colspan="4"><h5 class="mt-4"><b>Día '+(i+1)+': </b>'+dates[i].date+' <span class="btn btn-success ml-3 btn-sm" onclick="moreTurns('+i+')"><i class="fas fa-plus"></i> Añadir 1 turno</span></h5><input type="hidden" name="dateId['+i+']" value="'+dates[i].id+'"></td></tr>';
         contentTurns += '<tr class="trTurns" id="tr'+i+''+this.complementTr+'">';
            
         contentTurns +='<input type="hidden" id="idTurnNew'+i+'_0'+this.complementTr+'" class="idTurn" name="idTurn['+i+'][0]" value="'+dates[i]['id']+'"></input>';
         contentTurns +='<input type="hidden" id="turnId'+i+'_0'+this.complementTr+'" class="turnStatus" name="turnStatus['+i+'][0]" value="new"></input>';
         
         ths.turnsNews.push({
            idOld:'idTurnNew'+i+'_0'+''+this.complementTr,
            idNew:'',
            turnStatus:'turnId'+i+'_0'+this.complementTr
         });
            contentTurns += '<td class="pl-1 pr-1"><label>Nombre del turno: </label><input class="form-control mb-3 names" type="text" placeholder="p.ej: Turno A" name="nameTurn['+i+'][0]" value="" required></td>';
            contentTurns += '<td class="pl-1 pr-1"><label>Hora inicio: </label>';
               contentTurns += '<div class="input-group mb-3">';
                  contentTurns += '<select type="text" class="form-control hourInitial" name="hourInitial['+i+'][0]">';
                     for (var j = 0; j < 24; j++) {
                        if (j < 10) {
                           var option = '0'+j;
                        } else {
                           var option = j;
                        }
                        contentTurns += '<option value="'+option+'">'+option+' hrs</option>';
                     }
                  contentTurns += '</select>';
                  contentTurns += '<div class="input-group-prepend">';
                     contentTurns += '<span class="input-group-text" id="basic-addon1">:</span>';
                  contentTurns += '</div>';
                  contentTurns += '<select type="text" class="form-control minuteInitial" name="minuteInitial['+i+'][0]">';
                     for (var j = 0; j < 60; j+=15) {
                        if (j < 10) {
                           var option = '0'+j;
                        } else {
                           var option = j;
                        }
                        contentTurns += '<option value="'+option+'">'+option+' min</option>';
                     }
                  contentTurns += '</select>';
               contentTurns += '</div>';
            contentTurns += '</td>';
            contentTurns += '<td class="pl-1 pr-1"><label>Hora fin: </label>';
               contentTurns += '<div class="input-group mb-3">';
                  contentTurns += '<select type="text" class="form-control hourFinal" name="hourFinal['+i+'][0]">';
                     for (var j = 0; j < 24; j++) {
                        if (j < 10) {
                           var option = '0'+j;
                        } else {
                           var option = j;
                        }
                        contentTurns += '<option value="'+option+'">'+option+' hrs</option>';
                     }
                  contentTurns += '</select>';
                  contentTurns += '<div class="input-group-prepend">';
                     contentTurns += '<span class="input-group-text" id="basic-addon1">:</span>';
                  contentTurns += '</div>';
                  contentTurns += '<select type="text" class="form-control minuteFinal" name="minuteFinal['+i+'][0]">';
                     for (var j = 0; j < 60; j+=15) {
                        if (j < 10) {
                           var option = '0'+j;
                        } else {
                           var option = j;
                        }
                        contentTurns += '<option value="'+option+'">'+option+' min</option>';
                     }
                  contentTurns += '</select>';
               contentTurns += '</div>';
            contentTurns += '</td>';
            contentTurns += '<td class="pl-1 pr-1"><label>No. de accesos: </label><input class="form-control mb-3 quantity" type="number" name="quantity['+i+'][0]" min="0" required ></td>';
            contentTurns += '<td class="pl-1 pr-1 pt-2"><span class="btn btn-danger mt-2" onClick="deleteTurn('+i+','+i+',\'idTurnNew'+i+'_0\','+this.complementTr+')"><i class="fas fa-trash-alt"></i></span></td>';
         contentTurns += '</tr></tbody>';
      }
      contentTurns += '</table>';
      contentTurns += '<div class="col-xl-12 text-center mt-4"><button class="btn btn-primary" type="submit">Guardar</button></div>';
      $('#contentTurns').html(contentTurns);
   } else {
      var contentTurns = '<table id="table" class="w-100">';
      for (var i = 0; i < dates.length; i++) {
            contentTurns += '<tbody id="tbody'+i+'">';
               contentTurns += '<tr><td class="pl-1" colspan="4"><h5 class="mt-4"><b>Día '+(i+1)+': </b>'+dates[i].date+' <span class="btn btn-success ml-3 btn-sm" onclick="moreTurns('+i+')"><i class="fas fa-plus"></i> Añadir 1 turno</span></h5><input type="hidden" name="dateId['+i+']" value="'+dates[i].id+'"></td></tr>';
               for (var k = 0; k < dates[i].turns.length; k++) {
                  this.complementTr=Date.now();
                  contentTurns += '<tr class="trTurns"  id="tr'+k+''+this.complementTr+'">';
                     contentTurns +='<input type="hidden" class="idTurn" name="idTurn['+i+']['+k+']" value="'+dates[i].turns[k]['id']+'"></input>';
                     contentTurns +='<input type="hidden" class="turnStatus" id="turnId'+i+'_'+k+''+this.complementTr+'" name="turnStatus['+i+']['+k+']" value="noEdit"></input>';
                     contentTurns += '<td class="pl-1 pr-1"><label>Nombre del turno: </label><input onchange="detectarCambio('+i+','+k+''+this.complementTr+')" class="form-control mb-3 names" type="text" placeholder="p.ej: Turno A" name="nameTurn['+i+']['+k+']" value="'+dates[i].turns[k].name+'" required></td>';
                     contentTurns += '<td class="pl-1 pr-1"><label>Hora inicio: </label>';
                        contentTurns += '<div class="input-group mb-3">';
                           contentTurns += '<select type="text" class="form-control hourInitial" onchange="detectarCambio('+i+','+k+''+this.complementTr+')" name="hourInitial['+i+']['+k+']">';
                              for (var j = 0; j < 24; j++) {
                                 if (j < 10) {
                                    var option = '0'+j;
                                 } else {
                                    var option = j;
                                 }
                                 var selected = null;
                                 if (option == dates[i].turns[k].initial_hour.substr(0, 2)) {
                                    selected = 'selected';
                                 }
                                 contentTurns += '<option value="'+option+'" '+selected+'>'+option+' hrs</option>';
                              }
                           contentTurns += '</select>';
                           contentTurns += '<div class="input-group-prepend">';
                              contentTurns += '<span class="input-group-text" id="basic-addon1">:</span>';
                           contentTurns += '</div>';
                           contentTurns += '<select type="text" class="form-control minuteInitial" onchange="detectarCambio('+i+','+k+''+this.complementTr+')" name="minuteInitial['+i+']['+k+']">';
                              for (var j = 0; j < 60; j+=15) {
                                 if (j < 10) {
                                    var option = '0'+j;
                                 } else {
                                    var option = j;
                                 }
                                 var selected = null;
                                 if (option == dates[i].turns[k].initial_hour.substr(3, 2)) {
                                    selected = 'selected';
                                 }
                                 contentTurns += '<option value="'+option+'" '+selected+'>'+option+' min</option>';
                              }
                           contentTurns += '</select>';
                        contentTurns += '</div>';
                     contentTurns += '</td>';
                     contentTurns += '<td class="pl-1 pr-1"><label>Hora fin: </label>';
                        contentTurns += '<div class="input-group mb-3">';
                           contentTurns += '<select type="text" class="form-control hourFinal" onchange="detectarCambio('+i+','+k+''+this.complementTr+')" name="hourFinal['+i+']['+k+']">';
                              for (var j = 0; j < 24; j++) {
                                 if (j < 10) {
                                    var option = '0'+j;
                                 } else {
                                    var option = j;
                                 }
                                 var selected = null;
                                 if (option == dates[i].turns[k].final_hour.substr(0, 2)) {
                                    selected = 'selected';
                                 }
                                 contentTurns += '<option value="'+option+'" '+selected+'>'+option+' hrs</option>';
                              }
                           contentTurns += '</select>';
                           contentTurns += '<div class="input-group-prepend">';
                              contentTurns += '<span class="input-group-text" id="basic-addon1">:</span>';
                           contentTurns += '</div>';
                           contentTurns += '<select type="text" class="form-control minuteFinal" onchange="detectarCambio('+i+','+k+''+this.complementTr+')" name="minuteFinal['+i+']['+k+']">';
                              for (var j = 0; j < 60; j+=15) {
                                 if (j < 10) {
                                    var option = '0'+j;
                                 } else {
                                    var option = j;
                                 }
                                 var selected = null;
                                 if (option == dates[i].turns[k].final_hour.substr(3, 2)) {
                                    selected = 'selected';
                                 }
                                 contentTurns += '<option value="'+option+'" '+selected+'>'+option+' min</option>';
                              }
                           contentTurns += '</select>';
                        contentTurns += '</div>';
                     contentTurns += '</td>';
                     contentTurns += '<td class="pl-1 pr-1"><label>No. de accesos: </label><input onchange="detectarCambio('+i+','+k+''+this.complementTr+')" class="form-control mb-3 quantity" type="number" name="quantity['+i+']['+k+']"" value="'+dates[i].turns[k].quantity+'" min="0" required></td>';
                     contentTurns += '<td class="pl-1 pr-1 pt-2"><span class="btn btn-danger mt-2" onClick="deleteTurn('+i+', '+k+','+dates[i].turns[k]['id']+','+this.complementTr+')"><i class="fas fa-trash-alt"></i></span></td>';
                  contentTurns += '</tr>';
               }
            contentTurns += '</tbody>';
      }
      contentTurns += '</table>';
      contentTurns += '<div class="col-xl-12 text-center mt-4"><button class="btn btn-primary" type="submit">Guardar</button></div>';
      $('#contentTurns').html(contentTurns);



   }
}

function moreTurns(idDom) {
   this.complementTr=Date.now();
   var id = $("#tbody"+idDom+"  .trTurns").length;
   var contentTurns = '<tr class="trTurns"  id="tr'+id+''+this.complementTr+'">';
   contentTurns +='<input type="hidden" class="idTurn" id="idTurnNew'+idDom+'_'+id+''+this.complementTr+'" name="idTurn['+idDom+']['+id+']" value="'+id+'"></input>';
   contentTurns +='<input type="hidden" class="turnStatus" id="turnId'+idDom+'_'+id+''+this.complementTr+'" name="turnStatus['+idDom+']['+id+']" value="new"></input>';
      ths.turnsNews.push({
         idOld:'idTurnNew'+idDom+'_'+id+''+this.complementTr,
         idNew:'',
         turnStatus:'turnId'+idDom+'_'+id+''+this.complementTr
      });
      contentTurns += '<td class="pl-1 pr-1"><label>Nombre del turno: </label><input class="form-control mb-3 names" type="text" placeholder="p.ej: Turno A" name="nameTurn['+idDom+']['+id+']" value="" ></td>';
      contentTurns += '<td class="pl-1 pr-1"><label>Hora inicio: </label>';
               contentTurns += '<div class="input-group mb-3">';
                  contentTurns += '<select type="text" class="form-control hourInitial" name="hourInitial['+idDom+']['+id+']">';
                     for (var j = 0; j < 24; j++) {
                        if (j < 10) {
                           var option = '0'+j;
                        } else {
                           var option = j;
                        }
                        contentTurns += '<option value="'+option+'">'+option+' hrs</option>';
                     }
                  contentTurns += '</select>';
                  contentTurns += '<div class="input-group-prepend">';
                     contentTurns += '<span class="input-group-text" id="basic-addon1">:</span>';
                  contentTurns += '</div>';
                  contentTurns += '<select type="text" class="form-control minuteInitial" name="minuteInitial['+idDom+']['+id+']">';
                     for (var j = 0; j < 60; j+=15) {
                        if (j < 10) {
                           var option = '0'+j;
                        } else {
                           var option = j;
                        }
                        contentTurns += '<option value="'+option+'">'+option+' min</option>';
                     }
                  contentTurns += '</select>';
               contentTurns += '</div>';
            contentTurns += '</td>';
            contentTurns += '<td class="pl-1 pr-1"><label>Hora fin: </label>';
            contentTurns += '<div class="input-group mb-3">';
               contentTurns += '<select type="text" class="form-control hourFinal" name="hourFinal['+idDom+']['+id+']">';
                  for (var j = 0; j < 24; j++) {
                     if (j < 10) {
                        var option = '0'+j;
                     } else {
                        var option = j;
                     }
                     contentTurns += '<option value="'+option+'">'+option+' hrs</option>';
                  }
               contentTurns += '</select>';
               contentTurns += '<div class="input-group-prepend">';
                  contentTurns += '<span class="input-group-text" id="basic-addon1">:</span>';
               contentTurns += '</div>';
               contentTurns += '<select type="text" class="form-control minuteFinal" name="minuteFinal['+idDom+']['+id+']">';
                  for (var j = 0; j < 60; j+=15) {
                     if (j < 10) {
                        var option = '0'+j;
                     } else {
                        var option = j;
                     }
                     contentTurns += '<option value="'+option+'">'+option+' min</option>';
                  }
               contentTurns += '</select>';
            contentTurns += '</div>';
         contentTurns += '</td>';
      contentTurns += '<td class="pl-1 pr-1"><label>No. de accesos: </label><input class="form-control mb-3 quantity" type="number" name="quantity['+idDom+']['+id+']" min="0" required></td>';
      contentTurns += '<td class="pl-1 pr-1 pt-2"><span class="btn btn-danger mt-2" onClick="deleteTurn('+idDom+', '+id+',0,'+this.complementTr+')"><i class="fas fa-trash-alt"></i></span></td>';
   contentTurns += '</tr>';
   $('#tbody'+idDom).append(contentTurns);

   /** Nuevos Inputs */
   var cont = 0;
   $('#tbody'+idDom+' .trTurns .idTurn').each(function (e) {
      $(this).attr('name', 'idTurn['+idDom+']['+cont+']');
      cont++;
   });
   cont = 0;
   $('#tbody'+idDom+' .trTurns .turnStatus').each(function (e) {
      $(this).attr('name', 'turnStatus['+idDom+']['+cont+']');
      cont++;
   });
}


function deleteTurn(idTbody, idElement,idTurn,_complementTr) {


   /* Obtiene el id de los turnos registrados y los almacena en ths.turnsEliminated*/
   if(typeof idTurn == 'string'){
      var idTurn= $('#'+idTurn+''+_complementTr).val();
      ths.turnsEliminated.push(idTurn);
      console.log(">> if "+idTurn);
   }else{
      console.log(">> else"+idTurn);
      if(idTurn == 0){
         var idTurn= $('#idTurnNew'+idTbody+'_'+idElement+''+_complementTr).val();
         ths.turnsEliminated.push(idTurn);

      }else{
         //console.log(">> turnId"+idTbody+'_'+idElement);
         if($('#turnId'+idTbody+'_'+idElement+''+_complementTr).val()=='edit' || $('#turnId'+idTbody+'_'+idElement+''+_complementTr).val()=='noEdit'){
            //var auxIdTurn=$('#idTurn'+idTbody+'_'+idElement).val();
            ths.turnsEliminated.push(idTurn);
         }
      } 
   }
                       


   $('#tbody'+idTbody+" #tr"+idElement+''+_complementTr).remove();
   // $('.btn-success').attr('disabled', true);
   var cont = 0;/*
   $('#tbody'+idTbody+' .trTurns').each(function (e) {
      $(this).attr('id', 'tr'+cont);
      console.log(""+cont);
      cont++;
   });
   */

   cont = 0;
   $('#tbody'+idTbody+' .trTurns .names').each(function (e) {
      $(this).attr('name', 'nameTurn['+idTbody+']['+cont+']');
      cont++;
   });
   cont = 0;
   $('#tbody'+idTbody+' .trTurns .hourInitial').each(function (e) {
      $(this).attr('name', 'hourInitial['+idTbody+']['+cont+']');
      cont++;
   });
   cont = 0;
   $('#tbody'+idTbody+' .trTurns .minuteInitial').each(function (e) {
      $(this).attr('name', 'minuteInitial['+idTbody+']['+cont+']');
      cont++;
   });
   cont = 0;
   $('#tbody'+idTbody+' .trTurns .hourFinal').each(function (e) {
      $(this).attr('name', 'hourFinal['+idTbody+']['+cont+']');
      cont++;
   });
   cont = 0;
   $('#tbody'+idTbody+' .trTurns .minuteFinal').each(function (e) {
      $(this).attr('name', 'minuteFinal['+idTbody+']['+cont+']');
      cont++;
   });
   cont = 0;
   $('#tbody'+idTbody+' .trTurns .quantity').each(function (e) {
      $(this).attr('name', 'quantity['+idTbody+']['+cont+']');
      cont++;
   });

   /** Nuevos Inputs */
   cont = 0;
   $('#tbody'+idTbody+' .trTurns .idTurn').each(function (e) {
      $(this).attr('name', 'idTurn['+idTbody+']['+cont+']');
      cont++;
   });
   cont = 0;
   $('#tbody'+idTbody+' .trTurns .turnStatus').each(function (e) {
      $(this).attr('name', 'turnStatus['+idTbody+']['+cont+']');
      cont++;
   });
}


$('#formTurns').submit(function(e) {
   e.preventDefault();
   
   $.ajax({
      url: $('#URL').val()+'saveTurns',
      method: 'post',
      data: $("#formTurns").serialize()+'&turnsEliminated=' + ths.turnsEliminated+'&turnsNews='+JSON.stringify(ths.turnsNews),
      success: (res)=> {
         if (res.status == true) {
            ths.turnsEliminated=[];

            /*Actauliza los id de los turnos agregados y cambia es estatus del turno a edit*/
            if(res.idsTurnsNews.length>0){
               res.idsTurnsNews.forEach(turn => {
                  $('#'+turn.idOld).val(''+turn.idNew)
                  $('#'+turn.turnStatus).val("edit");
               });
               ths.turnsNews=[];
            }
            
            Swal.fire({
               position: 'bottom-end',
               icon: 'success',
               text: 'La información se guardo correctamente',
               showConfirmButton: false,
               timer: 2000
           });
         }
      },
      error: ()=> {
         console.log('ERROR');
      }
   })
   
   
   

});


function sidebar() {
   $('#sidebar').removeClass('hidden');
}

/*Obtiene cualquier cambio que se realice en la tabla*/
function detectarCambio(_day,_turn){
   $('#turnId'+_day+'_'+_turn).val("edit");
}
