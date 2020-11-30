<nav class="navbar navbar-dark bg-secondary navbar-expand-sm">
    <div class="container justify-content-start">
        <div class="navbar-brand">Import</div>
        <ul class="navbar-nav">
            @foreach(\Auth::user()->my_import_units as $unit)
                <li class="nav-item @if(Str::endsWith(url()->current(), 'import/'.$unit->id)) active @endif">
                    <a class="nav-link" href="{{ route('import.show', $unit) }}">{{ $unit->name }}</a>
                </li>
            @endforeach
        </ul>
    </div>
</nav>
