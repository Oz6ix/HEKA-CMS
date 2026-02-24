<?php $url_prefix = Config::get('app.app_route_prefix'); ?>
<div class="kt-subheader  kt-grid__item" id="kt_subheader">
    <div class="kt-container  kt-container--fluid ">
        <div class="kt-subheader__main">
            <h3 class="kt-subheader__title">{!! isset($heading_icon) ? '<i class="fa ' . $heading_icon . '"></i>&nbsp;' : '' !!} {{ isset($page_heading) ? $page_heading : '' }}</h3>
            <span class="kt-subheader__separator kt-subheader__separator--v"></span>
            <div class="kt-subheader__breadcrumbs">
                <a href="{{ url($url_prefix . '/dashboard') }}" class="kt-subheader__breadcrumbs-home"><i
                            class="flaticon2-shelter"></i></a>
                @yield('breadcrumb')
            </div>
            <span class="kt-subheader__desc"></span>
        </div>
        <div class="kt-subheader__toolbar">
            <div class="kt-subheader__wrapper">
            </div>
        </div>
    </div>
</div>