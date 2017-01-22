@extends('laravel-plonk::master')

@section('content')

<div class="row">
	<h1>Plonk</h1>
	<ul class="breadcrumbs">
		<li><a href="{{ route('plonk.index') }}">Index</a></li>
		<li class="unavailable">Upload</li>
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
		<form action="{{ route('plonk.store') }}" method="POST" enctype="multipart/form-data" accept="image/gif, image/jpeg, image/png">
			{!! csrf_field() !!}
			<fieldset>
				<legend>Upload</legend>
				<div class="small-12">
					<input type="hidden" name="data">
					<input type="file" name="file">
				</div>

				<div class="small-12">
					<label for="title">Title</label>
					<input type="text" name="title" placeholder="Title" maxlength="255" value="{{ old('title') }}">
				</div>

				<div class="small-12">
					<label for="alt">Alt tag</label>
					<input type="text" name="alt" placeholder="Alt" maxlength="255" value="{{ old('alt') }}">
				</div>

				<button type="submit"><i class="fa fa-lg fa-check"></i> Save</button>
			</fieldset>
		</form>
	</div>
</div>

<div class="row">
	<div class="small-12">
		<div class="panel callout radius small">
			<h4>Notes on ratios</h4>
			<p>Plonk would like you to upload images with one of the following ratios.</p>
			<ul>
				@foreach ($ratios as $key => $value)
					<li>{{ $key }}:{{ $value }}</li>
				@endforeach
			</ul>
			<p>Plonk will process any image, regardless of ratio, and so it is expected that you will provde an image with a specific ratio if required.</p>
		</div>
	</div>
</div>

@endsection
