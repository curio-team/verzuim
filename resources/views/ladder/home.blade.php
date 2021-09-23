@extends("layouts.app")

@push('head')
    <link rel="stylesheet" href="/css/ladder.css">
@endpush

@section('buttons')
    <a class="btn btn-outline-dark" href="javascript:window.print()"><i class="fas fa-print fa-fw"></i></a>
@endsection

@section("content")

    @foreach($data as $item)
        @include('ladder.ladder', [
            "students" => $item["students"],
            "group" => $item["group"],
            "last" => $item["last"],
            "now" => $now,
            "then" => $then
        ])
        @if(!$loop->last) <hr class="my-5" style="border-top: 7px solid gray"> @endif
    @endforeach

@endsection