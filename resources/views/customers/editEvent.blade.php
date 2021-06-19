@extends('customers.layout')
@section('heads')
    
@endsection
@section('content')
    @include('customers.topbarEdit')
    <div class="container">
        <input type="hidden" id="eventId" value="{{$event->id}}">
        <div class="row p-r">
            @if (isset($event->profile->name))
                <img class="w-100 img-edit" id="imageEvent" src="{{asset('media/events/'.$event->id.'/'.$event->profile->name.'')}}" alt="{{$event->name}}">
            @else
                <img class="w-100 img-edit" id="imageEvent" src="{{asset('media/general/not_image.png')}}" alt="{{$event->name}}">
            @endif
            <span class="p-a div-modal-image bg-gray-dark-900 text-white pointer p-1" id="btnEditImage"><i class="fas fa-pen"></i> Cambiar Imagen de Fondo</span>
            <div class="col-xl-12 w-100 pt-4 pb-3 back-title-edit">
                <h1 class="bold text-white-300" id="titleEvent">{{$event->name}} <h5><a class="text-white-300" id="viewWebsite" href="{{URL::asset('').$event_url}}" target="_blank">Ver sitio web</a></h5></h1>
                <div class="row mr-1 bg-gray-dark-900 p-a div-modal-edit">
                    <span class="text-white pointer" id="btnNameAndSite"><i class="fas fa-pen"></i> Editar nombre y sitio de ventas</span>
                </div>
            </div>
            <div class="row p-2 ml-3 bg-white div-logo-edit" id="contentLogo">
                @if (isset($event->logo->name))
                    <div class="col-xl-12 text-center h-100 pt-3">
                        <img class="w-100 h-100 p-a logotype" src="{{asset('media/events/'.$event->id.'/'.$event->logo->name.'')}}">
                    </div>
                    <span class="bg-gray-dark-900 text-white p-1 p-a font-small text-right btnEditLogo editLogo pointer"><i class="fas fa-pen"></i> &nbsp;Editar logo</span>
                    <span class="btn btn-danger p-a pt-1 pb-1 pr-2 pl-2 font-small btnDeleteLogo" id="btnDeleteLogo"><i class="fas fa-trash-alt"></i></span>
                @else
                    <div class="col-xl-12 text-center border-logo-edit h-100 pt-3">
                        <br>
                        <h4 class="bold"><a class="text-gray-dark-400 pointer btnEditLogo">Agregar Logo</a></h4>
                    </div>
                @endif
            </div>
        </div>
        <div class="row pl-4 pr-4">
            <div class="card w-100">
                <div class="card-body pt-5">
                    <div class="row">
                        <div class="col-xl-9">
                            <h3 class="bold">Acerca de &nbsp;&nbsp;<span class="text-gray font-small normal pointer" id="btnEditDescription"><i class="fas fa-pen"></i> Editar</span></h3>
                            <div class="row">
                                <div class="col-xl-12">
                                    <p class="text-justify text-gray-600" id="content-description">{{$event->description}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3">
                            <div class="card w-100 bg-gray-100">
                                <div class="card-body">
                                    <h6><b><i class="far fa-calendar-alt"></i> CUÁNDO</b> &nbsp;&nbsp;&nbsp;<span class="font-small pointer" id="btnEditDates"><i class="fas fa-pen"></i> Editar</span></h6>
                                    <h6>DE: <b id="content-initial_date">{{$event->initial_date}}</b></h6>
                                    <h6>AL: <b id="content-final_date">{{$event->final_date}}</b></h6>
                                    <div class="dropdown-divider mt-3 mb-3"></div>
                                    <h6><b><i class="fas fa-map-marker-alt"></i> DÓNDE</b> &nbsp;&nbsp;&nbsp;<span class="font-small pointer" id="btnEditAddress"><i class="fas fa-pen"></i> Editar</span></h6>
                                    <div class="row pl-3 pr-3">
                                        {{-- @if (isset($event->location->name)) --}}
                                            <div class="col-xl-12 w-100 @if (isset($event->location->name)) content-map @endif" id="content-map">

                                            </div>
                                        {{-- @endif --}}
                                    </div>
                                    <div class="dropdown-divider mt-3 mb-3"></div>
                                    <h6><b><i class="far fa-id-card"></i> CONTACTO</b> &nbsp;&nbsp;&nbsp;<span class="font-small pointer" id="btnAddContact"><i class="fas fa-pen"></i> Editar</span></h6>
                                    <div class="row">
                                        <div class="col xl-12" id="content-contact">
                                            @if (!empty($event->email))
                                                <a class="text-dark font-small"><i class="fas fa-envelope"></i> {{$event->email}}</a><br>
                                            @endif
                                            @if (!empty($event->phone))
                                                <a class="text-dark font-small"><i class="fas fa-phone"></i> {{$event->phone}}</a><br>
                                            @endif
                                            @if (!empty($event->twitter))
                                                <a class="text-dark font-small" href="https://twitter.com/{{$event->twitter}}" target="_blank"><i class="fab fa-twitter"></i> {{'@'.$event->twitter}}</a><br>
                                            @endif
                                            @if (!empty($event->facebook))
                                                <a class="text-dark font-small" href="{{$event->facebook}}" target="_blank"><i class="fab fa-facebook-f"></i> Facebook</a><br>
                                            @endif
                                            @if (!empty($event->instagram))
                                                <a class="text-dark font-small" href="{{$event->instagram}}" target="_blank"><i class="fab fa-instagram"></i> Instagram</a><br>
                                            @endif
                                            @if (!empty($event->website))
                                                <a class="text-dark font-small" href="{{$event->website}}" target="_blank"><i class="fas fa-link"></i> {{$event->website}}</a>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="dropdown-divider mt-3 mb-3"></div>
                                    <h6><b><i class="fas fa-list"></i> CATEGORÍA</b> &nbsp;&nbsp;&nbsp;<span class="font-small pointer" id="btnEditCategory"><i class="fas fa-pen"></i> Editar</span></h6>
                                    <h6 style="text-transform:uppercase" id="content-category">{{ $event->category->name }}</h6>
                                    <div class="dropdown-divider mt-3 mb-3"></div>
                                    <h6><b><i class="fas fa-donate"></i> TIPO DE COSTO</b> &nbsp;&nbsp;&nbsp;</h6>
                                    @if( $event->cost_type === 'paid' )
                                    <h6 style="text-transform:uppercase" id="content-cost_type">Con pago</h6>
                                    @elseif($event->cost_type === 'free')
                                    <h6 style="text-transform:uppercase" id="content-cost_type">Gratis</h6>
                                    @endif
                                    <div class="dropdown-divider mt-3 mb-3"></div>
                                    {{-- <h6><b><i class="fas fa-tags"></i> TRACKING</b> &nbsp;&nbsp;&nbsp;<span class="font-small pointer"><i class="fas fa-pen"></i> Editar</span></h6>
                                    <div class="row">
                                        <div class="col xl-12">
                                            <p class="bold mt-3 mb-2">Google Tag Manager</p>
                                            <span>Inactivo</span>
                                            <p class="bold mt-3 mb-2">Facebook Pixel</p>
                                            <span>Inactivo</span>
                                        </div>
                                    </div> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('modals.customers.editImage')
    @include('modals.customers.editLogo')
    @include('modals.customers.editNameWebsite')
    @include('modals.customers.editDescription')
    @include('modals.customers.editDates')
    @include('modals.customers.addContact')
    @include('modals.customers.editLocation')
    @include('modals.customers.editCategory')
@endsection
@section('scripts')
    <script src="{{asset('js/dropzone.js')}}"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDmxWjrQMl9hyuxzaoCm0_Ma03a92eu2b4&libraries=places"></script>
    {{-- <script src="{{asset('js/jquery.imgareaselect.js')}}"></script> --}}
    <script src="{{asset('js/customers/editEvent.js')}}"></script>
    <script>
        // processingDates('<?= $event->eventDates; ?>');
        $('#initial_date').val(@json($event->original_initial_date));
        $('#final_date').val(@json($event->original_final_date));
        var schedules = @json($event->eventDates);
        chargingSchedules(schedules);
        chargingMap('<?= (isset($event->location->latitude)) ? $event->location->latitude : null ?>', '<?= (isset($event->location->longitude)) ? $event->location->longitude : null ?>');
    </script>
@endsection