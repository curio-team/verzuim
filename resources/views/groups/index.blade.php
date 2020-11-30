@extends('layouts.app')

@section('content')

    <h3>Klassen</h3>
    <table class="table mt-4">
        @foreach($groups as $group)
            <tr>
                <td><a href="{{ route('groups.show', $group["name"]) }}">{{ $group["name"] }}</a></td>
                <td>
                    <a href="{{ route('groups.favorite', $group["name"]) }}">
                        @if($favorites->contains($group["name"]))
                            <i class="fas fa-trash fa-fw"></i>
                        @else
                            <i class="fas fa-plus fa-fw"></i>
                        @endif
                        favoriet
                    </a>
                </td>
            </tr>
        @endforeach
    </table>

@endsection