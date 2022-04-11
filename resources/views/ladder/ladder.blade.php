<div class="ladder">
    <form action="" method="get" class="formStart">
        <span>Bekijk ziekmeldingen vanaf:</span>
        <input type="text" name="start" placeholder="1900-01-31" @if(isset($_GET['start'])) value="{{$_GET['start'] }}" @endif>
        <input type="submit">
    </form>
    <div class="title">
        <h3>{{ $group }}</h3>
        <?php
        $format = "%a %e %b";
        if($then->format("Y") != $now->format("Y")) $format .= " %Y"
        ?>
        <p class="text-muted m-0">{{ $then->formatLocalized($format) }} t/m {{ $now->formatLocalized($format) }} (actueel t/m: {{ $last }})</p>
    </div>
    <div class="step step1">
        <div class="step-text">
            <p class="text-muted m-0">Fase 1</p>
            <p class="font-weight-bold m-0">Incidentele melding</p>
            <p class="m-0">Informeel contact</p>
        </div>
    </div>
    <div class="step step2">
        <div class="step-text">
            <p class="text-muted m-0">Fase 2</p>
            <p class="font-weight-bold m-0">Verhoogde attentie</p>
            <p class="m-0">Afspraken vastleggen</p>
        </div>
    </div>
    <div class="step step3">
        <div class="step-text">
            <p class="text-muted m-0">Fase 3</p>
            <p class="font-weight-bold m-0">Bovenmatig verzuim</p>
            <p class="m-0">Belafspraak ouders</p>
        </div>
    </div>
    <div class="step step4">
        <div class="step-text">
            <p class="text-muted m-0">Fase 4</p>
            <p class="font-weight-bold m-0">VSV-risico</p>
            <p class="m-0">Ouders op school</p>
        </div>
    </div>
    <div class="step step5">
        <div class="step-text">
            <p class="text-muted m-0">Fase 5</p>
            <p class="font-weight-bold m-0">Leerplicht of zorg</p>
            <p class="m-0">Melding leerplicht</p>
        </div>
    </div>
    <!------------------------------->
    @for($i = 1; $i <= 5; $i++)
        <div class="students students{{ $i }}">
            @foreach($students as $name => $student)
                @if($student["step"] == $i)
                    @if($student["handled"][$i])
                        <p class="my-1 text-muted">
                            <i class="far fa-fw fa-check-circle"></i>
                            <a href="{{ route('students.show', $student["id"]) }}">{{ $name }}</a>
                        </p>
                    @else
                        <p class="my-1">
                            <a class="link-icon" href="{{ route('students.handle', [$student["id"], $student["step"], $student["reason"]]) }}">
                                <i class="fas fa-fw fa-exclamation-circle color-step color-step{{ $i }}"></i>
                            </a>
                            <a href="{{ route('students.show', $student["id"]) }}">{{ $name }}</a>
                        </p>
                    @endif
                @endif
            @endforeach
        </div>
    @endfor

    @if(count($sickWeek))
        <div class="sickWeek">
            <strong>Meer dan een week ziek:</strong><br />
                @foreach($sickWeek as $student)
                    {{ $student->student_name }} <em>{{ $student->max_date }}</em><br />
                @endforeach
        </div>
    @endif

    @if(count($sick3x) || count($sick5x))
        <div class="sick3or5x">

            @if(count($sick3x))
                <strong>>3x ziek in 8wkn</strong><br />
                @foreach($sick3x as $student)
                    {{ $student->student_name }} <em>{{ $student->count }}x</em><br />
                @endforeach
                <br />
            @endif


            @if(count($sick5x))
                <strong>>5x ziek in 18wkn</strong><br />
                @foreach($sick5x as $student)
                    {{ $student->student_name }} <em>{{ $student->count }}x</em><br />
                @endforeach
            @endif

        </div>
    @endif
</div>