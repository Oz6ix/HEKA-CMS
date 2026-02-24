{{--<!--begin::Portlet--> kt-portlet--collapse--}}
<div class="kt-portlet" data-ktportlet="true" id="kt_portlet_homepage_element">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
               Notification Settings
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-group">
                <a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-outline-brand btn-icon-md"><i
                            class="la la-angle-down"></i></a>
            </div>
        </div>
    </div>
    <style type="text/css">.font-label-new{padding-top:4px!important;}</style>
    <!-- Messages section -->
    @include('backend.layouts.includes.notification_alerts')
    {!! Form::open(['route'=>('setting_notification_update'), 'id' => 'update_notification', 'class' => 'kt-form', 'files' => true, 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}

    <input type="hidden" name="id" value="{{$item['id']}}">
    <div class="kt-portlet__body">
        <div class="form-group row">
            <label class="col-3 col-form-label">
            </label>
            <div class="col-9 form-info">
                * = Required
            </div>
        </div>
        <div class="col-md-12">
        <div class="row">

        <div class="col-md-9">
            <div class="kt-sectionggg kt-section--firstggg">
                <div class="kt-section__body">
                    <div class="form-group row">
                        <label class="col-3 col-form-label text-md-right">Patient Registration</label>
                        <div class="col-9">
                            <input type="text" class="form-control" name="patient_registration_email" id="patient_registration_email"
                                   placeholder="Enter email address"
                                   value="{{ $item['patient_registration_email'] }}" data="{{ $item['patient_registration_email'] }}"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 col-form-label text-md-right">Phone no.</label>
                        <div class="col-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">+95</span>
                                </div>
                                <input type="text" class="form-control input-numeric "
                                       name="patient_phone"
                                       id="patient_phone" placeholder="Enter phone number" value="{{ $item['patient_phone'] }}">
                            </div>
                        </div>
                    </div> 

                    <div class="form-group row">
                        <label class="col-3 col-form-label text-md-right">Appointment Booking</label>
                        <div class="col-9">
                            <input type="text" class="form-control" name="appointment_booking_email" id="appointment_booking_email"
                                   placeholder="Enter email address"
                                   value="{{ $item['appointment_booking_email'] }}" data="{{ $item['appointment_booking_email'] }}"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 col-form-label text-md-right">Phone no.</label>
                        <div class="col-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">+95</span>
                                </div>
                                <input type="text" class="form-control input-numeric "
                                       name="appointment_phone"
                                       id="appointment_phone" placeholder="Enter phone number" value="{{ $item['appointment_phone'] }}">
                            </div>
                        </div>
                    </div>  


                    <div class="form-group row">
                        <label class="col-3 col-form-label text-md-right">Inventory Stock Status</label>
                        <div class="col-9">
                            <input type="text" class="form-control" name="inventory_stock_email" id="inventory_stock_email"
                                   placeholder="Enter email address"
                                   value="{{ $item['inventory_stock_email'] }}" data="{{ $item['inventory_stock_email'] }}"/>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-3 col-form-label text-md-right">Phone no.</label>
                        <div class="col-9">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">+95</span>
                                </div>
                                <input type="text" class="form-control input-numeric "
                                       name="inventory_stock_phone"
                                       id="inventory_stock_phone" placeholder="Enter phone number" value="{{ $item['inventory_stock_phone'] }}">
                            </div>
                        </div>
                    </div> 
                </div>
            </div>
        </div>              
            </div>  
        </div>      
       
     
    </div>
    <div class="kt-portlet__foot">
        <div>
            <button type="submit" class="btn btn-brand button-submit" id="update_notification">
                <i class="la la-check"></i>
                Update
            </button>
        </div>
    </div>
    {!! Form::close() !!}
</div>
<!--end::Portlet-->
