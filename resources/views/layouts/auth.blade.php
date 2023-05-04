<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>E-Absensi - SMAN 1 Montasik</title>

    <link rel="shortcut icon" href="{{ asset('logo.png') }}" type="image/png">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <div id="app">
        <div class="d-flex justify-content-center align-items-center" style="height: 100dvh">
            <div style="width: 100%; max-width: 400px">
                <div class="d-flex gap-3 justify-content-center my-3">
                    <img src="{{ asset('logo.png') }}" alt="Logo Sekolah" height="65">
                    <div class="">
                        <h1 class="fw-bold mb-0">E-ABSENSI</h1>
                        <h5 class="fw-light">SMAN 1 MONTASIK</h5>
                    </div>
                </div>
                <div class="p-3">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</body>

</html>
