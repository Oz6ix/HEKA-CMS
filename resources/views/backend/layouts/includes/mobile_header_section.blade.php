<?php
$controller = class_basename(\Route::current()->controller);
$action = class_basename(\Route::current()->action['uses']);
$url_prefix = Config::get('app.app_route_prefix');
$app_logo_head = Config::get('app.app_logo_head');
$app_logo_sub = Config::get('app.app_logo_sub');
?>
<div id="kt_header_mobile" class="kt-header-mobile  kt-header-mobile--fixed ">
    <div class="kt-header-mobile__logo" style="padding-bottom: 25px;padding-top: 25px;">
        <a href="{{ url($url_prefix . '/dashboard') }}">
           <!--   <div style="font-size: 21px;color:#00f7f7a6;">{{$app_logo_head}}
              <span style="font-size: 14px;color:#dc1212;font-weight: 400">{{$app_logo_sub}}<span></div> -->

             <img alt="{{ Config::get('app.site_title') }}"
                 src="{{ URL::asset('uploads/logos/cms-logo-png.png') }}" style="width:160px;"/> 
        </a>
    </div>
    <div class="kt-header-mobile__toolbar">
        <button class="kt-header-mobile__toggler kt-header-mobile__toggler--left" id="kt_aside_mobile_toggler">
            <span></span></button>
        <!--<button class="kt-header-mobile__toggler" id="kt_header_mobile_toggler"><span></span></button>-->
        <button class="kt-header-mobile__topbar-toggler" id="kt_header_mobile_topbar_toggler"><i
                    class="flaticon-more"></i></button>
    </div>
</div>