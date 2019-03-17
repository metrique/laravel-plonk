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
            
            @constituent('laravel-plonk::partial.resource-button-save', [
                'title' => 'Save',
            ])
        </form>
    </div>
</div>
