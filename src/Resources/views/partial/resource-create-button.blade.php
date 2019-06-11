<div>
    <a href="{{ $route }}" class="btn btn-secondary">
        <span class="mr-1">
            @constituent('laravel-building::partial.icon', [
                'icon' => 'fas ' . ($icon ?? '')
            ])
        </span>
        {{ $title }}
    </a>
</div>
