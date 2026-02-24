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
/*------ DATALIST DROPDOWN HIDDER CSS ---------*/

input::-webkit-calendar-picker-indicator {
  display: none;
}
 input::-webkit-calendar-picker-indicator {
     opacity: 0;
  }
/*--------- END --------*/


</style>


@section('content')
    <!-- Messages section -->
    
    
    {!! Form::open(['id' => 'add_form_first','name' => 'add_form_first', 'class' => 'kt-form', 'files' => true, 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
    <div class="space-y-6">
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                    <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                        <h2 class="text-base font-semibold text-slate-900"></h2>
                    </div>
                    <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                        <a href="{{ url($url_prefix . '/diagnosis/list/'.$appointment_id) }}" class="inline-flex items-center rounded-md bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200">
                            <i class="fas fa-arrow-left"></i>
                            <span class="kt-hidden-mobile">Back to List</span>
                        </a>
                        <!-- <button type="button" class="inline-flex items-center rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500">
                                <i class="fas fa-check"></i>
                                <span class="kt-hidden-mobile">Save</span>
                            </button>
                        </div> -->
                    </div>
                </div>
                
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
                                        <label class="block text-sm font-medium text-slate-700">Checkup Time
                                            <span class="text-red-500 text-xs">  </span>
                                        </label>
                                        <div class="sm:col-span-3">
                                        <input type="datetime-local" name="checkup_at" id="event_datetime" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" value="{{ $items[0]['checkup_at'] }}" required>
                                        </div>
                                      </div> 


                                     <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="block text-sm font-medium text-slate-700">Height
                                            <span class="text-red-500 text-xs">  </span>
                                        </label>
                                        <div class="col-5">
                                            <input type="text" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="height" id="height" 
                                                   placeholder="Enter height "
                                                   value="{{ $items[0]['height']  }}"/>
                                            <input type="hidden" name="appointment_basic_id" value="{{ $items[0]['id']  }}"/>
                                            <input type="hidden" name="patient_id" value="{{ $items[0]['patient_id']  }}"/>
                                        </div>
                                        <div class="col-4">
                                        <select class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="height_unit" id="height_unit">
                                            @foreach(config('global.height') as $key => $value)
                                            <option value="{{ $key }}" {{ ($key == $items[0]['height_unit']) ? 'selected' : '' }} >{{ $value }}</option>
                                            @endforeach 
                                        </select> 
                                        </div>
                                      </div> 

                                      <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="block text-sm font-medium text-slate-700">Weight
                                            <span class="text-red-500 text-xs">  </span>
                                        </label>
                                        <div class="col-5">
                                            <input type="text" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="weight" id="weight"
                                                   placeholder="Enter weight "
                                                   value="{{ $items[0]['weight'] }}"/>
                                        </div>
                                        <div class="col-4">
                                        <select class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="weight_unit" id="weight_unit">
                                            @foreach(config('global.weight') as $key => $value)
                                            <option value="{{ $key }}" {{ ($key == $items[0]['weight_unit']) ? 'selected' : '' }} >{{ $value }}</option>
                                            @endforeach 
                                        </select> 
                                        </div>
                                      </div> 

                                      <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="block text-sm font-medium text-slate-700">BP
                                            <span class="text-red-500 text-xs">  </span>
                                        </label>
                                        <div class="col-4">
                                            <input type="text" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="systolic_bp" id="systolic_bp"
                                                   placeholder="Systolic value "
                                                   value="{{ $items[0]['systolic_bp'] }}"/>
                                        </div>
                                        <div class="col-4">
                                            <input type="text" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="diastolic_bp" id="diastolic_bp"
                                                   placeholder="Diastolic value"
                                                   value="{{ $items[0]['diastolic_bp'] }}"/>
                                        </div>
                                        <div class="col-1"></div>
                                      </div> 

                                      <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="block text-sm font-medium text-slate-700">Pulse
                                            <span class="text-red-500 text-xs">  </span>
                                        </label>
                                        <div class="sm:col-span-3">
                                            <input type="text" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="pulse" id="pulse"
                                                   placeholder="Enter pulse"
                                                   value="{{ $items[0]['pulse'] }}"/>
                                        </div>
                                      </div> 


                                      <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="block text-sm font-medium text-slate-700">Temperature
                                            <span class="text-red-500 text-xs">  </span>
                                        </label>
                                        <div class="col-5">
                                            <input type="text" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="temperature" id="temperature"
                                                   placeholder="Enter temperature"
                                                   value="{{ $items[0]['temperature'] }}"/>
                                        </div>
                                        <div class="col-4">
                                        <select class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="temperature_unit" id="temperature_unit">
                                            @foreach(config('global.temperature') as $key => $value)
                                            <option value="{{ $key }}" {{ ($key == $items[0]['temperature_unit']) ? 'selected' : '' }} >{{ $value }}</option>
                                            @endforeach 
                                        </select> 
                                        </div>
                                      </div>  
                                      <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="block text-sm font-medium text-slate-700">SPO2
                                            <span class="text-red-500 text-xs">  </span>
                                        </label>
                                        <div class="sm:col-span-3">
                                            <input type="text" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="spo2" id="spo2"
                                            placeholder="Enter SPO2" value="{{ $items[0]['spo2'] }}"/>
                                        </div>
                                      </div> 
                                      <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="block text-sm font-medium text-slate-700">Respiration
                                            <span class="text-red-500 text-xs">  </span>
                                        </label>
                                        <div class="sm:col-span-3">
                                            <input type="text" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="respiration" id="respiration"
                                                   placeholder="Enter respiration"
                                                   value="{{ $items[0]['respiration'] }}"/>
                                        </div>
                                      </div> 

                                      <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="block text-sm font-medium text-slate-700">RBS
                                            <span class="text-red-500 text-xs">  </span>
                                        </label>
                                        <div class="sm:col-span-3">
                                            <input type="text" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="rbs" id="rbs"
                                                   placeholder="Enter RBS"
                                                   value="{{ $items[0]['rbs'] }}"/>
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
                                        <label class="block text-sm font-medium text-slate-700">Syptom Type
                                            <span class="text-red-500 text-xs">  </span>
                                        </label>
                                        <div class="sm:col-span-3">
                                            <select name="symptom_type_id" id="symptom_type" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm">
                                                @foreach($symptom_item as $symptom_type)
                                                    <option value="{{ $symptom_type->id }}"
                                                        @if($patientDiagnosis->symptom_type->id == $symptom_type->id)
                                                            selected
                                                        @endif
                                                    >{{ $symptom_type->symptom }}</option>>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="block text-sm font-medium text-slate-700">Syptom Type
                                            <span class="text-red-500 text-xs">  </span>
                                        </label>
                                        <div class="sm:col-span-3">
                                             <select class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" id="select2_symptom" >
                                              <option value="">--- Select Syptom---</option>
                                                @foreach($symptom_item as $data)
                                                    <option value="{{$data['id']}}" {{ ($data['id'] == $items[0]['symptom_type_id']) ? 'selected' : '' }}>
                                                        {{ $data['symptom'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="symptom_type_id" id="symptom_type_id"
                                                   value="{{ $items[0]['symptom_type_id'] }}"/>
                                        </div>
                                    </div>  -->

                                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="block text-sm font-medium text-slate-700">Symptom
                                            <span class="text-red-500 text-xs">  </span>
                                        </label>
                                        <div class="sm:col-span-3">
                                            <input type="text" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="symptom" id="symptom"
                                                   placeholder="Enter symptom"
                                                   value="{{ $items[0]['symptom'] }}"/>
                                        </div>
                                      </div> 

                                      <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="block text-sm font-medium text-slate-700">Description 
                                        </label>
                                        <div class="sm:col-span-3">
                                          <textarea class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="description" id="description" rows="6" spellcheck="false" placeholder="Enter description">{!! $items[0]['description'] !!}</textarea>
                                        </div>
                                      </div> 

                                      <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="block text-sm font-medium text-slate-700">Notes 
                                        </label>
                                        <div class="sm:col-span-3">
                                          <textarea class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="note" id="note" rows="6" spellcheck="false" placeholder="Enter note">{!! $items[0]['note'] !!}</textarea>
                                            <input type="hidden" name="dummy" id="dummy" />
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
                                            <input type="text" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="diagnosis" id="diagnosis"
                                                   placeholder="Enter diagnosis "
                                                   value="{{ $item_diagnosis->diagnosis }}"/>
                                        </div>
                                    </div> 

                                    <!-- Temporary off ICD -->
                                      <!-- <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="block text-sm font-medium text-slate-700">ICD Diagnosis
                                        <span class="text-red-500 text-xs"> * </span>
                                        </label>
                                        <div class="sm:col-span-3">
                                        <label class="switch">
                                            <input type="checkbox" id="togBtn"  value="on" name="icd_diagnosis" {{ ($item_diagnosis->icd_diagnosis == 1) ? 'checked' : null }}>
                                            <div class="slider round"></div>
                                        </label>
                                        </div>
                                      </div>  -->

                                      <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="block text-sm font-medium text-slate-700">Treatment & Intervention
                                        <span class="text-red-500 text-xs"> * </span>
                                        </label>
                                        <div class="sm:col-span-3">
                                             <select class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" id="select2_ti" >
                                              <option value="">--- Select Syptom---</option>
                                              @foreach($treatment_item as $data)
                                                    <option value="{{ $data['id'] }}" {{ ($data['id'] == $item_diagnosis->treatment_and_intervention_id) ? 'selected' : '' }} >
                                                        {{ $data['title'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="treatment_and_intervention_id" id="treatment_and_intervention_id"
                                                   value="{{ $item_diagnosis->treatment_and_intervention_id }}"/>
                                        </div>
                                    </div> 

                                </div></div>
                                <!-- Second part -->
                                <div class="kt-section kt-section--first" style="border:#c4c4c4 1px solid;padding:3%;">
                                <div>
                                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                    <span for="email_address" class="col-md-12  kt-widget__label">Prescription x</span>
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
                                                                    <input type="hidden" name="prescription_id" value="{{$prescription_value['id']}}">

                                                                    <input type="text" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="drug_name" value="{{$prescription_value['drug_name']}}" onkeyup="CallFunctionReset(this)" onfocusout="CallFunction(this)"  list="medicine" placeholder="Enter drug name">
                                                                    <datalist id="medicine">
                                                                    @foreach($medicines as $data)
                                                                        <option>
                                                                            {{ $data->item_name }}
                                                                        </option>
                                                                    @endforeach
                                                                    </datalist>
                                                                    <input type="hidden" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="drug_id" value="{{$prescription_value['drug_id']}}" id="drug_id">

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
                                                                    <input type="text" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="quantity" value="{{$prescription_value['quantity']}}"  placeholder="Enter quantity">
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
                                                                    <select class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="unit_id" id="unit_id">
                                                                    <option disabled selected value="">
                                                                        select an option
                                                                        </option>                                    
                                                                            @foreach($unit_item as $data)
                                                                                    <option value="{{ $data['id'] }}" {{ ($data['id'] == $prescription_value['unit_id']) ? 'selected' : '' }}>
                                                                                        {{ $data['unit'] }}
                                                                                    </option>
                                                                            @endforeach
                            
                                                                    </select> 
                                                                    
                                                                <!--  <input type="hidden" name="unit_id" id="punit_id" value="{{ $prescription_value['unit_id'] }}"/> -->
        

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
                                                                    <select class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="frequency_id" id="frequency_id">
                                                                    <option disabled selected value="">
                                                                        select an option
                                                                        </option> 
                                                                        @foreach($frequency_item as $data)
                                                                            <option value="{{ $data['id'] }}"  {{ ($data['id'] == $prescription_value['frequency_id']) ? 'selected' : '' }} >
                                                                                {{ $data['frequency'] }}
                                                                            </option>
                                                                        @endforeach                                   
                                                                    </select> 
                                                                    <!-- <input type="hidden" name="frequency_id" id="frequency_id" value="{{ $prescription_value['frequency_id'] }}"/>  -->                          
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
                                                                    <input type="text" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="no_of_days" value="{{$prescription_value['no_of_days']}}"  placeholder="Enter number of days">
                                                                </div>
                                                            </div>
                                                            <div class="d-md-none kt-margin-b-10"></div>
                                                        </div>
                                                        <div class="col-md-1" style="padding-top: 25px;">
                                                            <a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-label-danger btn-bold">
                                                                <i class="fas fa-trash"></i></a>
                                                        </div>
                                                    </div>
                                                @endforeach   
                                            @else     
                                                <div data-repeater-item class="form-group row align-items-center">
                                                    <div class="col-md-3">
                                                        <div class="kt-form__group--inline">
                                                            <div class="kt-form__label">
                                                                <label>Drug Name</label>
                                                            </div>
                                                            <div class="kt-form__control">
                                                                <input type="hidden" name="prescription_id" value="">
                                                            <input type="text" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="drug_name" onkeyup="CallFunctionReset(this)" onfocusout="CallFunction(this)"  list="medicine" placeholder="Enter drug name">
                                                                <datalist id="medicine">
                                                                @foreach($medicines as $data)
                                                                    <option>
                                                                        {{ $data->item_name }}
                                                                    </option>
                                                                @endforeach
                                                                </datalist>
                                                                <input type="hidden" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="drug_id" value="0" id="drug_id">
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
                                                                <input type="text" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="quantity" placeholder="Enter quantity">
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
                                                                <select class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="unit_id" id="unit_id">
                                                                <option disabled selected value="">select an option</option>                                    
                                                                @foreach($unit_item as $data)
                                                                        <option value="{{ $data['id'] }}">
                                                                            {{ $data['unit'] }}
                                                                        </option>
                                                                @endforeach
                                                                </select> 
                                                                
                                                                <!-- <input type="hidden" name="unit_id" id="punit_id" value="{{ old('unit_id') }}"/> -->
    

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
                                                                <select class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="frequency_id" id="frequency_id">
                                                                <option disabled selected value="">
                                                                    select an option
                                                                    </option>                                    
                                                                    @foreach($frequency_item as $data)
                                                                        <option value="{{ $data['id'] }}">
                                                                            {{ $data['frequency'] }}
                                                                        </option>
                                                                    @endforeach                           
                                                                </select> 
                                                                <!-- <input type="hidden" name="frequency_id" id="frequency_id" value="{{ old('frequency_id') }}"/> -->                           
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
                                                                <input type="text" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="no_of_days" placeholder="Enter number of days">
                                                            </div>
                                                        </div>
                                                        <div class="d-md-none kt-margin-b-10"></div>
                                                    </div>
                                                    <div class="col-md-1" style="padding-top: 25px;">
                                                        <a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-label-danger btn-bold">
                                                            <i class="fas fa-trash"></i></a>
                                                    </div>
                                                </div>
                                            @endif

                                            </div>
                                        </div>
                                        <div class="form-group form-group-last row">
                                            <label class="col-lg-2 col-form-label"></label>
                                            <div class="col-lg-4">
                                                <a href="javascript:;" data-repeater-create="" class="btn btn-bold btn-sm btn-label-brand">
                                                    <i class="fas fa-plus"></i> Add More
                                                </a>
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
                                                                <input type="hidden" name="medical_consumable_id" value="{{ $consumable_value['id'] }}" >

                                                                <input type="text" value="{{ $consumable_value['item_name'] }}" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="item_name" onkeyup="CallFunctionConsumableReset(this)"  onfocusout="CallFunctionConsumable(this)"  list="item_name" placeholder="Enter Consumable name">
                                                                <datalist id="item_name">
                                                                @foreach($consumables as $data)
                                                                    <option>
                                                                        {{ $data['item_code'].'-'.$data['inventorymaster']['item_name'] }}
                                                                    </option>
                                                                @endforeach
                                                                </datalist>
                                                                <input type="hidden" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="item" value="{{ $consumable_value['item'] }}" id="item">
                                                            
                                                            
                                                            </div>
                                                        </div>
                                                        <div class="d-md-none kt-margin-b-10"></div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="kt-form__group--inline">
                                                            <div class="kt-form__label">
                                                                <label class="kt-label m-label--single">Quantity</label>
                                                            </div>
                                                            <div class="kt-form__control">
                                                                <input type="text" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="quantity" id="quantity" value="{{ $consumable_value['quantity'] }}" data-rowid=1  placeholder="Enter quantity">
                                                            </div>
                                                        </div>
                                                        <div class="d-md-none kt-margin-b-10"></div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="kt-form__group--inline">
                                                            <div class="kt-form__label">
                                                                <label class="kt-label m-label--single">Unit</label>
                                                            </div>
                                                            <div class="kt-form__control">
                                                                <select class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="unit_id" id="unit_id" data-rowid=1>
                                                                <option disabled selected value="">
                                                                    select an option
                                                                    </option>    
                                                                    @foreach($unit_item as $data)
                                                                            <option value="{{ $data['id'] }}" {{ ($data['id'] == $consumable_value['unit_id']) ? 'selected' : '' }} >
                                                                                {{ $data['unit'] }}
                                                                            </option>
                                                                    @endforeach
                                                                </select>  
                                                                <!-- <input type="hidden" name="unit_id" id="munit_id" value="{{ $consumable_value['unit_id'] }}"/> -->                          
                                                            </div>
                                                        </div>
                                                        <div class="d-md-none kt-margin-b-10"></div>
                                                    </div>

                                                    <div class="col-md-1" style="padding-top: 25px;">
                                                        <a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-label-danger btn-bold">
                                                            <i class="fas fa-trash"></i></a>
                                                    </div>
                                                </div>

                                            @endforeach   
                                            @else     

                                            <div data-repeater-item class="form-group row align-items-center">
                                                    <div class="col-md-4">
                                                        <div class="kt-form__group--inline">
                                                            <div class="kt-form__label">
                                                                <label>Item</label>
                                                            </div>
                                                            <div class="kt-form__control">
                                                                <input type="hidden" name="medical_consumable_id" value="" >
                                                                <input type="text" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="item_name" onkeyup="CallFunctionConsumableReset(this)"  onfocusout="CallFunctionConsumable(this)"  list="item_name" placeholder="Enter Consumable name">
                                                                <datalist id="item_name">
                                                                @foreach($consumables as $data)
                                                                    <option>
                                                                        {{ $data['item_code'].'-'.$data['inventorymaster']['item_name'] }}
                                                                    </option>
                                                                @endforeach
                                                                </datalist>
                                                                <input type="hidden" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="item" value="" id="item">

                                                            </div>
                                                        </div>
                                                        <div class="d-md-none kt-margin-b-10"></div>
                                                    </div>
                                                    <div class="col-md-3">
                                                        <div class="kt-form__group--inline">
                                                            <div class="kt-form__label">
                                                                <label class="kt-label m-label--single">Quantity</label>
                                                            </div>
                                                            <div class="kt-form__control">
                                                                <input type="text" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="quantity" id="quantity"  placeholder="Enter quantity">
                                                            </div>
                                                        </div>
                                                        <div class="d-md-none kt-margin-b-10"></div>
                                                    </div>

                                                    <div class="col-md-3">
                                                        <div class="kt-form__group--inline">
                                                            <div class="kt-form__label">
                                                                <label class="kt-label m-label--single">Unit</label>
                                                            </div>
                                                            <div class="kt-form__control">
                                                                <select class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="unit_id" data-rowid="" id="unit_id">
                                                                <option disabled selected value="">
                                                                    select an option
                                                                    </option>                                    
                                                                    @foreach($unit_item as $data)
                                                                            <option value="{{ $data['id'] }}" >
                                                                                {{ $data['unit'] }}
                                                                            </option>
                                                                    @endforeach
                                                                </select>  
                                                                <!-- <input type="hidden" name="unit_id" id="munit_id" value="{{ old('unit_id') }}"/> -->                          
                                                            </div>
                                                        </div>
                                                        <div class="d-md-none kt-margin-b-10"></div>
                                                    </div>

                                                    <div class="col-md-1" style="padding-top: 25px;">
                                                        <a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-label-danger btn-bold">
                                                            <i class="fas fa-trash"></i></a>
                                                    </div>
                                                </div>                                          
                                          @endif
                                            </div>
                                        </div>
                                        <div class="form-group form-group-last row">
                                            <label class="col-lg-2 col-form-label"></label>
                                            <div class="col-lg-4">
                                                <a href="javascript:;" data-repeater-create="" class="btn btn-bold btn-sm btn-label-brand">
                                                    <i class="fas fa-plus"></i> Add More
                                                </a>
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
                                                            <input type="hidden" name="medical_test_id"  value="{{ $test_value['id'] }}" >

                                                            <input type="text" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" value="{{ $test_value['test_name'] }}" name="test_name" onkeyup="CallFunctionTestReset(this)" onfocusout="CallFunctionTest(this)"  list="test" placeholder="Enter test name">
                                                                <datalist id="test" onclick="CallFunctionTest(this)">
                                                                @foreach($tests as $data)
                                                                    <option>
                                                                        {{ $data['code'].': '.$data['test'] }}
                                                                    </option>
                                                                @endforeach
                                                                </datalist>
                                                                <input type="hidden" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="radiology_test_id" value="{{ $test_value['radiology_test_id'] }}" id="radiology_test_id">
                                                                <input type="hidden" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="pathology_test_id" value="{{ $test_value['pathology_test_id'] }}" id="pathology_test_id">
                                                            
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
                                                            <select class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="reffered_center_id" id="reffered_center_id">
                                                            <option disabled selected value>
                                                                select an option
                                                                </option>    
                                                                @foreach($center_item as $data)
                                                                        <option value="{{ $data['id'] }}"  {{ ($data['id'] == $test_value['reffered_center_id']) ? 'selected' : '' }}  >
                                                                            {{ $data['center'] }}
                                                                        </option>
                                                                @endforeach                                 
                                                            </select>   
                                                            <!-- <input type="hidden" name="reffered_center_id" id="reffered_center_id" value="{{ $test_value['reffered_center_id'] }}"/> -->                          
                                                        </div>
                                                    </div>
                                                    <div class="d-md-none kt-margin-b-10"></div>
                                                </div>

                                                <div class="col-md-1" style="padding-top: 25px;">
                                                    <a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-label-danger btn-bold">
                                                        <i class="fas fa-trash"></i></a>
                                                </div>
                                            </div>

                                        @endforeach   
                                        @else     

                                        <div data-repeater-item class="form-group row align-items-center">
                                                <div class="col-md-5">
                                                    <div class="kt-form__group--inline">
                                                        <div class="kt-form__label">
                                                            <label>Test</label>
                                                        </div>
                                                        <div class="kt-form__control">
                                                            <input type="hidden" name="medical_test_id"  value="" >
                                                                <input type="text" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="test_name" onkeyup="CallFunctionTestReset(this)" onfocusout="CallFunctionTest(this)"  list="test" placeholder="Enter test name">
                                                                <datalist id="test">
                                                                @foreach($tests as $data)
                                                                    <option>
                                                                        {{ $data['code'].': '.$data['test'] }}
                                                                    </option>
                                                                @endforeach
                                                                </datalist>
                                                                <input type="hidden" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="radiology_test_id" value="0" id="radiology_test_id">
                                                                <input type="hidden" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="pathology_test_id" value="0" id="pathology_test_id">

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
                                                            <select class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="reffered_center_id" id="reffered_center_id">
                                                            <option disabled selected value>
                                                                select an option
                                                                </option>                                    
                                                                @foreach($center_item as $data)
                                                                        <option value="{{ $data['id'] }}">
                                                                            {{ $data['center'] }}
                                                                        </option>
                                                                @endforeach                             
                                                            </select>   
                                                            <!-- <input type="hidden" name="reffered_center_id" id="reffered_center_id" value="{{ old('reffered_center_id') }}"/> -->                          
                                                        </div>
                                                    </div>
                                                    <div class="d-md-none kt-margin-b-10"></div>
                                                </div>

                                                <div class="col-md-1" style="padding-top: 25px;">
                                                    <a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-label-danger btn-bold">
                                                        <i class="fas fa-trash"></i></a>
                                                </div>
                                            </div>
                                        
                                        @endif
                                        
                                        </div>
                                    </div>
                                    <div class="form-group form-group-last row">
                                        <label class="col-lg-2 col-form-label"></label>
                                        <div class="col-lg-4">
                                            <a href="javascript:;" data-repeater-create="" class="btn btn-bold btn-sm btn-label-brand">
                                                <i class="fas fa-plus"></i> Add More
                                            </a>
                                        </div>
                                    </div>
                                </div>  
                            <!-- ! Input Repeter code   -->
                                <!-- One additional row added below to adjust page height -->
                            </div>
                        </div>
                        <!-- !Fourth part -->
                        <input type="hidden" name="dummy" id="dummy" />
                        <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                            <div class="kt-form__actions">
                                <div class="space-y-6">
                                    <div class="col-lg-3"></div>
                                    @if($patient_details[0]['status']==3)
                                    <span class="text-danger p-3">Bill generated.So you can't update the details.  </span>
                                    @endif
                                    <div class="col-lg-6">

                                    @if($patient_details[0]['status']==3)
                                    <button type="submit" href="{{ url($url_prefix . '/diagnosis') }}" disabled class="btn btn-brand">Submit</button>
                                    <a type="reset" href="{{ url($url_prefix . '/diagnosis/list/'.$appointment_id) }}" class="btn btn-secondary">Cancel</a>
                                    @else
                                    <button type="submit" href="{{ url($url_prefix . '/diagnosis') }}" class="btn btn-brand">Submit</button>
                                    <a type="reset" href="{{ url($url_prefix . '/diagnosis/list/'.$appointment_id) }}" class="btn btn-secondary">Cancel</a>
                                    @endif

                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

            {!! Form::close() !!}

                    <div class=" col-md-4">
                    {!! Form::open(['id' => 'add_form_third','name' => 'add_form_third', 'class' => 'kt-form ', 'files' => true, 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}

                        <div class="card" style="background-color: #f2f2f2;">
                            <div class="card-body">
                                <div class="space-y-6">
                                    <label class="col-md-12 kt-label">Chief Complaint <span class="text-red-500 text-xs"> * </span></label>
                                    <textarea class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="cheif_complaint" id="cheif_complaint" rows="5" spellcheck="false" placeholder="Enter cheif complaint">{{ $item_brief_note[0]['cheif_complaint'] }}</textarea>
                                    <label class="col-md-12 pt-1 kt-label">
                                    <input type="checkbox" name="cheif_complaint_status" id="cheif_complaint_status" class="" value="1"  {{ $item_brief_note[0]['cheif_complaint_status'] == 1 ? 'checked' : null }} />
                                    Highlight as important
                                    </label>
                                </div>

                                <div class="row pt-5 pb-2">
                                    <label class="col-md-12 kt-label">History of Present Illness(HOPI) </label>
                                    <textarea class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="history_of_present_illness" id="history_of_present_illness" rows="5" spellcheck="false" >{{ $item_brief_note[0]['history_of_present_illness'] }}</textarea>
                                    <label class="col-md-12 pt-1 kt-label">
                                    <input type="checkbox" name="history_of_present_illness_status" id="history_of_present_illness_status" class="" value="1"  {{ $item_brief_note[0]['history_of_present_illness_status'] == 1 ? 'checked' : null }} />
                                    Highlight as important
                                    </label>
                                </div>

                                <div class="row pt-5 pb-2">
                                    <label class="col-md-12 kt-label">Past History</label>
                                    <textarea class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="past_history" id="past_history" rows="5" spellcheck="false" >{{ $item_brief_note[0]['past_history'] }}</textarea>
                                    <label class="col-md-12 pt-1 kt-label">
                                    <input type="checkbox" name="past_history_status" id="past_history_status" class="" value="1"  {{ $item_brief_note[0]['past_history_status'] == 1 ? 'checked' : null }} />
                                    Highlight as important
                                    </label>
                                </div>

                                <div class="row pt-5 pb-5">
                                    <label class="col-md-12 kt-label">Physical Examiniation</label>
                                    <textarea class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="physical_examiniation" id="physical_examiniation" rows="5" spellcheck="false" placeholder="Enter cheif complaint">{{ $item_brief_note[0]['physical_examiniation'] }}</textarea>
                                    <label class="col-md-12 pt-1 kt-label">
                                    <input type="checkbox" name="physical_examiniation_status" id="physical_examiniation_status" class="" value="1"  {{ $item_brief_note[0]['physical_examiniation_status'] == 1 ? 'checked' : null }} />
                                    Highlight as important
                                    </label>
                                </div>
                                <input type="hidden" name="diagnosis_id" id="diagnosis_id" value="{{ $id }}" />
                            </div>


                        <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                            <div class="kt-form__actions">
                                <div class="space-y-6">
                                   
                                    <div class="col-lg-12">
                                    <button type="submit" href="{{ url($url_prefix . '/diagnosis') }}" class="btn btn-brand m-3">Submit</button>
                                    <a type="reset" href="{{ url($url_prefix . '/diagnosis/list/'.$id) }}" class="btn btn-secondary">Cancel</a>

                                    </div>
                                </div>
                            </div>
                        </div>

                        </div>
                        <!--  Second part -->
                        {!! Form::close() !!}
                    </div>
        </div>
         <!-- End Patient Details $item_reports -->



            <div class="kt-section kt-section--first" style="border:#c4c4c4 1px solid;padding:3%;">
                <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4" >
                    <div class="col-md-1" ></div>
                    
                    <div class="col-md-3" >
                    <span for="email_address" class="col-md-12  kt-widget__label">Reports Uploaded :</span>
                    </div>
                    <div class="col-md-8" id="uploaded">
                        @if(!empty($item_reports))
                    @foreach($item_reports as $report)
                    <div class="img-wrap ml-3"><a class="img" href="{{ url('public/uploads/patient/'.$report['report_name'])  }}" target="_blank" width="150" height="150" alt="pdf" ><i class="fa fa-file-pdf" aria-hidden="true" style="font-size: 60px;color:#BB0706"></i></a></div>
                   @endforeach
                   
                        @else
                            <span>NIL</span>
                        @endif
                    </div>
                </div>
            </div>




                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>

 
@endsection
@section('scripts')

<!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
 -->
 <script  type="text/javascript">

function CallFunction(drugname) {
        var medicine= drugname.value;
        var drugname= drugname.name;
        var second_name=drugname.split('[');
        index=second_name[1].split(']')[0];
//alert(textbox.name);
            $.ajax({   
                url: "{{ route('ajax_get_medicine_id') }}"+'/'+ medicine,                         
                type: "GET",
                cache: false,
                success: function (data) {
                   $("input[name='prescription["+index+"][drug_id]']").val(data.medicine_id);
                    
                }
            });
}               
function CallFunctionReset(drugname) {
        var drugname= drugname.name;
        var second_name=drugname.split('[');
        index=second_name[1].split(']')[0];
        $("input[name='prescription["+index+"][drug_id]']").val("0");
        $("input[name='prescription["+index+"][quantity]']").val("");
        $("input[name='prescription["+index+"][unit_id]']").val("");
        $("input[name='prescription["+index+"][frequency_id]']").val("");
        $("input[name='prescription["+index+"][no_of_days]']").val("");
}               
function CallFunctionTestReset(testname) {
        var testname= testname.name;
        var second_name=testname.split('[');
        index=second_name[1].split(']')[0];
        $("input[name='mts["+index+"][radiology_test_id]']").val("0");
        $("input[name='mts["+index+"][pathology_test_id]']").val("0");
}               
function CallFunctionTest(testname) {
        var test= testname.value;
        var testname= testname.name;
        var second_name=testname.split('[');
        index=second_name[1].split(']')[0];
        var test_code=test.split(':')[0];
        if(test.slice(0, 2)=='PT'){
           // alert(test_code);
            $.ajax({   
                url: "{{ route('ajax_get_pathology_test_id') }}"+'/'+ test_code,                         
                type: "GET",
                cache: false,
                success: function (data) {
                   $("input[name='mts["+index+"][pathology_test_id]']").val(data.test_id);
                }
            }); 
        }
        if(test.slice(0, 2)=='RD'){
            $.ajax({   
                url: "{{ route('ajax_get_radiology_test_id') }}"+'/'+ test_code,                         
                type: "GET",
                cache: false,
                success: function (data) {
                   $("input[name='mts["+index+"][radiology_test_id]']").val(data.test_id);
                }
            }); 
        }
}               
function CallFunctionConsumable(consumableName) {
    
        var consumable= consumableName.value;
        var count = (consumable.match(/\-/g) || []).length;
        
        
        var consumableName= consumableName.name;
        var second_name=consumableName.split('[');
        index=second_name[1].split(']')[0];

        

         var consumable_code=consumable.split('-');
         var item_code=consumable_code[0]+'-'+consumable_code[1];
        //alert(JSON.stringify(index));exit();


        if(count>=2){
            $.ajax({   
                url: "{{ route('ajax_get_consumable_id') }}"+'/'+ item_code,                         
                type: "GET",
                cache: false,
                success: function (data) {
                    //alert(data);
                    if(data.consumable_id!=0){
                   $("input[name='mcu["+index+"][item]']").val(data.consumable_id);
                    }
                    else{
                        swal.fire({
                            title: 'Please select items from the list',
                            type: 'warning',
                            });
                            }
                }
            }); 
        }
        else{
            swal.fire({
                    title: 'Please select items from the list',
                    type: 'warning',
                    });
        }
     
}               
function CallFunctionConsumableReset(consumablename) {
        var consumable= consumablename.name;
        var second_name=consumable.split('[');
        index=second_name[1].split(']')[0];
        //alert(JSON.stringify(index));

        //$("input[name='mcu["+index+"][tests]']").val("");
        $("input[name='mcu["+index+"][item]']").val("0");
        $("input[name='mcu["+index+"][quantity]']").val("");
        $("input[name='mcu["+index+"][unit_id]']").val("");
}   




$(document).ready(function () {
    $("form[name='add_form_second']").submit(function(event) {
                event.preventDefault();
                                
//alert(JSON.stringify(datap));
var validator = $("#add_form_second").validate();
/* First Repeater validation */
if(document.getElementsByName('prescription[0][drug_name]').length>0){
var datap= $('#kt_repeater_1').repeaterVal();
if((datap.prescription).length>0){
for(i=0;i<(datap.prescription).length;i++){
    if(datap['prescription'][i]['drug_name']!=""||datap['prescription'][i]['quantity']!=""||datap['prescription'][i]['unit_id']!=null||datap['prescription'][i]['frequency_id']!=null||datap['prescription'][i]['no_of_days']!=""){
        //alert(JSON.stringify((datap.prescription).length));
                var prescription = $('input[name^="prescription"]');
                var prescriptions = $('select[name^="prescription"]');
                    
                    prescription.filter('input[name$="[drug_name]"]').each(function() {
                        $(this).rules("add", {
                            required: true,
                            messages: {
                                required: "Drug is Mandatory"
                            }
                        });
                    });
                    prescription.filter('input[name$="[quantity]"]').each(function() {
                        $(this).rules("add", {
                            required: true,
                            digits: true,
                            messages: {
                                required: "quantity is Mandatory",
                                digits: "Quantity must be a number"
                            }
                        });
                    });
                    prescriptions.filter('select[name$="[unit_id]"]').each(function() {
                        $(this).rules("add", {
                            required: true,
                            messages: {
                                required : 'Unit is Mandatory',
                            }
                        });
                    });
                    prescriptions.filter('select[name$="[frequency_id]"]').each(function() {
                        $(this).rules("add", {
                            required: true,
                            messages: {
                                required : 'Frequency is Mandatory',
                            }
                        });
                    });

                    prescription.filter('input[name$="[no_of_days]"]').each(function() {
                        $(this).rules("add", {
                            required: true,
                            digits: true,
                            messages: {
                                digits: "Quantity must be a number",
                                required: "Number of days is Mandatory"
                            }
                        });
                    });
    }
}
}
}
/* First Repeater validation */


/* Second Repeater validation */
if(document.getElementsByName('mcu[0][item]').length>0){
var datam= $('#kt_repeater_2').repeaterVal();
if((datam.mcu).length>0){
for(i=0;i<(datam.mcu).length;i++){
    if(datam['mcu'][i]['quantity']!=""||datam['mcu'][i]['item']!=""||datam['mcu'][i]['unit_id']!=null){
                    var mcu = $('input[name^="mcu"]');
                    var mcus = $('select[name^="mcu"]');
                    mcu.filter('input[name$="[quantity]"]').each(function() {
                        $(this).rules("add", {
                            required: true,
                            digits: true,
                            messages: {
                                required: "quantity is Mandatory",
                                digits: "Quantity must be a number"
                            }
                        });
                    });
                    mcu.filter('input[name$="[item]"]').each(function() {
                        $(this).rules("add", {
                            required: true,
                            messages: {
                                required : 'Item is Mandatory',
                            }
                        });
                    });
                    mcus.filter('select[name$="[unit_id]"]').each(function() {
                        $(this).rules("add", {
                            required: true,
                            messages: {
                                required : 'Unit is Mandatory',
                            }
                        });
                    });
    }
}
}
}
/* ! Second Repeater validation */
/* Third Repeater validation  test_name reffered_center_id */
//alert(JSON.stringify((datat.mts).length));
if(document.getElementsByName('mts[0][test_name]').length>0){
var datat= $('#kt_repeater_3').repeaterVal();
if((datat.mts).length>0){
for(i=0;i<(datat.mts).length;i++){
    if(datat['mts'][i]['test_name']!=""||datat['mts'][i]['reffered_center_id']!=null){
                    var mts = $('input[name^="mts"]');
                    var mtss = $('select[name^="mts"]');
                    mts.filter('input[name$="[test_name]"]').each(function() {
                        $(this).rules("add", {
                            required: true,
                            messages: {
                                required: "Test Name is Mandatory"
                            }
                        });
                    });
                    mtss.filter('select[name$="[reffered_center_id]"]').each(function() {
                        $(this).rules("add", {
                            required: true,
                            messages: {
                                required : 'Reffered Center is Mandatory',
                            }
                        });
                    });
    }
}
}
}
/* ! Third Repeater validation */
form_res = validator.form();


/* Validation End */


                var diagnosis_id = $('#diagnosis_id').val();
                
				URL = "{{ route('update_diagnosis') }}";
               
            if(form_res == true){
                
                var formData1 = $('#add_form_first').serialize();
                var formData2 = $('#add_form_second').serialize();
                var formData3 = $('#add_form_third').serialize();
                var formData = formData1+formData2+formData3;
$('#kt_repeater_2').repeater({
errorMessage: true,
errorMessageClass: 'error_message',

});
              
                $.ajax({
                    type: 'POST',
                    paramName: "file",
                    url: URL,
                    data: formData,
                    success: function(result){
                       // alert(JSON.stringify(result));
                        $('#diagnosis_id').val(result.diagnosis_id);
                        if(result.status == "success"){
                            $.notify({
                                message: result.message,
                                icon: 'flaticon-bar-chart'
                            },{
                                type: 'success',
                                allow_dismiss: true,
                                placement: {
                                    from: "top",
                                    align: "right"
                                },
                            });
                            setTimeout(function () {
                                location.reload()
                            }, 1000);
                        }
                        else {
                            var datav = result['validation'].toString();
                            datav=datav.split(',');
                            $.each( datav, function( key, value ) {
                            $.notify({ message: value,icon: 'flaticon-bar-chart'},
                                    { type: 'danger', allow_dismiss: true,});
                                }); 
                        }
                        
                    },
                    error:function (result) {
                        
                        console.log("error");
                    }
                });
            }
    });

    $("form[name='add_form_third']").submit(function(event) {
                event.preventDefault();
                var validator = $("#add_form_third").validate();
                form_res = validator.form();
				URL = "{{ route('update_brief_note') }}";
                
            if(form_res == true){
                
                var formData1 = $('#add_form_first').serialize();
                var formData2 = $('#add_form_second').serialize();
                var formData3 = $('#add_form_third').serialize();
                var formData = formData1+formData2+formData3;
                //alert(JSON.stringify(formData)); exit();end();
                $.ajax({
                    type: 'POST',
                    paramName: "file",
                    url: URL,
                    data: formData,
                    success: function(result){
                       // alert(JSON.stringify(result));
                        if(result.status == "success"){
                            $.notify({
                                message: result.message,
                                icon: 'flaticon-bar-chart'
                            },{
                                type: 'success',
                                allow_dismiss: true,
                                placement: {
                                    from: "top",
                                    align: "right"
                                },
                            });
                            /* setTimeout(function () {
                                window.location.href = "{{ url('am/patient') }}"; 
                            }, 2000); */
                        }
                        else {
                            var datav = result['validation'].toString();
                            datav=datav.split(',');
                            $.each( datav, function( key, value ) {
                            $.notify({ message: value,icon: 'flaticon-bar-chart'},
                                    { type: 'danger', allow_dismiss: true,});
                                }); 
                        }
                        
                    },
                    error:function (result) {
                        
                        console.log("error");
                    }
                });
            }
    });    
});
</script>
<!--begin::Page Scripts(used by this page) -->  
    <script src="{{ URL::asset('assets/backend/js/demo1/pages/crud/forms/widgets/form-repeater.js') }}" type="text/javascript"></script> 
   <script src="{{ URL::asset('assets/backend/js/validations/diagnosis.js') }}" type="text/javascript"></script>     
    <script src="{{ URL::asset('assets/backend/js/scripts/diagnosis.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('assets/backend/js/demo1/pages/crud/forms/widgets/bootstrap-datepicker.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('assets/backend/js/demo1/pages/crud/forms/widgets/summernote.js') }}" type="text/javascript"></script>
<!--end::Page Scripts -->
@endsection
