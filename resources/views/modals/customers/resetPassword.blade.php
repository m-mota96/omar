<div class="modal fade" id="modalResetPassword" tabindex="-1" aria-labelledby="modalResetPasswordLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <form id="formResetPassword" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalResetPasswordLabel">Cambiar contraseña</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label>Nueva contraseña</label>
                    <input class="form-control mb-3" type="password" id="password">
                    <label>Confirmación de la nueva contraseña</label>
                    <input class="form-control mb-3" type="password" id="passwordConfirm">
                    <label>Contraseña actual</label>
                    <input class="form-control mb-3" type="password" id="lastPassword">
                    <p class="hidden text-red" id="instructions">
                        La nueva contraseña debe tener entre 8 y 16 caracteres, al menos un número, al menos una minúscula y al menos una mayúscula.
                    </p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Cambiar contraseña</button>
                </div>
            </form>
        </div>
    </div>
</div>