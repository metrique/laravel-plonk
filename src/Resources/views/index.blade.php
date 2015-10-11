@extends('metrique-plonk::master')

@section('content')

<div class="row">
	<h1>Plonk</h1>
	<p>Select a file...</p>
	<p>
		<a href="{{ route('plonk.create') }}" class="button small"><i class="fa fa-lg fa-plus"></i> Upload a new image</a>
	</p>
</div>

<div class="row">
	<h2>Assets</h2>
	@if(count($assets) < 1)
		<p>No assets, sorry!</p>
	@endif
	
	<fieldset>
		<ul class="small-block-grid-4">
			@foreach ($assets as $key => $value)
				<li>
					<p>
						<a class="th" href="{{ route('plonk.show', $value->id) }}">
							<img src="{{ $cdnify->cdn() . '/plonk/originals/' . $value->hash . '.' . $value->extension }}" width="100%" class="img-rounded">
						</a>
					</p>
					<p>
						<a href="{{ route('plonk.show', $value->id) }}" class="button tiny"><i class="fa fa-lg fa-eye"></i></a>
						<a href="{{ route('plonk.edit', $value->id) }}" class="button tiny"><i class="fa fa-lg fa-pencil"></i></a>
						<a href="{{ route('plonk.destroy', $value->id) }}" class="button tiny"><i class="fa fa-lg fa-trash"></i></a>
					</p>
				</li>
			@endforeach
		</ul>
	</fieldset>
</div>

<div class="row text-center">
	{!! $pagination !!}
</div>

@endsection