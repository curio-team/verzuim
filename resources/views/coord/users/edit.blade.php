@extends("layouts.app")
@section("content")

<div class="d-flex justify-content-between">
    <h3>Aanpassen {{ $user->name }}, {{ $user->id }}</h3>
    <a class="btn btn-light" href="{{ route('coord.users.index') }}"><i class="fas fa-times"></i> Annuleren</a>
</div>

<form method="POST" action="{{ route('coord.users.update', $user) }}" class="mt-4">
    @method('PATCH')
    @csrf
    <div class="form-group row">
        <label for="name" class="col-form-label col-sm-2">Naam:</label>
        <div class="col-sm-10 pl-0 d-flex align-items-center">{{ $user->name }}</div>
    </div>
    <div class="form-group row">
        <div class="col-form-label col-sm-2">Login-methode:</div>
        <div class="col-sm-10 d-flex align-items-center pl-0 justify-content-between">
            @if($user->login == "internal")
                <span>E-mail:&nbsp;<em>{{ $user->email }}</em></span>
                @if(session('password'))
                    <span>Gebruiker kan nu eenmalig inloggen met: <strong>{{ session('password') }}</strong></span>
                @else
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('coord.users.reset', $user) }}"><i class="fas fa-key"></i> Wachtwoord resetten</a>
                @endif
            @else
                curio.codes-account
            @endif
        </div>
    </div>
    <div class="form-group row">
        <label class="col-sm-2 col-form-label" for="active">Actief:</label>
        <div class="col-sm-10 d-flex align-items-center pl-0 justify-content-between">
            @if($user->active)
                <span>Ja, gebruiker kan inloggen</span>
            @else
                <span>Nee, gebruiker kan niet inloggen</span>
                <a class="btn btn-sm btn-outline-primary" href="{{ route('coord.users.activate', $user) }}"><i class="fas fa-lock-open"></i> Gebruiker activeren</a>
            @endif 
        </div>
    </div>

    <h4>Afdelingen van {{ $user->id }}</h4>
    <table class="table table-striped table-hover table-sm mt-2">
        <tr>
            <th>Afdeling</th>
            <th>Lid</th>
            <th>Importer</th>
            <th>Co&ouml;rdinator</th>
        </tr>
        @foreach($user->units as $unit)
            <tr>
                <td>{{ $unit->name }}</td>
                <td>
                    <input type="hidden" name="units[{{ $unit->id }}][member]" value="0">
                    <input type="checkbox" name="units[{{ $unit->id }}][member]" value="1" checked
                        @unless($myUnits->contains($unit->id)) disabled @endunless
                    >
                </td>
                <td>
                    <input type="hidden" name="units[{{ $unit->id }}][importer]" value="0">
                    <input type="checkbox" name="units[{{ $unit->id }}][importer]" value="1"
                        @if($unit->pivot->importer) checked @endif
                        @unless($myUnits->contains($unit->id)) disabled @endunless
                    >
                </td>
                <td>
                    <input type="hidden" name="units[{{ $unit->id }}][coord]" value="0">
                    <input type="checkbox" name="units[{{ $unit->id }}][coord]" value="1"
                        @if($unit->pivot->coord) checked @endif
                        @unless($myUnits->contains($unit->id)) disabled @endunless
                    >
                </td>
            </tr>
        @endforeach
    </table>

    <div class="form-group d-flex justify-content-between">
        <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Opslaan</button>
    </div>
</form>
@endsection