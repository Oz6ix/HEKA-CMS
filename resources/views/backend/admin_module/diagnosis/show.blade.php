@extends('backend.layouts.modern')
<style>
    .col-form-label {
    margin-bottom: 1.5rem !important;
}
.h5 {
    margin-bottom: 0.1rem !important;
}
.card-header {
    padding: 0.35rem 1rem !important;
}
    .kt-widget__label {
    color: #48465b;
    font-weight: 600!important;
    font-size: 13px!important;
}
#kt_content {
    font-size: 13px!important;
}

.switch {
  position: relative;
  display: inline-block;
  width: 90px;
  height: 34px;
}

.switch {
  position: relative;
  display: inline-block;
  width: 90px;
  height: 34px;
}

.switch input {display:none;}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ca2222;
  -webkit-transition: .4s;
  transition: .4s;
   border-radius: 34px;
}

.slider:before {
  position: absolute;
  content: "";
  height: 26px;
  width: 26px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
  border-radius: 50%;
}

input:checked + .slider {
  background-color: #c28515;
}

input:focus + .slider {
  box-shadow: 0 0 1px #2196F3;
}

input:checked + .slider:before {
  -webkit-transform: translateX(26px);
  -ms-transform: translateX(26px);
  transform: translateX(55px);
}

/*------ ADDED CSS ---------*/
.slider:after
{
 content:'OFF';
 color: white;
 display: block;
 position: absolute;
 transform: translate(-50%,-50%);
 top: 50%;
 left: 50%;
 font-size: 10px;
 font-family: Verdana, sans-serif;
}

input:checked + .slider:after
{  
  content:'ON';
}

/*--------- END --------*/


</style>



@section('content')
    <!-- Messages section -->
    
    

    <div class="space-y-6">
        <div class="col-lg-12">

            <!--begin::Portlet-->
            <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                    <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                        <h2 class="text-base font-semibold text-slate-900">View Details</h2>
                    </div>
                    <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                        <a href="{{ url($url_prefix . '/diagnosis/list/'.$appointment_id) }}" class="inline-flex items-center rounded-md bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200">
                            <i class="fas fa-arrow-left"></i>
                            <span class="kt-hidden-mobile">Back to List</span>
                        </a>
                        <a href="{{ url($url_prefix . '/diagnosis/edit/'.$id) }}"
                           class="btn btn-default btn-icon-sm">
                            <i class="la la-edit"></i>
                            <span class="kt-hidden-mobile">Edit</span>
                        </a>&nbsp;&nbsp;
                        <a class="text-red-600 hover:text-red-900 text-sm" href="javascript:;"
                           onclick="delete_record('{{ url($url_prefix . '/diagnosis/delete/'.$appointment_id) }}');">
                            <i class="fas fa-trash"></i>
                            <span class="kt-hidden-mobile">Delete</span>
                        </a>
                    </div>
                </div>
    {!! Form::open(['id' => 'add_form_first','name' => 'add_form_first', 'class' => 'kt-form', 'files' => true, 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
    <div class="space-y-6">
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                
                <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
            <!-- First part -->
                    <div class="row ">                   
                        <div class="col-md-4">
                            <div>
                                <div>
                                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="block text-sm font-medium text-slate-700">
                                        </label>
                                        <div class="col-9 form-info">
                                         
                                        </div>
                                    </div>

                                      <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="col-4 col-form-label">Checkup_at
                                            <span class="text-red-500 text-xs">  </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ $item_basic[0]['checkup_at'] }}</strong></label>
                                        </div>
                                      </div> 


                                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="col-4 col-form-label">Height
                                            <span class="text-red-500 text-xs">  </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ $item_basic[0]['height'] .' '. ($item_basic[0]['height_unit']!=NULL?config('global.height')[$item_basic[0]['height_unit']] :'')}}</strong></label>
                                        </div>
                                      </div> 

                                      <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="col-4 col-form-label">Weight
                                            <span class="text-red-500 text-xs">  </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ $item_basic[0]['weight'] .' '. ($item_basic[0]['weight_unit']!=NULL?config('global.weight')[$item_basic[0]['weight_unit']] :'') }}</strong></label>

                                        </div>
                                      </div> 

                                      <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="col-4 col-form-label">BP
                                            <span class="text-red-500 text-xs">  </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ $item_basic[0]['systolic_bp'].' / '.$item_basic[0]['diastolic_bp'] }}</strong></label>
                                        </div>
                                      </div> 

                                      <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="col-4 col-form-label">Pulse
                                            <span class="text-red-500 text-xs">  </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ $item_basic[0]['pulse'] }}</strong></label>
                                        </div>
                                      </div> 
                                      <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="col-4 col-form-label">Temperature
                                            <span class="text-red-500 text-xs">  </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ $item_basic[0]['temperature'] .' '. ($item_basic[0]['temperature_unit']!=NULL?config('global.temperature')[$item_basic[0]['temperature_unit']] :'') }}</strong></label>
                                        </div>
                                      </div> 

                                      <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="col-4 col-form-label">SPO2
                                            <span class="text-red-500 text-xs">  </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ $item_basic[0]['spo2'] }}</strong></label>
                                        </div>
                                      </div> 

                                      <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="col-4 col-form-label">Respiration
                                            <span class="text-red-500 text-xs">  </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ $item_basic[0]['respiration'] }}</strong></label>
                                        </div>
                                      </div> 

                                      <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="col-4 col-form-label">RBS
                                            <span class="text-red-500 text-xs">  </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ $item_basic[0]['rbs'] }}</strong></label>
                                        </div>
                                      </div> 

                                    <!-- One additional row added below to adjust page height -->
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div>
                                <div>
                                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="block text-sm font-medium text-slate-700">
                                        </label>
                                        <div class="col-9 form-info">
                                        </div>
                                    </div>
                                    
                                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="col-4 col-form-label">Syptom Type
                                            <span class="text-red-500 text-xs">  </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ $item_diagnosis->symptom_type->symptom }}</strong></label>
                                        </div>
                                    </div> 
                                    
                                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="col-4 col-form-label">Symptom
                                            <span class="text-red-500 text-xs">  </span>
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{{ $item_basic[0]['symptom'] }}</strong></label>
                                        </div>
                                      </div> 

                                      <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="col-4 col-form-label">Description 
                                        </label>
                                        <div class="col-8">
                                        <label class="col-form-label"><strong>{!! $item_basic[0]['description'] !!}</strong></label>
                                        </div>
                                      </div> 

                                      <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
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
                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                        <label class="block text-sm font-medium text-slate-700">
                        </label>
                        <div class="col-9 form-info">
                        </div>
                    </div>
                        @foreach ($patient_details as $detail)
                        <div class="card" style="background-color: #f2f2f2;">
                            <div class="card-header" style="background-color:#e8e8e8;"><h5>Appointment Details</h5></div>
                            <div class="card-body">
                                <div class="space-y-6">
                                    <label for="email_address" class="col-md-6  kt-widget__label text-md-right">Case No.:</label>
                                    <label for="email_address" class="col-md-6  text-md-left">{{ $detail->case_number }}</label>                 
                                </div>

                                <div class="space-y-6">
                                    <label class="col-md-6  kt-widget__label text-md-right">Appointment Date:</label>
                                    <label class="col-md-6  text-md-left">{{ $detail->appointment_date }}</label>                 
                                </div>

                                <div class="space-y-6">
                                    <label class="col-md-6  kt-widget__label text-md-right">Consulting Doctor:</label>
                                    <label class="col-md-6  text-md-left">{{ $detail->staff_doctor->name }}</label>                 
                                </div>

                            </div>
                        </div>
                        <!--  Second part -->
                        <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                        </div>
                        <div class="card" style="background-color: #f2f2f2;">
                            <div class="card-header" style="background-color:#e8e8e8;"><h5>Patient Details </h5></div>
                            <div class="card-body">
                                <div class="space-y-6">
                                    <label for="email_address" class="col-md-6 kt-widget__label text-md-right">Patient ID:</label>
                                    <label for="email_address" class="col-md-6 text-md-left">{{$detail->patient->patient_code }}</label>                 
                                </div>

                                <div class="space-y-6">
                                    <label class="col-md-6 kt-widget__label text-md-right">Patient Name:</label>
                                    <label class="col-md-6 text-md-left">{{$detail->patient->name }}</label>                 
                                </div>
                                     
                                <div class="space-y-6">
                                    <label class="col-md-6 kt-widget__label text-md-right">Phone:</label>
                                    <label class="col-md-6 text-md-left"> {{ $detail->patient->phone }} </label>                 
                                </div>
                                <div class="space-y-6">
                                    <label class="col-md-6 kt-widget__label text-md-right">Email:</label>
                                    <label class="col-md-6 text-md-left"> {{ $detail->patient->email }} </label>                 
                                </div>
                                <div class="space-y-6">
                                    <label class="col-md-6 kt-widget__label text-md-right">Age:</label>
                                    <label class="col-md-6 text-md-left"> {{ $detail->patient->age_year .'.'.$detail->patient->age_month }} </label>                 
                                </div>
                                <div class="space-y-6">
                                    <label class="col-md-6 kt-widget__label text-md-right">Guardian Name:</label>
                                    <label class="col-md-6 text-md-left"> {{ $detail->patient->guardian_name }} </label>                 
                                </div>

                            </div>
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
    <div class="space-y-6">
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                 <div class="card-header ml-5"><h5>Diagnosis Details</h5></div>
                <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
            <!-- First part -->
                    <div class="row ">                   
                        <div class="col-md-8">
                            <div>
                                <div class="kt-section__body" id="jscontent">

                                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="block text-sm font-medium text-slate-700">Diagnosis
                                        <span class="text-red-500 text-xs"> * </span>
                                        </label>
                                        <div class="sm:col-span-3">
                                        <label class="col-form-label"><strong>{{ $item_diagnosis->diagnosis }}</strong></label>
                                        </div>
                                      </div> 

                                      <!-- <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="block text-sm font-medium text-slate-700">ICD Diagnosis
                                        <span class="text-red-500 text-xs"> * </span>
                                        </label>
                                        <div class="sm:col-span-3">
                                        <label class="switch">
                                        <label class="col-form-label"><strong>{{ ($item_diagnosis->icd_diagnosis == 1) ? 'On' : 'Off' }}</strong></label>
                                        </label>
                                        </div>
                                      </div>  -->

                                      <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="block text-sm font-medium text-slate-700">Treatment & Intervention
                                        <span class="text-red-500 text-xs"> * </span>
                                        </label>
                                        <div class="sm:col-span-3">
                                        <label class="col-form-label"><strong>{{ $item_diagnosis->treatment->title }}</strong></label>
                                        </div>
                                    </div> 

                                </div></div>
                                <!-- Second part -->
                                <div class="kt-section kt-section--first" style="border:#c4c4c4 1px solid;padding:3%;">
                                <div>
                                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
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
                                                    <div class="">
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

                                                    <div class="">
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

                                                    <div class="">
                                                        <div class="kt-form__group--inline">
                                                            <div class="kt-form__label">
                                                                <label class="kt-label m-label--single">Frequency</label>
                                                                <span class="text-red-500 text-xs"> * </span>
                                                            </div>
                                                            <div class="kt-form__control">
                                                            <label class="col-form-label"><strong>{{ $prescription_value['frequency']['frequency'] }}</strong></label>
                                                            </div>
                                                        </div>
                                                        <div class="d-md-none kt-margin-b-10"></div>
                                                    </div>

                                                    <div class="">
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
                                <div>
                                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
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
                                                            <label class="col-form-label"><strong>{{ $consumable_value['item_name'] }}</strong></label>
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
                            <div>
                                <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
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
                                <div class="space-y-6">
                                    <label class="col-md-12 kt-label">Chief Complaint <span class="text-red-500 text-xs"> * </span></label>
                                    <textarea class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" readonly name="cheif_complaint" id="cheif_complaint" rows="5" spellcheck="false" placeholder="Enter cheif complaint">{{ $item_brief_note[0]['cheif_complaint'] }}</textarea>
                                    <label class="col-md-12 pt-1 kt-label">
                                    <input type="checkbox" disabled name="cheif_complaint_status" id="cheif_complaint_status" class="" value="1"  {{ $item_brief_note[0]['cheif_complaint_status'] == 1 ? 'checked' : null }} />
                                    Highlight as important
                                    </label>
                                </div>

                                <div class="row pt-5 pb-2">
                                    <label class="col-md-12 kt-label">History of Present Illness(HOPI) </label>
                                    <textarea class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" readonly name="history_of_present_illness" id="history_of_present_illness" rows="5" spellcheck="false" >{{ $item_brief_note[0]['history_of_present_illness'] }}</textarea>
                                    <label class="col-md-12 pt-1 kt-label">
                                    <input type="checkbox" disabled name="history_of_present_illness_status" id="history_of_present_illness_status" class="" value="1"  {{ $item_brief_note[0]['history_of_present_illness_status'] == 1 ? 'checked' : null }} />
                                    Highlight as important
                                    </label>
                                </div>

                                <div class="row pt-5 pb-2">
                                    <label class="col-md-12 kt-label">Past History</label>
                                    <textarea class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" readonly name="past_history" id="past_history" rows="5" spellcheck="false" >{{ $item_brief_note[0]['past_history'] }}</textarea>
                                    <label class="col-md-12 pt-1 kt-label">
                                    <input type="checkbox" disabled name="past_history_status" id="past_history_status" class="" value="1"  {{ $item_brief_note[0]['past_history_status'] == 1 ? 'checked' : null }} />
                                    Highlight as important
                                    </label>
                                </div>

                                <div class="row pt-5 pb-5">
                                    <label class="col-md-12 kt-label">Physical Examiniation</label>
                                    <textarea class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" readonly name="physical_examiniation" id="physical_examiniation" rows="5" spellcheck="false" placeholder="Enter cheif complaint">{{ $item_brief_note[0]['physical_examiniation'] }}</textarea>
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
               
                <div class="kt-section kt-section--first" style="border:#c4c4c4 1px solid;padding:3%;">
                <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4" >
                    <div class="col-md-1" ></div>
                    <div class="col-md-3" >
                    <span for="email_address" class="col-md-12  kt-widget__label">Reports Uploaded :</span>
                    </div>
                    <div class="col-md-8" id="uploaded">
                    @foreach($item_reports as $report)
                    <div class="img-wrap ml-3"><a class="img" href="{{ url('public/uploads/patient/'.$report['report_name'])  }}" target="_blank" width="150" height="150" alt="pdf" ><i class="fa fa-file-pdf" aria-hidden="true" style="font-size: 60px;color:#BB0706"></i></a></div>
                   @endforeach
                    </div>
                </div>
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
@endsection
