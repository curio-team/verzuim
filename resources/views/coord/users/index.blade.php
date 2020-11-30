@extends("layouts.app")
@section("content")

<div data-controller="modal">
    <div class="d-flex justify-content-between my-3">
        <h3>Gebruikers</h3>
        <button class="btn btn-outline-primary" data-action="click->modal#open"><i class="fas fa-search-plus"></i> Gebruiker toevoegen</button>
    </div>
    <table class="table table-striped table-hover mt-3">
        @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->id }}</td>
                <td>{{ $user->units->implode('name', ', ') }}</td>
                <td><a href="{{ route('coord.users.edit', $user) }}"><i class="fas fa-fw fa-edit"></i> Aanpassen</a></td>
            </tr>
        @endforeach
    </table>

    <!-- Modal users -->
    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-target="modal.modal">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <form action="{{ route('coord.users.units') }}" method="POST" class="modal-content form">
                {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label for="code">Docentcode:</label>
                        <input type="text" name="code" id="code" placeholder="ab01" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="unit">Afdeling:</label>
                        @if($units->count() > 1)
                            <select name="unit" id="unit" class="form-control">
                                <option value=""> - kies afdeling - </option>
                                @foreach($units as $unit)
                                    <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                @endforeach
                            </select>
                        @else
                            <div><em>{{ $units[0]->name }}</em></div>
                            <input type="hidden" name="unit" value="{{ $units[0]->id }}">
                        @endif
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gray" data-action="click->modal#close">Annuleren</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-plus"></i> Toevoegen</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
