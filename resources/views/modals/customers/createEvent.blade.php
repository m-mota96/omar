<div class="modal fade" id="modalCreateEvent" tabindex="-1" aria-labelledby="modalCreateEventLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formCreateEvent">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCreateEventLabel">Crear evento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-6 mb-3">
                            <label>Nombre del evento:</label>
                            <input class="form-control" type="text" id="nameEvent" required autocomplete="off">
                            <span class="alerts" id="txt_name_alert">Este nombre no esta disponible</span>
                            <span class="alerts-success" id="txt_name_success">Este nombre esta disponible</span>
                        </div>
                        <div class="col-xl-6 mb-3">
                            <label>Sitio web:</label>
                            <div class="row">
                                <div class="col-xl-4 pt-1">
                                    <h5 class="font-weight-bolder">bolteos.com/</h5>
                                </div>
                                <div class="col-xl-8">
                                    <input class="form-control" type="text" id="website" required autocomplete="off">
                                </div>
                            </div>
                            <span class="alerts" id="txt_website_alert">Este sitio web no esta disponible</span>
                            <span class="alerts-success" id="txt_website_success">Este sitio web esta disponible</span>
                        </div>
                        <div class="col-xl-6 mb-3">
                            <label>Asistencia estimada:</label>
                            <input class="form-control" type="number" min="1" id="quantity" required>
                        </div>
                         <div class="col-xl-6 mb-3">
                            <label>Categoría:</label>
                            <select class="form-control" id="categorySelect" aria-label="Default select example" required>
                                <option value="0" selected disabled>Seleccione una categoría</option>
                            </select>
                        </div>
                        <div class="col-xl-6 mb-3">
                            <label>Tipo de evento:</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input pointer inputs-radio" type="radio" name="cost_type" id="paid" value="paid" checked >
                                <label class="form-check-label pointer inputs-radio" for="paid">De consumo</label>
                            </div>
                            <div class="form-check form-check-inline mb-2 ml-3">
                                <input class="form-check-input pointer inputs-radio" type="radio" name="cost_type" id="free" value="free">
                                <label class="form-check-label pointer inputs-radio" for="free">De registro</label>
                            </div>
                        </div>
                        <div class="col-xl-6">
                            <label>Fechas del evento (<b>Inicial - Final</b>):</label><br>
                            {{-- <p class="btn btn-primary" data-finalId="1" id="moreDays">Añadir 1 día</p> --}}
                            <div class="input-group mb-2">
                                <input class="form-control" type="date" id="initial_date" required>
                                <div class="input-group-prepend">
                                    <div class="input-group-text bold">-</div>
                                </div>
                                <input class="form-control" type="date" id="final_date" required>
                            </div>
                            <span class="text-red hidden" id="incorrectDates">La fecha inicial debe ser menor o igual que la fecha final</span>
                        </div>

                        <div class="col-xl-8 mb-4" id="divSchedules">
                            <div class="form-check form-check-inline mt-1 pb-1">
                                <input class="form-check-input pointer" type="checkbox" id="indicatorSchedule" value="option1">
                                <label class="form-check-label pointer" for="indicatorSchedule">Los horarios serán los mismos para todos los días</label>
                            </div>
                        </div>
                        <div class="col-xl-12 mb-3" id="divDates">
                            
                        </div>
                        {{-- <div class="col-xl-12 mb-3">
                            <label>Finaliza:</label>
                            <div class="input-group">
                                <input class="form-control" type="date" id="final_date" required>
                                <div class="input-group-prepend">
                                  <div class="input-group-text"> De:</div>
                                </div>
                                <input class="form-control" type="time" id="initial_time_end" required>
                                <div class="input-group-prepend">
                                    <div class="input-group-text">a: </div>
                                </div>
                                <input class="form-control" type="time" id="final_time_end" required>
                            </div>
                        </div> --}}
                        <div class="col-xl-12 mb-3">
                            <label>Describe tu evento:</label>
                            <textarea class="form-control" id="description" rows="5"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="submitCreateEvent">Crear evento</button>
                </div>
            </form>
        </div>
    </div>
</div>