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
	<ul class="small-block-grid-4">
		@foreach ($assets as $key => $value)
			<li>
				<a class="th" href="{{ route('plonk.edit', $value->id) }}">
					<img src="{{ $cdnify->cdn() . '/plonk/originals/' . $value->hash . '.' . $value->extension }}" width="320px" class="img-rounded">
				</a>
				<p><a href="{{ route('plonk.edit', $value->id) }}" class="button tiny">Edit</a></p>
			</li>
		@endforeach
	</ul>
</div>

<div class="row text-center">
	{!! $pagination !!}
</div>

@stop