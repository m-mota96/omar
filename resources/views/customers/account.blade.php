@extends('customers.layout')
@section('heads')
    
@endsection
@section('content')
    @include('customers.topbar')
    <div class="container">
        <div class="card p-3">
            <div class="row p-3">
                <input type="hidden" value="{{Auth::user()->id}}" id="USER_ID">
                {{-- <div class="col-xl-6 offset-xl-3 pl-0 mb-3">
                    <div class="row">
                        <div class="col-xl-6">
                            <h3 class="bold">Mi Cuenta</h3>
                        </div>
                        <div class="col-xl-6 text-right pr-0">
                            <h5 class="text-blue-400 pointer" id="editProfile"><i class="fas fa-edit"></i> Editar perfil</h5>
                        </div>
                    </div>
                </div>
                <div class="card mb-3 bg-gray-100 col-xl-6 offset-xl-3">
                    <div class="row no-gutters">
                        <div class="col-xl-4 text-center pt-2">
                            <img class="w-75" src="{{asset('media/general/user.png')}}" class="card-img">
                        </div>
                        <div class="col-xl-8">
                            <div class="card-body">
                                <h5 class="card-title">{{Auth::user()->name}}</h5>
                                <p class="card-text mb-1"><i>{{Auth::user()->email}}</i></p>
                                <p class="card-text mb-1">Teléfono: <b>{{Auth::user()->phone}}</b></p>
                                <p class="text-blue-400 pointer" id="editPassword"><i class="fas fa-lock"></i> Editar contraseña</p>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="card w-100 p-3">
                    <div class="row">
                        <div class="col-xl-12">
                            <p class="mb-1 bold">INFORMACIÓN DE LA CUENTA</p>
                        </div>
                        <div class="col-xl-4">
                            <small class="mb-1 text-gray-dark-300">Correo electrónico:</small>
                            <p class="bold">{{Auth::user()->email}}</p>
                        </div>
                        <div class="col-xl-4">
                            <small class="mb-1 text-gray-dark-300">Teléfono:</small>
                            <p class="bold">{{Auth::user()->phone}}</p>
                        </div>
                        <div class="col-xl-4">
                            <small class="mb-1 text-gray-dark-300">Estatus de la cuenta:</small>
                            @if (empty(auth()->user()->contract))
                                <p class="bold"><span class="text-red"><i class="fas fa-file"></i> Sin contrato</span></p>
                            @else
                                <p class="bold"><span class="text-green"><i class="fas fa-file"></i> Con contrato</span></p>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card w-100 p-3 mt-3" id="contentTaxData">
                    @if (!empty($taxData))
                        <div class="row">
                            <div class="col-xl-12 mt-2">
                                <p class="mb-1 bold">INFORMACIÓN FISCAL</p>
                            </div>
                            <div class="col-xl-4">
                                <small class="mb-1 text-gray-dark-300">Representante legal:</small>
                                <p class="bold">{{Auth::user()->name}}</p>
                            </div>
                            <div class="col-xl-4">
                                <small class="mb-1 text-gray-dark-300">Razón social:</small>
                                <p class="bold">Prueba</p>
                            </div>
                            <div class="col-xl-4">
                                <small class="mb-1 text-gray-dark-300">RFC:</small>
                                <p class="bold">MOMM960316</p>
                            </div>
                            <div class="col-xl-4">
                                <small class="mb-1 text-gray-dark-300">Calle:</small>
                                <p class="bold">Trinidad Arvizu</p>
                            </div>
                            <div class="col-xl-4">
                                <small class="mb-1 text-gray-dark-300">Número exterior:</small>
                                <p class="bold">3326</p>
                            </div>
                            <div class="col-xl-4">
                                <small class="mb-1 text-gray-dark-300">Número interior:</small>
                                <p class="bold">N/A</p>
                            </div>
                            <div class="col-xl-4">
                                <small class="mb-1 text-gray-dark-300">Colonia:</small>
                                <p class="bold">Lomas de Polanco</p>
                            </div>
                            <div class="col-xl-4">
                                <small class="mb-1 text-gray-dark-300">Código postal:</small>
                                <p class="bold">44960</p>
                            </div>
                            <div class="col-xl-4">
                                <small class="mb-1 text-gray-dark-300">Estado:</small>
                                <p class="bold">Jalisco</p>
                            </div>
                            <div class="col-xl-4">
                                <small class="mb-1 text-gray-dark-300">Ciudad:</small>
                                <p class="bold">Guadalajara</p>
                            </div>
                        </div>
                    @else
                        <form class="row" id="formTaxData">
                            <div class="col-xl-12 mt-2">
                                <p class="mb-1 bold">INFORMACIÓN FISCAL</p>
                            </div>
                            <div class="col-xl-4">
                                <small class="mb-1 text-gray-dark-300">Representante legal:</small>
                                <input class="form-control mb-3" type="text" id="legal_representative" required>
                            </div>
                            <div class="col-xl-4">
                                <small class="mb-1 text-gray-dark-300">Razón social:</small>
                                <input class="form-control mb-3" type="text" id="business_name" required>
                            </div>
                            <div class="col-xl-4">
                                <small class="mb-1 text-gray-dark-300">RFC:</small>
                                <input class="form-control mb-3 to-uppercase" type="text" id="rfc" required>
                            </div>
                            <div class="col-xl-4">
                                <small class="mb-1 text-gray-dark-300">Calle:</small>
                                <input class="form-control mb-3" type="text" id="address" required>
                            </div>
                            <div class="col-xl-4">
                                <small class="mb-1 text-gray-dark-300">Número exterior:</small>
                                <input class="form-control mb-3" type="number" id="external_number" required>
                            </div>
                            <div class="col-xl-4">
                                <small class="mb-1 text-gray-dark-300">Número interior: (Opcional)</small>
                                <input class="form-control mb-3" type="text" id="intermal_number">
                            </div>
                            <div class="col-xl-4">
                                <small class="mb-1 text-gray-dark-300">Colonia:</small>
                                <input class="form-control mb-3" type="text" id="colony" required>
                            </div>
                            <div class="col-xl-4">
                                <small class="mb-1 text-gray-dark-300">Código postal:</small>
                                <input class="form-control mb-3" type="number" id="postal_code" required>
                            </div>
                            <div class="col-xl-4">
                                <small class="mb-1 text-gray-dark-300">Estado:</small>
                                <input class="form-control mb-3" type="text" id="state" required>
                            </div>
                            <div class="col-xl-4">
                                <small class="mb-1 text-gray-dark-300">Ciudad:</small>
                                <input class="form-control mb-3" type="text" id="city" required>
                            </div>
                            <div class="col-xl-12 text-center">
                                <p><b>Nota: </b> Verifique que la información sea correcta una vez guardada no podrá ser modificada</p>
                                <button class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
                    @endif
                </div>
                <div class="card w-100 p-3 mt-3" id="contentBankData">
                    @if (!empty($bankData))
                        <div class="row">
                            <div class="col-xl-12 mt-2">
                                <p class="mb-1 bold">DATOS BANCARIOS</p>
                            </div>
                            <div class="col-xl-4">
                                <small class="mb-1 text-gray-dark-300">Banco:</small>
                                <p class="bold">BBVA</p>
                            </div>
                            <div class="col-xl-4">
                                <small class="mb-1 text-gray-dark-300">Clave:</small>
                                <p class="bold">012345678965412369</p>
                            </div>
                            <div class="col-xl-4">
                                <small class="mb-1 text-gray-dark-300">Número de cuenta:</small>
                                <p class="bold">0123456789</p>
                            </div>
                            <div class="col-xl-4">
                                <small class="mb-1 text-gray-dark-300">Nombre del tarjetahabiente:</small>
                                <p class="bold">Miguel Angel Mota Murillo</p>
                            </div>
                        </div>
                    @else
                        <form class="row" id="formBankData">
                            <div class="col-xl-12 mt-2">
                                <p class="mb-1 bold">DATOS BANCARIOS</p>
                            </div>
                            <div class="col-xl-4">
                                <small class="mb-1 text-gray-dark-300">Banco:</small>
                                <input class="form-control mb-3" type="text" id="bank" required>
                            </div>
                            <div class="col-xl-4">
                                <small class="mb-1 text-gray-dark-300">Clave:</small>
                                <input class="form-control mb-3" type="text" id="key" required>
                            </div>
                            <div class="col-xl-4">
                                <small class="mb-1 text-gray-dark-300">Número de cuenta:</small>
                                <input class="form-control mb-3" type="text" id="number_account" required>
                            </div>
                            <div class="col-xl-4">
                                <small class="mb-1 text-gray-dark-300">Nombre del tarjetahabiente:</small>
                                <input class="form-control mb-3" type="text" id="name_propietary" required>
                            </div>
                            <div class="col-xl-12 text-center">
                                <p><b>Nota: </b> Verifique que la información sea correcta una vez guardada no podrá ser modificada</p>
                                <button class="btn btn-primary">Guardar</button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
            <div class="row pl-3" id="contentDocuments">
                
            </div>
        </div>
    </div>
    @include('modals.customers.resetPassword')
    @include('modals.customers.account')
@endsection
@section('scripts')
<script src="{{asset('js/dropzone.js')}}"></script>
    <script src="{{asset('js/customers/account.js')}}"></script>
    <script>
        var docs = @json($documents);
        processDocuments(docs);
    </script>
@endsection