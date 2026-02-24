@extends('backend.layouts.admin')

@section('breadcrumb')
    
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ url($url_prefix . '/patient') }}" class="kt-subheader__breadcrumbs-link">
        Patients </a>
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">View</span>
@endsection

@section('content')
    <!-- Messages section -->
    @include('backend.layouts.includes.notification_alerts')
    <div class="alert alert-light alert-elevate" role="alert">
        <div class="alert-icon"><i class="flaticon-information kt-font-brand"></i></div>
        <div class="alert-text">
            Display all the details of a Patient.
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">

            <!--begin::Portlet-->
            <div class="kt-portlet kt-portlet--last kt-portlet--head-lg kt-portlet--responsive-mobile"
                 id="kt_page_portlet">
                <div class="kt-portlet__head kt-portlet__head--lg">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title"></h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <a href="{{ url($url_prefix . '/patient') }}" class="btn btn-clean kt-margin-r-10">
                            <i class="la la-arrow-left"></i>
                            <span class="kt-hidden-mobile">Back to List</span>
                        </a>
                        <a href="{{ url($url_prefix . '/patient/edit/'.$item['id']) }}"
                           class="btn btn-default btn-icon-sm">
                            <i class="la la-edit"></i>
                            <span class="kt-hidden-mobile">Edit</span>
                        </a>&nbsp;&nbsp;
                        <a class="btn btn-icon-sm btn-hover-danger btn-font-danger btn-delete" href="javascript:;"
                           onclick="delete_record('{{ url($url_prefix . '/patient/delete/'.$item['id']) }}');">
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
                                <div class="kt-section__body detail-show">
                                    <h3 class="kt-section__title kt-section__title-lg">Patient Info:</h3>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Patient Code
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['patient_code'] }}</strong></label>
                                        </div>
                                    </div>
                                        <!-- <div class="form-group row"> 
                                            <label class="col-3 col-form-label">Any Known Allergies
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['any_known_allergies'] }}</strong></label>
                                        </div> 
                                        </div> -->
                                        <div class="form-group row">
                                            <label class="col-3 col-form-label">Name
                                            </label>
                                            <div class="col-9">
                                                <label class="col-form-label"><strong>{{ $item['name'] }}</strong></label>
                                            </div>
                                        </div>
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Email Address
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['email'] }}</strong></label>
                                        </div>
                                    </div>      
                                        <!-- <label class="col-3 col-form-label">Patient Photo
                                        </label>
                                        <div class="col-9">
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
                                        </div> -->
                                        <div class="form-group row">
                                            <label class="col-3 col-form-label">Phone
                                            </label>
                                            <div class="col-9">
                                                <label class="col-form-label"><strong>{{ $item['phone'] }}</strong></label>
                                            </div>
                                        </div>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Gender
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ config('global.gender')[$item['gender']] }}</strong></label>
                                        </div>
                                    </div>
                                    <!-- <div class="form-group row">
                                        <label class="col-3 col-form-label">Address
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['address'] }}</strong></label>
                                        </div>
                                    </div> -->
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Date Of Birth
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['dob'] }}</strong></label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Years
                                        </label>
                                        <div class="col-1">
                                            <label class="col-form-label"><strong>{{ $item['age_year'] }}</strong></label>
                                        </div>
                                        <label class="col-3 col-form-label">Months
                                        </label>
                                        <div class="col-1">
                                            <label class="col-form-label"><strong>{{ $item['age_month'] }}</strong></label>
                                        </div>
                                    </div>
                                    <!-- <div class="form-group row">
                                        <label class="col-3 col-form-label">Remarks
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['remark'] }}</strong></label>
                                        </div>
                                    </div> -->
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Guardian Name
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['guardian_name'] }}</strong></label>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Blood Group
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['patient_blood_group']['blood_group'] }}</strong></label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Marital Status
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label">
                                                @if(!empty($item['marital_status']))
                                                <strong>{{ config('global.maritial_status')[$item['marital_status']] }}</strong>
                                                @endif
                                            </label>
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
                                            <label class="col-3 col-form-label">Any Known Allergies
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['any_known_allergies'] }}</strong></label>
                                        </div> 
                                        </div> 

                                        <label class="col-3 col-form-label">Patient Photo
                                        </label>
                                        <div class="col-9">
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
                                        </div>
                                        
                                        <div class="form-group row">
                                        <label class="col-3 col-form-label">Address
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['address'] }}</strong></label>
                                        </div>
                                    </div> 
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Remarks
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong>{{ $item['remark'] }}</strong></label>
                                        </div>
                                    </div>
                                    <!-- Two additional rows added below to adjust page height -->
                                    <!-- <div class="form-group row">
                                        <label class="col-3 col-form-label">
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong></strong></label>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">
                                        </label>
                                        <div class="col-9">
                                            <label class="col-form-label"><strong></strong></label>
                                        </div>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2"></div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
@endsection
