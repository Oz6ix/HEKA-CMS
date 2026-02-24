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
    <br><br> <div class="col-md-12">
    <div class="row">
    <div class="col-lg-1"></div>
        <div class="col-lg-9">

            <!--begin::Portlet-->
            <div class="kt-portlet kt-portlet--last kt-portlet--head-lg kt-portlet--responsive-mobile"
                 id="kt_page_portlet">
                <div class="kt-portlet__head kt-portlet__head--lg">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">View Details</h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <a href="{{ url($url_prefix . '/patient_diagnosis_list/'.$appointment_id) }}" class="btn btn-clean kt-margin-r-10">
                            <i class="la la-arrow-left"></i>
                            <span class="kt-hidden-mobile">Back to List</span>
                        </a>
                        
                    </div>
                </div>
    {!! Form::open(['id' => 'add_form_first','name' => 'add_form_first', 'class' => 'kt-form', 'files' => true, 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="kt-portlet kt-portlet--last kt-portlet--head-lg kt-portlet--responsive-mobile"
                 id="kt_page_portlet">
                
                <div class="kt-portlet__body">
            <!-- First part -->
                    <div class="row ">                   
                        <div class="col-md-4">
                            <div class="kt-section kt-section--first">
                                <div class="kt-section__body pl-5">
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">
                                        </label>
                                        <div class="col-9 form-info">
                                         
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label">Height
                                            <span class="form-info">  </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ $item_basic[0]['height'] }}</strong></label>
                                        </div>
                                    </div> 

                                    <!-- <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-left">Height
                                            <span class="form-info">  </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label text-md-right"><strong>{{ $item_basic[0]['height']  }}</strong></label>
                                            <input type="hidden" name="appointment_basic_id" value="{{ $item_basic[0]['id']  }}"/>
                                            <input type="hidden" name="patient_id" value="{{ $item_basic[0]['patient_id']  }}"/>
                                        </div>
                                      </div> -->
                                      <div class="form-group row">
                                        <label class="col-4 col-form-label">Weight
                                            <span class="form-info">  </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ $item_basic[0]['weight'] }}</strong></label>

                                        </div>
                                      </div> 

                                      <div class="form-group row">
                                        <label class="col-4 col-form-label">BP
                                            <span class="form-info">  </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ $item_basic[0]['bp'] }}</strong></label>
                                        </div>
                                      </div> 

                                      <div class="form-group row">
                                        <label class="col-4 col-form-label">Pulse
                                            <span class="form-info">  </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ $item_basic[0]['pulse'] }}</strong></label>
                                        </div>
                                      </div> 

                                      <div class="form-group row">
                                        <label class="col-4 col-form-label">Temperature
                                            <span class="form-info">  </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ $item_basic[0]['temperature'] }}</strong></label>
                                        </div>
                                      </div> 

                                      <div class="form-group row">
                                        <label class="col-4 col-form-label">Respiration
                                            <span class="form-info">  </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ $item_basic[0]['respiration'] }}</strong></label>
                                        </div>
                                      </div> 

                                    <!-- One additional row added below to adjust page height -->
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="kt-section kt-section--first">
                                <div class="kt-section__body">
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">
                                        </label>
                                        <div class="col-9 form-info">
                                        </div>
                                    </div>
                                    
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label">Syptom Type
                                            <span class="form-info">  </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ ($item_basic[0]['symptom_type']!=null)?$item_basic[0]['symptom_type']['symptom']:'' }}</strong></label>
                                        </div>
                                    </div> 
                                    
                                    <div class="form-group row">
                                        <label class="col-4 col-form-label">Symptom
                                            <span class="form-info">  </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ $item_basic[0]['symptom'] }}</strong></label>
                                        </div>
                                      </div> 

                                      <div class="form-group row">
                                        <label class="col-4 col-form-label">Description 
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{!! $item_basic[0]['description'] !!}</strong></label>
                                        </div>
                                      </div> 

                                      <div class="form-group row">
                                        <label class="col-4 col-form-label">Notes 
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{!! $item_basic[0]['note'] !!}</strong></label>
                                        </div>
                                      </div> 

                                </div>
                            </div>
                        </div>


                    <div class=" col-md-4">
                    <div class="form-group row">
                        <label class="col-3 col-form-label">
                        </label>
                        <div class="col-9 form-info">
                        </div>
                    </div>
                        @foreach ($patient_details as $detail)
                        <div class="card" style="background-color: #f2f2f2;">
                            <div class="card-header" style="background-color:#e8e8e8;"><h5>Appointment Details</h5></div>
                            <div class="card-body">
                                <div class="row">
                                    <label for="email_address" class="col-md-6  kt-widget__label text-md-right">Case No.:</label>
                                    <label for="email_address" class="col-md-6  text-md-left">{{ $detail->case_number }}</label>                 
                                </div>

                                <div class="row">
                                    <label class="col-md-6  kt-widget__label text-md-right">Appointment Date:</label>
                                    <label class="col-md-6  text-md-left">{{ $detail->appointment_date }}</label>                 
                                </div>

                                <div class="row">
                                    <label class="col-md-6  kt-widget__label text-md-right">Consulting Doctor:</label>
                                    <label class="col-md-6  text-md-left">{{ $detail->staff_doctor->name }}</label>                 
                                </div>

                            </div>
                        </div>
                        <!--  Second part -->
                        <div class="form-group row">
                        </div>


                        @endforeach  
                    </div>
              
        </div>
         <!-- End Patient Details -->

                    
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
    {!! Form::close() !!}
    {!! Form::open(['id' => 'add_form_second','name' => 'add_form_second', 'class' => 'kt-form ', 'files' => true, 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
    <input type="hidden" name="id" value="{{ $appointment_id }}">
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="kt-portlet kt-portlet--last kt-portlet--head-lg kt-portlet--responsive-mobile"
                 id="kt_page_portlet">
                 <div class="card-header ml-5"><h5>Diagnosis Details</h5></div>
                <div class="kt-portlet__body">
            <!-- First part -->
                    <div class="row ">                   
                        <div class="col-md-8">
                            <div class="kt-section kt-section--first">
                                <div class="kt-section__body" id="jscontent">

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Diagnosis
                                        <span class="form-info"> * </span>
                                        </label>
                                        <div class="col-9">
                                        <label class="col-form-label"><strong>{{ $item_diagnosis->diagnosis }}</strong></label>
                                        </div>
                                      </div> 

                                      <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">ICD Diagnosis
                                        <span class="form-info"> * </span>
                                        </label>
                                        <div class="col-9">
                                        <label class="switch">
                                        <label class="col-form-label"><strong>{{ ($item_diagnosis->icd_diagnosis == 1) ? 'On' : 'Off' }}</strong></label>
                                        </label>
                                        </div>
                                      </div> 

                                      <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Treatment & Intervention
                                        <span class="form-info"> * </span>
                                        </label>
                                        <div class="col-9">
                                        <label class="col-form-label"><strong>{{ $item_diagnosis->treatment->title }}</strong></label>
                                        </div>
                                    </div> 

                                </div></div>
                                <!-- Second part -->
                                <div class="kt-section kt-section--first" style="border:#c4c4c4 1px solid;padding:3%;">
                                <div class="kt-section__body">
                                    <div class="form-group row">
                                    <span for="email_address" class="col-md-12  kt-widget__label">Prescription</span>
                                    </div> 
                                <!-- Input Repeter code   -->
                                    <div id="kt_repeater_1">
                                        <div class="form-group form-group-last row" id="kt_repeater_1">
                                            <div data-repeater-list="prescription" class="col-lg-12">
                                            @if (isset($item_prescription) && !empty($item_prescription) && count($item_prescription)>0)
                                            
                                                @foreach($item_prescription as $key => $prescription_value)


                                                <div data-repeater-item class="form-group row align-items-center">
                                                    <div class="col-md-3">
                                                        <div class="kt-form__group--inline">
                                                            <div class="kt-form__label">
                                                                <label>Drug Name</label>
                                                            </div>
                                                            <div class="kt-form__control">
                                                            <label class="col-form-label"><strong>{{$prescription_value['drug_name']}}</strong></label>
                                                            </div>
                                                        </div>
                                                        <div class="d-md-none kt-margin-b-10"></div>
                                                    </div>
                                                    <div class="col-md-2">
                                                        <div class="kt-form__group--inline">
                                                            <div class="kt-form__label">
                                                                <label class="kt-label m-label--single">Quantity</label>
                                                            </div>
                                                            <div class="kt-form__control">
                                                            <label class="col-form-label"><strong>{{$prescription_value['quantity']}}</strong></label>
                                                            </div>
                                                        </div>
                                                        <div class="d-md-none kt-margin-b-10"></div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="kt-form__group--inline">
                                                            <div class="kt-form__label">
                                                                <label class="kt-label m-label--single">Unit</label>
                                                            </div>
                                                            <div class="kt-form__control">
                                                            <label class="col-form-label"><strong>{{ $prescription_value['unit']['unit'] }}</strong></label>
                                                            </div>
                                                        </div>
                                                        <div class="d-md-none kt-margin-b-10"></div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="kt-form__group--inline">
                                                            <div class="kt-form__label">
                                                                <label class="kt-label m-label--single">Frequency</label>
                                                                <span class="form-info"> * </span>
                                                            </div>
                                                            <div class="kt-form__control">
                                                            <label class="col-form-label"><strong>{{ $prescription_value['frequency']['frequency'] }}</strong></label>
                                                            </div>
                                                        </div>
                                                        <div class="d-md-none kt-margin-b-10"></div>
                                                    </div>

                                                    <div class="col-md-2">
                                                        <div class="kt-form__group--inline">
                                                            <div class="kt-form__label">
                                                                <label class="kt-label m-label--single">No. of Days</label>
                                                            </div>
                                                            <div class="kt-form__control">
                                                            <label class="col-form-label"><strong>{{$prescription_value['no_of_days']}}</strong></label>
                                                            </div>
                                                        </div>
                                                        <div class="d-md-none kt-margin-b-10"></div>
                                                    </div>
                                                </div>
                                            



                                                @endforeach   
                                           @endif
                                            </div>
                                        </div>
                                    </div>  
                                <!-- ! Input Repeter code   -->
                                    <!-- One additional row added below to adjust page height -->
                                </div>
                            </div>
                            <!-- !Second part -->

                                <!-- Third part -->
                                <div class="kt-section kt-section--first" style="border:#c4c4c4 1px solid;padding:3%;">
                                <div class="kt-section__body">
                                    <div class="form-group row">
                                    <span for="email_address" class="col-md-12  kt-widget__label">Medical Consumable Used</span>
                                    
                                    </div> 
                                <!-- Input Repeter code   -->
                                    <div id="kt_repeater_2">
                                        <div class="form-group form-group-last row" id="kt_repeater_2">
                                            <div data-repeater-list="mcu" class="col-lg-12">

                                            @if (isset($item_medical_consumable) && !empty($item_medical_consumable) && count($item_medical_consumable)>0)
                                                @foreach($item_medical_consumable as $key => $consumable_value)

                                                <div data-repeater-item class="form-group row align-items-center">
                                                    <div class="col-md-4">
                                                        <div class="kt-form__group--inline">
                                                            <div class="kt-form__label">
                                                                <label>Item</label>
                                                            </div>
                                                            <div class="kt-form__control">
                                                            <label class="col-form-label"><strong>{{ $consumable_value['medical_consumable']['inventorymaster']['item_name'] }}</strong></label>
                                                            </div>
                                                        </div>
                                                        <div class="d-md-none kt-margin-b-10"></div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="kt-form__group--inline">
                                                            <div class="kt-form__label">
                                                                <label class="kt-label m-label--single">Quantity</label>
                                                            </div>
                                                            <div class="kt-form__control">
                                                            <label class="col-form-label"><strong>{{ $consumable_value['quantity'] }}</strong></label>
                                                            </div>
                                                        </div>
                                                        <div class="d-md-none kt-margin-b-10"></div>
                                                    </div>

                                                    <div class="col-md-4">
                                                        <div class="kt-form__group--inline">
                                                            <div class="kt-form__label">
                                                                <label class="kt-label m-label--single">Unit</label>
                                                            </div>
                                                            <div class="kt-form__control">
                                                            <label class="col-form-label"><strong>{{ $consumable_value['unit']['unit'] }}</strong></label>
                                                            </div>
                                                        </div>
                                                        <div class="d-md-none kt-margin-b-10"></div>
                                                    </div>

                                                </div>

                                            @endforeach   
                                          @endif
                                            </div>
                                        </div>

                                    </div>  
                                <!-- ! Input Repeter code   -->
                                    <!-- One additional row added below to adjust page height -->
                                </div>
                            </div>
                            <!-- !Third part -->

                            <!-- Fourth part -->
                            <div class="kt-section kt-section--first" style="border:#c4c4c4 1px solid;padding:3%;">
                            <div class="kt-section__body">
                                <div class="form-group row">
                                <span for="email_address" class="col-md-12  kt-widget__label">Medical Tests</span>
                                </div> 
                            <!-- Input Repeter code   -->
                                <div id="kt_repeater_3">
                                    <div class="form-group form-group-last row" id="kt_repeater_3">
                                        <div data-repeater-list="mts" class="col-lg-12">

                                        @if (isset($item_medical_test) && !empty($item_medical_test) && count($item_medical_test)>0)
                                                @foreach($item_medical_test as $key => $test_value)

                                            <div data-repeater-item class="form-group row align-items-center">
                                                <div class="col-md-5">
                                                    <div class="kt-form__group--inline">
                                                        <div class="kt-form__label">
                                                            <label>Test</label>
                                                        </div>
                                                        <div class="kt-form__control">
                                                        <label class="col-form-label"><strong>{{ $test_value['test_name'] }}</strong></label>
                                                        </div>
                                                    </div>
                                                    <div class="d-md-none kt-margin-b-10"></div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="kt-form__group--inline">
                                                        <div class="kt-form__label">
                                                            <label class="kt-label m-label--single">Reffered Center</label>
                                                        </div>
                                                        <div class="kt-form__control"> 
                                                        <label class="col-form-label"><strong>{{ $test_value['center']['center'] }}</strong></label>
                                                        </div>
                                                    </div>
                                                    <div class="d-md-none kt-margin-b-10"></div>
                                                </div>

                                            </div>

                                        @endforeach   
                                        @endif
                                        
                                        </div>
                                    </div>

                                </div>  
                            <!-- ! Input Repeter code   -->
                                <!-- One additional row added below to adjust page height -->
                            </div>
                        </div>
                        <!-- !Fourth part -->

                    </div>

            {!! Form::close() !!}

                    <div class=" col-md-4">
                    {!! Form::open(['id' => 'add_form_third','name' => 'add_form_third', 'class' => 'kt-form ', 'files' => true, 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}

                        <div class="card" style="background-color: #f2f2f2;">
                            <div class="card-body">
                                <div class="row">
                                    <label class="col-md-12 kt-label">Chief Complaint <span class="form-info"> * </span></label>
                                    <textarea class="form-control" readonly name="cheif_complaint" id="cheif_complaint" rows="5" spellcheck="false" placeholder="Enter cheif complaint">{{ $item_brief_note[0]['cheif_complaint'] }}</textarea>
                                    <label class="col-md-12 pt-1 kt-label">
                                    <input type="checkbox" disabled name="cheif_complaint_status" id="cheif_complaint_status" class="" value="1"  {{ $item_brief_note[0]['cheif_complaint_status'] == 1 ? 'checked' : null }} />
                                    Highlight as important
                                    </label>
                                </div>

                                <div class="row pt-5 pb-2">
                                    <label class="col-md-12 kt-label">History of Present Illness(HOPI) </label>
                                    <textarea class="form-control" readonly name="history_of_present_illness" id="history_of_present_illness" rows="5" spellcheck="false" >{{ $item_brief_note[0]['history_of_present_illness'] }}</textarea>
                                    <label class="col-md-12 pt-1 kt-label">
                                    <input type="checkbox" disabled name="history_of_present_illness_status" id="history_of_present_illness_status" class="" value="1"  {{ $item_brief_note[0]['history_of_present_illness_status'] == 1 ? 'checked' : null }} />
                                    Highlight as important
                                    </label>
                                </div>

                                <div class="row pt-5 pb-2">
                                    <label class="col-md-12 kt-label">Past History</label>
                                    <textarea class="form-control" readonly name="past_history" id="past_history" rows="5" spellcheck="false" >{{ $item_brief_note[0]['past_history'] }}</textarea>
                                    <label class="col-md-12 pt-1 kt-label">
                                    <input type="checkbox" disabled name="past_history_status" id="past_history_status" class="" value="1"  {{ $item_brief_note[0]['past_history_status'] == 1 ? 'checked' : null }} />
                                    Highlight as important
                                    </label>
                                </div>

                                <div class="row pt-5 pb-5">
                                    <label class="col-md-12 kt-label">Physical Examiniation</label>
                                    <textarea class="form-control" readonly name="physical_examiniation" id="physical_examiniation" rows="5" spellcheck="false" placeholder="Enter cheif complaint">{{ $item_brief_note[0]['physical_examiniation'] }}</textarea>
                                    <label class="col-md-12 pt-1 kt-label">
                                    <input type="checkbox" disabled name="physical_examiniation_status" id="physical_examiniation_status" class="" value="1"  {{ $item_brief_note[0]['physical_examiniation_status'] == 1 ? 'checked' : null }} />
                                    Highlight as important
                                    </label>
                                </div>
                                <input type="hidden" name="diagnosis_id" id="diagnosis_id" value="{{ $id }}" />
                            </div>



                        </div>
                        <!--  Second part -->
                        {!! Form::close() !!}
                    </div>
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
        <div class="col-lg-1"></div></div>
@endsection
@section('scripts')

    <!--begin::Page Scripts(used by this page) -->
    <script src="{{ URL::asset('assets/frontend/js/scripts/appointment.js') }}"
            type="text/javascript"></script>

    <script src="{{ URL::asset('assets/frontend/js/validations/admin_users.js') }}"
            type="text/javascript"></script>
    <!--end::Page Scripts -->
@endsection
