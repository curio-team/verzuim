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
        <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png">
        <link rel="manifest" href="/site.webmanifest">
        <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#603cba">
        <meta name="theme-color" content="#ffffff">
    </head>
    <body>

        <nav class="navbar navbar-light bg-light navbar-expand-sm">
            <div class="container">
                <span class="navbar-brand mb-0 h1 d-flex align-items-center"><img src="/logo.png" alt="logo" style="height: 28px;" class="pr-1">Verzuim</span>
                @if(\Auth::user()->type == "teacher")
                    <div class="d-flex justify-content-between flex-grow-1">
                        <ul class="navbar-nav">
                            <li class="nav-item @if(Route::currentRouteName() == 'home') active @endif">
                                <a class="nav-link" href="{{ route('home') }}">Start</a>
                            </li>
                            <li class="nav-item @if(Str::startsWith(Route::current()->uri, 'about')) active @endif">
                                <a class="nav-link" href="{{ route('about') }}">Uitleg</a>
                            </li>
                            <li class="nav-item @if(Str::startsWith(Route::current()->uri, 'groups')) active @endif">
                                <a class="nav-link" href="{{ route('groups.index') }}">Klassen</a>
                            </li>
                            @if(\Auth::user()->admin)
                                <li class="nav-item @if(Str::startsWith(Route::current()->uri, 'import')) active @endif">
                                    <a class="nav-link" href="{{ route('import.show') }}">Import</a>
                                </li>
                                <li class="nav-item @if(Str::startsWith(Route::current()->uri, 'admins')) active @endif">
                                    <a class="nav-link" href="{{ route('admins.show') }}">Admins</a>
                                </li>
                            @endif
                        </ul>
                        <div class="btn-group d-none d-md-flex">
                            @yield('buttons')
                            <a class="btn btn-outline-dark" href="{{ route('settings.show') }}"><i class="fas fa-cog fa-fw" aria-hidden="true"></i></a>
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
