@extends("layouts.app")
@section("content")

    <div class="row">
        <div class="col-md-7">
            <h3>Admins</h3>        
            <ul class="list-group">
                <li class="list-group-item p-0 border-0">
                    <form method="POST" action="{{ route('admins.save') }}">
                        @csrf
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text" style="border-bottom-left-radius: 0;">Docent-code:</span>
                            </div>
                            <input type="text" class="form-control" id="code" name="code" placeholder="ab01">
                            <div class="input-group-append">
                                <button class="btn btn-outline-secondary" style="border-bottom-right-radius: 0;" type="submit"><i class="fas fa-plus"></i> Toevoegen</button>
                            </div>
                        </div>
                    </form>
                </li>
                @foreach($admins as $admin)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <span>{{ $admin->user_id }}</span>
                        @if($admin->user_id != \Auth::user()->id):
                            <form method="POST" action="{{ route('admins.delete', $admin->user_id) }}">
                                @method("DELETE")
                                @csrf
                                <button class="btn py-0"><i class="fas fa-trash"></i></button>
                            </form>
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

@endsection