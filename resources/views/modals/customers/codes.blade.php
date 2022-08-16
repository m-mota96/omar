<div class="modal fade" id="modalCodes" tabindex="-1" aria-labelledby="modalCodesLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="formCodes" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalCodesLabel"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-6">
                            {{ csrf_field() }}
                            <input type="hidden" id="current_date" value="{{date('Y-m-d')}}">
                            <input class="form-control" type="hidden" name="code_id" id="code_id">
                            <label class="bold">Ingrese el código <span class="badge badge-primary pointer" onclick="generateCode()">Generar código</span></label>
                            <input class="form-control mb-3 to-uppercase" type="text" name="code" id="code" required autocomplete="off">
                            <label class="bold">Cantidad disponible</label>
                            <input class="form-control mb-3" type="number" name="quantity" id="quantity" required min="1">
                            <label class="bold">Fecha de expiración</label>
                            <input class="form-control mb-3" type="date" name="expiration" id="expiration" required>
                        </div>
                        <div class="col-xl-6">
                            <label class="bold">Porcentaje de descuento</label>
                            <input class="form-control mb-3" type="number" name="discount" id="discount" required>
                            <label class="bold">Asignar código a</label>
                            <select class="form-control mb-3" name="ticket_id" id="ticket_id" multiple="multiple" required>
                                {{-- <option value="" selected disabled>Elija un boleto</option> --}}
                                @foreach($tickets as $key => $t)
                                    <option value="{{$t->id}}">{{$t->name}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-xl-12">
                            <hr class="w-100">
                        </div>
                        <div class="col-xl-6">
                            <label class="bold">Empresa o influencer </label>
                            <span class="text-gray-600 normal">(Opcional)</span>
                            <input class="form-control mb-3" type="text" placeholder="Ingrese el nombre" name="customer_name" id="customer_name">
                        </div>
                        <div class="col-xl-6">
                            <label class="bold">Correo electrónico </label>
                            <span class="text-gray-600 normal">(Opcional)</span>
                            <input class="form-control mb-3" type="email" name="email" id="email">
                        </div>
                        <div class="col-xl-6">
                            <label class="bold">Contraseña </label>
                            <span class="text-gray-600 normal">(Opcional)</span>
                            <input class="form-control mb-3" type="text" name="password" id="password">
                        </div>
                        <div class="col-xl-6">
                            <label class="bold">Confirmar contraseña </label>
                            <span class="text-gray-600 normal">(Opcional)</span>
                            <input class="form-control mb-3" type="text" name="password_confirm" id="password_confirm">
                        </div>
                        <div class="col-xl-12">
                            <b class="text-danger">Nota: </b><span>si la empresa o el influencer ya estan registrados solo ingrese el nombre y el correo electrónico</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="submitCodes">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>