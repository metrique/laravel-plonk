@extends('metrique-plonk::master')

@section('content')

<div class="row">
	<h1>Plonk</h1>
	<p>Edit</p>

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
	<form action="{{ route('plonk.update', ['id' => $asset->id]) }}" method="POST">
		{!! csrf_field() !!}
		<input type="hidden" name="_method" value="PATCH">
		<fieldset>
			<label for="title">Title</label>
			<input type="text" name="title" placeholder="Title" maxlength="255" value="{{ $asset->title }}">
			<label for="alt">Alt tag</label>
			<input type="text" name="alt" placeholder="Alt" maxlength="255" value="{{ $asset->alt }}">
			<input type="submit" class="button small">
		</fieldset>
</div>
<div class="row"></div>
<div class="row"></div>

@endsection