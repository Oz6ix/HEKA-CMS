@if(Session::has('message'))
    <div class="alert alert-success" role="alert">
        <div class="alert-icon"><strong><i class="fa fa-check-circle" style="font-size: 15px;"></i> Success!</strong>
        </div>
        <div class="alert-text" style="font-size: 14px;">{{ Session::get('message') }}</div>
    </div>
@endif
@if(Session::has('error_message'))
    <div class="alert alert-danger" role="alert">
        <div class="alert-icon"><strong><i class="fa fa-times-circle" style="font-size: 15px;"></i> Error!</strong>
        </div>
        <div class="alert-text" style="font-size: 14px;">{{ Session::get('error_message') }}</div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="la la-close"></i></span>
            </button>
        </div>
    </div>
@endif
@if(Session::has('warning_message'))
    <div class="alert alert-warning" role="alert">
        <div class="alert-icon"><strong><i class="fa fa-info-circle" style="font-size: 15px;"></i> Info!</strong></div>
        <div class="alert-text">{!! Session::get('warning_message') !!}</div>
        <div class="alert-close">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true"><i class="la la-close"></i></span>
            </button>
        </div>
    </div>
@endif
{{-- Server-side error messages --}}
@if ($errors->any())
    @foreach ($errors->all() as $error)
        <div class="alert alert-danger" role="alert">
            <div class="alert-icon"><strong><i class="fa fa-times-circle" style="font-size: 15px;"></i>
                    Error!</strong></div>
            <div class="alert-text" style="font-size: 14px;">{{ $error }}</div>
            <div class="alert-close">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true"><i class="la la-close"></i></span>
                </button>
            </div>
        </div>
    @endforeach
@endif