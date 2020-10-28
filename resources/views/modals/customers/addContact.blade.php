<div class="modal fade" id="modalAddContact" tabindex="-1" aria-labelledby="modalAddContactLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="formAddContact" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAddContactLabel">Editar información de contacto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xl-12">
                            <label>Correo electrónico <span class="text-gray-600">(Opcional)</span></label>
                            <input class="form-control mb-3" type="email" id="emailContact" value="{{$event->email}}">
                            <label>Teléfono <span class="text-gray-600">(Opcional)</span></label>
                            <input class="form-control mb-3" type="number" id="phoneContact" value="{{$event->phone}}">
                            <label>Twitter <span class="text-gray-600">(Opcional)</span></label>
                            <input class="form-control mb-3" type="text" id="twitterContact" placeholder="p.ej: boletos sin el @" value="{{$event->twitter}}">
                            <label>Facebook <span class="text-gray-600">(Opcional)</span></label>
                            <input class="form-control mb-3" type="url" id="facebookContact" placeholder="p.ej: https://facebook.com/boletos" value="{{$event->facebook}}">
                            <label>Instagram <span class="text-gray-600">(Opcional)</span></label>
                            <input class="form-control mb-3" type="url" id="instagramContact" placeholder="p.ej: https://www.instagram.com/boletos" value="{{$event->instagram}}">
                            <label>Página web <span class="text-gray-600">(Opcional)</span></label>
                            <input class="form-control mb-3" type="url" id="websiteContact" placeholder="p.ej: https://boletos.com" value="{{$event->website}}">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="submitAddContact">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>