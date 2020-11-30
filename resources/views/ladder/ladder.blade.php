<div class="ladder">
    <div class="title">
        <h3>{{ $group }}</h3>
        <?php
        $format = "%a %e %b";
        if($then->format("Y") != $now->format("Y")) $format .= " %Y"
        ?>
        <p class="text-muted m-0">{{ $then->formatLocalized($format) }} t/m {{ $now->formatLocalized($format) }}</p>
    </div>
    <div class="step step1">
        <div class="step-text">
            <p class="text-muted m-0">Fase 1</p>
            <p class="font-weight-bold m-0">Indicentele melding</p>
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
</div>