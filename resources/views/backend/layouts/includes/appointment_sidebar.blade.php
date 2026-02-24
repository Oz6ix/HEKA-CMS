<br>
<?php
$controller = class_basename(\Route::current()->controller);
$action = class_basename(\Route::current()->action['uses']);
$url_prefix = Config::get('app.app_route_prefix');
$app_logo_head = Config::get('app.app_logo_head');
$app_logo_sub = Config::get('app.app_logo_sub');
?>
<div class="col-md-3">
    <div class="kt-portlet">
        <div class="kt-portlet__head">
            <div class="kt-portlet__head-label">
                <h3 class="kt-portlet__head-title">
                   Appointment Manage
                </h3>
            </div>
        </div>
        <div class="kt-portlet__body">
            
            <!--begin::Accordion-->
            <div class="accordion" id="accordionExample1">
                <div class="card">
                    <div class="card-header sp-head" id="headingOne">
                        <a href="{{ url($url_prefix.'/symptom_type') }}" class="card-title {{ ($controller=="SymptomTypeController") ? 'active' : ''}}">
                           Symptoms
                        </a>
                    </div>                                                    
                </div>
                <div class="card">
                    <div class="card-header sp-head" id="headingTwo">
                        <a href="{{ url($url_prefix . '/casualty') }}"class="card-title {{ ($controller == "CasualtyController") ? 'active' : ''}}">
                            Casualty
                        </a>
                    </div>                                                   
                </div>
                <div class="card">
                    <div class="card-header sp-head" id="headingThree1">
                         <a href="{{ url($url_prefix . '/tpa') }}"class="card-title {{ ($controller == "TpaController") ? 'active' : ''}}">
                            TPA
                        </a>
                    </div>                                                    
                </div>
                <div class="card">
                    <div class="card-header sp-head" id="headingThree1">
                         <a href="{{  url($url_prefix . '/frequency')  }}"class="card-title {{ ($controller == "FrequencyController") ? 'active' : ''}}">
                         Frequency
                        </a>
                    </div>                                                    
                </div>
                <div class="card">
                    <div class="card-header sp-head" id="headingThree1">
                         <a href="{{  url($url_prefix . '/center')  }}"class="card-title {{ ($controller == "CenterController") ? 'active' : ''}}">
                         Centers
                        </a>
                    </div>                                                    
                </div>
            </div>

            <!--end::Accordion-->
        </div>
    </div> 
</div>