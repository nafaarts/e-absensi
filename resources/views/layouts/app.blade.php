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

    {{-- bootstrap icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.4/font/bootstrap-icons.css">

    @stack('heads')

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-primary border-bottom">
        <div class="container">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <img src="{{ asset('logo.png') }}" height="30" class="d-inline-block align-top" alt="">
                <span class="ms-2">E-Absensi</span>
            </a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @can('is_admin')
                        <li class="nav-item active">
                            <a @class(['nav-link', 'active' => request()->routeIs('dashboard')]) href="{{ route('dashboard') }}">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a @class(['nav-link', 'active' => request()->routeIs('perizinan*')]) href="{{ route('perizinan.index') }}">Perizinan</a>
                        </li>
                        <li class="nav-item">
                            <a @class(['nav-link', 'active' => request()->routeIs('users*')]) href="{{ route('users.index') }}">User</a>
                        </li>
                        <li class="nav-item">
                            <a @class(['nav-link', 'active' => request()->routeIs('export')]) href="{{ route('export') }}">Ekspor</a>
                        </li>
                        <li class="nav-item">
                            <a @class(['nav-link', 'active' => request()->routeIs('jadwal*')]) href="{{ route('jadwal.index') }}">Jadwal</a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a @class(['nav-link', 'active' => request()->routeIs('absensi*')]) href="{{ route('absensi') }}">Absensi</a>
                        </li>
                        <li class="nav-item">
                            <a @class(['nav-link', 'active' => request()->routeIs('riwayat*')]) href="{{ route('riwayat') }}">Riwayat</a>
                        </li>
                        <li class="nav-item">
                            <a @class(['nav-link', 'active' => request()->routeIs('izin*')]) href="{{ route('izin.index') }}">Perizinan</a>
                        </li>
                    @endcan
                    <li class="nav-item dropdown">
                        <a @class([
                            'nav-link dropdown-toggle',
                            'active' => request()->routeIs('profil'),
                        ]) href="#" id="navbarDropdown" role="button"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            {{ auth()->user()->nama }}
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('profil') }}">
                                <i class="bi bi-person"></i>
                                Profil</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#"
                                onclick="document.getElementById('form-logout').submit()">
                                <i class="bi bi-box-arrow-in-right"></i>
                                Keluar</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-3">
        <div class="container">
            @yield('content')
        </div>
    </main>

    {{-- form logout --}}
    <form action="{{ route('logout') }}" method="post" id="form-logout">
        @csrf
    </form>

    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous">
    </script>

    @stack('scripts')
</body>

</html>
