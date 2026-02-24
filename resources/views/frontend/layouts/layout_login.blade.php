<?php
$controller = class_basename(\Route::current()->controller);
$action = class_basename(\Route::current()->action['uses']);
$url_prefix = Config::get('app.app_route_prefix');
$app_logo_head = Config::get('app.app_logo_head');
$app_logo_sub = Config::get('app.app_logo_sub');
$header_data = fetch_header_data();
?>
<!DOCTYPE html>
<html lang="en">
<!-- begin::Head -->
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ config('app.site_title') }}</title>
    <link rel="shortcut icon" href="{{ URL::asset('uploads/logos/favicon.png') }}"/>
    <link rel="icon" href="{{ URL::asset('uploads/logos/favicon.png') }}" type="image/x-icon"/>
    <!--begin::Fonts -->
    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
            google: {
                "families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]
            },
            active: function () {
                sessionStorage.fonts = true;
            }
        });
    </script>
      <link href="{{ URL::asset('assets/frontend/css/demo1/pages/login/login-1.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Fonts -->
    <!--begin::Page Custom Styles(used by this page) -->
    <link href="{{ URL::asset('assets/frontend/css/demo1/pages/login/login-4.css') }}" rel="stylesheet"
          type="text/css"/>
    <!--end::Page Custom Styles -->
    <!--begin:: Global Mandatory Vendors -->
    <link href="{{ URL::asset('assets/frontend/vendors/general/perfect-scrollbar/css/perfect-scrollbar.css') }}"
          rel="stylesheet" type="text/css"/>
    <!--end:: Global Mandatory Vendors -->
    <!--begin:: Global Optional Vendors -->
    <link href="{{ URL::asset('assets/frontend/vendors/general/tether/dist/css/tether.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/frontend/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/frontend/vendors/general/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/frontend/vendors/general/bootstrap-timepicker/css/bootstrap-timepicker.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/frontend/vendors/general/bootstrap-daterangepicker/daterangepicker.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/frontend/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/frontend/vendors/general/bootstrap-select/dist/css/bootstrap-select.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/frontend/vendors/general/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/frontend/vendors/general/select2/dist/css/select2.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/frontend/vendors/general/ion-rangeslider/css/ion.rangeSlider.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/frontend/vendors/general/nouislider/distribute/nouislider.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/frontend/vendors/general/owl.carousel/dist/assets/owl.carousel.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/frontend/vendors/general/owl.carousel/dist/assets/owl.theme.default.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/frontend/vendors/general/dropzone/dist/dropzone.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/frontend/vendors/general/summernote/dist/summernote.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/frontend/vendors/general/bootstrap-markdown/css/bootstrap-markdown.min.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/frontend/vendors/general/animate.css/animate.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('assets/frontend/vendors/general/toastr/build/toastr.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('assets/frontend/vendors/general/morris.js/morris.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('assets/frontend/vendors/general/sweetalert2/dist/sweetalert2.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/frontend/vendors/general/socicon/css/socicon.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('assets/frontend/vendors/custom/vendors/line-awesome/css/line-awesome.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/frontend/vendors/custom/vendors/flaticon/flaticon.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/frontend/vendors/custom/vendors/flaticon2/flaticon.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/frontend/vendors/general/@fortawesome/fontawesome-free/css/all.min.css') }}"
          rel="stylesheet" type="text/css"/>
    <!--end:: Global Optional Vendors -->
    <!--begin::Global Theme Styles(used by all pages) -->
    <link href="{{ URL::asset('assets/frontend/css/demo1/style.bundle.css') }}" rel="stylesheet"
          type="text/css"/>
    <!--end::Global Theme Styles -->
    <!--begin::Layout Skins(used by all pages) -->
    <link href="{{ URL::asset('assets/frontend/css/demo1/skins/header/base/light.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('assets/frontend/css/demo1/skins/header/menu/light.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('assets/frontend/css/demo1/skins/brand/dark.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('assets/frontend/css/demo1/skins/aside/dark.css') }}" rel="stylesheet"
          type="text/css"/>
    <!--end::Layout Skins -->
    <link href="{{ URL::asset('assets/frontend/css/custom-auth.css') }}" rel="stylesheet"
          type="text/css"/>
</head>
<!-- end::Head -->
<!-- begin::Body -->
<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">
<!-- begin:: Page -->
<div class="kt-grid kt-grid--ver kt-grid--root">
      <div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v1" id="kt_login">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--desktop kt-grid--ver-desktop kt-grid--hor-tablet-and-mobile">

          <!--begin::Aside-->
          <div class="kt-grid__item kt-grid__item--order-tablet-and-mobile-2 kt-grid kt-grid--hor kt-login__aside" style="background-image: url({{ URL::asset('assets/frontend/media/bg/bg-4.jpg') }});">
            <div class="kt-grid__item">
              <a href="#" class="kt-login__logo">
               <!--  <img src="{{ URL::asset('assets/frontend/media/logos/logo-4.png') }}"> -->
               <!-- <img src="{{ URL::asset('uploads/' . $header_data['directory_logos'] . '/' . $header_data['logo_name']['logo_desktop']) }}" class="img-fluid" alt=""> -->
              </a>
            </div>
            <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver">
              <div class="kt-grid__item kt-grid__item--middle">
                <h3 class="kt-login__title"></h3>
                <h4 class="kt-login__subtitle"></h4>
              </div>
            </div>
            <div class="kt-grid__item">
              <div class="kt-login__info">
                <div class="kt-login__copyright">
                  &copy {{date('Y')}} {{ $header_data['site_settings']['hospital_name'] }}
                </div>
                <!-- <div class="kt-login__menu">
                  <a href="#" class="kt-link">Privacy</a>
                  <a href="#" class="kt-link">Legal</a>
                  <a href="#" class="kt-link">Contact</a>
                </div> -->
              </div>
            </div>
          </div>
          <!--begin::Aside-->
          
           @yield('content')
          <!--end::Content-->
        </div>
      </div>
    </div>
                 

<!-- end:: Page -->
<!-- begin::Global Config(global config for global JS sciprts) -->
<script>
    var KTAppOptions = {
        "colors": {
            "state": {
                "brand": "#5d78ff",
                "dark": "#282a3c",
                "light": "#ffffff",
                "primary": "#5867dd",
                "success": "#34bfa3",
                "info": "#36a3f7",
                "warning": "#ffb822",
                "danger": "#fd3995"
            },
            "base": {
                "label": ["#c5cbe3", "#a1a8c3", "#3d4465", "#3e4466"],
                "shape": ["#f0f3ff", "#d9dffa", "#afb4d4", "#646c9a"]
            }
        }
    };
</script>
<!-- end::Global Config -->
<!--begin:: Global Mandatory Vendors -->
<script src="{{ URL::asset('assets/frontend/vendors/general/jquery/dist/jquery.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/popper.js/dist/umd/popper.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/bootstrap/dist/js/bootstrap.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/js-cookie/src/js.cookie.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/moment/min/moment.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/tooltip.js/dist/umd/tooltip.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/perfect-scrollbar/dist/perfect-scrollbar.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/sticky-js/dist/sticky.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/wnumb/wNumb.js') }}"
        type="text/javascript"></script>
<!--end:: Global Mandatory Vendors -->
<!--begin:: Global Optional Vendors -->
<script src="{{ URL::asset('assets/frontend/vendors/general/jquery-form/dist/jquery.form.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/block-ui/jquery.blockUI.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/custom/js/vendors/bootstrap-datepicker.init.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/custom/js/vendors/bootstrap-timepicker.init.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/bootstrap-daterangepicker/daterangepicker.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/bootstrap-maxlength/src/bootstrap-maxlength.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/custom/vendors/bootstrap-multiselectsplitter/bootstrap-multiselectsplitter.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/bootstrap-select/dist/js/bootstrap-select.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/bootstrap-switch/dist/js/bootstrap-switch.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/custom/js/vendors/bootstrap-switch.init.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/select2/dist/js/select2.full.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/ion-rangeslider/js/ion.rangeSlider.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/typeahead.js/dist/typeahead.bundle.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/handlebars/dist/handlebars.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/inputmask/dist/jquery.inputmask.bundle.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/inputmask/dist/inputmask/inputmask.date.extensions.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/inputmask/dist/inputmask/inputmask.numeric.extensions.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/nouislider/distribute/nouislider.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/owl.carousel/dist/owl.carousel.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/autosize/dist/autosize.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/clipboard/dist/clipboard.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/dropzone/dist/dropzone.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/summernote/dist/summernote.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/markdown/lib/markdown.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/bootstrap-markdown/js/bootstrap-markdown.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/custom/js/vendors/bootstrap-markdown.init.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/bootstrap-notify/bootstrap-notify.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/custom/js/vendors/bootstrap-notify.init.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/jquery-validation/dist/jquery.validate.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/jquery-validation/dist/additional-methods.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/custom/js/vendors/jquery-validation.init.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/toastr/build/toastr.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/raphael/raphael.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/morris.js/morris.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/chart.js/dist/Chart.bundle.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/custom/vendors/bootstrap-session-timeout/dist/bootstrap-session-timeout.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/custom/vendors/jquery-idletimer/idle-timer.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/waypoints/lib/jquery.waypoints.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/counterup/jquery.counterup.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/es6-promise-polyfill/promise.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/sweetalert2/dist/sweetalert2.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/custom/js/vendors/sweetalert2.init.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/jquery.repeater/src/lib.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/jquery.repeater/src/jquery.input.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/jquery.repeater/src/repeater.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/vendors/general/dompurify/dist/purify.js') }}"
        type="text/javascript"></script>
<!--end:: Global Optional Vendors -->
<!--begin::Global Theme Bundle(used by all pages) -->
<script src="{{ URL::asset('assets/frontend/js/demo1/scripts.bundle.js') }}" type="text/javascript"></script>
<!--end::Global Theme Bundle -->
<!--begin::Page Scripts(used by this page) -->
<script src="{{ URL::asset('assets/frontend/js/demo1/pages/login/login-general.js') }}"
        type="text/javascript"></script>
<!--end::Page Scripts -->
<!--begin::Custom javascript codes(used by all pages) -->
<script src="{{ URL::asset('assets/frontend/js/demo1/pages/components/extended/blockui.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/frontend/js/custom.js') }}" type="text/javascript"></script>
<!--end::Custom javascript codes -->
<script src="{{ URL::asset('assets/frontend/js/demo1/pages/login/login-1.js') }}" type="text/javascript"></script>
@yield('scripts')
</body>
<!-- end::Body -->
</html>
