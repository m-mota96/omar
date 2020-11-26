<div class="modal fade" id="modalAccount" tabindex="-1" aria-labelledby="modalAccountLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="formAccount" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalAccountLabel">Editar perfil</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label>Nombre</label>
                    <input class="form-control mb-3" type="text" id="name" value="{{Auth::user()->name}}">
                    <label>Teléfono</label>
                    <input class="form-control mb-3" type="number" id="phone" value="{{Auth::user()->phone}}">
                    <label>Correo electrónico</label>
                    <input class="form-control mb-3" type="email" value="{{Auth::user()->email}}" disabled>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar cambios</button>
                </div>
            </form>
        </div>
    </div>
</div>