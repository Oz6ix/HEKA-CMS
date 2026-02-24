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
                <div class="kt-portlet__body">
                    {!! Form::open(['route'=>('patient_profile_update'), 'id' => 'add_form', 'class' => 'kt-form', 'files' => true, 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}

                    <div class="row">
                   <!--  <div class="kt-section kt-section--first">
                                <div class="kt-section__body">    -->                     
                        <div class="col-md-7">
                           
                                    <div class="form-group row">
                                        <h4 class="col-6 col-form-label">Profile
                                        </h4>
                                        
                                    </div>   <br><br>                              
                                    <div class="form-group row">
                                            <label class="col-3 text-md-right col-form-label">Name
                                                <span class="form-info"> * </span>
                                            </label>
                                            <div class="col-9">
                                            <input type="text" class="form-control" name="name" id="name" placeholder="Enter full name"
                                                value="{{ $item->name }}"/>
                                            </div>
                                    </div> 
                                    <div class="form-group row">
                                            <label class="col-3  text-md-right col-form-label">Phone
                                                <span class="form-info"> * </span>
                                            </label>
                                            <div class="col-9">
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">+95</span>
                                                </div>
                                                <input type="text" class="form-control input-numeric "
                                                       name="phone"
                                                       id="phone" placeholder="Enter phone number" value="{{ $item['phone'] }}">
                                            </div>
                                            </div>
                                        </div> 
                                        <div class="form-group row">
                                            <label class="col-3  text-md-right col-form-label">Email
                                                <span class="form-info"> * </span>
                                            </label>
                                            <div class="col-9">
                                            <input type="email" class="form-control" name="email" id="email" placeholder="Enter email"
                                                value="{{  $item->email }}" data="{{ $item['email'] }}"/>
                                            </div>
                                        </div> 
                                        <div class="form-group row">
                                            <label class="col-3 text-md-right  col-form-label">Gender
                                                <span class="form-info"> * </span>
                                            </label>
                                            <div class="col-9">
                                            <select class="form-control" name="gender" id="gender">
                                            <option disabled selected value>
                                            Select an option
                                            </option>  
                                            @foreach(config('global.gender') as $key => $value)
                                            <option value="{{ $key }}" {{ ($key == $item['gender']) ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach 
                                            </select> 
                                            </div>
                                        </div> 

                                        <div class="form-group row">
                                            <label class="col-3 text-md-right  col-form-label">Date of Birth
                                                <span class="form-info"> * </span>
                                            </label>
                                            <div class="col-9">
                                            <input type="date" class="form-control" name="dob" id="dob" placeholder="Enter Date Of Birth"
                                                value="{{ $item->dob }}" max="<?php echo date("Y-m-d"); ?>"/>
                                            </div>
                                        </div> 

                                        <div class="form-group row">
                                            <label class="col-3 text-md-right  col-form-label">Age
                                                <span class="form-info">  </span>
                                            </label>
                                            <div class="col-9">
                                            <input type="text" class="form-control col-lg-4" style="display: inline-block;" name="age_year" id="age_year" placeholder="Year"
                                                value="{{ $item->age_year }}"/>
                                        <input type="text" class="form-control col-lg-4" style="display: inline;" name="age_month" id="age_month" placeholder="Month"
                                        value="{{ $item->age_month }}"/>
                                            </div>
                                        </div> 

                                        <div class="form-group row">
                                            <label class="col-3  text-md-right col-form-label">Blood Group
                                                <span class="form-info"> * </span>
                                            </label>
                                            <div class="col-9">
                                            <select class="form-control kt-select2" id="select2_blood_group" >
                                                <option value="">--- Select Blood Group---</option>
                                                @foreach($blood_group_item as $data)
                                                    <option value="{{ $data['id'] }}" {{ ($data['id'] == $item['blood_group']) ? 'selected' : '' }}>
                                                        {{ $data['blood_group'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="blood_group" id="blood_group"
                                                   value="{{ $item['blood_group'] }}"/>   
                                            </div>
                                        </div> 
                                        <div class="form-group row">
                                            <label class="col-3 text-md-right  col-form-label">Marital Status
                                                <span class="form-info">  </span>
                                            </label>
                                            <div class="col-9">
                                            <select class="form-control" name="marital_status" id="marital_status">
                                            <option disabled selected value>
                                            Select an option
                                            </option>  
                                            @foreach(config('global.maritial_status') as $key => $value)
                                            <option value="{{ $key }}" {{ ($key == $item['marital_status']) ? 'selected' : '' }}>{{ $value }}</option>
                                            @endforeach 
                                            </select> 
                                            </div>
                                        </div> 
                                        <div class="form-group row">
                                            <label class="col-3 text-md-right  col-form-label">Address
                                                <span class="form-info">  </span>
                                            </label>
                                            <div class="col-9">                                            
                                            <textarea  class="form-control" rows="5" name="address" id="address" placeholder="Address">{{ $item->address }}</textarea>
                                            </div>
                                        </div> 
                                    
                                    <!-- One additional row added below to adjust page height -->
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">
                                        </label>
                                        <div class="col-9 form-info">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-5">

                                <div class="form-group row">
                                        <h4 class="col-12 col-form-label text-md-right ">
                                        <a href="#" data-toggle="modal" data-target="#passwordUpdateModel">Click here to change login password. </a>
                                        </h4>
                                        
                                    </div>   <br><br>                                  
                                <div class="form-group">
                                    <label class="control-label" for="image">Image <span class="m-l-5 text-danger"> *</span></label>
                                    <input type="file" ng-required="displayCondition" class="form-control @error('image') is-invalid @enderror" name="file" id="file">
                                    <div class="invalid-feedback active">
                                    <i class="fa fa-exclamation-circle fa-fw"></i> @error('image') <span>{{ $message }}</span> @enderror
                                    </div>
                                    <figure class="mt-2" style="width: 80px; height: auto;">
                                    <label class="col-form-label"><strong>
                                            @if($item['patient_photo'])
                                            <div class="kt-form__image-logo-display">
                                            <button type="button" class="btn"
                                            data-toggle="kt-popover" data-trigger="focus"
                                            data-placement="left"
                                            title="Patient Photo" data-html="true"
                                            data-content="<img class='popover-img' src='{{ url('public/uploads/patient/'.$item['patient_folder_name'].'/'.$item['patient_photo'])  }}'
                                            alt='Staff Photo' />">
                                            <span class="kt-userpic-logo">
                                            <img src="{{ url('public/uploads/patient/'.$item['patient_folder_name'].'/'.$item['patient_photo'])  }}"
                                            alt="Patient Photo"/>
                                            </span>
                                            </button>
                                            </div>
										@else
											<img src="https://via.placeholder.com/120" alt="image">
										@endif
                                            
                                            </strong></label>
                                    </figure>
                                </div>
                        </div>


                           <!--  </div>

                        </div> -->



                    </div>
                     <div class="kt-portlet__foot">

                    <div class="kt-form__actions">
                        <div class="row"><div class="col-md-3"></div>
                    <div class="col-md-9">
                        <button type="submit" class="btn btn-brand button-submit" id="password_button">                            Submit
                        </button>
                       
                        </div>

                        
                        <div class="col-md-3"></div>


                        </div>
                    </div>
                </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <!--end::Portlet-->
        </div>
        <div class="col-lg-1"></div>
    </div>
     </div>
    <br><br>

    <div class="modal fade" id="passwordUpdateModel" tabindex="-1" role="dialog" aria-labelledby="registerModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="registerModal">Upload Item list file</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
            {!! Form::open(['url' => url($url_prefix . '/update_patient_password'), 'id' => 'password_form', 'class' => 'kt-form', 'files' => true, 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
                <div class="kt-portlet__body row">
                <div class="col-md-2"></div>
                <div class="col-md-8">
                    <div class="form-group row">
                        <label>Current Password
                            <span class="form-info"> * </span>
                            
                        </label>
                        <input type="password" class="form-control" name="current_password" id="current_password"
                               placeholder="Enter current password"/>
                    </div>
                    <div class="form-group row">
                        <label>New Password
                            <span class="form-info"> * </span>
                            
                        </label>
                        <input type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" class="form-control" name="new_password" id="new_password"
                               placeholder="Enter new password"/>
                    </div>
                    <div class="form-group row">
                        <label>Confirm Password
                            <span class="form-info"> * </span>
                           
                        </label>
                        <input type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"  class="form-control" name="confirm_password" id="confirm_password"
                               placeholder="Confirm new password"/>
                    </div>
                </div>
                <div class="col-md-2"></div>

                </div>
                <div class="kt-portlet__foot">
                    <div class="kt-form__actions">
                        <button type="submit" class="btn btn-brand button-submit" id="password_button">
                            <i class="la la-check"></i>Update
                        </button>
                    </div>
                </div>
            {!! Form::close() !!}
            </div>
        </div>
    </div>
</div>





    @include('backend.layouts.includes.admin_modal_popup_alert') 
@endsection
@section('scripts')
<script>
    $(document).ready(function () {
                $('#dob').on('change', function() {
                var dob = $("#dob").val();
                dob = new Date(dob);
                var today = new Date();
                var age = Math.floor((today-dob) / (365.25 * 24 * 60 * 60 * 1000));
                $("#age_year").val(age);
                var months;
                months = (today.getFullYear() - dob.getFullYear()) * 12;
                months -= dob.getMonth();
                months += today.getMonth();
                months=months%12;
                months <= 0 ? 0 : months;
                $("#age_month").val(months);
            });   
        });

</script>
    <!--begin::Page Scripts(used by this page) -->
    <script src="{{ URL::asset('assets/frontend/js/scripts/appointment.js') }}"
            type="text/javascript"></script>
            <script src="{{ URL::asset('assets/frontend/js/validations/patient.js') }}"
            type="text/javascript"></script>
            <script src="{{ URL::asset('assets/frontend/js/validations/ptient_profile.js') }}"
            type="text/javascript"></script>
    <!--end::Page Scripts -->
@endsection
