@extends('laravel-plonk::master')

@section('body')
    <div class="container">
        <div class="row">
            <div class="col-md-8 col-md-offset-2">

                <div class="row">
                    <div class="col-md-8">
                        <form action="{{ request()->url() }}" class="form-inline" method="GET">
                            <div class="form-group">
                                <input class="form-control" type="text" name="search" placeholder="Search..." value="{{ request()->query('search') }}">
                            </div>
                            <button type="submit" class="btn btn-default"><i class="fa fa-search"></i> Search</button>
                            @if(request()->has('search'))
                                <a href="{{ route('plonk.index') }}"><i class="fa fa-times"></i> Clear search</a>
                            @endif
                        </form>
                    </div>
                    <div class="col-md-4">
                        <p class="text-right">
                            <a href="{{ route('plonk.create') }}" class="btn btn-primary">
                                Upload an image
                            </a>
                        </p>
                    </div>
                </div>

                @if(count($assets) < 1)
                    <p>No images found. <a href="{{ route($routes['create']) }}">Upload an image.</a></p>
                @else
                @endif

                @foreach ($assets as $key => $value)

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <div class="row">
                                <div class="col-md-8">
                                    {{ $value->title }}
                                </div>
                                <div class="col-md-4 text-right">
                                    <form method="POST" action="{{ route('plonk.destroy', $value->id) }}">
                                        {!! csrf_field() !!}
                                        <input type="hidden" name="_method" value="DELETE">
                                        <button type="submit" class="btn btn-xs btn-danger" data-role="destroy">
                                            <i class="fa fa-fw fa-trash"></i>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="panel-body">
                            <img src="{{ $cdnify->cdn() . $plonk->resource($value->hash)->get('small') }}" width="100%" class="img-rounded img-responsive">
                        </div>

                        <ul class="list-group">
                            <li class="list-group-item">
                                <i class="fa fa-fw fa-id-card-o"></i> ID
                                <code>{{ $value->hash }}</code>
                            </li>
                            <li class="list-group-item">
                                <div class="row">
                                    <div class="col-md-6">
                                        <i class="fa fa-fw fa-link"></i> Small
                                        <code>{{ $cdnify->cdn() . $plonk->resource($value->hash)->get('small') }}</code>
                                    </div>
                                    <div class="col-md-6">
                                        <i class="fa fa-fw fa-link"></i> Large
                                        <code>{{ $cdnify->cdn() . $plonk->resource($value->hash)->get('large') }}</code>
                                    </div>
                                </div>
                            </li>
                            <li class="list-group-item">
                                <a href="{{ route('plonk.edit', $value->id) }}">
                                    <i class="fa fa-fw fa-tag" aria-hidden="true"></i>
                                    Edit tags
                                </a>
                            </li>
                        </ul>

                    </div>
                @endforeach

                <div class="row text-center">
                    {!! $assets->links() !!}
                </div>

            </div>
        </div>
    </div>
@endsection
