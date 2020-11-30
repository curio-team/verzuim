@extends("layouts.app")
@section("content")

    <h3>Account-aanvragen</h3>
    <table class="table table-striped table-hover mt-3">
        @forelse($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->id }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->units->implode('name', ', ') }}</td>
                <td>
                    <a class="btn btn-sm btn-outline-primary" href="{{ route('coord.users.activate', $user) }}">
                        <i class="fas fa-lock-open"></i>
                        Gebruiker activeren
                    </a>
                </td>
            </tr>
        @empty
            <tr>
                <td>Geen aanvragen voor jouw afdeling(en).</td>
            </tr>
        @endforelse
    </table>

@endsection