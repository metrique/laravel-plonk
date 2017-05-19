@extends('laravel-plonk::master')

@section('body')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                <div class="row">
                    <div class="panel panel-default">
                        <div class="panel-heading">Upload an image</div>
                        <div class="panel-body">
                            <form class="form-horizontal" role="form" accept="image/gif, image/jpeg, image/png" method="POST" action="{{ route($routes['store']) }}" enctype="multipart/form-data">
                                {{ csrf_field() }}

                                <div class="form-group{{ $errors->has('file') ? ' has-error' : '' }}">
                                    <label for="file" class="col-md-4 control-label">File</label>

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

                                <div class="form-group">
                                    <label for="title" class="col-md-4 control-label">Title</label>
                                    <div class="col-md-6">
                                        <input class="form-control" type="text" name="title" maxlength="255" value="{{ old('title') }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="alt" class="col-md-4 control-label">Alt tag</label>
                                    <div class="col-md-6">
                                        <input class="form-control" type="text" name="alt" maxlength="255" value="{{ old('alt') }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-md-8 col-md-offset-4">
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
    </div>
@endsection
