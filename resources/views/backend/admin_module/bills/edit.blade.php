@extends('backend.layouts.admin')
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

@section('breadcrumb')    
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ url($url_prefix . '/appointment') }}" class="kt-subheader__breadcrumbs-link">
        Appointments </a>
        <span class="kt-subheader__breadcrumbs-separator" ></span>
    <a  href="{{ url($url_prefix . '/diagnosis/list/'.$appointment_id) }}"  class="kt-subheader__breadcrumbs-link">
    Diagnosis </a>
    <span class="kt-subheader__breadcrumbs-separator" ></span>

    <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Edit</span>
@endsection
@section('content')
    <!-- Messages section -->
    @include('backend.layouts.includes.notification_alerts')
    <div class="alert alert-light alert-elevate" role="alert">
        <div class="alert-icon"><i class="flaticon-information kt-font-brand"></i></div>
        <div class="alert-text">
            Add diagnosis details.
        </div>
    </div>
    {!! Form::open(['id' => 'add_form_first','name' => 'add_form_first', 'class' => 'kt-form', 'files' => true, 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="kt-portlet kt-portlet--last kt-portlet--head-lg kt-portlet--responsive-mobile"
                 id="kt_page_portlet">
                <div class="kt-portlet__head kt-portlet__head--lg">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title"></h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <a href="{{ url($url_prefix . '/diagnosis/list/'.$appointment_id) }}" class="btn btn-clean kt-margin-r-10">
                            <i class="la la-arrow-left"></i>
                            <span class="kt-hidden-mobile">Back to List</span>
                        </a>
                        <!-- <div class="btn-group">
                            <button type="button" class="btn btn-brand button-submit" id="add_button">
                                <i class="la la-check"></i>
                                <span class="kt-hidden-mobile">Save</span>
                            </button>
                        </div> -->
                    </div>
                </div>
                
                <div class="kt-portlet__body">
            <!-- First part -->
                    <div class="row ">                   
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
                                        <label class="col-3 col-form-label text-md-right">Height
                                            <span class="form-info">  </span>
                                        </label>
                                        <div class="col-9">
                                            <input type="text" class="form-control" name="height" id="height"
                                                   placeholder="Enter height "
                                                   value="{{ $item_basic[0]['height']  }}"/>
                                            <input type="hidden" name="appointment_basic_id" value="{{ $item_basic[0]['id']  }}"/>
                                            <input type="hidden" name="patient_id" value="{{ $item_basic[0]['patient_id']  }}"/>
                                        </div>
                                      </div> 

                                      <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Weight
                                            <span class="form-info">  </span>
                                        </label>
                                        <div class="col-9">
                                            <input type="text" class="form-control" name="weight" id="weight"
                                                   placeholder="Enter weight "
                                                   value="{{ $item_basic[0]['weight'] }}"/>
                                        </div>
                                      </div> 

                                      <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">BP
                                            <span class="form-info">  </span>
                                        </label>
                                        <div class="col-9">
                                            <input type="text" class="form-control" name="bp" id="bp"
                                                   placeholder="Enter bp "
                                                   value="{{ $item_basic[0]['bp'] }}"/>
                                        </div>
                                      </div> 

                                      <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Pulse
                                            <span class="form-info">  </span>
                                        </label>
                                        <div class="col-9">
                                            <input type="text" class="form-control" name="pulse" id="pulse"
                                                   placeholder="Enter pulse"
                                                   value="{{ $item_basic[0]['pulse'] }}"/>
                                        </div>
                                      </div> 


                                      <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Temperature
                                            <span class="form-info">  </span>
                                        </label>
                                        <div class="col-9">
                                            <input type="text" class="form-control" name="temperature" id="temperature"
                                                   placeholder="Enter temperature"
                                                   value="{{ $item_basic[0]['temperature'] }}"/>
                                        </div>
                                      </div> 

                                      <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Respiration
                                            <span class="form-info">  </span>
                                        </label>
                                        <div class="col-9">
                                            <input type="text" class="form-control" name="respiration" id="respiration"
                                                   placeholder="Enter respiration"
                                                   value="{{ $item_basic[0]['respiration'] }}"/>
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
                                        <label class="col-3 col-form-label text-md-right">Syptom Type
                                            <span class="form-info">  </span>
                                        </label>
                                        <div class="col-9">
                                             <select class="form-control kt-select2" id="select2_symptom" >
                                              <option value="">--- Select Syptom---</option>
                                                @foreach($symptom_item as $data)
                                                    <option value="{{ $data['id'] }}" {{ ($data['id'] == $item_basic[0]['symptom_type_id']) ? 'selected' : '' }}>
                                                        {{ $data['symptom'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="symptom_type_id" id="symptom_type_id"
                                                   value="{{ $item_basic[0]['symptom_type_id'] }}"/>
                                        </div>
                                    </div> 

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Symptom
                                            <span class="form-info">  </span>
                                        </label>
                                        <div class="col-9">
                                            <input type="text" class="form-control" name="symptom" id="symptom"
                                                   placeholder="Enter symptom"
                                                   value="{{ $item_basic[0]['symptom'] }}"/>
                                        </div>
                                      </div> 

                                      <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Description 
                                        </label>
                                        <div class="col-9">
                                          <textarea class="form-control" name="description" id="description" rows="5" spellcheck="false" placeholder="Enter description">{!! $item_basic[0]['description'] !!}</textarea>
                                        </div>
                                      </div> 

                                      <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Notes 
                                        </label>
                                        <div class="col-9">
                                          <textarea class="form-control" name="note" id="note" rows="5" spellcheck="false" placeholder="Enter note">{!! $item_basic[0]['note'] !!}</textarea>
                                            <input type="hidden" name="dummy" id="dummy" />
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
                        <div class="card" style="background-color: #f2f2f2;">
                            <div class="card-header" style="background-color:#e8e8e8;"><h5>Patient Details </h5></div>
                            <div class="card-body">
                                <div class="row">
                                    <label for="email_address" class="col-md-6 kt-widget__label text-md-right">Patient ID:</label>
                                    <label for="email_address" class="col-md-6 text-md-left">{{$detail->patient->patient_code }}</label>                 
                                </div>

                                <div class="row">
                                    <label class="col-md-6 kt-widget__label text-md-right">Patient Name:</label>
                                    <label class="col-md-6 text-md-left">{{$detail->patient->name }}</label>                 
                                </div>
                                     
                                <div class="row">
                                    <label class="col-md-6 kt-widget__label text-md-right">Phone:</label>
                                    <label class="col-md-6 text-md-left"> {{ $detail->patient->phone }} </label>                 
                                </div>
                                <div class="row">
                                    <label class="col-md-6 kt-widget__label text-md-right">Email:</label>
                                    <label class="col-md-6 text-md-left"> {{ $detail->patient->email }} </label>                 
                                </div>
                                <div class="row">
                                    <label class="col-md-6 kt-widget__label text-md-right">Age:</label>
                                    <label class="col-md-6 text-md-left"> {{ $detail->patient->age_year .'.'.$detail->patient->age_month }} </label>                 
                                </div>
                                <div class="row">
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
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="kt-portlet kt-portlet--last kt-portlet--head-lg kt-portlet--responsive-mobile"
                 id="kt_page_portlet">
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
                                            <input type="text" class="form-control" name="diagnosis" id="diagnosis"
                                                   placeholder="Enter diagnosis "
                                                   value="{{ $item_diagnosis->diagnosis }}"/>
                                        </div>
                                      </div> 

                                      <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">ICD Diagnosis
                                        <span class="form-info"> * </span>
                                        </label>
                                        <div class="col-9">
                                        <label class="switch">
                                            <input type="checkbox" id="togBtn"  value="on" name="icd_diagnosis" {{ ($item_diagnosis->icd_diagnosis == 1) ? 'checked' : null }}>
                                            <div class="slider round"></div>
                                        </label>
                                        </div>
                                      </div> 

                                      <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Treatment & Intervention
                                        <span class="form-info"> * </span>
                                        </label>
                                        <div class="col-9">
                                             <select class="form-control kt-select2" id="select2_ti" >
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
                                                                <input type="text" class="form-control" name="drug_name" value="{{$prescription_value['drug_name']}}" placeholder="Enter drug name">
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
                                                                <input type="text" class="form-control" name="quantity" value="{{$prescription_value['quantity']}}"  placeholder="Enter quantity">
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
                                                                <select class="form-control" name="unit_id" id="unit_id">
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

                                                    <div class="col-md-2">
                                                        <div class="kt-form__group--inline">
                                                            <div class="kt-form__label">
                                                                <label class="kt-label m-label--single">Frequency</label>
                                                                <span class="form-info"> * </span>
                                                            </div>
                                                            <div class="kt-form__control">
                                                                <select class="form-control" name="frequency_id" id="frequency_id">
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

                                                    <div class="col-md-2">
                                                        <div class="kt-form__group--inline">
                                                            <div class="kt-form__label">
                                                                <label class="kt-label m-label--single">No. of Days</label>
                                                            </div>
                                                            <div class="kt-form__control">
                                                                <input type="text" class="form-control" name="no_of_days" value="{{$prescription_value['no_of_days']}}"  placeholder="Enter number of days">
                                                            </div>
                                                        </div>
                                                        <div class="d-md-none kt-margin-b-10"></div>
                                                    </div>
                                                    <div class="col-md-1" style="padding-top: 25px;">
                                                        <a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-label-danger btn-bold">
                                                            <i class="la la-trash-o"></i></a>
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
                                                                <input type="text" class="form-control" name="drug_name" placeholder="Enter drug name">
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
                                                                <input type="text" class="form-control" name="quantity" placeholder="Enter quantity">
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
                                                                <select class="form-control" name="unit_id" id="unit_id">
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

                                                    <div class="col-md-2">
                                                        <div class="kt-form__group--inline">
                                                            <div class="kt-form__label">
                                                                <label class="kt-label m-label--single">Frequency</label>
                                                                <span class="form-info"> * </span>
                                                            </div>
                                                            <div class="kt-form__control">
                                                                <select class="form-control" name="frequency_id" id="frequency_id">
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

                                                    <div class="col-md-2">
                                                        <div class="kt-form__group--inline">
                                                            <div class="kt-form__label">
                                                                <label class="kt-label m-label--single">No. of Days</label>
                                                            </div>
                                                            <div class="kt-form__control">
                                                                <input type="text" class="form-control" name="no_of_days" placeholder="Enter number of days">
                                                            </div>
                                                        </div>
                                                        <div class="d-md-none kt-margin-b-10"></div>
                                                    </div>
                                                    <div class="col-md-1" style="padding-top: 25px;">
                                                        <a href="javascript:;" data-repeater-delete="" class="btn-sm btn btn-label-danger btn-bold">
                                                            <i class="la la-trash-o"></i></a>
                                                    </div>
                                                </div>
                                            @endif

                                            </div>
                                        </div>
                                        <div class="form-group form-group-last row">
                                            <label class="col-lg-2 col-form-label"></label>
                                            <div class="col-lg-4">
                                                <a href="javascript:;" data-repeater-create="" class="btn btn-bold btn-sm btn-label-brand">
                                                    <i class="la la-plus"></i> Add More
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
                                                                <input type="text" class="form-control" name="item" value="{{ $consumable_value['item'] }}" data-rowid=1 placeholder="Enter item">
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
                                                                <input type="text" class="form-control" name="quantity" id="quantity" value="{{ $consumable_value['quantity'] }}" data-rowid=1  placeholder="Enter quantity">
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
                                                                <select class="form-control" name="unit_id" id="unit_id" data-rowid=1>
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
                                                            <i class="la la-trash-o"></i></a>
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
                                                                <input type="text" class="form-control" name="item" data-rowid="" placeholder="Enter item">
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
                                                                <input type="text" class="form-control" name="quantity" id="quantity"  placeholder="Enter quantity">
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
                                                                <select class="form-control" name="unit_id" data-rowid="" id="unit_id">
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
                                                            <i class="la la-trash-o"></i></a>
                                                    </div>
                                                </div>                                          
                                          @endif
                                            </div>
                                        </div>
                                        <div class="form-group form-group-last row">
                                            <label class="col-lg-2 col-form-label"></label>
                                            <div class="col-lg-4">
                                                <a href="javascript:;" data-repeater-create="" class="btn btn-bold btn-sm btn-label-brand">
                                                    <i class="la la-plus"></i> Add More
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
                                                            <input type="text" class="form-control" name="test_name"  value="{{ $test_value['test_name'] }}" id="test_name" placeholder="Enter test name">
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
                                                            <select class="form-control" name="reffered_center_id" id="reffered_center_id">
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
                                                        <i class="la la-trash-o"></i></a>
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
                                                            <input type="text" class="form-control" name="test_name" id="test_name" placeholder="Enter test name">
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
                                                            <select class="form-control" name="reffered_center_id" id="reffered_center_id">
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
                                                        <i class="la la-trash-o"></i></a>
                                                </div>
                                            </div>
                                        
                                        @endif
                                        
                                        </div>
                                    </div>
                                    <div class="form-group form-group-last row">
                                        <label class="col-lg-2 col-form-label"></label>
                                        <div class="col-lg-4">
                                            <a href="javascript:;" data-repeater-create="" class="btn btn-bold btn-sm btn-label-brand">
                                                <i class="la la-plus"></i> Add More
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
                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions">
                                <div class="row">
                                    <div class="col-lg-3"></div>
                                    <div class="col-lg-6">
                                    <button type="submit" href="{{ url($url_prefix . '/diagnosis') }}" class="btn btn-brand">Submit</button>
                                    <a type="reset" href="{{ url($url_prefix . '/diagnosis/list/'.$appointment_id) }}" class="btn btn-secondary">Cancel</a>

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
                                <div class="row">
                                    <label class="col-md-12 kt-label">Chief Complaint <span class="form-info"> * </span></label>
                                    <textarea class="form-control" name="cheif_complaint" id="cheif_complaint" rows="5" spellcheck="false" placeholder="Enter cheif complaint">{{ $item_brief_note[0]['cheif_complaint'] }}</textarea>
                                    <label class="col-md-12 pt-1 kt-label">
                                    <input type="checkbox" name="cheif_complaint_status" id="cheif_complaint_status" class="" value="1"  {{ $item_brief_note[0]['cheif_complaint_status'] == 1 ? 'checked' : null }} />
                                    Highlight as important
                                    </label>
                                </div>

                                <div class="row pt-5 pb-2">
                                    <label class="col-md-12 kt-label">History of Present Illness(HOPI) </label>
                                    <textarea class="form-control" name="history_of_present_illness" id="history_of_present_illness" rows="5" spellcheck="false" >{{ $item_brief_note[0]['history_of_present_illness'] }}</textarea>
                                    <label class="col-md-12 pt-1 kt-label">
                                    <input type="checkbox" name="history_of_present_illness_status" id="history_of_present_illness_status" class="" value="1"  {{ $item_brief_note[0]['history_of_present_illness_status'] == 1 ? 'checked' : null }} />
                                    Highlight as important
                                    </label>
                                </div>

                                <div class="row pt-5 pb-2">
                                    <label class="col-md-12 kt-label">Past History</label>
                                    <textarea class="form-control" name="past_history" id="past_history" rows="5" spellcheck="false" >{{ $item_brief_note[0]['past_history'] }}</textarea>
                                    <label class="col-md-12 pt-1 kt-label">
                                    <input type="checkbox" name="past_history_status" id="past_history_status" class="" value="1"  {{ $item_brief_note[0]['past_history_status'] == 1 ? 'checked' : null }} />
                                    Highlight as important
                                    </label>
                                </div>

                                <div class="row pt-5 pb-5">
                                    <label class="col-md-12 kt-label">Physical Examiniation</label>
                                    <textarea class="form-control" name="physical_examiniation" id="physical_examiniation" rows="5" spellcheck="false" placeholder="Enter cheif complaint">{{ $item_brief_note[0]['physical_examiniation'] }}</textarea>
                                    <label class="col-md-12 pt-1 kt-label">
                                    <input type="checkbox" name="physical_examiniation_status" id="physical_examiniation_status" class="" value="1"  {{ $item_brief_note[0]['physical_examiniation_status'] == 1 ? 'checked' : null }} />
                                    Highlight as important
                                    </label>
                                </div>
                                <input type="hidden" name="diagnosis_id" id="diagnosis_id" value="{{ $id }}" />
                            </div>


                        <div class="kt-portlet__foot">
                            <div class="kt-form__actions">
                                <div class="row">
                                   
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
         <!-- End Patient Details -->
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>

@include('backend.layouts.includes.admin_modal_popup_alert') 
@endsection
@section('scripts')

<!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
 -->
 <script>
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
