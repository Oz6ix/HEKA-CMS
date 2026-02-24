@extends('frontend.layouts.layout_inner')
@section('content')
<?php
$controller = class_basename(\Route::current()->controller);
$action = class_basename(\Route::current()->action['uses']);
$url_prefix = Config::get('app.app_route_customer_prefix');
$app_logo_head = Config::get('app.app_logo_head');
$app_logo_sub = Config::get('app.app_logo_sub');
$header_data = fetch_header_data();
?>
@include('frontend.layouts.includes.alert_popup')
@section('content')
    <!-- Messages section -->
    @include('backend.layouts.includes.notification_alerts')
    <br><br>
     <div class="col-md-12">
    <div class="row">
         <div class="col-lg-1"></div>
        <div class="col-lg-9">
            <!--begin::Portlet-->
            <div class="kt-portlet kt-portlet--last kt-portlet--head-lg kt-portlet--responsive-mobile"
                 id="kt_page_portlet">                
                <div class="kt-portlet__body">
                    {!! Form::open(['route'=>('appointment_create'), 'id' => 'add_form', 'class' => 'kt-form', 'files' => true, 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}

                    <div class="row">                        
                        <div class="col-md-9">
                            <div class="kt-section kt-section--first">
                                <div class="kt-section__body">
                                    <div class="form-group row">
                                        <label class="col-6 col-form-label">Enter appointment details and submit
                                        </label>
                                        <div class="col-6 form-info">
                                            * = Required
                                        </div>
                                    </div>   <br><br>                              
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Name
                                            <span class="form-info"> * </span>
                                            
                                        </label>
                                        <div class="col-9">
                                            <input type="text" class="form-control" name="name" id="name"
                                                   placeholder="Enter full name"
                                                   value="{{ old('name') }}"/>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Email Address
                                            <span class="form-info"> * </span>
                                           
                                        </label>
                                        <div class="col-9">
                                            <input type="text" class="form-control" name="email" id="email"
                                                   placeholder="Enter email address"
                                                   value="{{ old('email') }}"/>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Phone
                                            <span class="form-info"> * </span>
                                            
                                        </label>
                                        <div class="col-9">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">+95</span>
                                                </div>
                                                <input type="text" class="form-control input-numeric "
                                                       name="phone"
                                                       id="phone" placeholder="Enter phone number" value="{{ old('phone') }}">
                                            </div>
                                        </div>
                                    </div>                                    
                                    <!-- One additional row added below to adjust page height -->
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">
                                        </label>
                                        <div class="col-9 form-info">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2"></div>
                    </div>
                     <div class="kt-portlet__foot">

                    <div class="kt-form__actions">
                        <div class="row"><div class="col-md-2"></div>
                    <div class="col-md-9">
                        <button type="submit" class="btn btn-brand button-submit" id="password_button">                            Submit
                        </button>
                        <a href="#" class="btn btn-brand button-submit">                           
                            <span class="kt-hidden-mobile">Cancel</span>
                        </a>
                        </div>
                        </div>
                    </div>
                </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <!--end::Portlet-->
        </div>
        <div class="col-lg-1"></div>
    </div>
    </div>
    <br><br>
    @include('backend.layouts.includes.admin_modal_popup_alert') 
@endsection
@section('scripts')

    <!--begin::Page Scripts(used by this page) -->
    <script src="{{ URL::asset('assets/backend/js/validations/admin_users.js') }}"
            type="text/javascript"></script>
    <!--end::Page Scripts -->
@endsection
