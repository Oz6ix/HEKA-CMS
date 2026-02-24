<?php
$controller = class_basename(\Route::current()->controller);
$action = class_basename(\Route::current()->action['uses']);
$url_prefix = Config::get('app.app_route_prefix');
$app_logo_head = Config::get('app.app_logo_head');
$app_logo_sub = Config::get('app.app_logo_sub');
$header_data = fetch_header_data();
?>
<div class="kt-footer  kt-grid__item kt-grid kt-grid--desktop kt-grid--ver-desktop" id="kt_footer">
<div class="kt-container  kt-container--fluid ">
<div class="kt-footer__copyright">
 &copy {{date('Y')}}&nbsp;&nbsp;<a href="#" target="_blank" class="kt-link"> {{ $header_data['site_settings']['hospital_name'] }}</a>
</div>
<div class="kt-footer__menu">               
</div>
</div>
</div>