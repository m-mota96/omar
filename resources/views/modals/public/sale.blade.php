<div class="modal fade" id="modalSale" tabindex="-1" aria-labelledby="modalSaleLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            
            <form id="formSale" enctype="multipart/form-data">
                <div class="modal-header">
                    <h3 class="modal-title" id="modalSaleLabel">Realizar pago</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>


                <div id="page" style="display: block;  position: relative;  overflow: hidden;">
                    <div id="content-data" style="position: relative;">
                        <div class="modal-body">
                            
                            <h4 class="modal-title" id="modalSaleLabel"><strong>Datos de la orden</strong></h4>
                            <hr>
                            <div class="row">
                                <div class="col-sm-3">
                                    <label for="name">Nombre *</label>
                                    <input type="text" class="form-control" id="name" name="name[0][0]" required placeholder="Nombre">
                                </div>
                                <div class="col-sm-3">
                                    <label for="name">Correo *</label>
                                    <input type="email" class="form-control" id="email" name="email[0][0]" required placeholder="Correo">
                                </div>
                                <div class="col-sm-3">
                                    <label for="name">Confirmar correo *</label>
                                    <input type="email" class="form-control" id="confirmEmail[0][0]" required name="confirmEmail" placeholder="Confirmar correo">
                                </div>
                                <div class="col-sm-3">
                                    <label for="name">Teléfono *</label>
                                    <input type="text" class="form-control" id="phone[0][0]" required name="phone" placeholder="Teléfono">
                                </div>
                            </div>
                            <div class="row p-2">
                                <div class="col text-justify">
                                    <span class="mb-3 font-italic"><i class="fas fa-info-circle"></i> Debes de tener acceso al correo ya que a esta dirección se enviarán los boletos.</span>
                                </div>
                            </div>
                            <br>
                            <br>
                            <h4 class="modal-title" id="modalSaleLabel"><strong>Datos de los boletos</strong></h4>
                            <br>
                            <div class="container" id="container-tickets">
                                
                            </div>
                        </div>
                        <div class="modal-footer"> 
                            <button type="button" class="btn btn-info slidelink" id="showContentData" >Siguiente <i class="fas fa-arrow-right"></i></button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>

                    <div id="content-payment" style="position: relative;">
                        <div class="modal-body">
                                
                            <h4 class="modal-title" id="modalSaleLabel"><strong>Datos del pago</strong></h4>
                            <hr>


                            
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
                            <button type="button" class="btn btn-info leftsidelink" id="showContentPayment"><i class="fas fa-arrow-left"></i> Regresar</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary">Realizar pago</button>
                        </div>
                    </div>
                </div>
            </form>
            
        </div>
    </div>
</div>