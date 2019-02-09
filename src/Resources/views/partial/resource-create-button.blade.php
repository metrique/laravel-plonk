<div>
    <a href="{{ $route }}" class="btn btn-primary">
        <span class="mr-1">
            @constituent('laravel-building::partial.icon', [
                'icon' => 'fas ' . ($icon ?? '')
            ])
        </span>
        {{ $title }}
    </a>
</div>
