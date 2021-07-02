<div class="modal fade" id="modalEditCategory" tabindex="-1" aria-labelledby="modalEditCategoryLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formEditCategory" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditCategoryLabel">Edita la categoría del evento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-12 mb-3">
                        <select class="form-control" id="categorySelect" aria-label="Default select example" required>
                            <option value="0" selected>Seleccione una categoría</option>
                        </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="submitEditCategory">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>