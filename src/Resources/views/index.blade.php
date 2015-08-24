@extends('metrique-plonk::master')

@section('content')
<h1>Plonk</h1>
<p>
	<a href="{{ route('plonk.create') }}">Upload a file</a>
</p>

<h2>Assets</h2>
<ul>
	@foreach ($assets as $key => $value)
		<li><img src="{{ $cdnify->cdn() . '/plonk/originals/' . $value->hash . '.' . $value->extension }}" width="320px"></li>
	@endforeach
</ul>

{!! $assets->render() !!}

@stop