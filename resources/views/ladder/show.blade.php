@extends("layouts.app")

@push('head')
    <link rel="stylesheet" href="/css/ladder.css">
@endpush

@section('buttons')
    <a class="btn btn-outline-dark" href="javascript:window.print()"><i class="fas fa-print fa-fw"></i></a>
@endsection

@section("content")

    @include('ladder.ladder', [
        "students" => $students,
        "group" => $group,
        "now" => $now,
        "then" => $then
    ])

@endsection