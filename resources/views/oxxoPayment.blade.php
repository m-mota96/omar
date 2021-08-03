<div class="col-xl-12 mb-12 pr-0">
    <div class="form-check">
        <input class="form-check-input" onchange="completeDataPaymet()" type="checkbox" value="" id="autocompleted_DataPayment">
        <label class="form-check-label pointer" for="autocompleted_DataPayment">
            Autocompletar los datos de tarjeta con los de la orden.
        </label>
    </div>
</div>
<div class="col-xl-6 mb-3">
    <input class="form-control" type="text" placeholder="Nombre completo" name="name" id="name" required>
</div>
<div class="col-xl-6 mb-3">
    <input class="form-control" type="number" placeholder="Teléfono/Celular" name="phone" id="phone" maxlength="10" minlength="10" required>
</div>
<div class="col-xl-6 mb-3">
    <input class="form-control" type="email" placeholder="Correo electrónico" name="email" id="email" required>
</div>
<div class="col-xl-6 mb-3">
    <input class="form-control" type="email" placeholder="Confirmar correo electrónico" name="confirmEmail" id="confirmEmail" autocomplete="off" required>
</div>