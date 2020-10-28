<div class="modal fade" id="modalEditDescription" tabindex="-1" aria-labelledby="modalEditDescriptionLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formEditDescription" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditDescriptionLabel">Edita la descripción de tu evento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-12 mb-3">
                            <label>Descripción</label>
                            <textarea class="form-control" id="description" rows="10" required>{{$event->description}}</textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="submitEditDescription">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>