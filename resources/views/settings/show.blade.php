@extends("layouts.app")
@section("content")

    <h3>Instellingen</h3>
    <p class="text-muted">{{ $user->name }}, {{ $user->id }}</p>
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
        <div class="form-group">
            <button type="submit" class="btn btn-success">Opslaan <i class="fas fa-save"></i></button>
        </div>
    </form>

@endsection