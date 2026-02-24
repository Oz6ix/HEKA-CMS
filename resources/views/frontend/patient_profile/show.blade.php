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
    <div class="row">
    <div class="col-lg-1"></div>
        <div class="col-lg-9">

            <!--begin::Portlet-->
            <div class="kt-portlet kt-portlet--last kt-portlet--head-lg kt-portlet--responsive-mobile"
                 id="kt_page_portlet">
                <div class="kt-portlet__head kt-portlet__head--lg">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">Appointment Details:</h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <a href="{{ url($url_prefix . '/booked_appointment_list') }}" class="btn btn-clean kt-margin-r-10">
                            <i class="la la-arrow-left"></i>
                            <span class="kt-hidden-mobile">Back to List</span>
                        </a>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    {!! Form::open(['class' => 'kt-form']) !!}
                    <div class="row">                   
                        <div class="col-md-6">
                            <div class="kt-section kt-section--first">
                                <div class="kt-section__body">
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label">
                                        </label>
                                        <div class="col-8 form-info">
                                         
                                        </div>
                                    </div>
                                    <h3 class="kt-section__title kt-section__title-lg"></h3>

                                    <div class="form-group row">
                                        <label class="col-4 col-form-label">Patient 
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ $item['patient']['name'] }}</strong></label>
                                        </div>
                                    </div> 

                                    <div class="form-group row">
                                        <label class="col-4 col-form-label">Appointment Date
                                            <span class="form-info"> * </span>
                                        </label>
                                        <div class="col-4">  
                                        <label class="col-form-label"><strong>{{$item['appointment_date']}}</strong></label>                                         
                                        </div>
                                    </div> 

                                    <div class="form-group row">
                                        <label class="col-4 col-form-label">Case No.
                                            <span class="form-info">  </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ $item['case_number'] }}</strong></label>
                                        </div>
                                      </div> 

                                    <div class="form-group row">
                                        <label class="col-4 col-form-label">Casualty
                                            <span class="form-info">  </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ ($item['casualty'])!=NULL ? ($item['casualty']['casualty']) : '' }}</strong></label>
                                        </div>
                                    </div> 
                                    <!-- One additional row added below to adjust page height -->
                                </div>
                            </div>
                        </div>

                         <div class="col-md-6">
                            <div class="kt-section kt-section--first">
                                <div class="kt-section__body">
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label">
                                        </label>
                                        <div class="col-8 form-info">
                                        </div>
                                    </div>

                                    
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label">TPA
                                            <span class="form-info">  </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ $item['tpa']['tpa'] }}</strong></label>
                                        </div>
                                    </div> 

                                    <div class="form-group row">
                                        <label class="col-4 col-form-label">Reference
                                            <span class="form-info">  </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ $item['reference'] }}</strong></label>
                                        </div>
                                      </div> 

                                      <div class="form-group row">
                                        <label class="col-4 col-form-label">Consultant Doctor
                                            <span class="form-info"> * </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ $item['staff_doctor']['name'] }}</strong></label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                        
                    </div>
<!-- Second part -->
                    <div class="row secondary" style="background-color:#f2f2f2;">                   
                        <div class="col-md-6">
                            <div class="kt-section kt-section--first">
                                <div class="kt-section__body">
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label">
                                        </label>
                                        <div class="col-8 form-info">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label">Height
                                            <span class="form-info">  </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ $item_basic['height'] }}</strong></label>
                                        </div>
                                      </div> 

                                      <div class="form-group row">
                                        <label class="col-4 col-form-label">Weight
                                            <span class="form-info">  </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ $item_basic['weight'] }}</strong></label>

                                        </div>
                                      </div> 

                                      <div class="form-group row">
                                        <label class="col-4 col-form-label">BP
                                            <span class="form-info">  </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ $item_basic['bp'] }}</strong></label>
                                        </div>
                                      </div> 

                                      <div class="form-group row">
                                        <label class="col-4 col-form-label">Pulse
                                            <span class="form-info">  </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ $item_basic['pulse'] }}</strong></label>
                                        </div>
                                      </div> 
                                      <div class="form-group row">
                                        <label class="col-4 col-form-label">Temperature
                                            <span class="form-info">  </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ $item_basic['temperature'] }}</strong></label>
                                        </div>
                                      </div> 

                                      <div class="form-group row">
                                        <label class="col-4 col-form-label">Respiration
                                            <span class="form-info">  </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ $item_basic['respiration'] }}</strong></label>
                                        </div>
                                      </div> 
                                    <!-- One additional row added below to adjust page height -->
                                </div>
                            </div>
                        </div>

                         <div class="col-md-6">
                            <div class="kt-section kt-section--first">
                                <div class="kt-section__body">
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label">
                                        </label>
                                        <div class="col-8 form-info">
                                          
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label">Syptom Type
                                            <span class="form-info">  </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ ($item_basic['symptom_type'])!= NULL ? ($item_basic['symptom_type']['symptom']) : ''  }}</strong></label>
                                        </div>
                                    </div> 

                                    <div class="form-group row">
                                        <label class="col-4 col-form-label">Symptom
                                            <span class="form-info">  </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ $item_basic['symptom'] }}</strong></label>
                                        </div>
                                      </div> 

                                      <div class="form-group row">
                                        <label class="col-4 col-form-label">Description 
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ $item_basic['description'] }}</strong></label>
                                        </div>
                                      </div> 

                                      <div class="form-group row">
                                        <label class="col-4 col-form-label">Notes 
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ $item_basic['note'] }}</strong></label>
                                        </div>
                                      </div> 

                                    </div>
                            </div>
                        </div>
                        
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>
            <!--end::Portlet-->
        
        
        </div>
    </div>
    {!! Form::close() !!}
         <!-- End Patient Details -->
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
                </div>
            </div>
            <!--end::Portlet-->
        </div>
        </div>
        <div class="col-lg-1"></div>
@endsection
@section('scripts')

    <!--begin::Page Scripts(used by this page) -->
    <script src="{{ URL::asset('assets/frontend/js/scripts/appointment.js') }}"
            type="text/javascript"></script>

    <script src="{{ URL::asset('assets/frontend/js/validations/admin_users.js') }}"
            type="text/javascript"></script>
    <!--end::Page Scripts -->
@endsection
