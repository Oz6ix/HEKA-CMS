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
@include('frontend.layouts.includes.alert_popup')
@section('content')
    <!-- Messages section -->
    @include('backend.layouts.includes.notification_alerts')
    <br><br> <div class="col-md-12">
    <div class="row">
         <div class="col-lg-1"></div>
        <div class="col-lg-9">

    <div class="row printPageButton">
         <div class="col-md-12">
    <div class="alert alert-light alert-elevate" role="alert">
        <div class="alert-icon"><i class="flaticon-information kt-font-brand"></i></div>
        <div class="alert-text">
             Bills
        </div>
    </div>
      <div class="row">
<div class=" col-md-12">
    <div class="kt-portlet" id="kt_page_portlet">

        <div class="kt-portlet__body">
            <!--begin: Datatable -->
            <table class="table table-striped- table-bordered table-hover table-checkable table-listing-with-checkbox">
                        <thead>
                        <tr>
                            <!-- <th>
                                <label class="kt-checkbox kt-checkbox--single kt-checkbox--solid kt-checkbox--brand">
                                    <input type="checkbox" value="" class="m-group-checkable kt-group-checkable">
                                    <span></span>
                                </label>
                            </th> -->
                            <th>#</th>
                            <th>Bill No</th>                                      
                            <th>Date</th>
                            <th>Doctor</th>
                            <th>View</th>
                            <!--<th>Edit</th>
                            <th>Delete</th> -->
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if(isset($items) && sizeof($items) > 0)  {
                        $slno = 0;
                        $i=0;
                        foreach ($items as $item):
                        $slno++
                        ?>
                        <tr>
                            <!-- <td><label class="kt-checkbox kt-checkbox--single kt-checkbox--bold kt-checkbox--brand ">
                                    <input type="checkbox" value="{{ $item->id }}" class="kt-checkbox m-checkable kt-checkable">
                                    <span></span>
                                </label>
                            </td> -->
                            <td>{{ $slno }}</td>
                            <td>{{ $item['bill_number'] }}</td> 
                            <td>{{ date('M d, Y', strtotime($item['bill_date'])) }}</td>
                            <td>{{ $item['staff_doctor']['name'] }}</td>  
                                            
                            <!--<td >
                                <a class="btn btn-outline-hover-warning btn-icon btn-font-warning" data-toggle="kt-tooltip"
                                data-placement="left" data-skin="dark" title="" data-original-title="View details"
                                href="{{ url($url_prefix . '/diagnosis/view/'.$item->id) }}"><i
                                            class="fa fa-search"></i><span class="kt-hidden-mobile"></span></a> 
                            </td>-->
                            <td >
                                
                            <a href="{{ url($url_prefix . '/list_bill_set/'.$item->appointment_id) }}" class="package_course_detailsx" 
                            data-title="" data-description="" 
                            data-id="{{$item['bill_number']}}" >
                            <i class="fa fa-search">
                            </i><span class="kt-hidden-mobile"></span>
                            </a>
                        
<!--                             <a href="#" class="package_course_details"
                            data-toggle="modal" data-title="" data-description="" 
                            data-id="{{$item['bill_number']}}"                            
                            data-target="#exampleModalLong"><i class="fa fa-search">
                            </i><span class="kt-hidden-mobile"></span>
                            </a>
                        
 -->                        
                        </td>
                            
                        </tr>
                        <?php 
                            $i++;
                            endforeach; ?>
                        <?php } ?>
                        </tbody>
                    </table>
            <!--end: Datatable -->
        </div>
    </div>
     </div>
    </div>
 </div>
    </div>

    </div>
        <div class="col-lg-1"></div>
    </div></div>


  <style type="text/css">
    .spmodel .modal{z-index:99999!important; }
    .spmodel .modal-dialog{ max-width: 960px!important;}
    @media print {
  .printPageButton {
    display: none;
  }
}
</style>

    <!-- Modal -->
    <div class="spmodel">
        <div class="modal fade" id="exampleModalLong" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle5" aria-hidden="true">
          <div class="modal-dialog special" role="document">
            <div class="modal-content">
              <div class="modal-header printPageButton">
                <h5 class="modal-title" id="exampleModalLongTitle5" style="color:#fff">COURSE DETAILS</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                  <span aria-hidden="true" style="color:#fff">&times;</span>
                </button>
              </div>
              <div class="modal-body"> 
                <span id="relatedPart"></span>
              </div>
              <div class="modal-footer hidden-print">
                <button type="button" class="btn btn-brand btn-bold printPageButton" onclick="window.print();">Print Invoice</button>
                <button type="button" class="btn btn-secondary printPageButton" data-dismiss="modal">Close</button>
              </div>
            </div>
          </div>
        </div>
    </div>    


    <div class="modal fade" id="listModel" tabindex="-1" role="dialog" aria-labelledby="registerModal" aria-hidden="true">
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
                    <div class="form-group row" id="uploaded">
         
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
    


    @include('backend.layouts.includes.admin_modal_popup_alert') 
@endsection
@section('scripts')

<script type="text/javascript">
        $(document).ready(function(){  
            $('.package_course_details').click(function(event){  
               var GetSection_Id = $(this).attr('data-id');
                var CurrentURL='<?php echo (config('global.basepathcustomer')) ?>';           
                if(GetSection_Id!=""){ 
                    $.ajax({
                        type: "GET",
                        url: CurrentURL+"bill_customer/ajax_fetch_bill_print_data_other/"+GetSection_Id,
                       
                        success: function(html) { //alert(html);
                        $("#relatedPart").html(html);
                        }
                    });
                }
            });






        });



        function showModal(appointmentId,billNumber){
    //alert(billNumber);
			$('#listModel').modal('show');
			$('#appointment_id').val(appointmentId);
            APP_URL = "{{ url('/') }}";
            $.getJSON("{{ route('list_bill_set') }}"+"/"+ appointmentId, function(data){
               // alert(JSON.stringify(data[1]['bill_number']));
			
			//$('#uploaded').html('');
            var html="";
            for (const item of data) {
               // alert(JSON.stringify(data[0]['consumable_bill_status']));
               if(data[0]['consumable_bill_status']==1){
			html+=('<div class="col-md-2  " ><a href="#" class="package_course_details1" data-toggle="modal" data-title="" data-description="" data-id="'+billNumber+'" data-target="#exampleModalLong"> <span class="kt-widget17__icon"><i class="fa fa-user-friends stats-magenta11" style="color:#bc5eea!important;"></i> </span> <span class="kt-widget17__subtitle" style="color:#bc5eea!important;">Consumable</span></a></div>');
               }
               if(data[0]['pharmacy_bill_status']==1){
			html+=('<div class="col-md-2  " ><a href="#" class="package_course_details2" data-toggle="modal" data-title="" data-description="" data-id="'+billNumber+'" data-target="#exampleModalLong"> <span class="kt-widget17__icon"><i class="fa fa-user-friends stats-magenta11" style="color:#bc5eea!important;"></i> </span> <span class="kt-widget17__subtitle" style="color:#bc5eea!important;">Pharmacy</span></a></div>');
               }
               if(data[0]['pathology_bill_status']==1){
			html+=('<div class="col-md-2  " ><a href="#" class="package_course_details3" data-toggle="modal" data-title="" data-description="" data-id="'+billNumber+'" data-target="#exampleModalLong"> <span class="kt-widget17__icon"><i class="fa fa-user-friends stats-magenta11" style="color:#bc5eea!important;"></i> </span> <span class="kt-widget17__subtitle" style="color:#bc5eea!important;">Pathology</span></a></div>');
               }
               if(data[0]['radiology_bill_status']==1){
			html+=('<div class="col-md-2  " ><a href="#" class="package_course_details" data-toggle="modal" data-title="" data-description="" data-id="'+billNumber+'" data-target="#exampleModalLong"> <span class="kt-widget17__icon"><i class="fa fa-user-friends stats-magenta11" style="color:#bc5eea!important;"></i> </span> <span class="kt-widget17__subtitle" style="color:#bc5eea!important;">Radiology</span></a></div>');
               }
               if(data[0]['other_bill_status']==1){
			html+=('<div class="col-md-2  " ><a href="#" class="package_course_details" data-toggle="modal" data-title="" data-description="" data-id="'+billNumber+'" data-target="#exampleModalLong"> <span class="kt-widget17__icon"><i class="fa fa-user-friends stats-magenta11" style="color:#bc5eea!important;"></i> </span> <span class="kt-widget17__subtitle" style="color:#bc5eea!important;">Other</span></a></div>');
               }
            }
			$('#uploaded').html(html);
              
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
