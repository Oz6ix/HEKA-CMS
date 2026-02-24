@extends('backend.layouts.admin_login')

@section('content')
    <!-- BEGIN RESET PASSWORD FORM -->
    <div>
        <div class="kt-login__head">
            <h3 class="kt-login__title">Reset Password</h3>
            <div class="kt-login__desc">Enter a new login password:</div>
        </div>
        {!! Form::open(['url' => $url_prefix . '/reset/password/save', 'id' => 'password_form', 'class' => 'kt-form', 'method' => 'post']) !!}
        <input type="hidden" name="email" id="email" value="{{ $email }}">

        <!-- Messages section -->
        @include('backend.layouts.includes.notification_alerts_auth_pages')

        <div class="input-group">
            <input class="form-control" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" type="password" id="new_password" name="new_password"
                   placeholder="New Password" required="true">
        </div>
        <div class="input-group input-addon-right">
            <input class="form-control" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" type="password" id="confirm_password"
                   name="confirm_password"
                   placeholder="Confirm Password" required="true">
            <i class="fa fa-eye show-hide"></i>
        </div>
        <div class="kt-login__actions">
            <input type="submit" id="password_button" class="btn btn-brand btn-pill kt-login__btn-primary">
            </input>&nbsp;&nbsp;
            <button class="btn btn-secondary btn-pill kt-login__btn-secondary">
                <a href="{{ url($url_prefix) }}" style="color: #595d6e;">Back to Login</a>
            </button>
        </div>
        {!! Form::close() !!}
    </div>
    <!-- END RESET PASSWORD FORM -->
@endsection

@section('scripts')
    <!--begin::Page Scripts(used by this page) -->
    <script src="{{ URL::asset('assets/backend/js/validations/auth_pages.js') }}"
            type="text/javascript">
    </script>
    <!--end::Page Scripts -->
@endsection
