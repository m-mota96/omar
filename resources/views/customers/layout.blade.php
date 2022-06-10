<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Administrador</title>
    <link rel="stylesheet" href="{{asset('fontawesome5.15.4/css/all.css')}}">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
    <link href="{{asset('css/customers/general_customer.css')}}" rel="stylesheet">
    @yield('heads')
</head>
<body>
<!-- Page Wrapper -->
<input type="hidden" id="URL" value="{{URL::asset('')}}">
@yield('content')
<!-- End of Page Wrapper -->
@include('customers.footer')
<script src="{{asset('js/jquery-3.4.1.js')}}"></script>
<script src="{{asset('js/bootstrap.bundle.min.js')}}"></script>
<script src="{{asset('js/sweetalert2.js')}}"></script>
<script>
    function openSidebar() {
        $('#sidebar-movile').toggle().toggleClass('animate__fadeInLeft');
        $('#sidebar-movile').removeClass('animate__fadeOutLeft');
    }

    function closeSidebar() {
        $('#sidebar-movile').toggle().toggleClass('animate__fadeOutLeft');
        $('#sidebar-movile').removeClass('animate__fadeInLeft');
    }
</script>
@yield('scripts')
</body>
</html>