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

    <div class="row" style="margin-top: 80px;">
            <div class="col-lg-12 col-xl-12 order-lg-1 order-xl-1">
                <!--begin:: Widgets/Activity-->
                <div class="kt-portlet kt-portlet--fit kt-portlet--head-lg kt-portlet--head-overlay kt-portlet--skin-solid kt-portlet--height-fluid">
                    <div class="kt-portlet__body kt-portlet__body--fit">
                        <div class="kt-widget17">
                            <div class="kt-widget17__visual kt-widget17__visual--chart kt-portlet-fit--top kt-portlet-fit--sides"
                                 style="background-color: #fff">
                                <div class="kt-widget17__chart">
                                </div>
                            </div>
                            <div class="kt-widget17__stats"  >
                                <div class="kt-widget17__items">
                                       @if($item[0]['consumable_bill_status']==1)
                                       <div class="kt-widget17__item rounded-xl bg-light-danger " style="background-color: #e6d4efcf">

                                       <a href="#" class="package_course_details"
                                            data-toggle="modal" data-title="consumable" data-description="" 
                                            data-id="{{$bill_number}}"                            
                                            data-target="#exampleModalLong">
                                            <span class="kt-widget17__icon">
                                                <i class="fa fa-user-friends stats-magenta11" style="color:#bc5eea!important;"></i>
                                            </span>
                                            <span class="kt-widget17__subtitle" style="color:#bc5eea!important;">
                                                Consumable 
                                            </span>                                            
                                        </a> </div>
                                        @endif
                                   

                                    
                                    @if($item[0]['pharmacy_bill_status']==1)
                                    <div class="kt-widget17__item"  style="background-color: #cee2e1c7">
                                    <a href="#" class="package_course_details"
                                            data-toggle="modal" data-title="pharmacy" data-description="" 
                                            data-id="{{$bill_number}}"                            
                                            data-target="#exampleModalLong">
                                            <span class="kt-widget17__icon"></span>
                                            <i class="fa fa-calendar-check stats-green" style="color:#416eec!important;"></i>
                                            <span class="kt-widget17__subtitle" style="color:#416eec!important;">
                                                Pharmacy
                                            </span>                                            
                                        </a></div>   
                                        @endif
                                                                     
                             

                                    @if($item[0]['radiology_bill_status']==1)
                                    <div class="kt-widget17__item"  style="background-color: #cee2e1c7">
                                    <a href="#" class="package_course_details"
                                            data-toggle="modal" data-title="radiology" data-description="" 
                                            data-id="{{$bill_number}}"                            
                                            data-target="#exampleModalLong">
                                            <span class="kt-widget17__icon">
                                                <i class="fa fa-truck-moving stats-magenta" style="color:#8e8545!important;"></i>
                                            </span>
                                            <span class="kt-widget17__subtitle"style="color:#8e8545!important;">
                                               Radiology
                                            </span>                                           
                                        </a></div>
                                        @endif
                                                                      
                                    
                                  
                                   @if($item[0]['pathology_bill_status']==1)
                                   <div class="kt-widget17__item"  style="background-color: #9df1af91">
                                   <a href="#" class="package_course_details"
                                            data-toggle="modal" data-title="pathology" data-description="" 
                                            data-id="{{$bill_number}}"                            
                                            data-target="#exampleModalLong">
                                            <span class="kt-widget17__icon">
                                                <i class="fa fa-user stats-green" style="color:#318805!important;"></i>
                                            </span>
                                            <span class="kt-widget17__subtitle" style="color:#318805!important;">
                                               Pathology
                                            </span>                                            
                                        </a></div>
                                        @endif
                           
                                    
                                   
                                   @if($item[0]['other_bill_status']==1)
                                    <div class="kt-widget17__item"  style="background-color: #9df1af91">
                                    <a href="#" class="package_course_details"
                                            data-toggle="modal" data-title="other" data-description="" 
                                            data-id="{{$bill_number}}"                            
                                            data-target="#exampleModalLong">
                                            <span class="kt-widget17__icon">
                                                <i class="fa fa-user stats-green" style="color:#318805!important;"></i>
                                            </span>
                                            <span class="kt-widget17__subtitle" style="color:#318805!important;">
                                                Other
                                            </span>                                            
                                        </a>  </div>
                                        @endif
                                  
                                </div>
                                <div class="kt-widget17__items" > 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--end:: Widgets/Activity-->
            </div>
        </div>



</div>
     </div>
    </div>
 </div>
    </div>

    </div>
        <div class="col-lg-1"></div>
    </div>
</div>

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

    


    @include('backend.layouts.includes.admin_modal_popup_alert') 
@endsection
@section('scripts')

<script type="text/javascript">
        $(document).ready(function(){   
            $('.package_course_details').click(function(event){  
                var GetSection_Id = $(this).attr('data-id'); 
                if($(this).attr('data-title')=='pharmacy'){
                    var url="{{ url($url_prefix )}}"+"/bill_customer/ajax_fetch_bill_print_data_pharmacy/"+GetSection_Id;
                }
                if($(this).attr('data-title')=='consumable'){
                    var url="{{ url($url_prefix )}}"+"/bill_customer/ajax_fetch_bill_print_data_consumable/"+GetSection_Id;
                }
                if($(this).attr('data-title')=='radiology'){
                    var url="{{ url($url_prefix )}}"+"/bill_customer/ajax_fetch_bill_print_data_radiology/"+GetSection_Id;
                }
                if($(this).attr('data-title')=='pathology'){
                    var url="{{ url($url_prefix )}}"+"/bill_customer/ajax_fetch_bill_print_data_pathology/"+GetSection_Id;
                }
                if($(this).attr('data-title')=='other'){
                    var url="{{ url($url_prefix )}}"+"/bill_customer/ajax_fetch_bill_print_data_other/"+GetSection_Id;
                }
                if(GetSection_Id!=""){ 
                    $.ajax({
                        type: "GET",
                        url:url,
                        success: function(html) { //alert(html);
                        $("#relatedPart").html(html);
                        }
                    });
                }
            });
        });

</script>

    <!--begin::Page Scripts(used by this page) -->
    <script src="{{ URL::asset('assets/frontend/js/scripts/appointment.js') }}"
            type="text/javascript"></script>

    <script src="{{ URL::asset('assets/frontend/js/validations/admin_users.js') }}"
            type="text/javascript"></script>
    <!--end::Page Scripts -->
@endsection
