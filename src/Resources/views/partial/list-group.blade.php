@if($vue ?? false)
    <list-group inline-template>
        <div v-cloak class="list-group" :class="{destroy: !visible}">
            <div class="card mb-4">
                @if(isset($title))
                <div class="card-header d-flex justify-content-between">
                    <div>
                        @constituent('laravel-building::partial.icon', [
                            'icon' => sprintf('far fa-lg mr-1 %s', $icon)
                        ])
                        {!! $title !!}
                    </div>
                    <div class="d-flex align-items-center justify-content-end">
                        @if(isset($visible))
                            <div class="mr-3">
                                {{ $visible ? 'Visible' : 'Not visible' }}
                                @constituent('laravel-building::partial.icon', [
                                    'icon' => sprintf(
                                        'far fa-lg %s mr-1 text-secondary',
                                        $visible ? 'fa-check-circle' : 'fa-circle'
                                    )
                                ])
                            </div>
                        @endif

                        @if(isset($destroy))
                            @constituent('laravel-building::partial.resource-button-destroy', [
                                'route' => $destroy,
                                'attributes' => [
                                    'v-on:destroy-success' => "visible = false",
                                ],
                            ])
                        @endif
                    </div>
                    
                </div>
                @endif
                
                <ul class="list-group list-group-flush">
                    @foreach($items as $item)
                        @constituent('laravel-building::partial.list-group-item', $item)
                    @endforeach
                </ul>
            </div>
        </div>
    </list-group>
    
@else
    
<div class="list-group">
    <div class="card mb-4">
        @if(isset($title))
        <div class="card-header d-flex justify-content-between">
            <div>
                @constituent('laravel-building::partial.icon', [
                    'icon' => sprintf('far fa-lg mr-1 %s', $icon)
                ])
                {!! $title !!}
            </div>
            
            <div class="d-flex align-items-center justify-content-end">
                @if(isset($visible))
                    <div class="mr-3">
                        {{ $visible ? 'Visible' : 'Not visible' }}
                        @constituent('laravel-building::partial.icon', [
                            'icon' => sprintf(
                                'far fa-lg %s mr-1 text-secondary',
                                $visible ? 'fa-check-circle' : 'fa-circle'
                            )
                        ])
                    </div>
                @endif
                @if(isset($destroy))
                    @constituent('laravel-building::partial.resource-button-destroy', [
                        'route' => $destroy,
                        'attributes' => [
                            'v-on:destroy-success' => "visible = false",
                        ],
                    ])
                @endif
            </div>
            
        </div>
        @endif
        
        <ul class="list-group list-group-flush">
            @foreach($items as $item)
                @constituent('laravel-building::partial.list-group-item', $item)
            @endforeach
        </ul>
    </div>
</div>
@endif
