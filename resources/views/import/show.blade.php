@extends("layouts.app")
@section("content")

    <h1>Importeer presentie-data</h1>
    <p>Let op; uit het bestand worden enkel registratie ge&iuml;mporteerd die vallen <em>na</em> de laatste datum die nu is opgenomen in het systeem. Dit om dubbele imports te voorkomen. Importeer dus altijd een databestand voor alle klassen in een keer, je kunt niet later over nog gegevens van een andere klas importeren.</p>
    <div class="alert alert-info">Data ge&iuml;mporteerd t/m: {{ $last }}.</div>
    <p><a href="https://portal.rocwb.nl/ReportServer?/PARS/platte_data&rs:Command=Render&rc:Parameters=true" target="_blank">Download rapportage uit PARS als CSV <i class="fas fa-external-link-alt"></i></a></p>
    <form method="POST" action="{{ route('import.upload') }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <input type="file" name="file" accept=".csv">
        </div>
        <div class="form-group my-4">
            <button type="submit" class="btn btn-success btn-lg">Importeren <i class="fas fa-cloud-upload-alt"></i></button>
        </div>
    </form>

@endsection