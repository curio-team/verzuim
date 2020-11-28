@extends("layouts.app")

@push('head')
    <link rel="stylesheet" href="/css/ladder.css">
    <style>@page{size: A4 portrait;} @media print{ .ladder{page-break-after: auto;}}</style>
@endpush

@if(\Auth::user()->type == "teacher")
    @section('buttons')
        <a class="btn btn-outline-dark" href="javascript:window.print()"><i class="fas fa-print"></i></a>
    @endsection
@endif

@section("content")

    <div class="ladder ladder-sm">
        <div class="title">
            <h3>{{ $student["name"] }}</h3>
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
            </div>
        </div>
        <div class="step step2">
            <div class="step-text">
                <p class="text-muted m-0">Fase 2</p>
                <p class="font-weight-bold m-0">Verhoogde attentie</p>
            </div>
        </div>
        <div class="step step3">
            <div class="step-text">
                <p class="text-muted m-0">Fase 3</p>
                <p class="font-weight-bold m-0">Bovenmatig verzuim</p>
            </div>
        </div>
        <div class="step step4">
            <div class="step-text">
                <p class="text-muted m-0">Fase 4</p>
                <p class="font-weight-bold m-0">VSV-risico</p>
            </div>
        </div>
        <div class="step step5">
            <div class="step-text">
                <p class="text-muted m-0">Fase 5</p>
                <p class="font-weight-bold m-0">Leerplicht of zorg</p>
            </div>
        </div>
        <!------------------------------->
        @if($logs->where('type', 'Absent')->sum('duration') > 16 || $logs->where('type', 'Te laat')->count() > 12)
            <div class="students students5">
                <p class="my-1">
                    <i class="fas fa-fw fa-exclamation-circle color-step color-step5"></i>
                    {{ $student["name"] }}
                </p>
            </div>
        @elseif($logs->where('type', 'Absent')->sum('duration') > 12 || $logs->where('type', 'Te laat')->count() > 8)
            <div class="students students4">
                <p class="my-1">
                    <i class="fas fa-fw fa-exclamation-circle color-step color-step4"></i>
                    {{ $student["name"] }}
                </p>
            </div>
        @elseif($logs->where('type', 'Absent')->count() > 2 || $logs->where('type', 'Te laat')->count() > 4 || $logs->where('type', 'Regulier absent')->count() > 4)
            <div class="students students3">
                <p class="my-1">
                    <i class="fas fa-fw fa-exclamation-circle color-step color-step3"></i>
                    {{ $student["name"] }}
                </p>
            </div>
        @elseif($logs->where('type', 'Absent')->count() > 1 || $logs->where('type', 'Te laat')->count() > 3 || $logs->where('type', 'Regulier absent')->count() > 2)
            <div class="students students2">
                <p class="my-1">
                    <i class="fas fa-fw fa-exclamation-circle color-step color-step2"></i>
                    {{ $student["name"] }}
                </p>
            </div>
        @elseif($logs->where('type', 'Absent')->count() > 0 || $logs->where('type', 'Te laat')->count() > 1 || $logs->where('type', 'Regulier absent')->count() > 1)
            <div class="students students1">
                <p class="my-1">
                    <i class="fas fa-fw fa-exclamation-circle color-step color-step1"></i>
                    {{ $student["name"] }}
                </p>
            </div>
        @endif
    </div>
    @if($present > 0)
        <div class="alert alert-success my-4">Je bent in de aangegeven periode ook <strong>{{ $present }} uur</strong> present gemeld.</div>
    @endif
    <table class="table table-striped table-hover">
        <thead>
            <tr>
                <th>Type</th>
                <th>Datum start</th>
                <th>Tijd start</th>
                <th>Duur</th>
                <th>Registratie door</th>
                <th>Opmerkingen</th>
                @if(\Auth::user()->type == "teacher")
                    <th class="d-print-none">Afgehandeld</th>
                @endif
            </tr>
        </thead>
        <tbody>
        
            @foreach($logs as $log)
                <tr>
                    <td>{{ $log->type }}</td>
                    <td>{{ (new \Carbon\Carbon($log->date))->format("d-m-Y") }}</td>
                    <td>{{ $log->time_start }}</td>
                    <td>{{ $log->duration_text }}</td>
                    <td>{{ $log->logged_by }}</td>
                    <td>{{ $log->reason }} {{ $log->comment }}</td>
                    @if(\Auth::user()->type == "teacher")
                        <td class="d-print-none">
                            @if($log->handled1) In fase 1<br /> @endif
                            @if($log->handled2) In fase 2<br /> @endif
                            @if($log->handled3) In fase 3<br /> @endif
                            @if($log->handled4) In fase 4<br /> @endif
                            @if($log->handled5) In fase 5<br /> @endif
                        </td>
                    @endif
                </tr>
            @endforeach

        </tbody>
    </table>

@endsection