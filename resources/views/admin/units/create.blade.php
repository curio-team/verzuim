@extends("layouts.app")
@section("content")
    <div class="d-flex justify-content-between">
        <h3>Nieuwe afdeling</h3>
        <a class="btn btn-light" href="{{ route('admin.units.index') }}"><i class="fas fa-times"></i> Annuleren</a>
    </div>
    <form method="POST" action="{{ route('admin.units.store') }}" class="mt-4">
        @csrf
        <div class="form-group row">
            <label for="unit" class="col-form-label col-sm-2">Afdeling:</label>
            <input type="text" class="form-control col-sm-10" id="unit" name="unit" value="{{ old('unit') }}" placeholder="AA-BCD">
        </div>
        <div class="form-group d-flex justify-content-between">
            <button type="submit" class="btn btn-success"><i class="fas fa-save"></i> Opslaan</button>
        </div>
    </form>
@endsection