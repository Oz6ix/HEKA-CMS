@extends('frontend.layouts.layout_inner')
@section('content')
<style>
    .img-wrap {
    position: relative;
    display: inline-block;
    border: 1px red solid;
    font-size: 0;
}

.img-wrap .close {
    position: absolute;
    top: 2px;
    right: 2px;
    z-index: 100;
    background-color: #FFF;
    padding: 5px 2px 2px;
    color: #000;
    font-weight: bold;
    cursor: pointer;
    opacity: .2;
    text-align: center;
    font-size: 22px;
    line-height: 10px;
    border-radius: 50%;
}

.img-wrap:hover .close {
    opacity: 1;
}

</style>
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
                                Diagnosis Details
                            </h3>
                        </div>
                        <div class="kt-portlet__head-toolbar">
                        <a href="{{ url($url_prefix . '/booked_appointment_list/') }}" class="btn btn-clean kt-margin-r-10">
                            <i class="la la-arrow-left"></i>
                            <span class="kt-hidden-mobile">Back to List</span>
                        </a>
                    </div>
                    </div>
                </div>    
                       
                <div class="kt-portlet__body">
            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable table-listing-with-checkbox">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Diagnosis</th>                                      
                            <th>ICD</th>
                            <th>Treatment</th>
                            <th>Actions</th>
                            <!-- <th>Edit</th>
                            <th>Delete</th> -->
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
                            <td>{{ $item['diagnosis'] }}</td> 
                            <td>{{ $item['icd_diagnosis']=='1'? 'ON': 'OFF'  }}</td>
                            <td>{{ $item['treatment']['title'] }}</td>  
                                            
                            <td >
                          
                                <a class="btn btn-outline-hover-warning btn-icon btn-font-warning" 
                                data-placement="left" data-id="{{$item->id}}" data-skin="dark" title="" data-original-title="Upload" 
                                onclick="showModal({{$item->id}},{{$item->appointment_id}})" aria-haspopup="true">
                                <i class="fa fa-upload"></i><span class="kt-hidden-mobile"></span>
                                </a> 

                                <a class="btn btn-outline-hover-warning btn-icon btn-font-warning" data-toggle="kt-tooltip"
                                data-placement="left" data-skin="dark" title="" data-original-title="View details"
                                href="{{ url($url_prefix . '/patient_diagnosis_list_view/'.$item->id) }}"><i
                                class="fa fa-search"></i><span class="kt-hidden-mobile"></span></a> 

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
    </div></div>
    <br><br>


    <div class="modal fade" id="reportUploadModel" tabindex="-1" role="dialog" aria-labelledby="registerModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerModal">Upload Item list file</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ url($url_prefix . '/upload_reports') }}" id="registerForm" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <input type="hidden" name="diagnosis_id" id="diagnosis_id"/>
                        <input type="hidden" name="appointment_id" id="appointment_id"/>
                        <label for="nameInput" class="col-md-4 col-form-label text-md-right">Upload File</label>
                        <div class="col-md-6">
                            <input type="file" name="report_file" accept="application/pdf" id="report_file"/>
                            <span>AcceptedFiles: PDF Only</span> 
                            <span class="invalid-feedback" role="alert" id="nameError">
                            <strong></strong>
                            </span>
                        </div>
                    </div>
                    
                    <div class="form-group row" >
                    <div class="col-md-2" ></div>
                    <div class="col-md-8" id="uploaded"></div>
                    <div class="col-md-2" ></div>

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

<script>

function showModal(diagnosisId,appointmentId){
   // alert(appointmentId);
			$('#reportUploadModel').modal('show');
			$('#diagnosis_id').val(diagnosisId);
			$('#appointment_id').val(appointmentId);
            APP_URL = "{{ url('/') }}";
            $.getJSON("{{ route('list_reports') }}"+"/"+ diagnosisId, function(data){
                //alert(JSON.stringify(data));
			
			//$('#uploaded').html('');
            var html="";
            for (const item of data) {
			html+=('<div class="img-wrap ml-3"><span class="close">&times;</span><input type="hidden" name="doc[]" value="'+item['id']+'" /> <a class="remove-img" href="'+APP_URL+'/public/uploads/patient/'+item['report_name']+'" target="_blank" width="150" height="150" alt="pdf" ><i class="fa fa-file-pdf" aria-hidden="true" style="font-size: 60px;color:#BB0706"></i></a></div>');
            }
			$('#uploaded').html(html);
                var closeBtns = document.querySelectorAll('.img-wrap .close')
                for (var i = 0, l = closeBtns.length; i < l; i++) {
                closeBtns[i].addEventListener('click', function() {
                    var imgWrap = this.parentElement;
                    imgWrap.parentElement.removeChild(imgWrap);
                });
                }
		});
			/* $('#bannerImage').attr('src', APP_URL+'/uploads/'+data.image);
			$('#image').addClass('ignore'); */
	}




</script>
    <!--begin::Page Scripts(used by this page) -->
    <script src="{{ URL::asset('assets/frontend/js/scripts/appointment.js') }}"
            type="text/javascript"></script>

    <script src="{{ URL::asset('assets/frontend/js/validations/admin_users.js') }}"
            type="text/javascript"></script>
    <!--end::Page Scripts -->
@endsection
