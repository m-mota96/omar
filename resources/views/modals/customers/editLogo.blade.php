<div class="modal fade" id="modalEditLogo" tabindex="-1" aria-labelledby="modalEditLogoLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="formEditLogo" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditLogoLabel">Edita el logo del evento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label>Logo</label>
                    <div class="col-lg-12 upload rounded pt-5 pb-5 mb-4" id="upload2">
                        <text class="text-upload dz-message needsclick" div="drop">
                            <i class="fa fa-upload fa-2x valign "></i><br>
                            Suelte la imagen o haga click en el recuadro para cargar.
                        </text>
                    </div>
                    <p class="text-center">
                        <span class="btn btn-danger text-white" id="deletePreviewLogo"><i class="fas fa-trash-alt"></i></span>
                    </p>
                    <p><b>NOTA: </b>La imagen debe ser en formato jpg, png no mayor a 1MB. Recomendado 250px x 250px</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>