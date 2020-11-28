@extends("layouts.app")
@section("content")

    @if($unit->exists)
        <h3>Aanpassen {{ $unit->name }}</h3>
        <form method="POST" action="{{ route('admin.units.update', $unit) }}" class="mt-4">
        @method('PATCH')
    @else
        <h3>Nieuwe afdeling</h3>
        <form method="POST" action="{{ route('admin.units.store') }}" class="mt-4">
    @endif
        @csrf
        <div class="form-group row">
            <label for="unit" class="col-form-label col-sm-2">Afdeling:</label>
            <input type="text" class="form-control col-sm-10" id="unit" name="unit" value="{{ old('unit', $unit->name) }}" placeholder="AA-BCD">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-success">Opslaan <i class="fas fa-save"></i></button>
        </div>
    </form>

    @if($unit->exists)
        <form method="POST" action="{{ route('admin.units.destroy', $unit) }}">
            @method('DELETE')
            @csrf
            <button type="submit" class="btn btn-danger">Verwijderen <i class="fas fa-trash"></i></button>
        </form>
    @endif

@endsection