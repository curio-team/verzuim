@extends("layouts.app")
@section("content")

    <div data-controller="modal">
        <div class="d-flex justify-content-between align-items-start">
            <h3>Afdeling {{ $unit->name }}</h3>
            <button class="btn btn-light" data-action="click->modal#open"><i class="fas fa-search-plus"></i> Gebruiker toevoegen</button>
        </div>

        <form method="POST" action="{{ route('admin.units.users.update', $unit) }}">
            @method('PATCH')
            @csrf
            <table class="table table-striped table-hover table-sm mt-4">
                <tr>
                    <th>Naam</th>
                    <th>Gebruiker</th>
                    <th>Actief</th>
                    <th>{{ $unit->name }}: <em>Lid</em></th>
                    <th>{{ $unit->name }}: <em>Co&ouml;rdinator</em></th>
                </tr>
                @foreach($unit->users as $user)
                    <tr>
                        <td><a href="{{ route('admin.users.edit', $user) }}">{{ $user->name }}</a></td>
                        <td>{{ $user->id }}</td>
                        <td>
                            <input type="hidden" name="users[{{ $user->id }}][active]" value="0">
                            <input type="checkbox" name="users[{{ $user->id }}][active]" value="1" @if($user->active) checked @endif>
                        </td>
                        <td>
                            <input type="hidden" name="users[{{ $user->id }}][member]" value="0">
                            <input type="checkbox" name="users[{{ $user->id }}][member]" value="1" checked>
                        </td>
                        <td>
                            <input type="hidden" name="users[{{ $user->id }}][coord]" value="0">
                            <input type="checkbox" name="users[{{ $user->id }}][coord]" value="1" @if($user->pivot->coord) checked @endif>
                        </td>
                    </tr>
                @endforeach
            </table>

            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Opslaan</button>
        </form>

        <!-- Modal users -->
        <div class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" data-target="modal.modal">
            <div class="modal-dialog modal-dialog-scrollable" role="document">
                <form action="{{ route('admin.units.users.sync', $unit) }}" method="POST" class="modal-content">
                    {{ csrf_field() }}
                    <div class="modal-body px-0">
                        <table class="table table-sm" data-controller="filter" data-filter-display="table-row">
                            <tr>
                                <th class="pl-3">&nbsp;</th>
                                <th>Gebruiker</th>
                                <th class="pr-3">Code</th>
                            </tr>
                            <tr>
                                <td colspan="3" class="p-3"><input class="form-control" placeholder="Filter lijst" type="text" data-target="filter.query" data-action="input->filter#filter"></td>
                            </tr>
                            @foreach($users as $user)
                                <tr data-target="filter.list" data-controller="checkrow" data-action="click->checkrow#toggle">
                                    <td class="pl-3">
                                        <input type="checkbox" name="users[]" value="{{ $user->id }}" data-target="checkrow.box"
                                        @if($user->units->pluck('id')->contains($unit->id))
                                            checked
                                        @endif
                                        >
                                    </td>
                                    <td>{{ $user->name }}</td>
                                    <td class="pr-3">{{ $user->id }}</td>
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