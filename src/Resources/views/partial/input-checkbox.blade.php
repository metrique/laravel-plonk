@constituent('laravel-building::partial.input', [
    'attributes' => $attributes ?? [],
    'class' => $class ?? [],
    'checked' => $checked,
    'name' => $name,
    'tooltip' => $tooltip ?? '',
    'label' => $label ?? $name,
    'offset' => $offset ?? true,
    'type' => 'checkbox',
    'value' => $value ?? '',
])
