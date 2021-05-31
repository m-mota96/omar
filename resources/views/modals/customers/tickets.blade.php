<div class="modal fade" id="modalTickets" tabindex="-1" aria-labelledby="modalTicketsLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formTickets" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTicketsLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-6 mb-3">
                            <input class="form-control" type="hidden" id="ticket_id">
                            <label class="bold">Nombre</label>
                            <input class="form-control mb-3" type="text" id="nameTicket" placeholder="p.ej: General, VIP, Paquete Full Beneficios" required>
                            <label class="bold">Descripcion <span class="text-gray-600 normal">(Opcional)</span></label>
                            <textarea class="form-control mb-3" id="descriptionTicket" rows="3"></textarea>
                            <div class="row mb-5 mt-3 pl-3 pr-3" id="divSlider">
                                <label class="bold">¿Para cuantos días servira este boleto?</label>
                                
                            </div>
                            <div class="row mb-3">
                                <div class="col-xl-6">
                                    <label class="bold">A la venta desde...</label>
                                    <input class="form-control" type="date" id="start_sale" required>
                                </div>
                                <div class="col-xl-6">
                                    <label class="bold">...hasta</label>
                                    <input class="form-control" type="date" id="stop_sale" required>
                                </div>
                            </div>
                            <label class="bold">¿Desea aplicar descuento para este boleto?</label><br>
                            <div class="form-check mb-3">
                                <input class="form-check-input pointer" type="checkbox" value="" id="promotion">
                                <label class="form-check-label pointer" for="promotion">
                                    Si
                                </label>
                            </div>
                            <div class="row mb-3 hidden" id="divPromotions">
                                <div class="col-xl-12">
                                    <label class="bold">Ingrese el porcentaje de descuento</label>
                                    <input class="form-control mb-3 col-xl-6" type="text" id="discount">
                                    <label class="bold">¿Cuando finaliza la promoción?</label>
                                    <input class="form-control col-xl-6" type="date" id="date_promotion">
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-6 mb-3">
                            <label class="bold">Costo</label><br>
                            <div class="form-check form-check-inline mr-5">
                                <input class="form-check-input pointer inputs-radio" type="radio" name="price" id="cover" value="1" checked>
                                <label class="form-check-label pointer inputs-radio" for="cover">Con pago</label>
                            </div>
                            <div class="form-check form-check-inline mb-2 ml-5">
                                <input class="form-check-input pointer inputs-radio" type="radio" name="price" id="free" value="0">
                                <label class="form-check-label pointer inputs-radio" for="free">Gratis</label>
                            </div>
                            <input class="form-control col-xl-3 mb-2" type="number" id="priceTicket" min="50" required>
                            <p><i>* Las comisiones se cobran aparte del precio de tu boleto</i></p>
                            <div class="row mb-3 pt-3">
                                <div class="col-xl-6">
                                    <label class="bold">Min boletos por reservación</label>
                                    <input class="form-control col-xl-6" type="number" id="min_reservation" min="1" value="1" required>
                                </div>
                                <div class="col-xl-6">
                                    <label class="bold">Max boletos por reservación</label>
                                    <input class="form-control col-xl-6" type="number" id="max_reservation" required>
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-xl-6">
                                    <label class="bold">Cantidad disponible</label>
                                    <input class="form-control col-xl-5" type="number" id="quantity" min="1" required>
                                </div>
                            </div>
                            <label class="bold">Desea utilizar turnos para este boleto:</label><br>
                            @if ($indicatorTurns == true)
                                <div class="form-check form-check-inline mb-2">
                                    <input class="form-check-input pointer inputs-radio" type="radio" name="turns" value="0" id="turnInactive" checked>
                                    <label class="form-check-label pointer inputs-radio" for="turnInactive">No</label>
                                </div>
                                <div class="form-check form-check-inline ml-5">
                                    <input class="form-check-input pointer inputs-radio" type="radio" name="turns" value="1"  id="turnActive">
                                    <label class="form-check-label pointer inputs-radio" for="turnActive">Si</label>
                                </div>
                            @else
                                <label>Aun no ha registrado turnos, para crealos haga <a href="{{route('customer.turns', $event_id)}}">click aquí</a></label>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="submitTickets">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>