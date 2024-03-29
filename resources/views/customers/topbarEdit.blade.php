<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light topbar static-top shadow bg-gray-dark-800">
    <div class="container">
        <i class="fas fa-bars hidden-xl" onclick="openSidebar()"></i>
        <!-- Topbar Navbar -->
        <ul class="navbar-nav">
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link text-white" href="{{URL::asset('').$event_url}}" target="_blank" id="topbarWebsite">
                    <span class="mr-2 d-none d-lg-inline text-white"><i class="fas fa-link"></i> {{URL::asset('').$event_url}}</span>
                </a>
            </li>
            <li class="nav-item dropdown no-arrow">
                @if (empty(auth()->user()->contract))
                    <a class="nav-link text-red">
                        <span class="mr-2 d-none d-lg-inline bold"><i class="fas fa-file"></i> &nbsp;Sin contrato</span>
                    </a>
                @else
                    <a class="nav-link text-green">
                        <span class="mr-2 d-none d-lg-inline bold"><i class="fas fa-file"></i> &nbsp;Con contrato</span>
                    </a>
                @endif
            </li>
        </ul>
        <ul class="navbar-nav ml-auto">
            {{-- <div class="topbar-divider d-none d-sm-block"></div> --}}
            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link text-white" href="{{route('home')}}">
                    <span class="mr-2 d-none d-lg-inline text-white"><i class="fas fa-home"></i> Dashboard</span>
                </a>
            </li>
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link text-white" href="">
                    <span class="mr-2 d-none d-lg-inline text-white"><i class="fas fa-question"></i> Ayuda</span>
                </a>
            </li>
            <li class="nav-item dropdown no-arrow">
                <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-white">{{Auth::user()->name}}</span>
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                        Cerrar Sesion
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </div>
            </li>
        </ul>
    </div>
</nav>
<nav class="navbar navbar-expand navbar-light topbar static-top shadow bg-dark hidden-sm navbar-sub">
    <div class="container">
        <!-- Topbar Navbar -->
        <ul class="navbar-nav pt-1 pb-1">
            {{-- <div class="topbar-divider d-none d-sm-block"></div> --}}
            <!-- Nav Item - User Information -->
            <li class="mr-4">
                <a class="nav-link text-white" href="{{route('customer.stats', $event_id)}}"><i class="fas fa-chart-line"></i> Estadísticas</a>
            </li>
            <li class="nav-item dropdown no-arrow mr-4">
                {{-- <a class="nav-link text-white" href="{{route('customer.edit', $event_id)}}"><i class="fas fa-cog"></i> Configuración</a> --}}
                <a class="nav-link dropdown-toggle text-white" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <span class="mr-2 d-none d-lg-inline text-white"><i class="fas fa-cog"></i> Configuración</span>
                </a>
                <!-- Dropdown - User Information -->
                <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                    <a class="dropdown-item" href="{{route('customer.edit', $event_id)}}">
                        <i class="far fa-eye"></i> Apariencia
                    </a>
                    <a class="dropdown-item" href="{{route('customer.form_ticket', $event_id)}}">
                        <i class="far fa-map"></i> Forma por boleto
                    </a>
                </div>
            </li>
            <li class="mr-4">
                <a class="nav-link text-white" href="{{route('customer.turns', $event_id)}}"><i class="fas fa-list-ol"></i> Turnos</a>
            </li>
            <li class="mr-4">
                <a class="nav-link text-white" href="{{route('customer.tickets', $event_id)}}"><i class="fas fa-tag"></i> Boletos</a>
            </li>
            <li class="mr-4">
                <a class="nav-link text-white" href="{{route('customer.codes', $event_id)}}"><i class="fas fa-barcode"></i> Códigos</a>
            </li>
            <li class="mr-4">
                <a class="nav-link text-white" href="{{route('customer.reservations', $event_id)}}"><i class="fas fa-shopping-cart"></i> Reservaciones</a>
            </li>
            <li class="mr-4">
                <a class="nav-link text-white" href="{{route('customer.scan', $event_id)}}"><i class="fas fa-qrcode"></i> Escaner</a>
            </li>
            <li class="mr-4">
                <a class="nav-link text-white" href="{{route('customer.assistance', $event_id)}}"><i class="far fa-calendar-check"></i> Asistencia de evento</a>
            </li>
        </ul>
    </div>
</nav>
<!-- End of Topbar -->
<!-- Sidebar movile -->
<div class="d-block d-sm-block d-md-none w-50 sidebar-movile animate__animated" id="sidebar-movile" style="display: none !important;">
    <div class="col-12 pt-3 pb-3">
        <h4 class="mb-0"><a class="text-dark" href="{{route('home')}}"><i class="fas fa-home"></i> Dashboard</a></h4><span class="btn-close-side" onclick="closeSidebar()"><i class="fas fa-times"></i></span>
    </div>
    <hr class="sidebar-divider mt-0 mb-0">
    <div class="col-12 pt-2 pb-2">
        <a href="{{route('customer.stats', $event_id)}}" class="m-0 text-dark"><i class="fas fa-chart-line"></i> Estadísticas</a>
    </div>
    <hr class="sidebar-divider mt-0 mb-0">
    <div class="col-12 pt-2 pb-2">
        <a href="{{route('customer.edit', $event_id)}}" class="m-0 text-dark"><i class="fas fa-cog"></i> Configuración</a>
    </div>
    <hr class="sidebar-divider mt-0 mb-0">
    <div class="col-12 pt-2 pb-2">
        <a href="{{route('customer.turns', $event_id)}}" class="m-0 text-dark"><i class="fas fa-list-ol"></i> Turnos</a>
    </div>
    <hr class="sidebar-divider mt-0 mb-0">
    <div class="col-12 pt-2 pb-2">
        <a href="{{route('customer.tickets', $event_id)}}" class="m-0 text-dark"><i class="fas fa-tag"></i> Boletos</a>
    </div>
    <hr class="sidebar-divider mt-0 mb-0">
    <div class="col-12 pt-2 pb-2">
        <a href="{{route('customer.reservations', $event_id)}}" class="m-0 text-dark"><i class="fas fa-shopping-cart"></i> Reservaciones</a>
    </div>
    <hr class="sidebar-divider mt-0 mb-0">
    <div class="col-12 pt-2 pb-2">
        <a href="{{route('customer.scan', $event_id)}}" class="m-0 text-dark"><i class="fas fa-qrcode"></i> Escaner</a>
    </div>
    <hr class="sidebar-divider mt-0 mb-0">
    <div class="col-12 pt-2 pb-2">
        <a href="{{route('customer.assistance', $event_id)}}" class="m-0 text-dark"><i class="far fa-calendar-check"></i> Asistencia de evento</a>
    </div>
    <hr class="sidebar-divider mt-0 mb-0">
</div>