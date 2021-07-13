<div class="modal fade" id="modalSale" tabindex="-1" aria-labelledby="modalSaleLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form id="formSale" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalSaleLabel">Realizar pago</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped w-100 rounded mb-4">
                        <thead class="bg-gray-dark-400 text-center">
                            <tr>
                                <td colspan="4">
                                    <h3 class="mb-0">Resumen de tu Compra</h3>
                                </td>
                            </tr>
                        </thead>
                        <tbody id="body-sale">

                        </tbody>
                        <tbody id="body-comisions">

                        </tbody>
                        <tbody>
                            <tr class="table-info">
                                <td class="bold text-right" colspan="3">TOTAL</td>
                                <td class="bold text-right" id="total-sale"></td>
                            </tr>
                        </tbody>
                    </table>
                    <label>Método de pago</label>
                    <select class="form-control mb-3" id="payment-method" required>
                        <option value="" selected disabled>Seleccione un método de pago</option>
                        <option value="card">Tarjeta de Crédito/Débito</option>
                        {{-- <option value="oxxo">Pago en OXXO</option> --}}
                        <option id="optionFree" value="free">Gratis</option>
                    </select>
                    <div class="row" id="divContentPayment">

                    </div>
                </div>
                <div class="modal-footer"> 
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Realizar pago</button>
                </div>
            </form>
        </div>
    </div>
</div>