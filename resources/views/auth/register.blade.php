@extends("layouts.app")

@push('head')
    <link rel="stylesheet" href="/css/auth.css">
@endpush

@section("content")
    
    <div class="login row">
        <div class="col-md-6">
            <form class="flex-grow-1" method="POST" action="{{ route('register.do') }}">
                @csrf
                <div class="form-group">
                    <label for="name">Naam:</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                </div>
                <div class="form-group">
                    <label for="email">E-mailadres:</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="a.devries@curio.nl" value="{{ old('email') }}">
                </div>
                <div class="form-group">
                    <label for="code">Docentcode:</label>
                    <input type="text" class="form-control" id="code" name="code" placeholder="ab01" value="{{ old('code') }}">
                </div>
                <div class="form-group">
                    <label for="unit">Afdeling:</label>
                    <select name="unit" id="unit" class="form-control">
                        <option value=""> - kies een afdeling - </option>
                        @foreach ($units as $unit)
                            <option value="{{ $unit->id }}" @if(old('unit') == $unit->id) selected @endif>{{ $unit->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="password">Wachtwoord:</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Wachtwoord bevestigen:</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                </div>
                <button type="submit" class="btn btn-primary mr-1">Account aanvragen</button>
            </form>
        </div>
        <div class="col-md-6">
            <div class="alert alert-info text-center">Aanvragen van een account is enkel beschikbaar voor medewerkers.</div>
        </div>
    </div>

@endsection