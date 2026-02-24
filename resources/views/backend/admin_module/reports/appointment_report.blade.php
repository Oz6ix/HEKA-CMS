@extends('backend.layouts.modern')

@section('content')
    <!-- Messages section -->
    
    
    <div class="space-y-6">
        <div class="col-lg-12">

            <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                    <div class="kt-ribbon__target" style="top: 12px;">
                        <span class="kt-ribbon__inner"></span>Filter by
                    </div>
                </div>


            <!--begin::Portlet-->
            <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
             
                <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                    {!! Form::open(['url' => url($url_prefix . '/report/appointment_report'), 'id' => 'add_form', 'class' => 'kt-form', 'files' => true, 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
                    <div class="space-y-6">
                        <div class="col-xl-2"></div>
                        <div class="col-xl-8">
                            <div>
                                <div>
                                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="block text-sm font-medium text-slate-700">
                                        </label>                                       
                                    </div>                                   
                                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">                       
                            <div class="col-4">
                        <select class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" id="select2_doctor_id" >
                             <option disabled selected value>Select an option</option>
                             @foreach($doctor_item as $data)
                              <option value="{{ $data->id }}"  {{ (isset($selected_doctor_id) && $data->id == $selected_doctor_id) ? 'selected' : '' }}>
                                    {{ $data->name }} - {{ $data->staff_department->department }} - ({{ $data->staff_code }})
                                </option>
                            @endforeach                                  
                        </select>
                        <input type="hidden" name="search_product_id" id="search_product_id"
                               value=" "/>
                        <span class="text-small">Search for doctor using doctor name, code.</span>
                    </div>

                      <div class="col-4">
                        <select class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" id="select2_patient_id" >
                             <option disabled selected value>Select an option</option>
                             @foreach($patient_item as $data)
                              <option value="{{ $data->id }}"  {{ (isset($selected_patient_id) && $data->id == $selected_patient_id) ? 'selected' : '' }}>
                                    {{ $data->name }} - ({{ $data->patient_code }})
                                </option>
                            @endforeach                                  
                        </select>
                        <input type="hidden" name="search_product_id" id="search_product_id"
                               value=" "/>
                        <span class="text-small">Search for patient using patient name, code.</span>
                    </div>

                        <div class="col-4">
                        <button type="button" class="btn btn-brand" id="project_search_button">
                         Go
                        </button>
                         <a href="{{ url($url_prefix . '/report/appointment_report') }}" class="btn btn-secondary">Reset</a>
                        </div>                          


                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2"></div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <!--end::Portlet-->
            <style type="text/css">
                .new-invoice { padding: 10px 0!important;}
                .kt-invoice__container{width:100%!important;}
                .tab_back{background: #e3ebe3ad}
                .tab_back2{background: #c9f1f7c4}
            </style>
            <br><br>

            @if(!empty($select_appointment))
              <!--begin::Portlet-->
            <div class=" kt-portlet kt-portlet--last kt-portlet--head-lg kt-portlet--responsive-mobile"
                 id="kt_page_portlet">             
                <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">                   
                    <div class="space-y-6">
                        <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
                            <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                                <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                                    <div class="kt-invoice-2">
                                        <div class="new-invoice kt-invoice__head">
                                            <div class="kt-invoice__container">
                                                <div class="kt-invoice__brand">
                                                    <h1 class="kt-invoice__title"> Appointment Report</h1>
                                                    <div href="#" class="kt-invoice__logo">
                                                        <img src="{{ URL::asset('uploads/logos/cms-logo-png.png') }}" ></a>                      
                                                        <span class="kt-invoice__desc">
                                                            <span>Date: {{ date('M d, Y') }}</span>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>  
                                        <!-- Area Start -->
                                          <br><br>                   
                                            <div style="text-align: left;font-size:18px;font-weight: 600;">
                                                 <!-- AREA SUMMARY REPORT -->
                                              </div>
                                             <hr>
                                        <div class="kt-invoice__body-sp">
                                            <div class="kt-invoice__container">
                                                <div class="table-responsive">
                                                     <table class="min-w-full divide-y divide-slate-200">
                                                        <thead>
                                                        <tr>                                                           
                                                            <th>#</th>
                                                            <th>Patient Name</th>                                      
                                                            <th>Date</th>
                                                            <th>Phone</th>
                                                            <th>Case No.</th>
                                                            <th>Doctor</th>

                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        if(isset($select_appointment) && sizeof($select_appointment) > 0)  {
                                                        $slno = 0;
                                                        foreach ($select_appointment as $item):
                                                        $slno++
                                                        ?>
                                                        <tr>                                                           
                                                            <td>{{ $slno }}</td>
                                                            <td>{{ $item['patient']['name']}}</td> 
                                                            <td>{{date('M d, Y', strtotime($item['appointment_date']))}}</td>
                                                            <td>{{$item['patient']['phone']}}</td>
                                                            <td>{{$item['case_number']}}</td>
                                                            <td>{{$item['staff_doctor']['name']}}</td>
                                                        </tr>
                                                        <?php endforeach; ?>
                                                        <?php } ?>
                                                        </tbody>
                                                    </table>
                                                   
                                                </div>
                                            </div>
                                        </div>                        
                                    </div>
                                </div>
                            </div>
                        </div>  
                        
                    </div>                 
                </div>
            </div>
            <!--end::Portlet-->
            @else
            @if(empty($select_appointment))
                <div class="col-md-12 text-center" style="border:1px solid #ccc;padding:20px"> No Records Found.</div>          
            @endif                   
            @endif

   </div>
</div>

@endsection
@section('scripts')  
    <link href="{{ URL::asset('assets/backend/css/demo1/pages/invoices/invoice-2.css') }}" rel="stylesheet" type="text/css" />


    <script type="text/javascript">
    $(document).ready(function(){ //alert('aaaaaaaaaaaa');
        $('#project_search_button').click(function(event){ 
            var CurrentURL='<?php echo (config('global.basepathadmin')) ?>';
            var doctor_val = $( "#select2_doctor_id" ).val();
            var patient_val = $( "#select2_patient_id" ).val();
            //alert(project_val);
            var project_section_url = CurrentURL+"report/appointment_report/"+doctor_val+'/'+patient_val;
            window.location.href = project_section_url;
            return false;
        });
    });
</script>


    <!--end::Page Scripts -->
@endsection
