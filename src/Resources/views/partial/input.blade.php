<div {!! $constituent->classAttr([
    $errors->has($name) ? ' has-error' : '',
    'form-group',
    'row',
    $type
], $class) !!}>
    
    @if(!in_array($type, [
        'checkbox',
        'hidden'
    ]))
        <label for="{{ $name }}" class="col-sm-4 col-form-label text-md-right">
            {{ ucfirst(str_replace('_', ' ', $label ?? $name)) }}
            @if($tooltip)
                @constituent('dashboard.tooltip', [
                    'tooltip' => $tooltip
                ])
            @endif
        </label>
    @endif
    
    <div class="col-md-6 {{ $constituent->class(['offset-md-4' => $offset ?? false])}}">
        @switch($type)
            @case('checkbox')
            <div class="form-check">
                {{
                    $form->checkbox(
                        $name,
                        $value,
                        $checked,
                        array_merge([
                            'class' => 'form-check-input'
                        ], $attributes ?? [])
                    )
                }}
                <label for="{{ $attributes['id'] ?? $name }}">
                    {{ ucfirst(str_replace('_', ' ', $label ?? $name)) }}
                    @if($tooltip ?? false)
                        <a href="#" data-toggle="tooltip" title="{{ $tooltip }}">i</a>
                    @endif
                </label>
            </div>
            @break
            
            @case('markdown')
            <input-markdown name="{{ $name }}" v-model="form.{{ $name }}">
                <template slot="input">
                {{
                        $form->textarea(
                        $name,
                        $value,
                        array_merge([
                            'class' => ($errors->has($name) ? 'is-invalid ' : '') . 'form-control',
                            'ref' => $name,
                            'v-model' => 'form.' . $name,
                        ], $attributes ?? [])
                    )
                }}
                </template>
            </input-markdown>
            @break
            
            @case('select')
            {{
                $form->select(
                    $name,
                    $values,
                    $value,
                    array_merge([
                        'class' => 'form-control'
                    ], $attributes ?? [])
                )
            }}
            @break
            
            @case('select-multi')
            {{
                $form->select(
                    $name,
                    $values,
                    $value,
                    array_merge([
                        'class' => 'form-control',
                        'multiple' => true,
                    ], $attributes ?? [])
                )
            }}
            @break
            
            @case('textarea')
            {{
                $form->textarea(
                    $name,
                    $value,
                    array_merge([
                        'class' => ($errors->has($name) ? 'is-invalid ' : '') . 'form-control',
                    ], $attributes ?? [])
                )
            }}
            @break
            
            @default
            {{
                $form->input(
                    $type,
                    $name,
                    $value,
                    array_merge([
                        'class' => ($errors->has($name) ? 'is-invalid ' : '') . 'form-control',
                    ], $attributes ?? [])
                )
            }}
        @endswitch
        
        @if(!in_array($type, [
            'checkbox',
            'hidden'
        ]))
            @if($vue ?? false)
                <span class="invalid-feedback"
                    v-if="form.errors.has('{{ $name }}')"
                    v-text="form.getError('{{ $name }}')">
                </span>
            @else
                @if($errors->has($name))
                    <span class="invalid-feedback">
                        <strong>{{ $errors->first($name) }}</strong>
                    </span>
                @endif
            @endif
        @endif
    </div>
</div>
