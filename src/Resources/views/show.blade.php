@extends('metrique-plonk::master')

@section('content')

<div class="row">
	<h1>Plonk</h1>
	<ul class="breadcrumbs">
		<li><a href="{{ route('plonk.index') }}">Index</a></li>
		<li class="unavailable">Show</li>
	</ul>
</div>

<div class="row">
	<div class="small-12">
		<fieldset>
			<legend>Show</legend>
			<h2>{{ $asset->title }}</h2>
			<h3 class="subheader">{{ $asset->alt }}</h3>

			<p class="th">
				<img src="{{ $cdnify->cdn() . '/plonk/originals/' . $asset->hash . '.' . $asset->extension }}" width="100%" class="img-rounded">
			</p>
			<label for="json-embed">Json embed</label>
			<textarea name="json-embed" style="height: 200px;">{!! $asset->toJson() !!}</textarea>
		</fieldset>
	</div>
</div>

@endsection