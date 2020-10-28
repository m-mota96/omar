<div class="col-xl-6 mb-3 pr-0">
    <input class="form-control" type="email" placeholder="Correo electrónico" name="email" id="email">
</div>
<div class="col-xl-6 mb-3 pr-0">
    <input class="form-control" type="email" placeholder="Confirmar correo electrónico" name="confirmEmail" id="confirmEmail">
</div>
<div class="col-xl-6 mb-3 pr-0">
    <input type="hidden" id="conektaTokenId">
    <input class="form-control" type="text" placeholder="Nombre del propietario de la tarjeta" data-conekta="card[name]" name="name" id="name">
</div>
<div class="col-xl-6 mb-3 pr-0">
    <input class="form-control" type="number" placeholder="Número de Tarjeta de Débito o Crédito" maxlength="16" onkeypress='return event.charCode >= 48 && event.charCode <= 57' name="card" id="card" data-conekta="card[number]">
</div>
<div class="col-xl-6 mb-3 pr-0">
    <div class="row">
        <div class="col-xl-4 pr-0">
            <input class="form-control" type="number" placeholder="Mes ({{date('m')}})" maxlength="2" data-conekta="card[exp_month]">
        </div>
        <div class="col-xl-4 pr-0">
            <input class="form-control" type="number" placeholder="Año ({{date('Y')}})" maxlength="4" data-conekta="card[exp_year]">
        </div>
        <div class="col-xl-4 pr-0">
            <input class="form-control" type="number" placeholder="CVC" maxlength="4" data-conekta="card[cvc]">
        </div>
    </div>
</div>