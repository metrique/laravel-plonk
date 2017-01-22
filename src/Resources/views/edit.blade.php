@extends('laravel-plonk::master')

@section('content')

<div class="row">
	<h1>Plonk</h1>
	<ul class="breadcrumbs">
		<li><a href="{{ route('plonk.index') }}">Index</a></li>
		<li class="unavailable">Edit</li>
	</ul>

	@if($errors->any())
		<h3>Errors</h3>
		<ul>
		@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
		</ul>
	@endif
</div>
<div class="row">
	<div class="small-12">
		<form action="{{ route('plonk.update', ['id' => $asset->id]) }}" method="POST">
			{!! csrf_field() !!}
			<input type="hidden" name="_method" value="PATCH">
			<fieldset>
				<legend>Edit</legend>
				<p class="th">
					<img src="{{ $cdnify->cdn() . '/plonk/originals/' . $asset->hash . '.' . $asset->extension }}" width="100%" class="img-rounded">
				</p>
				<label for="title">Title</label>
				<input type="text" name="title" placeholder="Title" maxlength="255" value="{{ $asset->title }}">
				<label for="alt">Alt tag</label>
				<input type="text" name="alt" placeholder="Alt" maxlength="255" value="{{ $asset->alt }}">
				<button type="submit"><i class="fa fa-lg fa-check"></i> Save</button>
			</fieldset>
		</form>
	</div>
</div>

@endsection