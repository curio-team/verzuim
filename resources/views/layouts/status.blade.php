@if (is_array(session('status')))
	@foreach(session('status') as $type => $msg)
		<div class="alert alert-{{ $type }}">
	        {{ $msg }}
	    </div>
	@endforeach
@elseif(session('status'))
	<div class="alert alert-info">
        {{ session('status') }}
    </div>
@endif

@if(count($errors))
	<div class="form-group">
		<div class="alert alert-danger">
			<ul>
				@foreach($errors->all() as $error)
					<li>{{ $error }}</li>
				@endforeach
			</ul>
		</div>
	</div>
@endif
