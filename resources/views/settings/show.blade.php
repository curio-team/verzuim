@extends("layouts.app")
@section("content")

    <h3>Instellingen</h3>
    <p class="text-muted">{{ $user->name }}, {{ $user->id }}</p>
    @if($user->password_once)
        <div class="alert alert-warning">Wijzig je wachtwoord voor je verder kunt gaan</div>
    @endif
    <form method="POST" action="{{ route('settings.save') }}">
        @csrf
        <div class="form-group row">
            <label for="weeks" class="col-form-label col-sm-3">Aantal weken terug kijken:</label>
            <div class="col-sm-9">
                <select name="weeks" id="weeks" class="form-control">
                    @for($i = 1; $i <= 16; $i++)
                        <option value="{{ $i }}" @if($i == $user->weeks) selected @endif>{{ $i }}</option>
                    @endfor
                </select>
                <small class="form-text text-muted">Vanaf de afgelopen maandag gezien, kijkt het programma nog ... weken terug. De lopende week wordt ook meegenomen. Aanbevolen is 4 weken, omdat leerplicht ook daarmee werkt.</small>
            </div>
        </div>
        <div @if($user->password_once) class="alert alert-warning pt-4" @endif>
            <div class="form-group row">
                <label for="password" class="col-sm-3">Nieuw wachtwoord:</label>
                <div class="col-sm-9">
                    <input type="password" class="form-control" id="password" name="password">
                </div>
            </div>
            <div class="form-group row">
                <label for="password_confirmation" class="col-sm-3">Wachtwoord bevestigen:</label>
                <div class="col-sm-9">
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                </div>
            </div>
        </div>

        <div class="form-group">
            <button type="submit" class="btn btn-success">Opslaan <i class="fas fa-save"></i></button>
        </div>
    </form>

@endsection