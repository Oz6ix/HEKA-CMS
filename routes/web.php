<?php

use App\Models\Appointment;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;

$url_prefix = Config::get('app.app_route_prefix', 'admin');

/* General page routes */
Route::get('/', function () {
    $prefix = Config::get('app.app_route_prefix', 'admin');
    return redirect($prefix . '/login');
});
Route::get('/construction', function () {
    return view('backend.errors.under_construction');
});
Route::get('/maintenance', function () {
    return view('backend.errors.under_maintenance');
});
Route::get($url_prefix . '/not_authorized', function () {
    return view('backend.auth.access_denied');
});

Route::get($url_prefix . '/login', [App\Http\Controllers\AdminModule\AuthenticateController::class, 'login'])->name('login');
Route::post($url_prefix . '/login', [App\Http\Controllers\AdminModule\AuthenticateController::class, 'store']);
Route::get($url_prefix . '/logout', [App\Http\Controllers\AdminModule\AuthenticateController::class, 'logout'])->name('logout');

/* Password set for the first time */
Route::get($url_prefix . '/set/password/view/{email}', [App\Http\Controllers\AdminModule\AuthenticateController::class, 'view_set_password']);
Route::post($url_prefix . '/set/password/save', [App\Http\Controllers\AdminModule\AuthenticateController::class, 'save_set_password'])->name('save_set_password');

/* Admin Forgot Password reset */
Route::post($url_prefix . '/admin_send_email_link', [App\Http\Controllers\AdminModule\AuthenticateController::class, 'send_email_link'])->name('admin_send_email_link');
Route::get($url_prefix . '/reset/password/view/{email}', [App\Http\Controllers\AdminModule\AuthenticateController::class, 'view_reset_password']);
Route::post($url_prefix . '/reset/password/save', [App\Http\Controllers\AdminModule\AuthenticateController::class, 'save_reset_password'])->name('admin_forgot_password_set');

Route::group(['prefix' => $url_prefix, 'namespace' => 'App\Http\Controllers\AdminModule', 'middleware' => ['auth', 'permission']], function () {

    Route::get('/dashboard', 'DashboardController@dashboard')->name('dashboard');
    Route::get('/dashboard/appointments-by-date/{date}', 'DashboardController@appointmentsByDate')->name('dashboard.appointments_by_date');

    /* General settings */
    Route::get('/general_settings/{current_section?}', 'SettingGeneralController@edit');
    Route::post('/general_setting/update_logo', 'SettingGeneralController@update_site')->name('setting_logo_update');
    Route::post('/general_setting/update_general_info', 'SettingGeneralController@update_general_info')->name('setting_general_info_update');

    /* Configuration */
    Route::get('/configuration/', 'SettingConfigurationController@edit');
    Route::post('/configuration/update', 'SettingConfigurationController@update')->name('setting_configuration_update');

    /* Notification*/
    Route::get('/setting_notification/', 'SettingNotificationController@edit');
    Route::post('/setting_notification/update', 'SettingNotificationController@update')->name('setting_notification_update');

    /* Supplier */
    Route::resource('/setting_suppliers', 'SettingSupplierController');
    Route::get('/setting_supplier/create', 'SettingSupplierController@create');
    Route::post('/setting_supplier/store', 'SettingSupplierController@store')->name('setting_supplier_add');
    Route::get('/setting_supplier/edit/{id}', 'SettingSupplierController@edit');
    Route::post('/setting_supplier/update', 'SettingSupplierController@update')->name('setting_supplier_update');
    Route::get('/setting_supplier/delete/{id}', 'SettingSupplierController@destroy');
    Route::get('/setting_supplier/delete_multiple/{ids}', 'SettingSupplierController@destroy_multiple');
    Route::get('/setting_supplier/view/{id}', 'SettingSupplierController@show');
    Route::get('/setting_supplier/activate/{id}', 'SettingSupplierController@activate');
    Route::get('/setting_supplier/deactivate/{id}', 'SettingSupplierController@deactivate');
    Route::get('/setting_supplier/exists/{data}/{id?}', 'SettingSupplierController@exists');
    Route::get('/setting_supplier/ajax_duplicate_email/{email?}', 'SettingSupplierController@ajax_duplicate_name')->name('setting_supplier_duplicate_name');

    /* Units */
    Route::resource('/setting_units', 'SettingUnitController');
    Route::get('/setting_unit/create', 'SettingUnitController@create');
    Route::post('/setting_unit/store', 'SettingUnitController@store')->name('unit_add');
    Route::get('/setting_unit/edit/{id}', 'SettingUnitController@edit');
    Route::post('/setting_unit/update', 'SettingUnitController@update')->name('unit_update');
    Route::get('/setting_unit/delete/{id}', 'SettingUnitController@destroy');
    Route::get('/setting_unit/delete_multiple/{ids}', 'SettingUnitController@destroy_multiple');
    Route::get('/setting_unit/view/{id}', 'SettingUnitController@show');
    Route::get('/setting_unit/activate/{id}', 'SettingUnitController@activate');
    Route::get('/setting_unit/deactivate/{id}', 'SettingUnitController@deactivate');
    Route::get('/setting_unit/exists/{data}/{id?}', 'SettingUnitController@exists');
    Route::get('/setting_unit/ajax_duplicate_unit/{unit?}', 'SettingUnitController@ajax_duplicate_name')->name('setting_unit_duplicate_name');

    /* Hospital charge Category */
    Route::resource('/hospital_charge_categorys', 'SettingHospitalChargeCategoryController');
    Route::get('/hospital_charge_category/create', 'SettingHospitalChargeCategoryController@create');
    Route::post('/hospital_charge_category/store', 'SettingHospitalChargeCategoryController@store')->name('hospital_charge_category_add');
    Route::get('/hospital_charge_category/edit/{id}', 'SettingHospitalChargeCategoryController@edit');
    Route::post('/hospital_charge_category/update', 'SettingHospitalChargeCategoryController@update')->name('hospital_charge_category_update');
    Route::get('/hospital_charge_category/delete/{id}', 'SettingHospitalChargeCategoryController@destroy');
    Route::get('/hospital_charge_category/delete_multiple/{ids}', 'SettingHospitalChargeCategoryController@destroy_multiple');
    Route::get('/hospital_charge_category/view/{id}', 'SettingHospitalChargeCategoryController@show');
    Route::get('/hospital_charge_category/activate/{id}', 'SettingHospitalChargeCategoryController@activate');
    Route::get('/hospital_charge_category/deactivate/{id}', 'SettingHospitalChargeCategoryController@deactivate');
    Route::get('/hospital_charge_category/exists/{data}/{id?}', 'SettingHospitalChargeCategoryController@exists');
    Route::get('/hospital_charge_category/ajax_duplicate_name/{name?}', 'SettingHospitalChargeCategoryController@ajax_duplicate_name')->name('hospital_charge_category_duplicate_name');

    /* Hospital charge */
    Route::resource('/hospital_charges', 'SettingHospitalChargeController');
    Route::get('/hospital_charge/create', 'SettingHospitalChargeController@create');
    Route::post('/hospital_charge/store', 'SettingHospitalChargeController@store')->name('hospital_charge_add');
    Route::get('/hospital_charge/edit/{id}', 'SettingHospitalChargeController@edit');
    Route::post('/hospital_charge/update', 'SettingHospitalChargeController@update')->name('hospital_charge_update');
    Route::get('/hospital_charge/delete/{id}', 'SettingHospitalChargeController@destroy');
    Route::get('/hospital_charge/delete_multiple/{ids}', 'SettingHospitalChargeController@destroy_multiple');
    Route::get('/hospital_charge/view/{id}', 'SettingHospitalChargeController@show');
    Route::get('/hospital_charge/activate/{id}', 'SettingHospitalChargeController@activate');
    Route::get('/hospital_charge/deactivate/{id}', 'SettingHospitalChargeController@deactivate');
    Route::get('/hospital_charge/exists/{data}/{id?}', 'SettingHospitalChargeController@exists');
    Route::get('/hospital_charge/ajax_duplicate_name/{name?}', 'SettingHospitalChargeController@ajax_duplicate_name')->name('hospital_charge_duplicate_name');

    /* Inventory Category */
    Route::resource('/inventory_categorys', 'InventoryCategoryController');
    Route::get('/inventory_category/create', 'InventoryCategoryController@create');
    Route::post('/inventory_category/store', 'InventoryCategoryController@store')->name('inventory_category_add');
    Route::get('/inventory_category/edit/{id}', 'InventoryCategoryController@edit');
    Route::post('/inventory_category/update', 'InventoryCategoryController@update')->name('inventory_category_update');
    Route::get('/inventory_category/delete/{id}', 'InventoryCategoryController@destroy');
    Route::get('/inventory_category/delete_multiple/{ids}', 'InventoryCategoryController@destroy_multiple');
    Route::get('/inventory_category/view/{id}', 'InventoryCategoryController@show');
    Route::get('/inventory_category/activate/{id}', 'InventoryCategoryController@activate');
    Route::get('/inventory_category/deactivate/{id}', 'InventoryCategoryController@deactivate');
    Route::get('/inventory_category/exists/{data}/{id?}', 'InventoryCategoryController@exists');
    Route::get('/inventory_category/ajax_duplicate_name/{name?}', 'InventoryCategoryController@ajax_duplicate_name')->name('inventory_category_duplicate_name');

    /* Inventory item master */
    Route::resource('/inventory_masters', 'InventoryItemMasterController');
    Route::get('/inventory_master/create', 'InventoryItemMasterController@create');
    Route::post('/inventory_master/store', 'InventoryItemMasterController@store')->name('inventory_master_add');
    Route::get('/inventory_master/edit/{id}', 'InventoryItemMasterController@edit');
    Route::post('/inventory_master/update', 'InventoryItemMasterController@update')->name('inventory_master_update');
    Route::get('/inventory_master/delete/{id}', 'InventoryItemMasterController@destroy');
    Route::get('/inventory_master/delete_multiple/{ids}', 'InventoryItemMasterController@destroy_multiple');
    Route::get('/inventory_master/view/{id}', 'InventoryItemMasterController@show');
    Route::get('/inventory_master/activate/{id}', 'InventoryItemMasterController@activate');
    Route::get('/inventory_master/deactivate/{id}', 'InventoryItemMasterController@deactivate');
    Route::get('/inventory_master/exists/{data}/{id?}', 'InventoryItemMasterController@exists');
    Route::get('/inventory_master/ajax_duplicate_name/{name?}', 'InventoryItemMasterController@ajax_duplicate_name')->name('inventory_master_duplicate_name');

    /* Inventory stock */
    Route::resource('/inventory_stocks', 'InventoryStockController');
    Route::get('/inventory_stock/create', 'InventoryStockController@create');
    Route::post('/inventory_stock/store', 'InventoryStockController@store')->name('inventory_stock_add');
    Route::get('/inventory_stock/edit/{id}', 'InventoryStockController@edit');
    Route::post('/inventory_stock/update', 'InventoryStockController@update')->name('inventory_stock_update');
    Route::get('/inventory_stock/delete/{id}', 'InventoryStockController@destroy');
    Route::get('/inventory_stock/delete_multiple/{ids}', 'InventoryStockController@destroy_multiple');
    Route::get('/inventory_stock/view/{id}', 'InventoryStockController@show');
    Route::get('/inventory_stock/activate/{id}', 'InventoryStockController@activate');
    Route::get('/inventory_stock/deactivate/{id}', 'InventoryStockController@deactivate');
    Route::get('/inventory_stock/exists/{data}/{id?}', 'InventoryStockController@exists');
    Route::get('/inventory_stock/ajax_duplicate_name/{name?}', 'InventoryStockController@ajax_duplicate_name')->name('inventory_master_duplicate_name');
    Route::get('/inventory_stock/ajax_fecth_item_master/{id?}', 'InventoryStockController@ajax_fecth_item_master')->name('ajax_fecth_item_master'); 
    Route::get('/inventory_stock/alerts', 'InventoryStockController@alerts')->name('inventory_stock_alerts');
    Route::post('/inventory_stock/adjustment', 'InventoryStockController@store_adjustment')->name('inventory_stock_adjustment');

    /* Pharmacy Sales */
    Route::get('/pharmacy_sales', 'PharmacySalesController@index')->name('pharmacy_sales');
    Route::post('/pharmacy_sales/store', 'PharmacySalesController@store')->name('pharmacy_sales_store');
    Route::get('/pharmacy_sales/external', 'PharmacySalesController@external_create')->name('pharmacy_sales_external');
    Route::post('/pharmacy_sales/external/store', 'PharmacySalesController@external_store')->name('pharmacy_sales_external_store');
    Route::get('/pharmacy_sales/external/list', 'PharmacySalesController@external_index')->name('pharmacy_sales_external_list');
    Route::get('/pharmacy_sales/invoice/{id}', 'PharmacySalesController@invoice')->name('pharmacy_sales_invoice');
    Route::get('/pharmacy_sales/search_drug', 'PharmacySalesController@search_drug')->name('pharmacy_sales_search_drug');

    /* Referrals */
    Route::get('/referrals', 'ReferralController@index')->name('referrals.index');
    Route::get('/referral/create', 'ReferralController@create')->name('referral.create');
    Route::post('/referral/store', 'ReferralController@store')->name('referral.store');
    Route::post('/referral/status/{id}', 'ReferralController@update_status')->name('referral.status');
    Route::get('/referral/delete/{id}', 'ReferralController@destroy')->name('referral.delete');

    /* Medical Certificates */
    Route::get('/medical_certificates', 'MedicalCertificateController@index')->name('medical_certificates.index');
    Route::get('/medical_certificate/create', 'MedicalCertificateController@create')->name('medical_certificate.create');
    Route::post('/medical_certificate/store', 'MedicalCertificateController@store')->name('medical_certificate.store');
    Route::get('/medical_certificates/print/{id}', 'MedicalCertificateController@print')->name('medical_certificate.print');
    Route::get('/medical_certificate/delete/{id}', 'MedicalCertificateController@destroy')->name('medical_certificate.delete');

    /* User groups */
    Route::resource('/user_groups', 'UserGroupController');
    Route::get('/user_group/create', 'UserGroupController@create');
    Route::post('/user_group/store', 'UserGroupController@store');
    Route::get('/user_group/edit/{id}', 'UserGroupController@edit');
    Route::post('/user_group/update', 'UserGroupController@update');
    Route::get('/user_group/delete/{id}', 'UserGroupController@destroy');
    Route::get('/user_group/delete_multiple/{ids}', 'UserGroupController@destroy_multiple');
    Route::get('/user_group/view/{id}', 'UserGroupController@show');
    Route::get('/user_group/activate/{id}', 'UserGroupController@activate');
    Route::get('/user_group/deactivate/{id}', 'UserGroupController@deactivate');

    /* Profile update */
    Route::get('/profile', 'ProfileController@edit')->name('profile.edit');
    Route::post('/profile/update', 'ProfileController@update');
    Route::post('/password/update', 'ProfileController@update_password');
    
    /* Admin users */
    Route::resource('/admin_users', 'AdminUserController');
    Route::get('/admin_user/create', 'AdminUserController@create');
    Route::post('/admin_user/store', 'AdminUserController@store')->name('admin_user_add');
    Route::get('/admin_user/edit/{id}', 'AdminUserController@edit');
    Route::post('/admin_user/update', 'AdminUserController@update')->name('admin_user_update');
    Route::get('/admin_user/delete/{id}', 'AdminUserController@destroy');
    Route::get('/admin_user/delete_multiple/{ids}', 'AdminUserController@destroy_multiple');
    Route::get('/admin_user/view/{id}', 'AdminUserController@show');
    Route::get('/admin_user/activate/{id}', 'AdminUserController@activate');
    Route::get('/admin_user/deactivate/{id}', 'AdminUserController@deactivate');
    Route::get('/admin_user/exists/{data}/{id?}', 'AdminUserController@exists');
    Route::get('/admin_user/ajax_duplicate_email/{email?}', 'AdminUserController@ajax_duplicate_email')->name('admin_user_duplicate_email');

    /* Staff Manage */
    Route::resource('/staffs', 'StaffController');
    Route::get('/staffs', 'StaffController@index')->name('staffs.index');
    Route::get('/staff/create', 'StaffController@create')->name('staff.create');
    Route::post('/staff/store', 'StaffController@store')->name('staff.store');
    Route::get('/staff/edit/{id}', 'StaffController@edit')->name('staff.edit');
    Route::post('/staff/update', 'StaffController@update')->name('staff.update');
    Route::get('/staff/delete/{id}', 'StaffController@destroy')->name('staff.destroy');
    Route::get('/staff/delete_multiple/{ids}', 'StaffController@destroy_multiple');
    Route::get('/staff/view/{id}', 'StaffController@show')->name('staff.show');
    Route::get('/staff/activate/{id}', 'StaffController@activate')->name('staff.activate');
    Route::get('/staff/deactivate/{id}', 'StaffController@deactivate')->name('staff.deactivate');
    Route::get('/staff/exists/{data}/{id?}', 'StaffController@exists');
    Route::post('/staff/storeImage', 'StaffController@storeImage')->name('staffImage');
    Route::post('/staff/staffDocument', 'StaffController@storeDocument')->name('staffDocument');
    Route::post('/staff/storeResume', 'StaffController@storeResume')->name('storeResume');
    Route::get('/staff/ajax_duplicate_email/{email?}', 'StaffController@ajax_duplicate_email')->name('staff_duplicate_email');

    /* Staff Departments */
    Route::resource('/staff_departments', 'StaffDepartmentController');
    Route::get('/staff_department/create', 'StaffDepartmentController@create');
    Route::post('/staff_department/store', 'StaffDepartmentController@store')->name('department_add');
    Route::get('/staff_department/edit/{id}', 'StaffDepartmentController@edit');
    Route::post('/staff_department/update', 'StaffDepartmentController@update')->name('department_update');
    Route::get('/staff_department/delete/{id}', 'StaffDepartmentController@destroy');
    Route::get('/staff_department/delete_multiple/{ids}', 'StaffDepartmentController@destroy_multiple');
    Route::get('/staff_department/view/{id}', 'StaffDepartmentController@show');
    Route::get('/staff_department/activate/{id}', 'StaffDepartmentController@activate');
    Route::get('/staff_department/deactivate/{id}', 'StaffDepartmentController@deactivate');
    Route::get('/staff_department/exists/{data}/{id?}', 'StaffDepartmentController@exists');
    Route::get('/staff_manage', 'StaffDepartmentController@staff_manage');
    Route::post('/staff_department/upload', 'StaffDepartmentController@upload')->name('department_upload');

    /* Staff Designation */
    Route::resource('/staff_designations', 'StaffDesignationController');
    Route::get('/staff_designation/create', 'StaffDesignationController@create');
    Route::post('/staff_designation/store', 'StaffDesignationController@store')->name('designation_add');
    Route::get('/staff_designation/edit/{id}', 'StaffDesignationController@edit');
    Route::post('/staff_designation/update', 'StaffDesignationController@update')->name('designation_update');
    Route::get('/staff_designation/delete/{id}', 'StaffDesignationController@destroy');
    Route::get('/staff_designation/delete_multiple/{ids}', 'StaffDesignationController@destroy_multiple');
    Route::get('/staff_designation/view/{id}', 'StaffDesignationController@show');
    Route::get('/staff_designation/activate/{id}', 'StaffDesignationController@activate');
    Route::get('/staff_designation/deactivate/{id}', 'StaffDesignationController@deactivate');
    Route::get('/staff_designation/exists/{data}/{id?}', 'StaffDesignationController@exists');

    /* Staff Roles */
    Route::resource('/staff_roles', 'StaffRoleController');
    Route::get('/staff_role/create', 'StaffRoleController@create');
    Route::post('/staff_role/store', 'StaffRoleController@store')->name('role_add');
    Route::get('/staff_role/edit/{id}', 'StaffRoleController@edit');
    Route::post('/staff_role/update', 'StaffRoleController@update')->name('role_update');
    Route::get('/staff_role/delete/{id}', 'StaffRoleController@destroy');
    Route::get('/staff_role/delete_multiple/{ids}', 'StaffRoleController@destroy_multiple');
    Route::get('/staff_role/view/{id}', 'StaffRoleController@show');
    Route::get('/staff_role/activate/{id}', 'StaffRoleController@activate');
    Route::get('/staff_role/deactivate/{id}', 'StaffRoleController@deactivate');
    Route::get('/staff_role/exists/{data}/{id?}', 'StaffRoleController@exists');

    /* Staff Specialist */
    Route::resource('/staff_specialists', 'StaffSpecialistController');
    Route::get('/staff_specialist/create', 'StaffSpecialistController@create');
    Route::post('/staff_specialist/store', 'StaffSpecialistController@store')->name('specialist_add');
    Route::get('/staff_specialist/edit/{id}', 'StaffSpecialistController@edit');
    Route::post('/staff_specialist/update', 'StaffSpecialistController@update')->name('specialist_update');
    Route::get('/staff_specialist/delete/{id}', 'StaffSpecialistController@destroy');
    Route::get('/staff_specialist/delete_multiple/{ids}', 'StaffSpecialistController@destroy_multiple');
    Route::get('/staff_specialist/view/{id}', 'StaffSpecialistController@show');
    Route::get('/staff_specialist/activate/{id}', 'StaffSpecialistController@activate');
    Route::get('/staff_specialist/deactivate/{id}', 'StaffSpecialistController@deactivate');
    Route::get('/staff_specialist/exists/{data}/{id?}', 'StaffSpecialistController@exists');

    /*Patient */
    Route::resource('/patient', 'PatientController');
    Route::post('/patient/store', 'PatientController@store')->name('patient_add');
    Route::post('/patient/storeImage', 'PatientController@storeImage')->name('storeImage');
    Route::get('/patient/viewImage/{id}', 'PatientController@viewImage');
    Route::get('/patient/edit/{id}', 'PatientController@edit');
    Route::post('/patient/update', 'PatientController@update')->name('patient_update');
    Route::get('/patient/delete/{id}', 'PatientController@destroy');
    Route::get('/patient/delete_multiple/{ids}', 'PatientController@destroy_multiple');
    Route::get('/patient/view/{id}', 'PatientController@show');
    Route::get('/patient/activate/{id}', 'PatientController@activate');
    Route::get('/patient/deactivate/{id}', 'PatientController@deactivate');
    Route::get('/patient/exists/{data}/{id?}', 'PatientController@exists');
    Route::get('/patient/ajax_duplicate_email/{email?}', 'PatientController@ajax_duplicate_email')->name('patient_duplicate_email');

    /*Appointments */
    Route::get('/appointment/calendar-events', 'AppointmentController@calendarEvents')->name('appointment.calendar_events');
    Route::resource('/appointment', 'AppointmentController');
    Route::get('/appointment/create/{id?}', 'AppointmentController@create');
    Route::post('/appointment/store', 'AppointmentController@store')->name('appointment_add');
    Route::get('/appointment/delete/{id}', 'AppointmentController@destroy');
    Route::get('/appointment/delete_multiple/{ids}', 'AppointmentController@destroy_multiple');
    Route::get('/appointment/edit/{id}', 'AppointmentController@edit');
    Route::post('/appointment/update', 'AppointmentController@update')->name('appointment_update');
    Route::get('/appointment/view/{id}', 'AppointmentController@show')->name('show');
    Route::get('/appointment/change_status/{status}/{id?}', 'AppointmentController@change_status')->name('change_status');
    Route::post('/appointment/appointment_search/', 'AppointmentController@appointment_search');
    // print Appointment by ppz
    Route::get('/appointment/ajax_appt_print_data/{id?}', 'AppointmentController@getApptPrintData')->name('appt_data');

    /*Diagnosis */
    Route::resource('/diagnosis', 'PatientDiagnosisController');
    Route::get('/diagnosis/list/{id?}', 'PatientDiagnosisController@list');
    Route::get('/diagnosis/history/{id?}', 'PatientDiagnosisController@history');
    Route::get('/diagnosis/create/{id?}', 'PatientDiagnosisController@create');
    Route::post('/diagnosis/store', 'PatientDiagnosisController@store')->name('add_diagnosis');
    Route::post('/diagnosis/update', 'PatientDiagnosisController@update')->name('update_diagnosis');
    Route::post('/diagnosis/update_brief_note', 'PatientDiagnosisController@update_brief_note')->name('update_brief_note');
    Route::get('/diagnosis/edit/{id}', 'PatientDiagnosisController@edit');
    Route::get('/diagnosis/delete/{id}', 'PatientDiagnosisController@destroy');
    Route::get('/diagnosis/delete_multiple/{ids}', 'PatientDiagnosisController@destroy_multiple');
    Route::get('/diagnosis/view/{id}', 'PatientDiagnosisController@show');
    Route::get('/diagnosis/history_view/{id}', 'PatientDiagnosisController@history_view');
    Route::get('/diagnosis/ajax_medicine_list/{text?}', 'PatientDiagnosisController@ajax_medicine_list')->name('ajax_medicine_list');
    Route::get('/diagnosis/ajax_get_medicine_id/{text?}', 'PatientDiagnosisController@ajax_get_medicine_id')->name('ajax_get_medicine_id');
    Route::get('/diagnosis/ajax_get_pathology_test_id/{text?}', 'PatientDiagnosisController@ajax_get_pathology_test_id')->name('ajax_get_pathology_test_id');
    Route::get('/diagnosis/ajax_get_radiology_test_id/{text?}', 'PatientDiagnosisController@ajax_get_radiology_test_id')->name('ajax_get_radiology_test_id');
    Route::get('/diagnosis/ajax_get_consumable_id/{text?}', 'PatientDiagnosisController@ajax_get_consumable_id')->name('ajax_get_consumable_id');

    /* Symptoms type */
    Route::resource('/symptom_type', 'SymptomTypeController');
    Route::get('/symptom_type/create', 'SymptomTypeController@create');
    Route::post('/symptom_type/store', 'SymptomTypeController@store')->name('symptom_add');
    Route::get('/symptom_type/edit/{id}', 'SymptomTypeController@edit');
    Route::post('/symptom_type/update', 'SymptomTypeController@update')->name('symptom_update');
    Route::get('/symptom_type/delete/{id}', 'SymptomTypeController@destroy');
    Route::get('/symptom_type/delete_multiple/{ids}', 'SymptomTypeController@destroy_multiple');
    Route::get('/symptom_type/view/{id}', 'SymptomTypeController@show');
    Route::get('/symptom_type/activate/{id}', 'SymptomTypeController@activate');
    Route::get('/symptom_type/deactivate/{id}', 'SymptomTypeController@deactivate');
    Route::get('/symptom_type/exists/{data}/{id?}', 'SymptomTypeController@exists');
    Route::get('/appointment_manage', 'SymptomTypeController@appointment_manage');
    Route::post('/symptom_type/upload', 'SymptomTypeController@upload')->name('symptom_upload');

    /* casualty type */
    Route::resource('/casualty', 'CasualtyController');
    Route::get('/casualty/create', 'CasualtyController@create');
    Route::post('/casualty/store', 'CasualtyController@store')->name('casualty_add');
    Route::get('/casualty/edit/{id}', 'CasualtyController@edit');
    Route::post('/casualty/update', 'CasualtyController@update')->name('casualty_update');
    Route::get('/casualty/delete/{id}', 'CasualtyController@destroy');
    Route::get('/casualty/delete_multiple/{ids}', 'CasualtyController@destroy_multiple');
    Route::get('/casualty/view/{id}', 'CasualtyController@show');
    Route::get('/casualty/activate/{id}', 'CasualtyController@activate');
    Route::get('/casualty/deactivate/{id}', 'CasualtyController@deactivate');
    Route::get('/casualty/exists/{data}/{id?}', 'CasualtyController@exists');
    Route::post('/casualty/upload', 'CasualtyController@upload')->name('casualty_upload');

    /* tpa type */
    Route::resource('/tpa', 'TpaController');
    Route::get('/tpa/create', 'TpaController@create');
    Route::post('/tpa/store', 'TpaController@store')->name('tpa_add');
    Route::get('/tpa/edit/{id}', 'TpaController@edit');
    Route::post('/tpa/update', 'TpaController@update')->name('tpa_update');
    Route::get('/tpa/delete/{id}', 'TpaController@destroy');
    Route::get('/tpa/delete_multiple/{ids}', 'TpaController@destroy_multiple');
    Route::get('/tpa/view/{id}', 'TpaController@show');
    Route::get('/tpa/activate/{id}', 'TpaController@activate');
    Route::get('/tpa/deactivate/{id}', 'TpaController@deactivate');
    Route::get('/tpa/exists/{data}/{id?}', 'TpaController@exists');
    Route::post('/tpa/upload', 'TpaController@upload')->name('tpa_upload');

    /* Frequency  type */
    Route::resource('/frequency', 'FrequencyController');
    Route::get('/frequency/create', 'FrequencyController@create');
    Route::post('/frequency/store', 'FrequencyController@store')->name('frequency_add');
    Route::get('/frequency/edit/{id}', 'FrequencyController@edit');
    Route::post('/frequency/update', 'FrequencyController@update')->name('frequency_update');
    Route::get('/frequency/delete/{id}', 'FrequencyController@destroy');
    Route::get('/frequency/delete_multiple/{ids}', 'FrequencyController@destroy_multiple');
    Route::get('/frequency/view/{id}', 'FrequencyController@show');
    Route::get('/frequency/activate/{id}', 'FrequencyController@activate');
    Route::get('/frequency/deactivate/{id}', 'FrequencyController@deactivate');
    Route::get('/frequency/exists/{data}/{id?}', 'FrequencyController@exists');
    Route::post('/frequency/upload', 'FrequencyController@upload')->name('frequency_upload');

    /* center  type */
    Route::resource('/center', 'CenterController');
    Route::get('/center/create', 'CenterController@create');
    Route::post('/center/store', 'CenterController@store')->name('center_add');
    Route::get('/center/edit/{id}', 'CenterController@edit');
    Route::post('/center/update', 'CenterController@update')->name('center_update');
    Route::get('/center/delete/{id}', 'CenterController@destroy');
    Route::get('/center/delete_multiple/{ids}', 'CenterController@destroy_multiple');
    Route::get('/center/view/{id}', 'CenterController@show');
    Route::get('/center/activate/{id}', 'CenterController@activate');
    Route::get('/center/deactivate/{id}', 'CenterController@deactivate');
    Route::get('/center/exists/{data}/{id?}', 'CenterController@exists');
    Route::post('/center/upload', 'CenterController@upload')->name('center_upload');

    /* Bills */
    Route::resource('/bills', 'PatientBillController');
    Route::get('/bills/create', 'PatientBillController@create');
    Route::post('/bills/store', 'PatientBillController@store')->name('pharmacy_bill_add');
    Route::get('/bills/ajax_casenumber/{appointment_id?}', 'PatientBillController@ajax_casenumber')->name('ajax_casenumber');
    

    /* Pharmacy Category */
    Route::resource('/pharmacy_categorys', 'SettingPharmacyCategoryController');
    Route::get('/pharmacy_category/create', 'SettingPharmacyCategoryController@create');
    Route::post('/pharmacy_category/store', 'SettingPharmacyCategoryController@store')->name('pharmacy_category_add');
    Route::get('/pharmacy_category/edit/{id}', 'SettingPharmacyCategoryController@edit');
    Route::post('/pharmacy_category/update', 'SettingPharmacyCategoryController@update')->name('pharmacy_category_update');
    Route::get('/pharmacy_category/delete/{id}', 'SettingPharmacyCategoryController@destroy');
    Route::get('/pharmacy_category/delete_multiple/{ids}', 'SettingPharmacyCategoryController@destroy_multiple');
    Route::get('/pharmacy_category/view/{id}', 'SettingPharmacyCategoryController@show');
    Route::get('/pharmacy_category/activate/{id}', 'SettingPharmacyCategoryController@activate');
    Route::get('/pharmacy_category/deactivate/{id}', 'SettingPharmacyCategoryController@deactivate');
    Route::get('/pharmacy_category/exists/{data}/{id?}', 'SettingPharmacyCategoryController@exists');
    Route::get('/pharmacy_category/ajax_duplicate_name/{name?}', 'SettingPharmacyCategoryController@ajax_duplicate_name')->name('pharmacy_duplicate_name');

    /* Pharmacy */
    Route::resource('/pharmacys', 'SettingPharmacyController');
    Route::get('/pharmacy/create', 'SettingPharmacyController@create');
    Route::post('/pharmacy/store', 'SettingPharmacyController@store')->name('pharmacy_add');
    Route::get('/pharmacy/edit/{id}', 'SettingPharmacyController@edit');
    Route::post('/pharmacy/update', 'SettingPharmacyController@update')->name('pharmacy_update');
    Route::get('/pharmacy/delete/{id}', 'SettingPharmacyController@destroy');
    Route::get('/pharmacy/delete_multiple/{ids}', 'SettingPharmacyController@destroy_multiple');
    Route::get('/pharmacy/view/{id}', 'SettingPharmacyController@show');
    Route::get('/pharmacy/activate/{id}', 'SettingPharmacyController@activate');
    Route::get('/pharmacy/deactivate/{id}', 'SettingPharmacyController@deactivate');
    Route::get('/pharmacy/exists/{data}/{id?}', 'SettingPharmacyController@exists');
    Route::get('/pharmacy/ajax_duplicate_name/{name?}', 'SettingPharmacyController@ajax_duplicate_name')->name('pharmacy_duplicate_name');

    /* Pathology Category */
    Route::resource('/pathology_categorys', 'SettingPathologyCategoryController');
    Route::get('/pathology_category/create', 'SettingPathologyCategoryController@create');
    Route::post('/pathology_category/store', 'SettingPathologyCategoryController@store')->name('pathology_category_add');
    Route::get('/pathology_category/edit/{id}', 'SettingPathologyCategoryController@edit');
    Route::post('/pathology_category/update', 'SettingPathologyCategoryController@update')->name('pathology_category_update');
    Route::get('/pathology_category/delete/{id}', 'SettingPathologyCategoryController@destroy');
    Route::get('/pathology_category/delete_multiple/{ids}', 'SettingPathologyCategoryController@destroy_multiple');
    Route::get('/pathology_category/view/{id}', 'SettingPathologyCategoryController@show');
    Route::get('/pathology_category/activate/{id}', 'SettingPathologyCategoryController@activate');
    Route::get('/pathology_category/deactivate/{id}', 'SettingPathologyCategoryController@deactivate');
    Route::get('/pathology_category/exists/{data}/{id?}', 'SettingPathologyCategoryController@exists');
    Route::get('/pathology_category/ajax_duplicate_name/{name?}', 'SettingPathologyCategoryController@ajax_duplicate_name')->name('pathology_duplicate_name');

    /* Pathology */
    Route::resource('/pathologys', 'SettingPathologyController');
    Route::get('/pathology/create', 'SettingPathologyController@create');
    Route::post('/pathology/store', 'SettingPathologyController@store')->name('pathology_add');
    Route::get('/pathology/edit/{id}', 'SettingPathologyController@edit');
    Route::post('/pathology/update', 'SettingPathologyController@update')->name('pathology_update');
    Route::get('/pathology/delete/{id}', 'SettingPathologyController@destroy');
    Route::get('/pathology/delete_multiple/{ids}', 'SettingPathologyController@destroy_multiple');
    Route::get('/pathology/view/{id}', 'SettingPathologyController@show');
    Route::get('/pathology/activate/{id}', 'SettingPathologyController@activate');
    Route::get('/pathology/deactivate/{id}', 'SettingPathologyController@deactivate');
    Route::get('/pathology/exists/{data}/{id?}', 'SettingPathologyController@exists');
    Route::get('/pathology/ajax_duplicate_name/{name?}', 'SettingPathologyController@ajax_duplicate_name')->name('pathology_duplicate_name');

    /* Radiology Category */
    Route::resource('/radiology_categorys', 'SettingRadiologyCategoryController');
    Route::get('/radiology_category/create', 'SettingRadiologyCategoryController@create');
    Route::post('/radiology_category/store', 'SettingRadiologyCategoryController@store')->name('radiology_category_add');
    Route::get('/radiology_category/edit/{id}', 'SettingRadiologyCategoryController@edit');
    Route::post('/radiology_category/update', 'SettingRadiologyCategoryController@update')->name('radiology_category_update');
    Route::get('/radiology_category/delete/{id}', 'SettingRadiologyCategoryController@destroy');
    Route::get('/radiology_category/delete_multiple/{ids}', 'SettingRadiologyCategoryController@destroy_multiple');
    Route::get('/radiology_category/view/{id}', 'SettingRadiologyCategoryController@show');
    Route::get('/radiology_category/activate/{id}', 'SettingRadiologyCategoryController@activate');
    Route::get('/radiology_category/deactivate/{id}', 'SettingRadiologyCategoryController@deactivate');
    Route::get('/radiology_category/exists/{data}/{id?}', 'SettingRadiologyCategoryController@exists');
    Route::get('/radiology_category/ajax_duplicate_name/{name?}', 'SettingRadiologyCategoryController@ajax_duplicate_name')->name('radiology_duplicate_name');

    /* Radiology */
    Route::resource('/radiologys', 'SettingRadiologyController');
    Route::get('/radiology/create', 'SettingRadiologyController@create');
    Route::post('/radiology/store', 'SettingRadiologyController@store')->name('radiology_add');
    Route::get('/radiology/edit/{id}', 'SettingRadiologyController@edit');
    Route::post('/radiology/update', 'SettingRadiologyController@update')->name('radiology_update');
    Route::get('/radiology/delete/{id}', 'SettingRadiologyController@destroy');
    Route::get('/radiology/delete_multiple/{ids}', 'SettingRadiologyController@destroy_multiple');
    Route::get('/radiology/view/{id}', 'SettingRadiologyController@show');
    Route::get('/radiology/activate/{id}', 'SettingRadiologyController@activate');
    Route::get('/radiology/deactivate/{id}', 'SettingRadiologyController@deactivate');
    Route::get('/radiology/exists/{data}/{id?}', 'SettingRadiologyController@exists');
    Route::get('/radiology/ajax_duplicate_name/{name?}', 'SettingRadiologyController@ajax_duplicate_name')->name('radiology_duplicate_name');

    /* Quantity */
    Route::resource('/setting_quantitys', 'SettingQuantityController');
    Route::get('/setting_quantity/create', 'SettingQuantityController@create');
    Route::post('/setting_quantity/store', 'SettingQuantityController@store')->name('quantity_add');
    Route::get('/setting_quantity/edit/{id}', 'SettingQuantityController@edit');
    Route::post('/setting_quantity/update', 'SettingQuantityController@update')->name('quantity_update');
    Route::get('/setting_quantity/delete/{id}', 'SettingQuantityController@destroy');
    Route::get('/setting_quantity/delete_multiple/{ids}', 'SettingQuantityController@destroy_multiple');
    Route::get('/setting_quantity/view/{id}', 'SettingQuantityController@show');
    Route::get('/setting_quantity/activate/{id}', 'SettingQuantityController@activate');
    Route::get('/setting_quantity/deactivate/{id}', 'SettingQuantityController@deactivate');
    Route::get('/setting_quantity/exists/{data}/{id?}', 'SettingQuantityController@exists');
    Route::get('/setting_quantity/ajax_duplicate_quantity/{quantity?}', 'SettingQuantityController@ajax_duplicate_name')->name('setting_quantity_duplicate_name');


    /* report */
    Route::get('/report/appointment_report/{doctor_id?}/{patient_id?}', 'ReportController@appointment_report');
    Route::get('/report/revenue_report/{id?}', 'ReportController@revenue_report');
    Route::get('/report/download_revenue_report/', 'ReportController@download_revenue_report');
    Route::post('/report/export_revenue_report/', 'ReportController@export_revenue_report');

    Route::post('/inventory_master/import_item_master/', 'InventoryItemMasterController@import_item_master');
    Route::post('/inventory_stock/import_item_stock/', 'InventoryStockController@import_item_stock');
    Route::post('/pharmacy/import_medicines', 'SettingPharmacyController@import_medicines');

    Route::get('/bill/ajax_fetch_bill_print_data/{id?}', 'PatientBillController@ajax_fetch_bill_print_data');

    // pharmacy generic 
    Route::resource('/pharmacy_generic', 'PharmacyGenericController');
    // pharmacy dosage
    Route::resource('/pharmacy_dosage', 'PharmacyDosageController');

    /* EMR Module */
    Route::get('/emr/list', 'EMRController@index')->name('emr.index');
    Route::get('/emr/workbench/{id}', 'EMRController@show')->name('emr.show');
    Route::post('/emr/store', 'EMRController@store')->name('emr.store');
    Route::post('/emr/save-draft', 'EMRController@saveDraft')->name('emr.saveDraft');
    Route::get('/emr/ajax-search-drugs', 'EMRController@ajaxSearchDrugs');
    Route::get('/emr/ajax-search-tests', 'EMRController@ajaxSearchTests');
    Route::post('/emr/upload-document', 'EMRController@uploadDocument')->name('emr.uploadDocument');
    Route::post('/emr/delete-document', 'EMRController@deleteDocument')->name('emr.deleteDocument');
    Route::post('/emr/save-test-result', 'EMRController@saveTestResult')->name('emr.saveTestResult');

    /* Revenue Cycle Management (RCM) Module */
    Route::get('/rcm', 'RevenueCycleManagementController@index')->name('rcm.index');
    Route::get('/rcm/create', 'RevenueCycleManagementController@create')->name('rcm.create');
    Route::get('/rcm/ajax_fetch_items/{id}', 'RevenueCycleManagementController@ajax_fetch_billable_items');
    Route::get('/rcm/ajax_search_patients', 'RevenueCycleManagementController@ajax_search_patients');
    Route::post('/rcm/store', 'RevenueCycleManagementController@store')->name('rcm.store');
    Route::get('/rcm/invoice/{id}', 'RevenueCycleManagementController@show')->name('rcm.show');
    Route::post('/rcm/invoice/{id}/pay', 'RevenueCycleManagementController@markAsPaid')->name('rcm.pay');
    Route::post('/rcm/invoice/{id}/settle-credit', 'RevenueCycleManagementController@settleCredit')->name('rcm.settle');
    Route::get('/rcm/receipt/{id}', 'RevenueCycleManagementController@receipt')->name('rcm.receipt');

});

Route::get('/customer', [App\Http\Controllers\Frontend\CustomerController::class, 'login'])->name('patient_login');
Route::get('/customer/forgotpassword', [App\Http\Controllers\Frontend\CustomerController::class, 'patient_forgotpassword'])->name('patient_forgotpassword');
Route::post('/customer/send_email_link', [App\Http\Controllers\Frontend\CustomerController::class, 'patient_send_email_link'])->name('patient_send_email_link');
Route::get('/customer/reset/password/view/{email}', [App\Http\Controllers\Frontend\CustomerController::class, 'patient_view_reset_password'])->name('patient_view_reset_password');
Route::post('/customer/reset/password/save', [App\Http\Controllers\Frontend\CustomerController::class, 'patient_save_reset_password'])->name('patient_save_reset_password');

Route::get('/customer/register', [App\Http\Controllers\Frontend\CustomerController::class, 'register'])->name('patient_register');
Route::post('/customer/register_create', [App\Http\Controllers\Frontend\CustomerController::class, 'register_create'])->name('register_create');
Route::post('/customer/authenticate', [App\Http\Controllers\Frontend\CustomerController::class, 'authenticate'])->name('authenticate');

/* Protected Patient Routes */
Route::group(['middleware' => ['auth:blogger']], function () {
    Route::get('/customer/appointment', [App\Http\Controllers\Frontend\CustomerController::class, 'appointment'])->name('patient_appointment');
    Route::post('/customer/appointment_create', [App\Http\Controllers\Frontend\CustomerController::class, 'appointment_create'])->name('appointment_create');
    Route::get('/customer_logout', [App\Http\Controllers\Frontend\CustomerController::class, 'customer_logout']);

    Route::get('/customer/book_appointment', [App\Http\Controllers\Frontend\PatientAppointmentController::class, 'index'])->name('index');
    Route::post('/customer/book_appointment_store', [App\Http\Controllers\Frontend\PatientAppointmentController::class, 'book_appointment_store'])->name('book_appointment_store');
    Route::get('/customer/booked_appointment_list', [App\Http\Controllers\Frontend\PatientAppointmentController::class, 'booked_appointment_list'])->name('booked_appointment_list');
    Route::get('/customer/booked_appointment_show/{id}', [App\Http\Controllers\Frontend\PatientAppointmentController::class, 'booked_appointment_show'])->name('booked_appointment_show');
    Route::get('/customer/booked_appointment_cancel/{id}', [App\Http\Controllers\Frontend\PatientAppointmentController::class, 'booked_appointment_cancel'])->name('booked_appointment_cancel');

    Route::get('/customer/patient_diagnosis_list/{id}', [App\Http\Controllers\Frontend\PatientAppointmentController::class, 'patient_diagnosis_list'])->name('patient_diagnosis_list');
    Route::get('/customer/patient_diagnosis_list_view/{id}', [App\Http\Controllers\Frontend\PatientAppointmentController::class, 'patient_diagnosis_list_view'])->name('patient_diagnosis_list_view');
    Route::post('/customer/upload_reports/', [App\Http\Controllers\Frontend\PatientAppointmentController::class, 'upload_reports'])->name('upload_reports');
    Route::get('/customer/list_reports/{id?}', [App\Http\Controllers\Frontend\PatientAppointmentController::class, 'list_reports'])->name('list_reports');

    Route::get('/customer/list_bills/', [App\Http\Controllers\Frontend\CustomerBillController::class, 'index']);
    Route::get('/customer/list_bill_set/{id?}', [App\Http\Controllers\Frontend\CustomerBillController::class, 'list_bill_set'])->name('list_bill_set');
    Route::get('/customer/bill_customer/ajax_fetch_bill_print_data_pharmacy/{id?}', [App\Http\Controllers\Frontend\CustomerBillController::class, 'ajax_fetch_bill_print_data_pharmacy']);
    Route::get('/customer/bill_customer/ajax_fetch_bill_print_data_radiology/{id?}', [App\Http\Controllers\Frontend\CustomerBillController::class, 'ajax_fetch_bill_print_data_radiology']);
    Route::get('/customer/bill_customer/ajax_fetch_bill_print_data_pathology/{id?}', [App\Http\Controllers\Frontend\CustomerBillController::class, 'ajax_fetch_bill_print_data_pathology']);
    Route::get('/customer/bill_customer/ajax_fetch_bill_print_data_other/{id?}', [App\Http\Controllers\Frontend\CustomerBillController::class, 'ajax_fetch_bill_print_data_other']);
    Route::get('/customer/bill_customer/ajax_fetch_bill_print_data_consumable/{id?}', [App\Http\Controllers\Frontend\CustomerBillController::class, 'ajax_fetch_bill_print_data_consumable']);

    /* Profile update */
    Route::get('/customer/patient_profile', [App\Http\Controllers\Frontend\ProfileController::class, 'index']);
    Route::post('/customer/patient_profile_update', [App\Http\Controllers\Frontend\ProfileController::class, 'patient_profile_update'])->name('patient_profile_update');
    Route::post('/customer/update_patient_password', [App\Http\Controllers\Frontend\ProfileController::class, 'update_patient_password'])->name('update_patient_password');
});
