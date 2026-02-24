<?php
$controller = class_basename(\Route::current()->controller);
$action = class_basename(\Route::current()->action['uses']);
$url_prefix = Config::get('app.app_route_prefix');
$app_logo_head = Config::get('app.app_logo_head');
$app_logo_sub = Config::get('app.app_logo_sub');
?>
<style type="text/css">.kt-menu__ver-arrow{color:#fff!important;}</style>
    <div class="kt-aside-menu-wrapper kt-grid__item kt-grid__item--fluid" id="kt_aside_menu_wrapper">
        <div id="kt_aside_menu" class="kt-aside-menu kt-scroll ps ps--active-y" data-ktmenu-vertical="1" data-ktmenu-scroll="1" data-ktmenu-dropdown-timeout="500" style="height: 302px; overflow: hidden;">
            <ul class="kt-menu__nav ">
                <li class="kt-menu__item {{ ($controller == "DashboardController") ? 'kt-menu__item--active' : ''}} aria-haspopup="true">
                    <a href="{{ url($url_prefix . '/dashboard') }}" class="kt-menu__link "> <span class="kt-menu__link-icon fa fa-home"></span> <span class="kt-menu__link-text">Dashboard</span> </a>
                </li>

                @if(check_privilege('admin_users'))

                <li class="kt-menu__item {{ ($controller == "AdminUserController") ? 'kt-menu__item--active' : ''}} aria-haspopup="true">
                    <a href="{{ url($url_prefix . '/admin_users') }}" class="kt-menu__link "> <span class="kt-menu__link-icon fa fa-user-friends"></span> <span class="kt-menu__link-text">Admin Users</span> </a>
                </li>
                @endif

                @if(check_privilege('staff'))
                    <li class="kt-menu__item  kt-menu__item--submenu {{ (($controller == "StaffController") || ($controller == "StaffDepartmentController") || ($controller == "StaffSpecialistController") || ($controller == "StaffDesignationController") || ($controller == "StaffRoleController")) ? 'kt-menu__item--open kt-menu__item--here kt-menu__item--active' : ''}}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                        <a href="javascript:;" class="kt-menu__link kt-menu__toggle"> <span class="kt-menu__link-icon fa fa-user"></span> <span class="kt-menu__link-text">Staff</span> <i class="kt-menu__ver-arrow la la-angle-right"></i> </a>
                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                            <ul class="kt-menu__subnav">
                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"> <span class="kt-menu__link">
                                        <span class="kt-menu__link-text">Staff List</span></span>
                                </li>
                                <li class="kt-menu__item {{ ($controller == "StaffController") ? 'kt-menu__item--active' : ''}}" aria-haspopup="true">
                                    <a href="{{ url($url_prefix . '/staffs') }}" class="kt-menu__link "> <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i> <span class="kt-menu__link-text">Staff List</span> </a>
                                </li>

                                <li class="kt-menu__item {{ ($controller == "StaffDepartmentController") ? 'kt-menu__item--active' : ''}}" aria-haspopup="true">
                                    <a href="{{ url($url_prefix . '/staff_manage') }}" class="kt-menu__link "> <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i> <span class="kt-menu__link-text">Staff Settings</span> </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if(check_privilege('patients'))
                    <li class="kt-menu__item {{ ($controller == "PatientController") ? 'kt-menu__item--active' : ''}} aria-haspopup="true">
                        <a href="{{ url($url_prefix . '/patient') }}" class="kt-menu__link "> <span class="kt-menu__link-icon fa fa-user-friends"></span> <span class="kt-menu__link-text">Patient</span> </a>
                    </li>
                @endif

                @if(check_privilege('appointments'))
                <li class="kt-menu__item  kt-menu__item--submenu {{ (($controller == "AppointmentController") || ($controller == "SymptomTypeController")|| ($controller == "CasualtyController")|| ($controller == "TpaController")|| ($controller == "FrequencyController")|| ($controller == "PatientDiagnosisController")|| ($controller == "CenterController") ) ? 'kt-menu__item--open kt-menu__item--here kt-menu__item--active' : ''}}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                        <a href="javascript:;" class="kt-menu__link kt-menu__toggle"> <span class="kt-menu__link-icon fa fa-user"></span> <span class="kt-menu__link-text">Appointments</span> <i class="kt-menu__ver-arrow la la-angle-right"></i> </a>
                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                            <ul class="kt-menu__subnav">
                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"> <span class="kt-menu__link">
                                        <span class="kt-menu__link-text">Staff List</span></span>
                                </li>
                                <li class="kt-menu__item {{ (($controller == "AppointmentController")|| ($controller == "PatientDiagnosisController")) ? 'kt-menu__item--active' : ''}}" aria-haspopup="true">
                                    <a href="{{ url($url_prefix . '/appointment') }}" class="kt-menu__link "> <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i> <span class="kt-menu__link-text">Appointments</span> </a>
                                </li>

                                <li class="kt-menu__item {{ (($controller == "SymptomTypeController")|| ($controller == "CasualtyController")|| ($controller == "TpaController")|| ($controller == "FrequencyController") || ($controller == "CenterController"))? 'kt-menu__item--active' : ''}}" aria-haspopup="true">
                                    <a href="{{ url($url_prefix . '/symptom_type') }}" class="kt-menu__link "> <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i> <span class="kt-menu__link-text">Appointment Settings</span> </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                @endif

                @if(check_privilege('appointments'))
                    <li class="kt-menu__item {{ ($controller == "EMRController") ? 'kt-menu__item--active' : ''}} aria-haspopup="true">
                        <a href="{{ route('emr.index') }}" class="kt-menu__link "> <span class="kt-menu__link-icon fa fa-user-md"></span> <span class="kt-menu__link-text">Doctor Workbench</span> </a>
                    </li>
                @endif


                @if(check_privilege('bills'))
                    <li class="kt-menu__item {{ ($controller == "RevenueCycleManagementController") ? 'kt-menu__item--active' : ''}} aria-haspopup="true">
                        <a href="{{ route('rcm.index') }}" class="kt-menu__link "> <span class="kt-menu__link-icon fa fa-file-invoice-dollar"></span> <span class="kt-menu__link-text">Revenue Cycle Management</span> </a>
                    </li>
                @endif

                @if(check_privilege('inventory'))
                <li class="kt-menu__item  kt-menu__item--submenu {{ (($controller == "InventoryCategoryController") || ($controller == "InventoryItemMasterController") || ($controller == "InventoryStockController")) ? 'kt-menu__item--open kt-menu__item--here kt-menu__item--active' : ''}}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                        <a href="javascript:;" class="kt-menu__link kt-menu__toggle"> <span class="kt-menu__link-icon fa fa-truck-moving"></span> <span class="kt-menu__link-text">Inventory</span> <i class="kt-menu__ver-arrow la la-angle-right"></i> </a>
                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                            <ul class="kt-menu__subnav">
                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"> <span class="kt-menu__link">
                                        <span class="kt-menu__link-text">Inventory</span></span>
                                </li>
                                <li class="kt-menu__item {{ ($controller == "InventoryCategoryController") ? 'kt-menu__item--active' : ''}}" aria-haspopup="true">
                                    <a href="{{ url($url_prefix . '/inventory_categorys') }}" class="kt-menu__link "> <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i> <span class="kt-menu__link-text">Inventory Category</span> </a>
                                </li>

                                <li class="kt-menu__item {{ ($controller == "InventoryStockController") ? 'kt-menu__item--active' : ''}}" aria-haspopup="true">
                                    <a href="{{ url($url_prefix . '/inventory_stocks') }}" class="kt-menu__link "> <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i> <span class="kt-menu__link-text">Inventory Stock</span> </a>
                                </li>

                                <li class="kt-menu__item {{ ($controller == "InventoryItemMasterController") ? 'kt-menu__item--active' : ''}}" aria-haspopup="true">
                                    <a href="{{ url($url_prefix . '/inventory_masters') }}" class="kt-menu__link "> <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i> <span class="kt-menu__link-text">Inventory Item Master</span> </a>
                                </li>

                                <li class="kt-menu__item {{ ($controller == "PharmacyGenericController") ? 'kt-menu__item--active' : ''}}" aria-haspopup="true">
                                    <a href="{{ url($url_prefix . '/pharmacy_generic') }}" class="kt-menu__link "> <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i> <span class="kt-menu__link-text">Pharmacy Generic</span> </a>
                                </li>

                                <li class="kt-menu__item {{ ($controller == "PharmacyDosageController") ? 'kt-menu__item--active' : ''}}" aria-haspopup="true">
                                    <a href="{{ url($url_prefix . '/pharmacy_dosage') }}" class="kt-menu__link "> <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i> <span class="kt-menu__link-text">Pharmacy Dosage</span> </a>
                                </li>


                            </ul>
                        </div>
                    </li>
                @endif

                @if(check_privilege('appointment_report') || check_privilege('revenue_report')) 

                    <li class="kt-menu__item  kt-menu__item--submenu {{ (($controller == "ReportController") || ($controller == "ReportController") ) ? 'kt-menu__item--open kt-menu__item--here kt-menu__item--active' : ''}}" aria-haspopup="true" data-ktmenu-submenu-toggle="hover">
                        <a href="javascript:;" class="kt-menu__link kt-menu__toggle"> <span class="kt-menu__link-icon fa fa-user"></span> <span class="kt-menu__link-text">Reports</span> <i class="kt-menu__ver-arrow la la-angle-right"></i> </a>
                        <div class="kt-menu__submenu "><span class="kt-menu__arrow"></span>
                            <ul class="kt-menu__subnav">
                                <li class="kt-menu__item  kt-menu__item--parent" aria-haspopup="true"> <span class="kt-menu__link">
                                        <span class="kt-menu__link-text">Reports</span></span>
                                </li>

                                @if(check_privilege('appointment_report')) 
                                    <li class="kt-menu__item {{ ($controller == "ReportController") ? 'kt-menu__item--active' : ''}}" aria-haspopup="true">
                                        <a href="{{ url($url_prefix . '/report/appointment_report') }}" class="kt-menu__link "> <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i> <span class="kt-menu__link-text">Appointment report</span> </a>
                                    </li>
                                @endif

                                @if(check_privilege('revenue_report')) 
                                    <li class="kt-menu__item {{ ($controller == "ReportController") ? 'kt-menu__item--active' : ''}}" aria-haspopup="true">
                                        <a href="{{ url($url_prefix . '/report/revenue_report') }}" class="kt-menu__link "> <i class="kt-menu__link-bullet kt-menu__link-bullet--dot"><span></span></i> <span class="kt-menu__link-text">Revenue Report</span> </a>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endif


                @if(check_privilege('general_settings') || check_privilege('user_groups') || check_privilege('notifications') || check_privilege('hospital_charges') || check_privilege('pharmacy') || check_privilege('phatology') || check_privilege('radiology') || check_privilege('suppliers') || check_privilege('configuration')) 
                    <li class="kt-menu__item {{ (($controller == "SettingGeneralController") || ($controller == "UserGroupController") || ($controller == "SettingConfigurationController") || ($controller == "SettingNotificationController") || ($controller == "SettingSupplierController") || ($controller == "SettingUnitController") || ($controller == "SettingHospitalChargeCategoryController") || ($controller == "SettingHospitalChargeController")) ? 'kt-menu__item--active' : ''}} aria-haspopup="true">
                        <a href="{{ url($url_prefix . '/general_settings') }}" class="kt-menu__link "> <span class="kt-menu__link-icon fa fa-cogs"></span> <span class="kt-menu__link-text">Settings</span> </a>
                    </li>
                @endif

                <li class="kt-menu__item {{ ($controller == "ProfileController") ? 'kt-menu__item--active' : ''}} aria-haspopup="true">
                    <a href="{{ url($url_prefix . '/profile') }}" class="kt-menu__link "> <span class="kt-menu__link-icon fa fa-user-cog"></span> <span class="kt-menu__link-text">Profile</span> </a>
                </li>
                <li class="kt-menu__item " aria-haspopup="true">
                    <a href="{{ url($url_prefix . '/logout') }}" class="kt-menu__link "> <span class="kt-menu__link-icon fa fa-sign-out-alt"></span> <span class="kt-menu__link-text">Logout</span> </a>
                </li>
            </ul>
        </div>
    </div>