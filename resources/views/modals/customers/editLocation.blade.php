<div class="modal fade" id="modalEditLocation" tabindex="-1" aria-labelledby="modalEditLocationLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="formEditLocation" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLocationLabel">Edita la dirección del evento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-12 mb-3">
                            <label>Lugar</label>
                            <input class="form-control mb-3" type="text" id="locationEvent" placeholder="p.ej: Auditorio Luis Elizondo, Estadio La Bombonera" required>
                            <label>Dirección</label>
                            <input class="form-control mb-3" type="search" id="addressEvent" placeholder="Usa el siguiente formato: 'calle, colonia, ciudad'" required>
                            <input type="hidden" id="latitude">
                            <input type="hidden" id="longitude">
                            <label class="mb-1">Dirección</label>
                            <label class="font-small text-gray-600 mb-0">Se posiciona en la dirección que hayas ingresado. O puedes reubicarlo manualmente.</label>
                            <div class="w-100" id="map">

                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="submitEditLocation">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>