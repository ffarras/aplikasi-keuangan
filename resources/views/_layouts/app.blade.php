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
    <link rel="stylesheet" href="/atlantis/css/bootstrap.min.css">
    <link rel="stylesheet" href="/atlantis/css/atlantis.min.css">
    <link rel="stylesheet" href="/atlantis/css/fonts.css">
    <link rel="stylesheet" href="/atlantis/plugin/bootstrap-datepicker/css/bootstrap-datepicker.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.1/css/buttons.dataTables.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.37/css/bootstrap-datetimepicker.min.css">
    <script src="atlantis/js/plugin/webfont/webfont.min.js"></script>
	<script>
		WebFont.load({
			google: {"families":["Lato:300,400,700,900"]},
			custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['atlantis/css/fonts.min.css']},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>

    <style>
        .parsley-error {
            border-color: #dc3545 !important;
        }
    </style>

    @yield('styles')
</head>
<body>
    <div class="wrapper">
        @include('_layouts.app.header')
        @include('_layouts.app.sidebar')

        <div class="main-panel">
            <div class="content" id="app">
                @yield('content')
            </div>

            <!-- @include('_layouts.app.footer') -->
        </div>

        <!-- @include('_layouts.app.custom-template') -->
    </div>

    <script src="{{ mix('js/app.js') }}"></script>
	<script src="{{ mix('js/plugin.js') }}"></script>
    <!-- <script src="{{ mix('js/setting-demo.js') }}"></script> -->
    <script src="atlantis/js/demo.js"></script>
    @stack('scripts')
</body>
</html>
