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
            <!--end::Head-->
            <!--begin::Body-->
            <div class="kt-login__body">
              <!--begin::Signin-->
              <div class="kt-login__form">
                <div class="kt-login__title">
                  <h3>Reset Password</h3>
                </div>
                <!--begin::Form-->
                
                  {!! Form::open(['route'=>('patient_save_reset_password'), 'id' => 'add_form', 'class' => 'kt-form', 'files' => true, 'method' => 'post', 'enctype' => 'multipart/form-data']) !!} 
                  {{Form::token()}}
                  <input type="hidden" name="email" id="email" value="{{ $email }}">
                 <div class="form-group">
                    <input class="form-control" type="password" placeholder="Password" name="password" id="password">
                  </div>
                  <div class="form-group">
                    <input class="form-control" type="password" placeholder="Confirm Password" name="confirm_password" id="confirm_password">
                  </div>
                   <div class="kt-login__actions">                   
                    <button type="submit" class="btn btn-primary btn-elevate kt-login__btn-primary">Submit</button>
                  </div>

                  <!--begin::Action-->                  
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
 <script src="{{ URL::asset('assets/frontend/js/validations/frontend_patient_register.js') }}"
            type="text/javascript"></script>

@endsection
