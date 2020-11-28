@extends("layouts.app")
@section("content")

    <h3>Gebruikers</h3>
    <table class="table table-striped table-hover mt-3">
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->id }}</td>
                <td><a href="{{ route('admin.users.edit', $user) }}"><i class="fas fa-fw fa-edit"></i> Aanpassen</a></td>
            </tr>
        @endforeach
    </table>

@endsection
