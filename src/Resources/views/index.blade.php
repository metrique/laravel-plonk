@extends('laravel-plonk::main')

@section('content')
    @constituent('laravel-building::partial.resource-page-title', [
        'icon' => 'fas fa-fw fa-images',
        'title' => 'Images'
    ])

    <div class="row justify-content-center my-4">
        <div class="col-md-8 d-flex justify-content-between">
            <form action="{{ request()->url() }}" class="form-inline" method="GET">
                <div class="form-group">
                    <input class="form-control" type="text" name="search" placeholder="Search..." value="{{ request()->query('search') }}">
                </div>
                <button type="submit" class="btn btn-primary ml-2">
                    <i class="fa fa-search"></i> Search
                </button>
                
                @if(request()->has('search'))
                    <a class="ml-2" href="{{ route($routes['index']) }}">
                        <i class="fa fa-times"></i> Clear search
                    </a>
                @endif
            </form>

            @constituent('laravel-building::partial.resource-create-button', [
                'icon' => 'fas fa-fw fa-plus',
                'title' => 'Upload a new image',
                'route' => route($routes['create'])
            ])
        </div>
    </div>
    
    <div class="row justify-content-center mb4">
        <div class="col-md-8">
            @if(count($assets) < 1)
                <p>No images found. <a href="{{ route($routes['create']) }}">Upload a new image.</a></p>
            @endif

            @foreach ($assets as $key => $value)
                
                <img src="{{ config('plonk.url', '') . $plonk->resource($value->hash)->get('smallest') }}" width="100%" class="img-thumbnail mb-4">
                
                @constituent('laravel-building::partial.list-group', [
                    'icon' => 'fa fa-fw fa-image',
                    'title' => sprintf('%s', $value->title),
                    'destroy' => route($routes['destroy'], $value->id),
                    'items' => [
                        [
                            'title' => $value->hash,
                            'icon' => 'fas fa-fw fa-fingerprint',
                            // 'route' => '/'. str_replace('_', '/', $page->hash),
                        ],[
                            'title' => sprintf('
                                <a href="%s"><i class="fas fa-xs fa-image"></i> Small image</a>
                                <a class="ml-4" href="%s"><i class="fas fa-image"></i> Large image</a>',
                                config('plonk.url', '') . $plonk->resource($value->hash)->get('smallest'),
                                config('plonk.url', '') . $plonk->resource($value->hash)->get('largest')
                            ),
                            'icon' => null,
                        ],[
                            'title' => 'Edit tags',
                            'icon' => 'fas fa-cog',
                            'route' => route($routes['edit'], $value->id) 
                        ]
                    ]
                ])
            @endforeach
        </div>
    </div>
@endsection
