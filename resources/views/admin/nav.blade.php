<nav class="navbar navbar-dark bg-secondary navbar-expand-sm">
    <div class="container justify-content-start">
        <div class="navbar-brand">Admin</div>
        <ul class="navbar-nav">
            <li class="nav-item @if(Str::startsWith(Route::current()->uri, 'admin/units')) active @endif">
                <a class="nav-link" href="{{ route('admin.units.index') }}">Afdelingen</a>
            </li>
        </ul>
    </div>
</nav>