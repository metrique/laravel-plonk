@if(isset($route))
    <a {!! $constituent->classAttr([
        'list-group-item',
        'list-group-item-action',
        'd-flex',
        'justify-content-between',
        'align-items-center'
    ], $class) !!} href="{{ $route }}" {!! $constituent->attributes($attributes) !!}>
        <span>
            @if(isset($icon))
                <i class="far fa-fw {{ $icon }} mr-1"></i>
            @endif
            <strong>{!! $title !!}</strong>
        </span>
        @if(isset($badge))
            <span class="badge badge-primary badge-pill">{{ $badge }}</span>
        @endif
    </a>
@else
    <div {!! $constituent->classAttr([
        'list-group-item',
        'd-flex',
        'justify-content-between',
        'align-items-center'
    ], $class) !!}>
        <span>
            @if(isset($icon))
                <i class="far fa-fw {{ $icon }} mr-1"></i>
            @endif
            {!! $title !!}
        </span>
        @if(isset($badge))
            <span class="badge badge-primary badge-pill">{{ $badge }}</span>
        @endif
    </div>
@endif
