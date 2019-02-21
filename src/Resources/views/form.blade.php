@if($edit)
    <img src="{{ config('plonk.url', '') . $plonk->resource($asset->hash)->get('smallest') }}" width="100%" class="img-thumbnail mb-4">
@endif

<div class="card">
    <div class="card-header">
        @if($edit)
            Edit image
        @else
            Upload a new image
        @endif
    </div>
    <div class="card-body">
        <form class="form-horizontal" enctype="multipart/form-data" role="form" method="post" action="{{ $action }}">
            @include('laravel-plonk::partial.form-requisites')
            
            {{-- @constituent('laravel-plonk::partial.input-hidden', [
                'name' => 'data'
            ]) --}}
            
            @if(!$edit)
                @constituent('laravel-plonk::partial.input-file', [
                    'name' => 'file',
                    'attributes' => [
                        'autofocus',
                        'required',
                    ]
                ])
            @endif
            
            @constituent('laravel-plonk::partial.input-text', [
                'name' => 'title',
                'value' => $edit ? $asset->title : '',
                'attributes' => [
                    'maxlength' => "255",
                    'required',
                ]
            ])
            
            @constituent('laravel-plonk::partial.input-text', [
                'name' => 'alt',
                'value' => $edit ? $asset->alt : '',
                'attributes' => [
                    'maxlength' => "255",
                    'required',
                ]
            ])
            
            @constituent('laravel-building::partial.resource-button-save', [
                'title' => 'Save',
            ])
        </form>
    </div>
</div>
{{-- @extends('laravel-plonk::main')

@section('content')
    
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">

                <div class="card">
                    <div class="card-header">Upload a new image</div>
                    <div class="card-body">
                        <form class="form-horizontal" role="form" accept="image/gif, image/jpeg, image/png" method="POST" action="{{ route($routes['store']) }}" enctype="multipart/form-data">
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('file') ? ' has-error' : '' }} row">
                                <label for="file" class="col-md-4 col-form-label">File</label>

                                <div class="col-md-6">
                                    <input type="hidden" name="data">
                                    <input id="file" type="file" class="form-control" name="file" value="{{ old('file') }}" required autofocus>

                                    @if ($errors->has('file'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('file') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="title" class="col-md-4 col-form-label">Title</label>
                                <div class="col-md-6">
                                    <input class="form-control" type="text" name="title" maxlength="255" value="{{ old('title') }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="alt" class="col-md-4 col-form-label">Alt tag</label>
                                <div class="col-md-6">
                                    <input class="form-control" type="text" name="alt" maxlength="255" value="{{ old('alt') }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Upload
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection --}}
