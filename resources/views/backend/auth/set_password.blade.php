@extends('backend.layouts.admin_login')

@section('content')
    <!-- BEGIN SET PASSWORD FORM -->
    <div>
        <div class="kt-login__head">
            <h3 class="kt-login__title">Set Password</h3>
            <div class="kt-login__desc">Create a new login password:</div>
        </div>
        {!! Form::open(['route'=>('save_set_password'), 'id' => 'password_form', 'class' => 'kt-form', 'files' => true, 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}  

        <input type="hidden" name="email" id="email" value="{{ $email }}">
        <!-- Messages section -->
        @include('backend.layouts.includes.notification_alerts_auth_pages')

        <div class="input-group">
            <input class="form-control" type="password" id="new_password" name="new_password"
                   placeholder="New Password" required="true">
        </div>
        <div class="input-group input-addon-right">
            <input class="form-control" type="password" id="confirm_password"
                   name="confirm_password"
                   placeholder="Confirm Password" required="true">
            <i class="fa fa-eye show-hide"></i>
        </div>
        <div class="kt-login__actions">
            <input type="submit" id="password_button" class="btn btn-brand btn-pill kt-login__btn-primary">
            </input>&nbsp;&nbsp;
            <button class="btn btn-secondary btn-pill kt-login__btn-secondary">
                <a href="{{ url($url_prefix).'/login' }}" style="color: #595d6e;">Back to Login</a>
            </button>
        </div>
        {!! Form::close() !!}
    </div>
    <!-- END SET PASSWORD FORM -->
@endsection
