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
                            <input class="form-control" type="text" id="nameEvent" required>
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
                                    <input class="form-control" type="text" id="website" required>
                                </div>
                            </div>
                            <span class="alerts" id="txt_website_alert">Este sitio web no esta disponible</span>
                            <span class="alerts-success" id="txt_website_success">Este sitio web esta disponible</span>
                        </div>
                        <div class="col-xl-6 mb-3">
                            <label>Asistencia estimada:</label>
                            <input class="form-control" type="number" min="1" id="quantity" required>
                        </div>
                        {{-- <div class="col-xl-6 mb-3">
                            <label>Precio de los boletos:</label>
                            <input class="form-control" type="number" min="1" id="price" required>
                        </div> --}}
                        {{-- <div class="col-xl-6 mb-3">
                            <label>Lugar del evento:</label>
                            <input class="form-control" type="text" id="location" required>
                        </div> --}}
                        <div class="col-xl-6 mb-3">
                            <label>Días de evento:</label>
                            <select class="form-control" id="daysEvent">
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                        <div class="col-xl-12 mb-3" id="dates">
                            <label>Día 1:</label>
                            <div class="input-group mb-2">
                                <input class="form-control inputs-dates-create-event" type="date" id="date_0" required>
                                <div class="input-group-prepend">
                                  <div class="input-group-text bold"> De:</div>
                                </div>
                                {{-- <input class="form-control" type="time" id="initial_time_start" required> --}}
                                <select class="form-control" id="initial_hour_0">
                                    <option value="00">00</option>
                                    <option value="01">01</option>
                                    <option value="02">02</option>
                                    <option value="03">03</option>
                                    <option value="04">04</option>
                                    <option value="05">05</option>
                                    <option value="06">06</option>
                                    <option value="07">07</option>
                                    <option value="08">08</option>
                                    <option value="09">09</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                    <option value="14">14</option>
                                    <option value="15">15</option>
                                    <option value="16">16</option>
                                    <option value="17">17</option>
                                    <option value="18">18</option>
                                    <option value="19">19</option>
                                    <option value="20">20</option>
                                    <option value="21">21</option>
                                    <option value="22">22</option>
                                    <option value="23">23</option>
                                </select>
                                <div class="input-group-prepend">
                                    <div class="input-group-text">:</div>
                                </div>
                                <select class="form-control" id="initial_minute_0">
                                    <option value="00">00</option>
                                    <option value="15">15</option>
                                    <option value="30">30</option>
                                    <option value="45">45</option>
                                </select>
                                <div class="input-group-prepend">
                                    <div class="input-group-text bold">a: </div>
                                </div>
                                {{-- <input class="form-control" type="time" id="final_time_start" required> --}}
                                <select class="form-control" id="final_hour_0">
                                    <option value="00">00</option>
                                    <option value="01">01</option>
                                    <option value="02">02</option>
                                    <option value="03">03</option>
                                    <option value="04">04</option>
                                    <option value="05">05</option>
                                    <option value="06">06</option>
                                    <option value="07">07</option>
                                    <option value="08">08</option>
                                    <option value="09">09</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                    <option value="14">14</option>
                                    <option value="15">15</option>
                                    <option value="16">16</option>
                                    <option value="17">17</option>
                                    <option value="18">18</option>
                                    <option value="19">19</option>
                                    <option value="20">20</option>
                                    <option value="21">21</option>
                                    <option value="22">22</option>
                                    <option value="23">23</option>
                                </select>
                                <div class="input-group-prepend">
                                    <div class="input-group-text">:</div>
                                </div>
                                <select class="form-control" id="final_minute_0">
                                    <option value="00">00</option>
                                    <option value="15">15</option>
                                    <option value="30">30</option>
                                    <option value="45">45</option>
                                </select>
                            </div>
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