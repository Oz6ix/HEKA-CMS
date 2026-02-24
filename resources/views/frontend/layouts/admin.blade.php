<!DOCTYPE html>
<html lang="en">
<!-- begin::Head -->
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ config('app.site_title') }}{{ isset($page_title) ? ' : ' . $page_title : ''}}</title>
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
    <!--end::Fonts -->
    <!--begin:: Global Mandatory Vendors -->
    <link href="{{ URL::asset('assets/backend/vendors/general/perfect-scrollbar/css/perfect-scrollbar.css') }}"
          rel="stylesheet" type="text/css"/>
    <!--end:: Global Mandatory Vendors -->
    <!--begin:: Global Optional Vendors -->
    <link href="{{ URL::asset('assets/backend/vendors/general/tether/dist/css/tether.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/backend/vendors/general/bootstrap-datepicker/dist/css/bootstrap-datepicker3.css') }}"
          rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('assets/backend/vendors/general/bootstrap-datetime-picker/css/bootstrap-datetimepicker.css') }}"
          rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('assets/backend/vendors/general/bootstrap-timepicker/css/bootstrap-timepicker.css') }}"
          rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('assets/backend/vendors/general/bootstrap-daterangepicker/daterangepicker.css') }}"
          rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('assets/backend/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.css') }}"
          rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('assets/backend/vendors/general/bootstrap-select/dist/css/bootstrap-select.css') }}"
          rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('assets/backend/vendors/general/bootstrap-switch/dist/css/bootstrap3/bootstrap-switch.css') }}"
          rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('assets/backend/vendors/general/select2/dist/css/select2.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/backend/vendors/general/ion-rangeslider/css/ion.rangeSlider.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/backend/vendors/general/nouislider/distribute/nouislider.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/backend/vendors/general/owl.carousel/dist/assets/owl.carousel.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/backend/vendors/general/owl.carousel/dist/assets/owl.theme.default.css') }}"
          rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('assets/backend/vendors/general/dropzone/dist/dropzone.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/backend/vendors/general/summernote/dist/summernote.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/backend/vendors/general/bootstrap-markdown/css/bootstrap-markdown.min.css') }}"
          rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('assets/backend/vendors/general/animate.css/animate.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('assets/backend/vendors/general/toastr/build/toastr.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('assets/backend/vendors/general/morris.js/morris.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('assets/backend/vendors/general/sweetalert2/dist/sweetalert2.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/backend/vendors/general/socicon/css/socicon.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('assets/backend/vendors/custom/vendors/line-awesome/css/line-awesome.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/backend/vendors/custom/vendors/flaticon/flaticon.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/backend/vendors/custom/vendors/flaticon2/flaticon.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/backend/vendors/general/@fortawesome/fontawesome-free/css/all.min.css') }}"
          rel="stylesheet" type="text/css"/>
    <link href="{{ URL::asset('assets/backend/vendors/custom/pace-loading-progress-bar/pace.css') }}"
          rel="stylesheet" type="text/css"/>
    <!--end:: Global Optional Vendors -->
    <!--begin::Global Theme Styles(used by all pages) -->
    <link href="{{ URL::asset('assets/backend/css/demo1/style.bundle.css') }}" rel="stylesheet"
          type="text/css"/>
    <!--end::Global Theme Styles -->
    <!--begin::Layout Skins(used by all pages) -->
    <link href="{{ URL::asset('assets/backend/css/demo1/skins/header/base/light.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('assets/backend/css/demo1/skins/header/menu/light.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('assets/backend/css/demo1/skins/brand/light.css') }}" rel="stylesheet"
          type="text/css"/>
    <link href="{{ URL::asset('assets/backend/css/demo1/skins/aside/light.css') }}" rel="stylesheet"
          type="text/css"/>
    <!--end::Layout Skins -->
    <!--begin::Custom styles(used by all pages) -->
    <link href="{{ URL::asset('assets/backend/css/custom.css') }}" rel="stylesheet" type="text/css"/>
    <!--end::Custom styles -->
    @yield('styles')
</head>
<!-- end::Head -->
<!-- begin::Body -->
<body class=" kt-print-content-only kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">
<!-- begin:: Page -->
<!-- begin:: Header Mobile -->
@include('backend.layouts.includes.mobile_header_section')
<!-- end:: Header Mobile -->
<div class="kt-grid kt-grid--hor kt-grid--root">
    <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--ver kt-page">
        <!-- begin:: Sidebar Section -->
    @include('backend.layouts.includes.admin_sidebar_section')
    <!-- end:: Sidebar Section -->
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor kt-wrapper" id="kt_wrapper">
            <!-- begin:: Header -->
        @include('backend.layouts.includes.header_section')
        <!-- end:: Header -->
            <div class="kt-content  kt-grid__item kt-grid__item--fluid kt-grid kt-grid--hor" id="kt_content">
                <!-- begin:: Content Head -->
            @include('backend.layouts.includes.content_header_section')
            <!-- end:: Content Head -->
                <!-- begin:: Content -->
                <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
                    @yield('content')
                </div>
                <!-- end:: Content -->
            </div>
            <!-- begin:: Footer -->
            <div class="kt-footer  kt-grid__item kt-grid kt-grid--desktop kt-grid--ver-desktop" id="kt_footer">
                <div class="kt-container  kt-container--fluid ">
                    <div class="kt-footer__copyright">
                        <?php echo date('Y');?> &copy; {{ config('app.site_title_footer') }}
                    </div>
                    <div class="kt-footer__menu">
                    </div>
                </div>
            </div>
            <!-- end:: Footer -->
        </div>
    </div>
</div>
<!-- end:: Page -->
<!-- begin::Scrolltop -->
<div id="kt_scrolltop" class="kt-scrolltop">
    <i class="fa fa-arrow-up"></i>
</div>
<!-- end::Scrolltop -->
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
<script src="{{ URL::asset('assets/backend/vendors/general/jquery/dist/jquery.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/popper.js/dist/umd/popper.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/bootstrap/dist/js/bootstrap.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/js-cookie/src/js.cookie.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/moment/min/moment.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/tooltip.js/dist/umd/tooltip.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/perfect-scrollbar/dist/perfect-scrollbar.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/sticky-js/dist/sticky.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/wnumb/wNumb.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/bootbox/bootbox.min.js') }}"></script>
<!--end:: Global Mandatory Vendors -->
<!--begin:: Global Optional Vendors -->
<script src="{{ URL::asset('assets/backend/vendors/general/jquery-form/dist/jquery.form.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/block-ui/jquery.blockUI.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/custom/js/vendors/bootstrap-datepicker.init.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/bootstrap-datetime-picker/js/bootstrap-datetimepicker.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/custom/js/vendors/bootstrap-timepicker.init.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/bootstrap-daterangepicker/daterangepicker.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/bootstrap-maxlength/src/bootstrap-maxlength.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/custom/vendors/bootstrap-multiselectsplitter/bootstrap-multiselectsplitter.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/bootstrap-select/dist/js/bootstrap-select.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/bootstrap-switch/dist/js/bootstrap-switch.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/custom/js/vendors/bootstrap-switch.init.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/select2/dist/js/select2.full.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/ion-rangeslider/js/ion.rangeSlider.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/typeahead.js/dist/typeahead.bundle.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/handlebars/dist/handlebars.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/inputmask/dist/jquery.inputmask.bundle.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/inputmask/dist/inputmask/inputmask.date.extensions.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/inputmask/dist/inputmask/inputmask.numeric.extensions.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/nouislider/distribute/nouislider.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/owl.carousel/dist/owl.carousel.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/autosize/dist/autosize.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/clipboard/dist/clipboard.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/dropzone/dist/dropzone.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/summernote/dist/summernote.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/markdown/lib/markdown.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/bootstrap-markdown/js/bootstrap-markdown.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/custom/js/vendors/bootstrap-markdown.init.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/bootstrap-notify/bootstrap-notify.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/custom/js/vendors/bootstrap-notify.init.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/jquery-validation/dist/jquery.validate.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/jquery-validation/dist/additional-methods.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/custom/js/vendors/jquery-validation.init.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/toastr/build/toastr.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/raphael/raphael.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/morris.js/morris.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/chart.js/dist/Chart.bundle.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/custom/vendors/bootstrap-session-timeout/dist/bootstrap-session-timeout.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/custom/vendors/jquery-idletimer/idle-timer.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/waypoints/lib/jquery.waypoints.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/counterup/jquery.counterup.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/es6-promise-polyfill/promise.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/sweetalert2/dist/sweetalert2.min.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/custom/js/vendors/sweetalert2.init.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/jquery.repeater/src/lib.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/jquery.repeater/src/jquery.input.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/jquery.repeater/src/repeater.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/general/dompurify/dist/purify.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/vendors/custom/pace-loading-progress-bar/pace.min.js') }}"
        type="text/javascript"></script>
<!--end:: Global Optional Vendors -->
<!--begin::Global Theme Bundle(used by all pages) -->
<script src="{{ URL::asset('assets/backend/js/demo1/scripts.bundle.js') }}" type="text/javascript"></script>
<!--end::Global Theme Bundle -->
<!--begin::Custom javascript codes(used by all pages) -->
<script src="{{ URL::asset('assets/backend/js/demo1/pages/components/extended/blockui.js') }}"
        type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/js/custom.js') }}" type="text/javascript"></script>
<!--end::Custom javascript codes -->
@yield('scripts')
</body>
<!-- end::Body -->
