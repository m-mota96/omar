<!-- Topbar -->
<nav class="navbar navbar-expand navbar-light topbar static-top shadow bg-gray-dark-800">
    <div class="container">
        <!-- Topbar Navbar -->
        <ul class="navbar-nav ml-auto">
            {{-- <div class="topbar-divider d-none d-sm-block"></div> --}}
            <!-- Nav Item - User Information -->
            <li class="mr-2">
                <a class="nav-link text-white" href="{{route('customer.documents')}}">
                    <span class="mr-2 d-none d-lg-inline text-white">Mi cuenta</span>
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
<nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow bg-dark">
    <div class="container">
        <!-- Topbar Navbar -->
        <ul class="navbar-nav pt-1 pb-1">
            {{-- <div class="topbar-divider d-none d-sm-block"></div> --}}
            <!-- Nav Item - User Information -->
            <li class="mr-4">
                <a class="text-white pointer" href="{{route('home')}}"><i class="fas fa-list-ul"></i> Mis eventos</a>
            </li>
            <li class="mr-4">
                <a class="text-white pointer" href=""><i class="fas fa-chart-line"></i> Estadísticas globales</a>
            </li>
        </ul>
    </div>
</nav>
<!-- End of Topbar -->