@extends("layouts.app")

@push('head')
    <link rel="stylesheet" href="/css/auth.css">
@endpush

@section("content")
    
    <div class="login row">
        <div class="col-md-6">
            <form class="flex-grow-1" method="POST" action="{{ route('login.do.internal') }}">
                @csrf
                <div class="form-group">
                    <label for="email">E-mailadres of docent-code:</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="ab01">
                </div>
                <div class="form-group">
                    <label for="password">Wachtwoord:</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember" checked value="1">
                    <label class="form-check-label" for="remember">Onthoud mij</label>
                </div>
                <button type="submit" class="btn btn-primary mr-1">Login</button>
                <a class="btn btn-light mr-1" href="{{ route('register') }}">Account aanvragen</a>
            </form>
        </div>

        <div class="col-md-6 d-flex align-items-center flex-column">
            <p class="text-muted" style="font-weight: bold">Login met curio.codes:</p>
            <a class="btn btn-lg btn-light btn-curio" href="{{ route('login.do.amoclient') }}"><img src="Curio-software-developers-klein.png" alt="curio.codes"></a>
        </div>
    </div>

@endsection