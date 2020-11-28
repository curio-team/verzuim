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

    <h3>{{ $student["name"] }}</h3>
    <?php
    $format = "%a %e %b";
    if($then->format("Y") != $now->format("Y")) $format .= " %Y"
    ?>
    <p class="text-muted m-0">{{ $then->formatLocalized($format) }} t/m {{ $now->formatLocalized($format) }}</p>
    <div class="alert alert-primary my-4">Geen registraties in de aangegeven periode.</div>
    @if($present > 0)
        <div class="alert alert-success my-4">Je bent in de aangegeven periode wel <strong>{{ $present }} uur</strong> present gemeld.</div>
    @endif

@endsection