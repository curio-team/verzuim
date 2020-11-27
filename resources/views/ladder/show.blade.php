@extends("layouts.app")

@push('head')
    <link rel="stylesheet" href="/css/ladder.css">
@endpush

@section("content")

    @include('ladder.ladder', [
        "students" => $students,
        "group" => $group,
        "now" => $now,
        "then" => $then
    ])

@endsection