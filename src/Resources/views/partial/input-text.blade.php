@constituent('laravel-building::partial.input', [
    'attributes' => $attributes ?? [],
    'class' => $class ?? [],
    'name' => $name,
    'tooltip' => $tooltip ?? '',
    'label' => $label ?? $name,
    'type' => 'text',
    'value' => $value ?? '',
])
