<!DOCTYPE html>
<html lang="en">
<head>
    <!-- ================= HEADER ================= -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Purple Admin')</title>

    <!-- ================= STYLE GLOBAL ================= -->
    <link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/ti-icons/css/themify-icons.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendors/font-awesome/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" />

    <!-- ================= STYLE PAGE ================= -->
    @yield('style-page')
</head>
<body>

<div class="container-scroller">

    <!-- ================= NAVBAR ================= -->
    @include('components.navbar')

    <div class="container-fluid page-body-wrapper">

        <!-- ================= SIDEBAR ================= -->
        @include('components.sidebar')

        <!-- ================= CONTENT ================= -->
        <div class="main-panel">
            <div class="content-wrapper">
                @yield('content')
            </div>

            <!-- ================= FOOTER ================= -->
            @include('components.footer')

        </div>

    </div>
</div>

<!-- ================= JAVASCRIPT GLOBAL ================= -->
<script src="{{ asset('assets/vendors/js/vendor.bundle.base.js') }}"></script>
<script src="{{ asset('assets/js/off-canvas.js') }}"></script>
<script src="{{ asset('assets/js/misc.js') }}"></script>
<script src="{{ asset('assets/js/settings.js') }}"></script>
<script src="{{ asset('assets/js/todolist.js') }}"></script>
<script src="{{ asset('assets/js/jquery.cookie.js') }}"></script>

<!-- ================= JAVASCRIPT PAGE ================= -->
@yield('js-page')

</body>
</html>
