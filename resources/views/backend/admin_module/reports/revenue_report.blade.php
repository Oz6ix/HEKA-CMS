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
                    {!! Form::open(['url' => url($url_prefix . '/report/revenue_report'), 'id' => 'add_form', 'class' => 'kt-form', 'files' => true, 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
                    <div class="space-y-6">
                        <div class="col-md-8">
                            <div>
                                <div>
                                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="block text-sm font-medium text-slate-700">
                                        </label>                                       
                                    </div>                                   
                                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">                       
                            <div class="col-4">
                        <select class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" id="select2_doctor_id" >
                             
                                <option value="2" {{ (isset($selected_type) && '2' == $selected_type) ? 'selected' : '' }}>Select Pharmacy</option>
                                <option value="3"{{ (isset($selected_type) && '3' == $selected_type) ? 'selected' : '' }}>Pathology</option>
                                <option value="4"{{ (isset($selected_type) && '4' == $selected_type) ? 'selected' : '' }}>Radiology</option>
                                <option value="1"{{ (isset($selected_type) && '1' == $selected_type) ? 'selected' : '' }}>Others</option>                                
                        </select>
                        <input type="hidden" name="search_product_id" id="search_product_id"
                               value=" "/>
                        <!-- <span class="text-small"></span> -->
                    </div>                      

                        <div class="col-4">
                        <button type="button" class="btn btn-brand" id="project_search_button">
                         Go
                        </button>
                         <a href="{{ url($url_prefix . '/report/revenue_report') }}" class="btn btn-secondary">Reset</a>
                        </div>                          


                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                        <a href="{{ url($url_prefix . '/report/download_revenue_report') }}" class="inline-flex items-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500">
                            <i class="fas fa-arrow-left"></i>
                            <span class="kt-hidden-mobile">Export</span>
                        </a> 
                        
                        </div>
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

            @if(!empty($items) &&isset($items) && sizeof($items) > 0)
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
                                                    <h1 class="kt-invoice__title"> Revenue Report</h1>
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
                                                            <th>Bill No.</th>                                      
                                                            <th>Date</th>
                                                            <th>Patient Name</th> 
                                                            <th>Doctor</th>
                                                            <th>Discount (%)</th>
                                                            <th>Tax (%)</th>
                                                            <th>Total (K)</th>
                                                            <th>Net Amount (K)</th>

                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php
                                                        if(isset($items) && sizeof($items) > 0)  {
                                                        $slno = 0;
                                                        foreach ($items as $item):
                                                        $slno++
                                                        ?>
                                                        <tr>                                                           
                                                            <td>{{ $slno }}</td>
                                                            <td>{{$item['bill_number']}}</td>
                                                            <td>{{date('M d, Y', strtotime($item['bill_date']))}}</td>
                                                            <td>{{ $item['patient']['name']}}</td>                     
                                                            <td>{{$item['staff_doctor']['name']}}</td>
                                                            <td>{{$item['discount']}}</td>
                                                            <td>{{$item['tax']}}</td>
                                                            <td>{{$item['total']}}</td>
                                                            <td>{{$item['net_amount']}}</td>
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
            @if(sizeof($items) <= 0)
                <div class="col-md-12 text-center" style="border:1px solid #ccc;padding:20px"> No Records Found.</div>          
            @endif                   
            @endif

   </div>
</div>



<div class="modal fade" id="exportmodel" tabindex="-1" role="dialog" aria-labelledby="registerModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerModal">}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ url($url_prefix . '/report/export_revenue_report') }}" id="registerForm" enctype="multipart/form-data">
                    @csrf

                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                        <label for="nameInput" class="col-md-4 col-form-label text-md-right">Name</label>

                        <div class="col-md-6">
<input type="file" name="export_file" id="export_file" />
                            <span class="invalid-feedback" role="alert" id="nameError">
                                <strong></strong>
                            </span>
                        </div>
                    </div>

                    
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                Register
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>






@endsection
@section('scripts')  
    <link href="{{ URL::asset('assets/backend/css/demo1/pages/invoices/invoice-2.css') }}" rel="stylesheet" type="text/css" />


    <script type="text/javascript">
    $(document).ready(function(){ //alert('aaaaaaaaaaaa');
        $('#project_search_button').click(function(event){ 
            var CurrentURL='<?php echo (config('global.basepathadmin')) ?>';
            var type_val = $( "#select2_doctor_id" ).val();
            //var patient_val = $( "#select2_patient_id" ).val();
            //alert(project_val);
            var project_section_url = CurrentURL+"report/revenue_report/"+type_val;
            window.location.href = project_section_url;
            return false;
        });
    });
</script>


    <!--end::Page Scripts -->
@endsection
