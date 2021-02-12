@extends("layouts.app")
@section("content")

    <h3>Importeer data voor {{ $unit->name }}</h3>
    <p>Let op; uit het bestand worden enkel registratie ge&iuml;mporteerd die vallen <em>na</em> de laatste datum die nu is opgenomen in het systeem. Dit om dubbele imports te voorkomen. Importeer dus altijd een databestand voor alle klassen in een keer, je kunt niet later alsnog gegevens van een andere klas importeren.</p>
    <p><a href="https://ssrs01.curio.nl/ReportServer/Pages/ReportViewer.aspx?/PARS/platte_data" target="_blank">Download rapportage uit PARS als CSV <i class="fas fa-external-link-alt"></i></a></p>
    <div class="alert alert-info">Data ge&iuml;mporteerd voor <strong>{{ $unit->name }}</strong> tot en met: <strong>{{ $last }}</strong>.</div>
    <form method="POST" action="{{ route('import.upload', $unit) }}" enctype="multipart/form-data">
        @csrf
        <div class="form-group row">
            <label for="file" class="col-sm-2">Bestand:</label>
            <div class="col-sm-10">
                <input type="file" name="file" id="file" accept=".csv">
            </div>
        </div>
        <div class="form-group row">
            <label class="col-sm-2">Afdeling:</label>
            <div class="col-sm-10">
                {{ $unit->name }}
            </div>
        </div>
        <div class="form-group my-4">
            <button type="submit" class="btn btn-success btn-lg">Importeren <i class="fas fa-cloud-upload-alt"></i></button>
        </div>
    </form>

@endsection