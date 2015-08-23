<h1>Plonk</h1>
<p>Upload a file...</p>

<h2>Upload</h2>
@if($errors->any())
	<h3>Errors</h3>
	<ul>
	@foreach ($errors->all() as $error)
		<li>{{ $error }}</li>
	@endforeach
	</ul>
@endif

<form action="{{ route('plonk.store') }}" method="POST" enctype="multipart/form-data" accept="image/gif, image/jpeg, image/png">
	{!! csrf_field() !!}
	<fieldset>
		<input type="file" name="file">
		<label for="title">Title</label>
		<input type="text" name="title" placeholder="Title" maxlength="255" value="{{ old('title') }}">
		<label for="alt">Alt tag</label>
		<input type="text" name="alt" placeholder="Alt" maxlength="255" value="{{ old('alt') }}">
		<input type="submit">
	</fieldset>
</form>

<h2>Notes</h2>
<h3>Ratios</h3>
<p>Plonk would like you to upload images with one of the following ratios.</p>
<ul>
	@foreach ($ratios as $key => $value)
		<li>{{ $key }}:{{ $value }}</li>
	@endforeach
</ul>
<p>Plonk will process any image, regardless of ratio, and so it is expected that you will provde an image with a specific ratio if required.</p>