<!doctype html>
<html lang="nl">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
        <link href="/fontawesome/css/all.css" rel="stylesheet">
        <title>Verzuim</title>
        @stack('head')
    </head>
    <body>

        <nav class="navbar navbar-light bg-light navbar-expand-sm">
            <div class="container">
                <span class="navbar-brand mb-0 h1">Verzuim</span>
                @if(\Auth::user()->type == "teacher")
                    <div class="d-flex justify-content-between flex-grow-1">
                        <ul class="navbar-nav">
                            <li class="nav-item @if(Route::currentRouteName() == 'home') active @endif">
                                <a class="nav-link" href="{{ route('home') }}">Start</a>
                            </li>
                            <li class="nav-item @if(Str::startsWith(Route::current()->uri, 'groups')) active @endif">
                                <a class="nav-link" href="{{ route('groups.index') }}">Klassen</a>
                            </li>
                            <li class="nav-item @if(Str::startsWith(Route::current()->uri, 'import')) active @endif">
                                <a class="nav-link" href="{{ route('import.show') }}">Import</a>
                            </li>
                        </ul>
                        <div class="btn-group d-none d-md-flex">
                            @yield('buttons')
                            <a class="btn btn-outline-dark" href="{{ route('settings.show') }}"><i class="fas fa-user fa-fw" aria-hidden="true"></i>&nbsp;<span>{{ Auth::user()->id }}</span></a>
                        </div>
                    </div>
                @endif
            </div>
        </nav>
        @yield('subnav')
        <div class="@yield('container', 'container mt-3')">
            @include('layouts.status')
            @yield('content')
        </div>
    </body>
</html>
