@extends("layouts.app")

@push('head')
    <link rel="stylesheet" href="/css/ladder.css">
@endpush

@section("content")

    @foreach($data as $item)
        @include('ladder.ladder', [
            "students" => $item["students"],
            "group" => $item["group"],
            "now" => $now,
            "then" => $then
        ])
        @if(!$loop->last) <hr class="my-5" style="border-top: 7px solid gray"> @endif
    @endforeach

@endsection