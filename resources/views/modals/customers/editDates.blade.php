<div class="modal fade" id="modalEditDates" tabindex="-1" aria-labelledby="modalEditDatesLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formEditDates" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditDatesLabel">Edita las fechas de tu evento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
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
                    </div>
                    <div class="row">
                        <div class="col-xl-12 mb-3" id="divDates">
                            
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="submitEditDates">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>