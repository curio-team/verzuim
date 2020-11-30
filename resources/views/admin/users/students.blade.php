@extends("layouts.app")
@section("content")

    <h3>Studenten</h3>
    <div class="alert alert-info">Je kunt deze accounts niet verwijderen en/of blokkeren, omdat studenten altijd extern inloggen. Ga eventueel naar <a href="https://login.curio.codes/" target="_blank">curio.codes <i class="fas fa-external-link-alt"></i></a></div>
    <table class="table table-striped table-hover mt-3">
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->id }}</td>
            </tr>
        @endforeach
    </table>

@endsection
