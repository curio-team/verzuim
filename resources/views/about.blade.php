@extends("layouts.app")
@section("content")

    <h3>Uitleg</h3>

    <div class="d-flex">
        <div class="mr-5" style="min-width: 320px;">
            <p class="mt-4"><strong>Fase 1</strong></p>
            <ul>
                <li>>0 melding(en) Absent</li>
                <li>>1 melding(en) Te laat</li>
                <li>>1 melding(en) Regulier absent</li>
            </ul>

            <p class="mt-4"><strong>Fase 2</strong></p>
            <ul>
                <li>>1 melding(en) Absent</li>
                <li>>3 melding(en) Te laat</li>
                <li>>2 melding(en) Regulier absent</li>
            </ul>

            <p class="mt-4"><strong>Fase 3</strong></p>
            <ul>
                <li>>2 melding(en) Absent</li>
                <li>>4 melding(en) Te laat</li>
                <li>>4 melding(en) Regulier absent</li>
            </ul>

            <p class="mt-4"><strong>Fase 4</strong></p>
            <ul>
                <li>>12 uren Absent</li>
                <li>>8 melding(en) Te laat</li>
            </ul>

            <p class="mt-4"><strong>Fase 5</strong></p>
            <ul>
                <li>>16 uren Absent</li>
                <li>>12 melding(en) Te laat</li>
            </ul>
        </div>
        <div>
            <p class="mt-4 mb-0"><strong>Afwerken van meldingen</strong></p>
            <p>De uitroeptekens op de ladder kun je aanklikken; je werkt daarmee de registraties af die de melding veroorzaken en de student krijgt een vinkje bij de naam. Zo kun je voor jezelf aangeven dat je een actie hebt uitgevoerd op deze student.</p>
            <p>Zodra een nieuwe registratie wordt ingevoerd, en de student zit door het totaal van registraties nog steeds in <em>een</em> van de fases, zul je weer opnieuw een uitroepteken zien. Dat gebeurt ook wanneer de student een fase omhoog gaat.</p>
            <p>Je ziet dus altijd de situatie gemeten over de afgelopen X weken, waarbij je meldingen kunt afwerkingen bij wijze van todo-lijst. Zordra de situatie verandert, verschijnt het uitroepteken weer.</p>

            <p class="mt-4 mb-0"><strong>Details bekijken</strong></p>
            <p>Door op de <em>naam</em> van een student te klikken kun je alle registraties bekijken die ten grondslag liggen aan een melding.</p>

            <p class="mt-4 mb-0"><strong>Inloggen voor student</strong></p>
            <p>De student kan zelf ook inloggen en ziet dan ongeveer het zelfde scherm als wanneer je als docent de details bekijkt van die student.</p>
        </div>
    </div>
@endsection
