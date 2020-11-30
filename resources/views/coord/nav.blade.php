<nav class="navbar navbar-dark bg-secondary navbar-expand-sm">
    <div class="container justify-content-start">
        <div class="navbar-brand">Co&ouml;rdinator</div>
        <ul class="navbar-nav">
            <li class="nav-item @if(Str::startsWith(Route::current()->uri, 'coord/users')) active @endif">
                <a class="nav-link" href="{{ route('coord.users.index') }}">Gebruikers</a>
            </li>
            <li class="nav-item @if(Str::startsWith(Route::current()->uri, 'coord/requests')) active @endif">
                <a class="nav-link" href="{{ route('coord.users.requests') }}">Aanvragen</a>
            </li>
        </ul>
    </div>
</nav>