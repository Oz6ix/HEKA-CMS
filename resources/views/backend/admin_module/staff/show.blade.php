@extends('backend.layouts.admin')

@section('breadcrumb')
    
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ url($url_prefix . '/staffs') }}" class="kt-subheader__breadcrumbs-link">
        Staff </a>
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">View</span>
@endsection

@section('content')
    <!-- Messages section -->
    @include('backend.layouts.includes.notification_alerts')
    <div class="alert alert-light alert-elevate" role="alert">
        <div class="alert-icon"><i class="flaticon-information kt-font-brand"></i></div>
        <div class="alert-text">
            Display all the details of an staff.
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">

            <!--begin::Portlet-->
            <div class="kt-portlet kt-portlet--last kt-portlet--head-lg kt-portlet--responsive-mobile"
                 id="kt_page_portlet">
                <div class="kt-portlet__head kt-portlet__head--lg">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">View Details</h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <a href="{{ url($url_prefix . '/staffs') }}" class="btn btn-clean kt-margin-r-10">
                            <i class="la la-arrow-left"></i>
                            <span class="kt-hidden-mobile">Back to List</span>
                        </a>
                        <a href="{{ url($url_prefix . '/staff/edit/'.$item['id']) }}"
                           class="btn btn-default btn-icon-sm">
                            <i class="la la-edit"></i>
                            <span class="kt-hidden-mobile">Edit</span>
                        </a>&nbsp;&nbsp;
                        <a class="btn btn-icon-sm btn-hover-danger btn-font-danger btn-delete" href="javascript:;"
                           onclick="delete_record('{{ url($url_prefix . '/staff/delete/'.$item['id']) }}');">
                            <i class="la la-trash"></i>
                            <span class="kt-hidden-mobile">Delete</span>
                        </a>
                    </div>
                </div>
                <div class="kt-portlet__body">
                    {!! Form::open(['class' => 'kt-form']) !!}
                    <div class="row">
                        
                        <div class="col-md-6">
                            <div class="kt-section kt-section--first">
                                <div class="kt-section__body">
                                    <h3 class="kt-section__title kt-section__title-lg">Staff Info:</h3>
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Staff ID
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label "><strong>{{ $item['staff_code'] }}</strong></label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Role
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['staff_role']['role'] }}</strong></label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Designation
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['staff_designation']['designation'] }}</strong></label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Department
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['staff_department']['department'] }}</strong></label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Name
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['name'] }}</strong></label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Phone no.
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['phone'] }}</strong></label>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Alternative Phone no.
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['phone_alternative'] }}</strong></label>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Email
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['email'] }}</strong></label>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Name
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['name'] }}</strong></label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                          <label class="col-3 col-form-label text-md-right">Staff Photo</label>                                         
                                          <div class="col-9">
                                            @if(isset($item['staff_document']['0']['staff_image']) && !empty($item['staff_document']['0']['staff_image']))
                                            <div class="kt-form__image-logo-display">
                                            <button type="button" class="btn"
                                            data-toggle="kt-popover" data-trigger="focus"
                                            data-placement="left"
                                            title="Staff Photo" data-html="true"
                                            data-content="<img class='popover-img' src='{{ URL::asset('uploads/staff/'. $item['staff_directory']. '/'.$item['staff_document']['0']['staff_image']) }}'
                                            alt='Staff Photo' />">
                                            <span class="kt-userpic-logo">
                                            <img src="{{ URL::asset('uploads/staff/'. $item['staff_directory']. '/'.$item['staff_document']['0']['staff_image']) }}"
                                            alt="Staff Photo"/>
                                            </span>
                                            </button>
                                            </div>
                                            @endif
                                        </div>
                                      </div> 




                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Current Address
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['current_address'] }}</strong></label>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Permanent Address
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['permanent_address'] }}</strong></label>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Facebook
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['facebook_url'] }}</strong></label>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">LinkedIn
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['linkedin_url'] }}</strong></label>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Twitter
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['twitter_url'] }}</strong></label>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Instagram
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['instagram_url'] }}</strong></label>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Enable Admin Access
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>
                                            @if($item['permission_admin_access']==1)
                                            Yes
                                            @else
                                            No
                                            @endif</strong></label>
                                        </div>
                                    </div>


                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Group
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>

                                                @isset($item['staff_user_group'][0]['user_group']['title']) {{ $item['staff_user_group'][0]['user_group']['title'] }} @endisset


                                           </strong></label>
                                        </div>
                                    </div>                                    
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="kt-section kt-section--first">
                                <div class="kt-section__body">
                                    <h3 class="kt-section__title kt-section__title-lg">&nbsp;</h3>
                                </div>
                            </div>

                                     <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Specialist
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['staff_specialist']['specialist'] }}</strong></label>
                                        </div>
                                    </div> 

                                     <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Gender
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ config('global.gender')[$item['gender']] }}</strong></label>
                                        </div>
                                    </div> 


                                     <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Marital Status
                                        </label>
                                        <div class="col-9">
                                            @if(!empty($item['maritial_status']))
                                            <label class="col-form-label"><strong>{{ config('global.maritial_status')[$item['maritial_status']] }}</strong></label>
                                            @endif
                                        </div>
                                    </div> 

                                     <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Blood Group
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['staff_blood_group']['blood_group'] }}</strong></label>
                                        </div>
                                    </div>  

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Date of Birth
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['dob'] }}</strong></label>
                                        </div>
                                    </div> 


                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Date of Joining
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['date_join'] }}</strong></label>
                                        </div>
                                    </div> 

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Qualification
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['qualification'] }}</strong></label>
                                        </div>
                                    </div> 

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Work Experience
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['work_experience'] }}</strong></label>
                                        </div>
                                    </div> 

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label text-md-right">Note
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['note'] }}</strong></label>
                                        </div>
                                    </div>

                                     @if(isset($item['staff_document']['0']['resume_file_type']) && !empty($item['staff_document']['0']['resume_file_type']))
                              <?php if(($item['staff_document']['0']['resume_file_type'] =='pdf') || ($item['staff_document']['0']['resume_file_type'] =='Pdf') || ($item['staff_document']['0']['resume_file_type'] =='PDF')) { 
                              $file_type1='fa-file-pdf';
                              $file_color_one='#BB0706';
                              }elseif(($item['staff_document']['0']['resume_file_type'] =='xlsx') || ($item['staff_document']['0']['resume_file_type'] =='xls') || ($item['staff_document']['0']['resume_file_type'] =='xltm')){ 
                              $file_type1='fa-file-excel';
                              $file_color_one='#1D6F42';
                              }elseif(($item['staff_document']['0']['resume_file_type'] =='doc') || ($item['staff_document']['0']['resume_file_type'] =='docx') || ($item['staff_document']['0']['resume_file_type'] =='dotm')){ 
                              $file_type1='fa-file-word';
                              $file_color_one='#2A5699';
                              }elseif(($item['staff_document']['0']['resume_file_type'] =='ppt') || ($item['staff_document']['0']['resume_file_type'] =='pptx')){ 
                              $file_type1='fa-file-powerpoint';
                              $file_color_one='#D04423';
                              }else{
                              $file_type1='fa-file-download';
                              } ?>
                            @endif

                             @if(isset($item['staff_document']['0']['document_file_type']) && !empty($item['staff_document']['0']['document_file_type']))

                              <?php if($item['staff_document']['0']['document_file_type'] =='pdf' || $item['staff_document']['0']['document_file_type'] =='Pdf' || $item['staff_document']['0']['document_file_type'] =='PDF') { 
                              $file_type2='fa-file-pdf';
                              $file_color_two='#BB0706';
                              }elseif($item['staff_document']['0']['document_file_type'] =='xlsx' || $item['staff_document']['0']['document_file_type'] =='xls' || $item['staff_document']['0']['document_file_type'] =='xltm'){ 
                              $file_type2='fa-file-excel';
                              $file_color_two='#1D6F42';
                              }elseif($item['staff_document']['0']['document_file_type'] =='doc' || $item['staff_document']['0']['document_file_type'] =='docx' || $item['staff_document']['0']['document_file_type'] =='dotm'){ 
                              $file_type2='fa-file-word';
                              $file_color_two='#2A5699';
                              }elseif(($item['staff_document']['0']['document_file_type'] =='ppt') || ($item['staff_document']['0']['document_file_type'] =='pptx')){ 
                              $file_type2='fa-file-powerpoint';
                              $file_color_two='#D04423';
                              }else{
                              $file_type2='fa-file-download';
                              } ?>
                            @endif

                            <div class="form-group row">
                                          <label class="col-3 col-form-label text-md-right">Resume</label>
                                          
                                          <div class="col-9">
                                          @if(isset($item['staff_document']['0']['resume']) && !empty($item['staff_document']['0']['resume']))
                                             <span><a href="{{ URL::asset('uploads/staff/' . $item['staff_directory'] .'/'.$item['staff_document']['0']['resume']) }}" download><i class="fa {{ $file_type1 }}" aria-hidden="true" style="font-size: 36px;color:{{$file_color_one}}"></i></a></span>
                                            @endif
                                        </div>
                                      </div> 


                                      <div class="form-group row">
                                          <label class="col-3 col-form-label text-md-right">Document</label>                                         
                                          <div class="col-9">
                                            @if(isset($item['staff_document']['0']['document']) && !empty($item['staff_document']['0']['document']))
                                            <span><a href="{{ URL::asset('uploads/staff/' . $item['staff_directory'] .'/'.$item['staff_document']['0']['document']) }}" download><i class="fa {{ $file_type2 }}" aria-hidden="true" style="font-size: 36px;color:{{$file_color_two}}"></i></a></span>
                                            @endif
                                        </div>
                                      </div>   


                                </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
@endsection
