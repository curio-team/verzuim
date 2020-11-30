@extends("layouts.app")
@section("content")

<div data-controller="modal">
    <div class="d-flex justify-content-between">
        <h3>Aanpassen {{ $user->name }}, {{ $user->id }}</h3>
        <a class="btn btn-light" href="{{ route('admin.users.index') }}"><i class="fas fa-times"></i> Annuleren</a>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="mt-4">
        @method('PATCH')
        @csrf
        <div class="form-group row">
            <label for="name" class="col-form-label col-sm-2">Naam:</label>
            <div class="col-sm-10 pl-0">
                <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $user->name) }}">
            </div>
        </div>
        <div class="form-group row">
            <div class="col-form-label col-sm-2">Login-methode:</div>
            <div class="col-sm-10 d-flex align-items-center pl-0 justify-content-between">
                @if($user->login == "internal")
                    <span>E-mail:&nbsp;<em>{{ $user->email }}</em></span>
                    @if(session('password'))
                        <span>Gebruiker kan nu eenmalig inloggen met: <strong>{{ session('password') }}</strong></span>
                    @else
                        <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.users.reset', $user) }}"><i class="fas fa-key"></i> Wachtwoord resetten</a>
                    @endif
                @else
                    curio.codes-account
                @endif
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="active">Actief:</label>
            <div class="col-sm-10 pl-0 pt-2">
                <input type="hidden" name="active" value="0">
                <input type="checkbox" id="active" name="active" value="1" @if($user->active) checked @endif>
                <label for="active">Gebruiker kan inloggen</label>
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2 col-form-label" for="admin">Admin:</label>
            <div class="col-sm-10 pl-0 pt-2">
                <input type="hidden" name="admin" value="0">
                <input type="checkbox" id="admin" name="admin" value="1" @if($user->admin) checked @endif>
                <label for="admin">Globale admin (beheert afdelingen en gebruikers)</label>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-5 mb-3">
            <h4>Afdelingen van {{ $user->id }}</h4>
            <button class="btn btn-outline-primary" data-action="click->modal#open"><i class="fas fa-search-plus"></i> Afdeling toevoegen</button>
        </div>
        <table class="table table-striped table-hover table-sm mt-2">
            <tr>
                <th>Afdeling</th>
                <th>Lid</th>
                <th>Importer</th>
                <th>Co&ouml;rdinator</th>
            </tr>
            @foreach($user->units as $unit)
                <tr>
                    <td><a href="{{ route('admin.units.edit', $unit) }}">{{ $unit->name }}</a></td>
                    <td>
                        <input type="hidden" name="units[{{ $unit->id }}][member]" value="0">
                        <input type="checkbox" name="units[{{ $unit->id }}][member]" value="1" checked>
                    </td>
                    <td>
                        <input type="hidden" name="units[{{ $unit->id }}][importer]" value="0">
                        <input type="checkbox" name="units[{{ $unit->id }}][importer]" value="1" @if($unit->pivot->importer) checked @endif>
                    </td>
                    <td>
                        <input type="hidden" name="units[{{ $unit->id }}][coord]" value="0">
                        <input type="checkbox" name="units[{{ $unit->id }}][coord]" value="1" @if($unit->pivot->coord) checked @endif>
                    </td>
                </tr>
            @endforeach
        </table>

        <div class="form-group d-flex justify-content-between">
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Opslaan</button>
            <button type="submit" class="btn btn-danger" form="delete" onclick="return confirm('test');"><i class="fas fa-trash"></i> Verwijderen</button>
        </div>
    </form>

    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" id="delete">
        @method('DELETE')
        @csrf
    </form>

    <!-- Modal units -->
    <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-target="modal.modal">
        <div class="modal-dialog modal-dialog-scrollable" role="document">
            <form action="{{ route('admin.users.units.sync', $user) }}" method="POST" class="modal-content">
                {{ csrf_field() }}
                <div class="modal-body px-0">
                    <table class="table table-sm" data-controller="filter" data-filter-display="table-row">
                        <tr>
                            <td colspan="2" class="p-3"><input class="form-control" placeholder="Filter lijst" type="text" data-target="filter.query" data-action="input->filter#filter"></td>
                        </tr>
                        @foreach($units as $unit)
                            <tr data-target="filter.list" data-controller="checkrow" data-action="click->checkrow#toggle">
                                <td class="pl-3">
                                    <input type="checkbox" name="units[]" value="{{ $unit->id }}" data-target="checkrow.box"
                                    @if($user->units->pluck('id')->contains($unit->id))
                                        checked
                                    @endif
                                    >
                                </td>
                                <td class="pr-3">{{ $unit->name }}</td>
                            </tr>
                        @endforeach
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-gray" data-action="click->modal#close">Annuleren</button>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Opslaan</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection