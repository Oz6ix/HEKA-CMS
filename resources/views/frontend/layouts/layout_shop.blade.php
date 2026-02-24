<!DOCTYPE html>
<html lang="en" class="no-js">

<!-- begin::Head -->
<head>
    <meta charset="utf-8"/>
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @if(isset($homepage_title))
        <title>{{ $homepage_title }}</title>
    @else
        <title>{{ config('app.site_title') }}{{ isset($page_title) ? ' : ' . $page_title : ''}}</title>
    @endif
    @if(isset($homepage_keywords))
        <meta name="keywords" content="{{ $homepage_keywords }}">
    @else
        <meta name=" keywords" content="{{ isset($meta_keywords) ? $meta_keywords : ''}}">
    @endif
    @if(isset($homepage_description))
        <meta name="description" content="{{ $homepage_description }}">
    @else
        <meta name="description" content="{{ isset($meta_description) ? $meta_description : ''}}">
    @endif
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <link rel="shortcut icon" href="{{ URL::asset('uploads/logos/favicon.png') }}"/>
    <link rel="icon" href="{{ URL::asset('uploads/logos/favicon.png') }}" type="image/x-icon"/>

    <!--begin::Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900&display=swap" rel="stylesheet">
    <!--end::Fonts -->

    <!--begin::Global Vendors(used by all pages) -->
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/frontend/css/glyphicons.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/frontend/css/chs-styles.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/frontend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/frontend/css/magnific-popup.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/frontend/css/animate.css') }}">
    <link rel="stylesheet" type="text/css"
          href="{{ URL::asset('assets/frontend/css/owl.carousel.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/frontend/css/themify-icons.css') }}">
    <link rel="stylesheet" type="text/css"
          href="{{ URL::asset('assets/frontend/css/pe-icon-7-stroke.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/frontend/css/icofont.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/frontend/css/meanmenu.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/frontend/css/jquery-ui.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/frontend/css/easyzoom.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/frontend/css/bundle.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/frontend/css/dropdown.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/frontend/css/media.css') }}">

    <script type="text/javascript"
            src="{{ URL::asset('assets/frontend/js/modernizr-2.8.3.min.js') }}"></script>
    <!--end::Global Vendors -->

    <!--begin::Custom styles(used by all pages) -->
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/frontend/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/frontend/css/responsive.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('assets/frontend/css/shop-page.css') }}">
    <!--end::Custom styles -->
    @if(isset($current_theme))
        <link rel="stylesheet" type="text/css"
              href="{{ URL::asset('uploads/shop_page_theme_css_files/' . $current_theme) }}">
    @endif

    @yield('styles')

    <?php $header_data = fetch_header_data(); ?>
    @if($header_data['google_analytics'] != null && !empty($header_data['google_analytics']))
        {!! $header_data['google_analytics'] !!}
    @endif
     <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<!-- end::Head -->

<!-- begin::Body -->
<body>
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade
    your browser</a> to improve your experience.</p>
<![endif]-->
@php
    $contact_info = fetch_contact_info();
@endphp

<!-- begin:: Header -->
@include('frontend.layouts.includes.header_section')
<!-- end:: Header -->

<!-- begin:: Header Menu -->
@include('frontend.layouts.includes.header_menu_section')
<!-- end:: Header Menu -->

<!-- begin:: Content -->
@yield('content')
<!-- end:: Content -->

<!-- begin:: Footer -->
@include('frontend.layouts.includes.footer_section')
<!-- end:: Footer -->

<!--begin::Global Vendors(used by all pages) -->
<script type="text/javascript" src="{{ URL::asset('assets/frontend/js/jquery-1.12.0.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/frontend/js/popper.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/frontend/js/bootstrap.min.js') }}"></script>
<script type="text/javascript"
        src="{{ URL::asset('assets/frontend/js/jquery.magnific-popup.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/frontend/js/isotope.pkgd.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/frontend/js/imagesloaded.pkgd.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/frontend/js/jquery.counterup.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/frontend/js/waypoints.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/frontend/js/ajax-mail.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/frontend/js/owl.carousel.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/frontend/js/plugins.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/frontend/js/bootbox/bootbox.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/frontend/js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/frontend/js/additional-methods.min.js') }}"></script>
<!--end::Global Vendors -->

<!--begin::Custom Scripts(used by all pages) -->
<script type="text/javascript">
    var base_url = '{{ url('/') }}';
</script>
<script type="text/javascript" src="{{ URL::asset('assets/frontend/js/main.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/frontend/js/custom.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/frontend/js/validations/visualsearch.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/frontend/js/validations/customer_login.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/frontend/js/validations/customer_register.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/frontend/js/validations/customer_enquiry.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/frontend/js/validations/customer_forgot_password.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/frontend/js/validations/customer_enquiry_product.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('assets/frontend/js/validations/newsletter.js') }}"></script>
<!--end::Custom Scripts -->

<script type="text/javascript">
    $(document).ready(function () {
        $('#file-input').on('change', function () { //on file input change
            if (window.File && window.FileReader && window.FileList && window.Blob) //check File API supported browser
            {
                $('#thumb-output').html(''); //clear html of output element
                var data = $(this)[0].files; //this file data

                $.each(data, function (index, file) { //loop though each file
                    if (/(\.|\/)(gif|jpe?g|png)$/i.test(file.type)) { //check supported file type
                        var fRead = new FileReader(); //new filereader
                        fRead.onload = (function (file) { //trigger function on successful read
                            return function (e) {
                                var img = $('<img/>').addClass('thumb').attr('src', e.target.result); //create image element
                                $('#thumb-output').append(img); //append image to output element
                            };
                        })(file);
                        fRead.readAsDataURL(file); //URL representing the file's data.
                    }
                });

            } else {
                alert("Your browser doesn't support File API!"); //if File API is absent
            }
        });
    });
</script>
<script>
    function openForm() {
        document.getElementById("myForm").style.display = "block";
    }

    function closeForm() {
        document.getElementById("myForm").style.display = "none";
    }
</script>
<script>
    function myFunction() {
        var popup = document.getElementById("myPopup");
        popup.classList.toggle("show");
    }
</script>
 <script type="text/javascript">
    $(document).ready(function() {
   
    setTimeout(function() {
    $(".alert").alert('close');
    }, 6000);
    });

</script>
@yield('scripts')
</body>
<!-- end::Body -->
</html>