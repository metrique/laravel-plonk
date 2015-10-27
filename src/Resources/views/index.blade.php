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
						<form method="POST" action="{{ route('plonk.destroy', $value->id) }}">
							<a href="{{ route('plonk.show', $value->id) }}" class="button tiny"><i class="fa fa-lg fa-eye"></i></a>
							<a href="{{ route('plonk.edit', $value->id) }}" class="button tiny"><i class="fa fa-lg fa-pencil"></i></a>
							{!! csrf_field() !!}
							<input type="hidden" name="_method" value="DELETE">
							<button type="submit" class="tiny" data-role="destroy"><i class="fa fa-lg fa-trash"></i></button>
						</form>
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