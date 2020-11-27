@extends("layouts.app")
@section("content")

    <h1>Importeer presentie-data</h1>
    <p><a href="https://portal.rocwb.nl/ReportServer?/PARS/platte_data&rs:Command=Render&rc:Parameters=true" target="_blank">Download rapportage uit PARS als CSV <i class="fas fa-external-link-alt"></i></a></p>
    <form method="POST" action="{{ route('import.upload') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <input type="file" name="file" accept=".csv">
        </div>
        <div class="form-group">
            <button type="submit" class="btn btn-success">Importeren <i class="fas fa-cloud-upload-alt"></i></button>
        </div>
    </form>

@endsection