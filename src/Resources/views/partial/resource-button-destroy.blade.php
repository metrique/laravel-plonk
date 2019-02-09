<form action="{{ $route }}" method="POST" {!! $constituent->attributes($attributes) !!}>
    @csrf
    @method('DELETE')
    
    @constituent('laravel-building::partial.input-submit', [
        'level' => 'btn-sm btn-secondary',
        'icon' => 'fas fa-trash',
        'title' => 'Delete',
        'attributes' => [
            'data-role' => 'destroy'
        ]
    ])
</form>
