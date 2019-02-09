@if($errors->count() > 0)
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="alert alert-warning" role="alert">
                    {{ $errors->first() }}
                </div>
            </div>
        </div>
    </div>
@endif
