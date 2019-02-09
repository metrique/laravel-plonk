@extends('laravel-plonk::main')

@section('content')
    @constituent('laravel-building::partial.resource-page-title', [
        'icon' => 'fas fa-fw fa-images',
        'title' => 'Images'
    ])
    
    <div class="row justify-content-center">
        <div class="col-md-8 ">
            @include('laravel-plonk::form', [
                'action' => route($routes['update'], $asset->id),
                'edit' => true,
                'data' => $data,
            ])
        </div>
    </div>
@endsection
