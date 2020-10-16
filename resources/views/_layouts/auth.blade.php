<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Azura Labs</title>
    <link rel="icon" href="{{ asset('images/favicon.ico') }}" type="image/x-icon"/>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <link rel="stylesheet" href="atlantis/css/bootstrap.min.css">
    <link rel="stylesheet" href="atlantis/css/atlantis.min.css">
    <link rel="stylesheet" href="atlantis/css/fonts.css">
    @stack('styles')
</head>
<body>
    <div class="wrapper overlay-sidebar">
        @include('_layouts.auth.header')

        <div class="main-panel bg-primary-gradient">
            <div class="content" id="app">
                @yield('content')
            </div>

            <!-- @include('_layouts.app.footer') -->
        </div>

        <!-- @include('_layouts.app.custom-template') -->
    </div>

    <script src="atlantis/js/app.js"></script>
	<script src="atlantis/js/plugin.js"></script>
    <script src="atlantis/js/setting-demo.js"></script>
    <!-- <script src="atlantis/js/core/jquery.3.2.1.min.js"></script> -->
	<script src="atlantis/js/core/popper.min.js"></script>
	<script src="atlantis/js/core/bootstrap.min.js"></script>
	<!-- jQuery UI -->
	<script src="atlantis/js/plugin/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
	<script src="atlantis/js/plugin/jquery-ui-touch-punch/jquery.ui.touch-punch.min.js"></script>

    @stack('scripts')
</body>
</html>
