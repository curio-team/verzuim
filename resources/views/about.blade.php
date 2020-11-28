@extends("layouts.app")
@section("content")

    <h3>Uitleg</h3>

    <div class="d-flex">
        <div class="mr-5" style="min-width: 320px;">
            <p class="mt-4"><strong>Fase 1</strong></p>
            <ul>
                <li>&gt;0 melding(en) Absent</li>
                <li>&gt;1 melding(en) Te laat</li>
                <li>&gt;1 melding(en) Regulier absent</li>
            </ul>

            <p class="mt-4"><strong>Fase 2</strong></p>
            <ul>
                <li>&gt;1 melding(en) Absent</li>
                <li>&gt;3 melding(en) Te laat</li>
                <li>&gt;2 melding(en) Regulier absent</li>
            </ul>

            <p class="mt-4"><strong>Fase 3</strong></p>
            <ul>
                <li>&gt;2 melding(en) Absent</li>
                <li>&gt;4 melding(en) Te laat</li>
                <li>&gt;4 melding(en) Regulier absent</li>
            </ul>

            <p class="mt-4"><strong>Fase 4</strong></p>
            <ul>
                <li>&gt;12 uren Absent</li>
                <li>&gt;8 melding(en) Te laat</li>
            </ul>

            <p class="mt-4"><strong>Fase 5</strong></p>
            <ul>
                <li>&gt;16 uren Absent</li>
                <li>&gt;12 melding(en) Te laat</li>
            </ul>
        </div>
        <div>
            <p class="mt-4 mb-0"><strong>Het <em>voortschrijdend venster</em>-principe</strong></p>
            <p>De voorgestelde situatie op de ladder is gebaseerd op de huidige week en de X weken daarvoor (standaard is 4 weken). Hierdoor kijk je steeds naar de situatie van het nabije verleden, wat er voor zorgt dat studenten ook weer naar beneden kunnen stappen op de ladder als het beter gaat.</p>
            <p>Stel dat student A in de afgelopen weken veel afwezig is geweest, en daardoor op trede drie of vier is beland. Daarop maak je afspraken met A over verbetering. In de komende weken zullen de oude absenties uit beeld verdwijnen door het vooruitlopende venster. Als de afpraken werken en er geen <em>nieuwe</em> absentie ontstaat, zal de student dus dalen op de ladder.</p>

            <p class="mt-4 mb-0"><strong>Werking van de meldingen</strong></p>
            <p>De uitroeptekens op de ladder kun je aanklikken; je markeert daarmee de registraties die de melding veroorzaken als <em>gezien</em>. De student krijgt een vinkje bij de naam, zo kun je voor jezelf aangeven dat je een actie hebt uitgevoerd op deze melding.</p>
            <p>Wanneer gemaakte afpraken te weinig effect hebben komen er nieuwe registraties voor de student. Daarop zal het vinkje weer veranderen in een uitroepteken, bij wijze van attentie dat het nog niet helemaal goed gaat. Je kunt de student daar weer op aanspreken, en vervolgens ook deze nieuwe melding afwerken door het uitroepteken aan te klikken.</p>

            <p class="mt-4 mb-0"><strong>Details van een melding</strong></p>
            <p>Door op de <em>naam</em> van een student te klikken bekijk je alle registraties van de afgelopen periode.</p>

            <p class="mt-4 mb-0"><strong>Inloggen voor student</strong></p>
            <p>De student kan zelf ook inloggen en ziet dan ongeveer het zelfde scherm als wanneer je als docent de details bekijkt van die student. Studenten hebben geen verdere mogelijkheden in de applicatie.</p>
        </div>
    </div>
@endsection
