@extends('laravel-plonk::master')

@section('body')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2">

                <div class="card">
                    <div class="card-header">Edit tags</div>
                    <div class="card-body">
                        <img src="{{ $cdnify->cdn() . $plonk->resource($asset->hash)->get('small') }}" width="100%" class="img-rounded img-responsive">
                    </div>
                    <div class="card-body">
                        <form class="form-horizontal" role="form" accept="image/gif, image/jpeg, image/png" method="POST" action="{{ route($routes['update'], ['id' => $asset->id]) }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            {{ method_field('PATCH') }}

                            <div class="form-group row">
                                <label for="title" class="col-md-4 col-form-label">Title</label>
                                <div class="col-md-6">
                                    <input class="form-control" type="text" name="title" maxlength="255" value="{{ $asset->title }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="alt" class="col-md-4 col-form-label">Alt tag</label>
                                <div class="col-md-6">
                                    <input class="form-control" type="text" name="alt" maxlength="255" value="{{ $asset->alt }}">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Save
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
