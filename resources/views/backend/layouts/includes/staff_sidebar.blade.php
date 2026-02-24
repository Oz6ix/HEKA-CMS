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
                    Staff Manage
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            
            <!--begin::Accordion-->
            <div class="accordion" id="accordionExample1">
                <div class="card">
                    <div class="card-header sp-head" id="headingOne">
                        <a href="{{ url($url_prefix . '/staff_departments') }}" class="card-title {{ ($controller == "StaffDepartmentController") ? 'active' : ''}}">
                           Department
                        </a>
                    </div>                                                    
                </div>
                <div class="card">
                    <div class="card-header sp-head" id="headingTwo">
                        <a href="{{ url($url_prefix . '/staff_designations') }}"class="card-title {{ ($controller == "StaffDesignationController") ? 'active' : ''}}">
                            Designation
                        </a>
                    </div>                                                   
                </div>
                <div class="card">
                    <div class="card-header sp-head" id="headingThree1">
                         <a href="{{ url($url_prefix . '/staff_roles') }}"class="card-title {{ ($controller == "StaffRoleController") ? 'active' : ''}}">
                            Role
                        </a>
                    </div>                                                    
                </div>
                <div class="card">
                    <div class="card-header sp-head" id="headingThree1">
                         <a href="{{ url($url_prefix . '/staff_specialists') }}"class="card-title {{ ($controller == "StaffSpecialistController") ? 'active' : ''}}">
                            Specialist
                        </a>
                    </div>                                                    
                </div>
            </div>

            <!--end::Accordion-->
        </div>
    </div> 
</div>