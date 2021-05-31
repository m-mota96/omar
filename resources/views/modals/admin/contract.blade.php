<div class="modal fade" id="modalContract" tabindex="-1" aria-labelledby="modalContractLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="formContract" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalContractLabel">Subir contrato</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row pl-2 pr-2">
                        <label for="fileContract">
                            <input type="file" id="fileContract" required accept=".pdf">
                        </label>
                        <input type="hidden" id="user_id">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar documento</button>
                </div>
            </form>
        </div>
    </div>
</div>