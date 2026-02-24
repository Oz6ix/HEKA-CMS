{{--<!--begin::Portlet--> kt-portlet--collapse--}}
<div class="kt-portlet" data-ktportlet="true" id="kt_portlet_homepage_element">
    <div class="kt-portlet__head">
        <div class="kt-portlet__head-label">
            <h3 class="kt-portlet__head-title">
               Configuration Settings
            </h3>
        </div>
        <div class="kt-portlet__head-toolbar">
            <div class="kt-portlet__head-group">
                <a href="#" data-ktportlet-tool="toggle" class="btn btn-sm btn-icon btn-outline-brand btn-icon-md"><i
                            class="la la-angle-down"></i></a>
            </div>
        </div>
    </div>
    <style type="text/css">.font-label-new{padding-top:4px!important;}</style>
    <!-- Messages section -->

    @include('backend.layouts.includes.notification_alerts')

    {!! Form::open(['route'=>('setting_configuration_update'), 'id' => 'update_form_homepage_element', 'class' => 'kt-form', 'files' => true, 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}

    <input type="hidden" name="id" value="{{$item['id']}}">
    <div class="kt-portlet__body">
        <div class="form-group row">
            <label class="col-3 col-form-label">
            </label>
            <div class="col-9 form-info">
                * = Required
            </div>
        </div>
        <div class="col-md-12">
        <div class="row">

        <div class="col-md-12">
            <div class="kt-sectionggg kt-section--firstggg">
                <div class="kt-section__body">
                    <div class="form-group row">                       
                        <div class="col-9">
                            <div class="kt-checkbox-list">
                                <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand font-label-new">
                                    <input type="checkbox" name="enable_pharmacy_status" {{ $item['enable_pharmacy_status'] == 1 ? "checked" : "" }}/>Enable Pharmacy Master
                                    <span></span>
                                </label>                                               
                            </div>
                        </div>
                    </div>


                <div class="form-group row">              
                    <div class="col-9">
                        <div class="kt-checkbox-list">
                            <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand font-label-new">
                                <input type="checkbox" name="enable_pathology_status" {{ $item['enable_pathology_status'] == 1 ? "checked" : "" }}/> Enable Pathology Master
                                <span></span>
                            </label>                                               
                        </div>
                    </div>
                </div>


                <div class="form-group row">                    
                    <div class="col-9">
                        <div class="kt-checkbox-list">
                            <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand font-label-new">
                                <input type="checkbox" name="enable_radiology_status" {{ $item['enable_radiology_status'] == 1 ? "checked" : "" }}/> Enable Radiology Master
                                <span></span>
                            </label>                                               
                        </div>
                    </div>
                </div>

                <div class="form-group row">              
                    <div class="col-9">
                        <div class="kt-checkbox-list">
                            <label class="kt-checkbox kt-checkbox--bold kt-checkbox--brand font-label-new">
                                <input type="checkbox" name="enable_inventory_status" {{ $item['enable_inventory_status'] == 1 ? "checked" : "" }}/> Enable Inventory Master
                                <span></span>
                            </label>                                               
                        </div>
                    </div>
                </div>

                     


                    

                </div>
            </div>
        </div>       
            
            
            </div>  
        </div>      
       
     
    </div>
    <div class="kt-portlet__foot">
        <div>
            <button type="submit" class="btn btn-brand button-submit" id="update_button_homepage_element">
                <i class="la la-check"></i>
                Update
            </button>
        </div>
    </div>
    {!! Form::close() !!}
</div>
<!--end::Portlet-->
