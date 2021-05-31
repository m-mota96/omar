function createTurnsContent(dates) {
   var indicator = false;
   for (var i = 0; i < dates.length; i++) {
      if (dates[i].turns.length > 0) {
         indicator = true;
      }
   }
   if (indicator == false) {
      var contentTurns = '<table class="w-100">';
      for (var i = 0; i < dates.length; i++) {
         contentTurns += '<tbody id="tbody'+i+'">';
         contentTurns += '<tr><td class="pl-1" colspan="4"><h5 class="mt-4"><b>Día '+(i+1)+': </b>'+dates[i].date+' <span class="btn btn-success ml-3 btn-sm" onclick="moreTurns('+i+')"><i class="fas fa-plus"></i> Añadir 1 turno</span></h5><input type="hidden" name="dateId['+i+']" value="'+dates[i].id+'"></td></tr>';
         contentTurns += '<tr class="trTurns" id="tr0">';
            contentTurns += '<td class="pl-1 pr-1"><label>Nombre del turno: </label><input class="form-control mb-3 names" type="text" placeholder="p.ej: Turno A" name="nameTurn['+i+'][0]" required></td>';
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
            contentTurns += '<td class="pl-1 pr-1"><label>No. de accesos: </label><input class="form-control mb-3 quantity" type="number" name="quantity['+i+'][0]"></td>';
            contentTurns += '<td class="pl-1 pr-1 pt-2"><span class="btn btn-danger mt-2" onClick="deleteTurn('+i+', 0)"><i class="fas fa-trash-alt"></i></span></td>';
         contentTurns += '</tr></tbody>';
      }
      contentTurns += '</table>';
      contentTurns += '<div class="col-xl-12 text-center mt-4"><button class="btn btn-primary" type="submit">Guardar</button></div>';
      $('#contentTurns').html(contentTurns);
   } else {
      var contentTurns = '<table class="w-100">';
      for (var i = 0; i < dates.length; i++) {
            contentTurns += '<tbody id="tbody'+i+'">';
               contentTurns += '<tr><td class="pl-1" colspan="4"><h5 class="mt-4"><b>Día '+(i+1)+': </b>'+dates[i].date+' <span class="btn btn-success ml-3 btn-sm" onclick="moreTurns('+i+')"><i class="fas fa-plus"></i> Añadir 1 turno</span></h5><input type="hidden" name="dateId['+i+']" value="'+dates[i].id+'"></td></tr>';
               for (var k = 0; k < dates[i].turns.length; k++) {
                  contentTurns += '<tr class="trTurns" id="tr'+k+'">';
                     contentTurns += '<td class="pl-1 pr-1"><label>Nombre del turno: </label><input class="form-control mb-3 names" type="text" placeholder="p.ej: Turno A" name="nameTurn['+i+']['+k+']" value="'+dates[i].turns[k].name+'" required></td>';
                     contentTurns += '<td class="pl-1 pr-1"><label>Hora inicio: </label>';
                        contentTurns += '<div class="input-group mb-3">';
                           contentTurns += '<select type="text" class="form-control hourInitial" name="hourInitial['+i+']['+k+']">';
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
                           contentTurns += '<select type="text" class="form-control minuteInitial" name="minuteInitial['+i+']['+k+']">';
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
                           contentTurns += '<select type="text" class="form-control hourFinal" name="hourFinal['+i+']['+k+']">';
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
                           contentTurns += '<select type="text" class="form-control minuteFinal" name="minuteFinal['+i+']['+k+']">';
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
                     contentTurns += '<td class="pl-1 pr-1"><label>No. de accesos: </label><input class="form-control mb-3 quantity" type="number" name="quantity['+i+']['+k+']"" value="'+dates[i].turns[k].quantity+'"></td>';
                     contentTurns += '<td class="pl-1 pr-1 pt-2"><span class="btn btn-danger mt-2" onClick="deleteTurn('+i+', '+k+')"><i class="fas fa-trash-alt"></i></span></td>';
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
   var id = $("#tbody"+idDom+"  .trTurns").length;
   var contentTurns = '<tr class="trTurns"  id="tr'+id+'">';
      contentTurns += '<td class="pl-1 pr-1"><label>Nombre del turno: </label><input class="form-control mb-3 names" type="text" placeholder="p.ej: Turno A" name="nameTurn['+idDom+']['+id+']"></td>';
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
      contentTurns += '<td class="pl-1 pr-1"><label>No. de accesos: </label><input class="form-control mb-3 quantity" type="number" name="quantity['+idDom+']['+id+']"></td>';
      contentTurns += '<td class="pl-1 pr-1 pt-2"><span class="btn btn-danger mt-2" onClick="deleteTurn('+idDom+', '+id+')"><i class="fas fa-trash-alt"></i></span></td>';
   contentTurns += '</tr>';
   $('#tbody'+idDom).append(contentTurns);
}

function deleteTurn(idTbody, idElement) {
   $('#tbody'+idTbody+" #tr"+idElement).remove();
   // $('.btn-success').attr('disabled', true);
   var cont = 0;
   $('#tbody'+idTbody+' .trTurns').each(function (e) {
      $(this).attr('id', 'tr'+cont);
      cont++;
   });
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
   // $('.btn-success').attr('disabled', false);
}

$('#formTurns').submit(function(e) {
   e.preventDefault();
   $.ajax({
      url: $('#URL').val()+'saveTurns',
      method: 'post',
      data: $("#formTurns").serialize(),
      success: (res)=> {
         if (res.status == true) {
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