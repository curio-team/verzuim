@extends("layouts.app")
@section("content")

    <div class="d-flex justify-content-between align-items-start">
        <h3>Afdelingen</h3>
        <a href="{{ route('admin.units.create') }}" class="btn btn-success"><i class="fas fa-plus"></i> Nieuw</a>
    </div>
    <table class="table table-striped table-hover mt-3">
        @foreach($units as $unit)
            <tr>
                <td>{{ $unit->name }}</td>
                <td><a href="{{ route('admin.units.users.index', $unit) }}"><i class="fas fa-fw fa-users"></i> Gebruikers</a></td>
                <td><a href="{{ route('admin.units.edit', $unit) }}"><i class="fas fa-fw fa-edit"></i> Aanpassen</a></td>
            </tr>
        @endforeach
    </table>

@endsection
