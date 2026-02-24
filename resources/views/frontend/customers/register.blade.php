@extends('frontend.layouts.layout_master')
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
            <div class="kt-login__head">
              <span class="kt-login__signup-label">Already have an account?</span>&nbsp;&nbsp;
              <a href="{{ route('patient_login') }}" class="kt-link kt-login__signup-link">Sign in!</a>
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
                  <h3>Sign Up</h3>
                </div>
                <!--begin::Form-->  
                {!! Form::open(['route'=>('register_create'), 'id' => 'add_form', 'class' => 'kt-form', 'files' => true, 'method' => 'post', 'enctype' => 'multipart/form-data']) !!} 
                {{Form::token()}}
                
                   <div class="form-group ">
                    <input class="form-control not-allowed isabled btn-light" readonly type="text" value="{{$patient_code}}" placeholder="Patient code" name="patient_code" autocomplete="off">
                  </div>
                  
                   <div class="form-group">
                    <input class="form-control" type="text" placeholder="Name" name="name" autocomplete="off">
                  </div>

                   <div class="form-group">
                    <input class="form-control" type="text" placeholder="Email" name="email" autocomplete="off" id="register_email">
                  </div>
                   <div class="form-group">
                    <input class="form-control" type="text" placeholder="Phone" name="phone" autocomplete="off">
                  </div>                  
                  <div class="form-group">
                    <input class="form-control" type="password" placeholder="Password" name="password" id="password">
                  </div>
                  <div class="form-group">
                    <input class="form-control" type="password" placeholder="Confirm Password" name="confirm_password" id="confirm_password">
                  </div>
                  <!--begin::Action-->
                  <div class="kt-login__actions">                   
                    <button type="submit" class="btn btn-primary btn-elevate kt-login__btn-primary">Submit</button>
                  </div>                   
                  <!--end::Action-->
                {!! Form::close() !!}  
                <!--end::Form-->
              </div>                
              <!--end::Signin-->
            </div>
            <!--end::Body-->
          </div>
      <!--Register Modal End -->
      <div class="emailmodal modal fade" id="popupmodel" tabindex="-1" role="basic" aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h5 class="modal-title modelTitleClass">Modal Title</h5>
                  </div>
                  <div class="modal-body" id="popupContent">
                  </div>
                  <div class="modal-footer">
                      <button type="button" class="btn default" data-dismiss="modal">Close</button>
                  </div>
              </div>
              <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
      </div>
@endsection
@section('scripts')
    <script type="text/javascript"
            src="{{ URL::asset('assets/frontend/js/validations/frontend_patient_register.js') }}"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            var crntName1 = $("#register_email").val();
            $("body").on('keyup', "#register_email", function () { //alert(crntName1);
                var name = $("#register_email").val();
                var originalElemVal = $("#register_email").attr("data");
                if (crntName1 != name) {
                    //var id = 0;
                    var elemVal = $("#register_email").val();
                    //alert(elemVal);
                    $.ajax({
                        url: "{{ config('global.basepath') }}user/ajax_duplicate_email/" + elemVal,
                        type: "GET",
                        cache: false,
                        success: function (html) {
                            if (html == 1) {
                                $('#popupmodel').modal('show');
                                $('.modelTitleClass').html("Duplicate Email Address");
                                $('#popupContent').html("Email Address already exists.");
                                $("#register_email").val(originalElemVal);
                            }
                        }
                    });
                }
            });            
        });
    </script>


@endsection
