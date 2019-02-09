<div class="row">
    <div class="col-sm-12">
        <h1>{{ $heading }}</h1>
    </div>
    @if(isset($link))
        <div class="col-sm-12 text-right">
            <a href="{{ $link }}" class="btn btn-primary">
                <i class="fa fa-lg {{ $icon or '' }}"></i> {{ $title }}
            </a>
        </div>
    @endif
</div>
