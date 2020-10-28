<div class="modal fade" id="modalEditNameWebsite" tabindex="-1" aria-labelledby="modalEditNameWebsiteLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="formEditNameWebsite" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditNameWebsiteLabel">Edita el nombre y sitio de ventas de tu evento</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-12 mb-3">
                            <label>Nombre del evento</label>
                            <input class="form-control" type="text" id="nameEvent" required value="{{$event->name}}" autocomplete="off">
                            <span class="alerts" id="txt_name_alert">Este nombre no esta disponible</span>
                            <span class="alerts-success" id="txt_name_success">Este nombre esta disponible</span>
                        </div>
                        <div class="col-xl-12 mb-3">
                            <label>Sitio de ventas</label>
                            <div class="row">
                                <div class="col-xl-4 pt-1">
                                    <h5 class="font-weight-bolder">bolteos.com/</h5>
                                </div>
                                <div class="col-xl-8">
                                    <input class="form-control" type="text" id="website" required value="{{$event->url}}" autocomplete="off">
                                </div>
                            </div>
                            <span class="alerts" id="txt_website_alert">Este sitio web no esta disponible</span>
                            <span class="alerts-success" id="txt_website_success">Este sitio web esta disponible</span>
                        </div>
                        <div class="col-xl-12 mb-3">
                            <label>Asistencia estimada</label>
                            <input class="form-control" type="number" id="quantity" required value="{{$event->quantity}}" autocomplete="off">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="submitEditNameWebsite">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>