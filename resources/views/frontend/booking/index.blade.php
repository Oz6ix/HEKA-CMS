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
                 <div class="kt-portlet" id="kt_page_portlet">
                    <div class="kt-portlet__head kt-portlet__head--lg">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                               Bookings
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                        
                    </div>
                    </div>
                </div>                  

                <div class="kt-portlet__body">
            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable table-listing-with-checkbox">
                <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>                                      
                    <th>Doctor</th>
                    <th>View</th>
                    <th>Status</th>
                    <th>Diagnosis</th>
                    <!-- <th>Upload Reports</th> -->
                    <th>Action</th>
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
                    <td>{{ $item->appointment_date }}</td>  
                    <td>{{ $item->staff_doctor->name }}</td>
                    <td>
                        <a class="btn btn-outline-hover-warning btn-icon btn-font-warning" data-toggle="kt-tooltip"
                           data-placement="left" data-skin="dark" title="" data-original-title="View details"
                           href="{{ url($url_prefix . '/booked_appointment_show/'.$item->id) }}">
                           <i class="fa fa-search"></i><span class="kt-hidden-mobile"></span>
                        </a>
                    </td>
                    <td>
                        @if($item->status == 1) <span>Open</span> 
                        @elseif($item->status == 2) <span>Cancelled</span> 
                        @else <span>Closed</span>  
                        @endif
                    </td>
                    <td>
                        <a class="btn btn-outline-hover-warning btn-icon btn-font-dark" data-toggle="kt-tooltip"
                            data-placement="left" data-skin="dark" title="" data-original-title="Diagnosis"
                            href="{{ url($url_prefix . '/patient_diagnosis_list/'.$item->id) }}">
                            <i class="fa fa-flask"></i><span class="kt-hidden-mobile"></span>
                        </a>
                    </td>
                    <!-- <td>
                    @if($item->diagnosis_status == 1)
                        <a class="btn btn-outline-hover-warning btn-icon btn-font-warning" data-toggle="modal" data-target="#importmodel"
                            data-placement="left" data-id="{{$item->id}}" data-skin="dark" title="" data-original-title="Upload" 
                            aria-haspopup="true">
                            <i class="fa fa-upload"></i><span class="kt-hidden-mobile"></span>
                        </a> 
                    @else
                    <a class="btn btn-outline-hover-warning btn-icon btn-font-warning not-allowed" disabled="disabled"
                            data-placement="left" data-skin="dark" title="" data-original-title="Upload" 
                            aria-haspopup="true">
                            <i class="fa fa-upload"></i><span class="kt-hidden-mobile"></span>
                        </a>    
                    @endif
                    </td> -->
                    <td>
                    @if($item->diagnosis_status == 0)
                        
                    <a href="{{ url($url_prefix . '/booked_appointment_cancel/'.$item->id) }}" class="btn btn-brand btn-elevate btn-icon-sm">
                        <span class="kt-hidden-mobile">Cancel</span>
                    </a>
                        
                    @else
                    <span></span>    
                    @endif
                    </td>


                </tr>
                <?php endforeach; ?>
                <?php } ?>
                </tbody>
            </table>
            <!--end: Datatable -->
                </div>
            </div>
            <!--end::Portlet-->
        </div>
        <div class="col-lg-1"></div>
    </div>
    </div>
    <br><br>

    <div class="modal fade" id="importmodel" tabindex="-1" role="dialog" aria-labelledby="registerModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerModal">Upload Item list file</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ url($url_prefix . '/inventory_master/import_item_master') }}" id="registerForm" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <label for="nameInput" class="col-md-4 col-form-label text-md-right">Upload File</label>

                        <div class="col-md-6">
                            <input type="file" name="export_file" id="export_file"  />
                            <span class="invalid-feedback" role="alert" id="nameError">
                            <strong></strong>
                            </span>
                        </div>
                    </div>
                    <div class="form-group row mb-0">
                        <div class="col-md-6 offset-md-4">
                            <button type="submit" class="btn btn-primary">
                                Submit
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

    @include('backend.layouts.includes.admin_modal_popup_alert') 
@endsection
@section('scripts')

    <!--begin::Page Scripts(used by this page) -->
    <script src="{{ URL::asset('assets/frontend/js/scripts/appointment.js') }}"
            type="text/javascript"></script>

    <script src="{{ URL::asset('assets/frontend/js/validations/admin_users.js') }}"
            type="text/javascript"></script>
    <!--end::Page Scripts -->
@endsection
