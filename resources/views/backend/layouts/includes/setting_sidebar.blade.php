<br>
<?php
$controller = class_basename(\Route::current()->controller);
$action = class_basename(\Route::current()->action['uses']);
$url_prefix = Config::get('app.app_route_prefix');
$app_logo_head = Config::get('app.app_logo_head');
$app_logo_sub = Config::get('app.app_logo_sub');
?>
<div class="col-md-3">
        <div class="kt-portlet">
            <div class="kt-portlet__head">
                <div class="kt-portlet__head-label">
                    <h3 class="kt-portlet__head-title">
                        Settings
                    </h3>
                </div>
            </div>
            <div class="kt-portlet__body">
                
                <!--begin::Accordion-->
                <div class="accordion" id="accordionExample1">
                    <div class="card">
                        <div class="card-header sp-head" id="headingOne">
                            <a href="{{ url($url_prefix . '/general_settings') }}" class="card-title {{ ($controller == "SettingGeneralController") ? 'active' : ''}}">
                               General Info
                            </a>
                        </div>                                                    
                    </div>
                    <div class="card">
                        <div class="card-header sp-head" id="headingTwo">
                            <a href="{{ url($url_prefix . '/setting_notification') }}"class="card-title {{ ($controller == "SettingNotificationController") ? 'active' : ''}}">
                                Notifications
                            </a>
                        </div>                                                   
                    </div>
                    <div class="card">
                        <div class="card-header sp-head" id="headingThree1">
                             <a href="{{ url($url_prefix . '/user_groups') }}"class="card-title {{ ($controller == "UserGroupController") ? 'active' : ''}}">
                                Permissions
                            </a>
                        </div>                                                    
                    </div>
                    <div class="card">
                        <div class="card-header sp-head" id="headingThree1">
                             <a href="{{ url($url_prefix . '/hospital_charges') }}"class="card-title {{ ($controller == "SettingHospitalChargeController" || $controller == "SettingHospitalChargeCategoryController" ) ? 'active' : ''}}">
                                Hospital Charges
                            </a>
                        </div>                                                    
                    </div>

                    <div class="card">
                        <div class="card-header sp-head" id="headingThree1">
                             <a href="{{ url($url_prefix . '/pharmacys') }}"class="card-title {{ ($controller == "SettingPharmacyController" || $controller == "SettingPharmacyCategoryController" ) ? 'active' : ''}}">
                               Pharmacy
                            </a>
                        </div>                                                    
                    </div>

                    <div class="card">
                        <div class="card-header sp-head" id="headingThree1">
                        <a href="{{ url($url_prefix . '/pathologys') }}"class="card-title {{ ($controller == "SettingPathologyController" || $controller == "SettingPathologyCategoryController" ) ? 'active' : ''}}">
                                Pathology
                            </a>
                        </div>                                                    
                    </div>

                    <div class="card">
                        <div class="card-header sp-head" id="headingThree1">
                        <a href="{{ url($url_prefix . '/radiologys') }}"class="card-title {{ ($controller == "SettingRadiologyController" || $controller == "SettingRadiologyCategoryController" ) ? 'active' : ''}}">
                                Radiology
                            </a>
                        </div>                                                    
                    </div>

                    <div class="card">
                        <div class="card-header sp-head" id="headingThree1">
                             <a href="{{ url($url_prefix . '/setting_suppliers') }}"class="card-title {{ ($controller == "SettingSupplierController") ? 'active' : ''}}">
                                Suppliers
                            </a>
                        </div>                                                    
                    </div>

                     <div class="card">
                        <div class="card-header sp-head" id="headingThree1">
                             <a href="{{ url($url_prefix . '/setting_units') }}"class="card-title {{ ($controller == "SettingUnitController") ? 'active' : ''}}">
                                Units
                            </a>
                        </div>                                                    
                    </div>


                    <div class="card">
                        <div class="card-header sp-head" id="headingThree1">
                             <a href="{{ url($url_prefix . '/configuration') }}"class="card-title {{ ($controller == "SettingConfigurationController") ? 'active' : ''}}">
                                Configuration
                            </a>
                        </div>                                                    
                    </div>


                </div>

                <!--end::Accordion-->
            </div>
        </div>
    </div>