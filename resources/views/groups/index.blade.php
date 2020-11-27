@extends('layouts.app')

@section('content')

    <table class="table mt-4">
        @foreach($groups as $group)
            <tr>
                <td><a href="{{ route('groups.show', $group["id"]) }}">{{ $group["name"] }}</a></td>
                <td>
                    <a href="{{ route('groups.favorite', $group["id"]) }}">
                        @if($favorites->contains($group["id"]))
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