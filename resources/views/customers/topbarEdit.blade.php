<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light topbar static-top shadow bg-gray-dark-800">
    <div class="container">
        <!-- Topbar Navbar -->
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
<nav class="navbar navbar-expand navbar-light topbar static-top shadow bg-dark">
    <div class="container">
        <!-- Topbar Navbar -->
        <ul class="navbar-nav pt-1 pb-1">
            {{-- <div class="topbar-divider d-none d-sm-block"></div> --}}
            <!-- Nav Item - User Information -->
            <li class="mr-4">
                <span class="text-white pointer"><i class="fas fa-chart-line"></i> Estadísticas</span>
            </li>
            <li class="mr-4">
                <a class="text-white pointer" href="{{route('admin.edit', $event_id)}}"><i class="fas fa-cog"></i> Configuración</a>
            </li>
            <li class="mr-4">
                <a class="text-white pointer" href="{{route('admin.tickets', $event_id)}}"><i class="fas fa-tag"></i> Boletos</a>
            </li>
            <li class="mr-4">
                <span class="text-white pointer"><i class="fas fa-shopping-cart"></i> Reservaciones</span>
            </li>
            <li class="mr-4">
                <span class="text-white pointer"><i class="fas fa-list-ul"></i> Registro</span>
            </li>
            <li class="mr-4">
                <span class="text-white pointer"><i class="fas fa-star"></i> Promociones</span>
            </li>
        </ul>
    </div>
</nav>
<!-- End of Topbar -->