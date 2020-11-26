<div class="modal fade" id="modaldetailsSale" tabindex="-1" aria-labelledby="modaldetailsSaleLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="formdetailsSale" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modaldetailsSaleLabel">Detalles de la compra</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="w-100 table table-striped">
                        <thead>
                            <tr>
                                <th>Tipo de boleto</th>
                                <th>Precio</th>
                                <th>Estatus</th>
                            </tr>
                        </thead>
                        <tbody id="tbodyDetails">

                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>