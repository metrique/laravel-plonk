@extends('metrique-plonk::master')

@section('content')

<div class="row">
	<h1>Plonk</h1>
	<p>Select a file...</p>
	<p>
		<a href="{{ route('plonk.create') }}" class="button small">Upload a new file</a>
	</p>
</div>

<div class="row">
	<h2>Assets</h2>
	@if(count($assets) < 1)
		<p>No assets, sorry!</p>
	@endif

	<ul class="small-block-grid-4">
		@foreach ($assets as $key => $value)
			<li>
				<p>
					<a class="th" href="{{ route('plonk.edit', $value->id) }}">
						<img src="{{ $cdnify->cdn() . '/plonk/originals/' . $value->hash . '.' . $value->extension }}" width="320px" class="img-rounded">
					</a>
				</p>
				<p>
					<a href="{{ route('plonk.edit', $value->id) }}" class="button tiny">Edit</a>
					<a href="{{ route('plonk.destroy', $value->id) }}" class="button tiny">Delete</a>
				</p>
			</li>
		@endforeach
	</ul>
</div>

<div class="row text-center">
	{!! $pagination !!}
</div>

@endsection