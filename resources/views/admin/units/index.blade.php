@extends("layouts.app")
@section("content")

    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Afdeling</th>
                <th>Gebruikers</th>
                <th class="p-0 align-middle text-right">
                    <a href="{{ route('admin.units.create') }}" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Nieuw</a>
                </th>
            </tr>
        </thead>
        <tbody>

            @foreach($units as $unit)
                <tr>
                    <td>{{ $unit->name }}</td>
                    <td></td>
                    <td class="text-right align-middle">
                        <a href="{{ route('admin.units.edit', $unit) }}" class="btn btn-sm btn-primary"><i class="fas fa-fw fa-edit"></i></a>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>

@endsection
