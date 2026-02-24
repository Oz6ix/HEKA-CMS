@extends('frontend.layouts.layout_login')
@section('content')
<?php
$controller = class_basename(\Route::current()->controller);
$action = class_basename(\Route::current()->action['uses']);
$url_prefix = Config::get('app.app_route_prefix');
$app_logo_head = Config::get('app.app_logo_head');
$app_logo_sub = Config::get('app.app_logo_sub');
$header_data = fetch_header_data();
?>
@include('frontend.layouts.includes.alert_popup')
{{Form::token()}}
<!--begin::Content-->
          <div class="kt-grid__item kt-grid__item--fluid  kt-grid__item--order-tablet-and-mobile-1  kt-login__wrapper">
            <!--begin::Head-->
            <div class="kt-login__head">
              <span class="kt-login__signup-label">Don't have an account yet?</span>&nbsp;&nbsp;
              <a href="{{ route('patient_register') }}" class="kt-link kt-login__signup-link">Sign Up</a>
            </div>
            <!--end::Head-->
            <!--begin::Body-->
            <div class="kt-login__body">
              <!--begin::Signin-->
              <div class="kt-login__form">
                <div style="text-align: center;padding-top:40px;padding-bottom:40px;">
                 <img src="{{ URL::asset('uploads/' . $header_data['directory_logos'] . '/' . $header_data['logo_name']['logo_desktop']) }}" class="img-fluid" alt="">
                </div>
                <div class="kt-login__title">
                  <h3>Forgotten Password ?</h3>
                </div>
                <!--begin::Form-->
                
                  {!! Form::open(['route'=>('patient_send_email_link'), 'id' => 'add_form', 'class' => 'kt-form', 'files' => true, 'method' => 'post', 'enctype' => 'multipart/form-data']) !!} 
                  {{Form::token()}}
                  <div class="form-group">
                    <input class="form-control" type="text" placeholder="Email" name="email" autocomplete="off">
                  </div>
                 
                  <!--begin::Action-->
                  <div class="kt-login__actions">
                    <span class="kt-login__signup-label">Don't have an account yet?&nbsp;&nbsp;
                    <a href="{{ route('patient_login') }}" class="kt-link kt-login__signup-link">Sign in!</a></span>
                    <button type="submit" class="btn btn-primary btn-elevate kt-login__btn-primary">Submit</button>
                  </div>
                  {!! Form::close() !!}
                  <!--end::Action-->  
                <!--end::Form-->
              </div>                
              <!--end::Signin-->
            </div>
            <!--end::Body-->
          </div>


@endsection
@section('scripts')
 <script src="{{ URL::asset('assets/frontend/js/validations/frontend_login.js') }}"
            type="text/javascript"></script>
@endsection
