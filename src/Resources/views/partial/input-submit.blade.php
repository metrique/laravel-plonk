<button type="submit" class="btn {{ $level ?? 'btn-primary' }}" {!! $constituent->attributes($attributes) !!}>
    @constituent('laravel-building::partial.icon', [
        'icon' => $icon ?? ''
    ])
    {{ $title ?? 'Submit' }}
</button>
