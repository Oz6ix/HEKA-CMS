@extends('backend.layouts.admin')
@section('breadcrumb')
<style>
.dropzone .dz-preview .dz-progress {
display:none;
}
.dropzone .dz-preview .dz-image {
  width:100%;  
  max-width: 120px;
  height: 120px;
}

</style>

    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="javascript:;" class="kt-subheader__breadcrumbs-link">
        Users </a>
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ url($url_prefix . '/admin_users') }}" class="kt-subheader__breadcrumbs-link">
        Admin Users </a>
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Edit</span>
@endsection
@section('content')
    <!-- Messages section -->
    @include('backend.layouts.includes.notification_alerts')
    <div class="alert alert-light alert-elevate" role="alert">
        <div class="alert-icon"><i class="flaticon-information kt-font-brand"></i></div>
        <div class="alert-text">
            Edit an existing admin user.
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="kt-portlet kt-portlet--last kt-portlet--head-lg kt-portlet--responsive-mobile"
                 id="kt_page_portlet">
                <div class="kt-portlet__head kt-portlet__head--lg">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">Edit Details</h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <a href="{{ url($url_prefix . '/patient') }}" class="btn btn-clean kt-margin-r-10">
                            <i class="la la-arrow-left"></i>
                            <span class="kt-hidden-mobile">Back to List</span>
                        </a>
                        <div class="btn-group">
                            <button type="button" class="btn btn-brand button-submit" id="add_button">
                                <i class="la la-check"></i>
                                <span class="kt-hidden-mobile">Update</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">
<!--                 {!! Form::open(['id' => 'add_form','name' => 'add_form', 'class' => 'kt-form ', 'files' => true,  'enctype' => 'multipart/form-data']) !!}-->
<meta name="csrf-token" content="{{ csrf_token() }}">
<form method="POST" action="" accept-charset="UTF-8" id="add_form" name="add_form" class="kt-form " enctype="multipart/form-data" novalidate="novalidate">
                    <input type="hidden" name="id" value="{{$item->id }}">
                    <input type="hidden" name="patient_folder_name" value="{{$item->patient_folder_name }}">
                    <div class="row">
                        <div class="col-xl-2"></div>
                        <div class="col-xl-8">
                            <div class="kt-section kt-section--first">
                                <div class="kt-section__body">
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">
                                        </label>
                                        <div class="col-9 form-info">
                                            * = Required
                                        </div>
                                    </div>
                                    <h3 class="kt-section__title kt-section__title-lg">Patient Info:</h3>
                            <!-- Form group 1 -->
                                <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label class="col-form-label">Patient ID
                                            <span class="form-info"> * </span>
                                            <span data-skin="dark" data-toggle="kt-tooltip" data-placement="top"
                                                    title="" data-original-title="Patient ID">
                                                <i class="fa fa-question-circle"></i>
                                            </span>
                                        </label>
                                    <input type="text" class="form-control" readonly name="patient_code" id="patient_code" placeholder="Enter Patient ID"
                                                value="{{ $item->patient_code }}"/>
                                                <label class="col-form-label">Name
                                            <span class="form-info"> * </span>
                                            <span data-skin="dark" data-toggle="kt-tooltip" data-placement="top"
                                                    title="" data-original-title="Name of the admin user">
                                                <i class="fa fa-question-circle"></i>
                                            </span>
                                        </label>
                                    <input type="text" class="form-control" name="name" id="name" placeholder="Enter full name"
                                                value="{{ $item->name }}"/>
                                                
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="col-form-label">Any Known Allergies
                                            <span class="form-info"> * </span>
                                            <span data-skin="dark" data-toggle="kt-tooltip" data-placement="top"
                                                    title="" data-original-title="Name of the admin user">
                                                <i class="fa fa-question-circle"></i>
                                            </span>
                                        </label>
                                        <textarea  class="form-control" rows="5" name="any_known_allergies" id="any_known_allergies" placeholder="Any Known allergies">{{  $item->any_known_allergies }}</textarea>
                                    </div>
                                </div>
                            <!-- ! End Form group 1 -->

                            <!-- Form group 2 -->
                            <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label class="col-form-label">Phone
                                            <span class="form-info"> * </span>
                                            <span data-skin="dark" data-toggle="kt-tooltip" data-placement="top"
                                                    title="" data-original-title="Patient ID">
                                                <i class="fa fa-question-circle"></i>
                                            </span>
                                        </label>
                                    <input type="text" class="form-control" name="phone" id="phone" placeholder="Enter Phone number"
                                                value="{{  $item->phone }}"/>
                                                <label class="col-form-label">Emaill
                                            <span class="form-info"> * </span>
                                            <span data-skin="dark" data-toggle="kt-tooltip" data-placement="top"
                                                    title="" data-original-title="Name of the admin user">
                                                <i class="fa fa-question-circle"></i>
                                            </span>
                                        </label>
                                    <input type="email" class="form-control" name="email" id="email" placeholder="Enter email"
                                                value="{{  $item->email }}"/>
                                                
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="col-form-label">photo
                                            <span class="form-info"> * </span>
                                            <span data-skin="dark" data-toggle="kt-tooltip" data-placement="top"
                                                    title="" data-original-title="Name of the admin user">
                                                <i class="fa fa-question-circle"></i>
                                            </span>
                                        </label>
                                        <textarea  class="form-control" rows="5" name="patient_photo" id="patient_photo" placeholder="patient photo">{{  $item->patient_photo }}</textarea>
                                    </div>
                                </div>                            <!-- ! End Form group 2 -->
                            <!-- Form group 3 -->
                            <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label class="col-form-label">Gender
                                            <span class="form-info"> * </span>
                                            <span data-skin="dark" data-toggle="kt-tooltip" data-placement="top"
                                                    title="" data-original-title="Patient ID">
                                                <i class="fa fa-question-circle"></i>
                                            </span>
                                        </label>
                                            <select  class="form-control" name="gender" id="gender">
                                                <option value="">Select Gender</option>
                                                <option value="Male" {{ ($item->gender == 'Male') ? 'selected' : '' }} >Male</option>
                                                <option value="Female" {{ ($item->gender == 'Female') ? 'selected' : '' }} >Female</option>
                                            </select>
                                                <label class="col-form-label">Date of Birth
                                            <span class="form-info"> * </span>
                                            <span data-skin="dark" data-toggle="kt-tooltip" data-placement="top"
                                                    title="" data-original-title="Date Of Birth">
                                                <i class="fa fa-question-circle"></i>
                                            </span>
                                        </label>
                                    <input type="date" class="form-control" name="dob" id="dob" placeholder="Enter Date Of Birth"
                                                value="{{ $item->dob }}"/>
                                                
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="col-form-label">Address
                                            <span class="form-info">  </span>
                                            <span data-skin="dark" data-toggle="kt-tooltip" data-placement="top"
                                                    title="" data-original-title="Address of the patient">
                                                <i class="fa fa-question-circle"></i>
                                            </span>
                                        </label>
                                        <textarea  class="form-control" rows="5" name="address" id="address" placeholder="Address">{{ $item->address }}</textarea>
                                    </div>
                                </div>                            <!-- ! End Form group 3  -->
                            <!-- Form group 4 -->
                            <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label class="col-form-label">Age
                                            <span class="form-info"> * </span> 
                                            <span data-skin="dark" data-toggle="kt-tooltip" data-placement="top"
                                                    title="" data-original-title="Age of Patient">
                                                <i class="fa fa-question-circle"></i>
                                            </span>
                                        </label><br/>
                                        <input type="text" class="form-control col-lg-4" style="display: inline-block;" name="age_year" id="age_year" placeholder="Year"
                                                value="{{ $item->age_year }}"/>
                                        <input type="text" class="form-control col-lg-4" style="display: inline;" name="age_month" id="age_month" placeholder="Month"
                                        value="{{ $item->age_month }}"/>
                                        <div class="col-lg-4"></div>
                                                <label class="col-form-label">Guardian Name
                                            <span class="form-info"> * </span>
                                            <span data-skin="dark" data-toggle="kt-tooltip" data-placement="top"
                                                    title="" data-original-title="Guardian Name">
                                                <i class="fa fa-question-circle"></i>
                                            </span>
                                        </label>
                                         <input type="text" class="form-control" name="guardian_name" id="guardian_name" placeholder="Guardian Name"
                                                value="{{ $item->guardian_name }}"/>
                                                
                                    </div>
                                    <div class="col-lg-6">
                                        <label class="col-form-label">Remarks
                                            <span class="form-info"> * </span>
                                            <span data-skin="dark" data-toggle="kt-tooltip" data-placement="top"
                                                    title="" data-original-title="Remarks">
                                                <i class="fa fa-question-circle"></i>
                                            </span>
                                        </label>
                                        <textarea  class="form-control" rows="5" name="remark" id="remark" placeholder="Remarks">{{ $item->remark }}</textarea>
                                    </div>
                                </div>                            <!-- ! End Form group 4 -->
                            <!-- Form group 5 -->
                            <div class="form-group row">
                                    <div class="col-lg-6">
                                        <label class="col-form-label">Blood Group
                                            <span class="form-info"> * </span>
                                            <span data-skin="dark" data-toggle="kt-tooltip" data-placement="top"
                                                    title="" data-original-title="Blood Group">
                                                <i class="fa fa-question-circle"></i>
                                            </span>
                                        </label>
                                    <select class="form-control" name="blood_group" id="blood_group">
                                        <option value="">Select Blood Group</option>
                                        <option value="A-positive" {{ ("A-positive" == $item->blood_group) ? 'selected' : '' }}>A-positive</option>
                                        <option value="A-negative" {{ ("A-negative" == $item->blood_group) ? 'selected' : '' }}>A-negative</option>
                                        <option value="B-positive" {{ ("B-positive" == $item->blood_group) ? 'selected' : '' }}>B-positive</option>
                                        <option value="B-negative" {{ ("B-negative" == $item->blood_group) ? 'selected' : '' }}>B-negative</option>
                                        <option value="AB-positive" {{ ("AB-positive" == $item->blood_group) ? 'selected' : '' }}>AB-positive</option>
                                        <option value="AB-negative" {{ ("AB-negative" == $item->blood_group) ? 'selected' : '' }}>AB-negative</option>
                                        <option value="O-positive" {{ ("O-positive" == $item->blood_group) ? 'selected' : '' }}>O-positive</option>
                                        <option value="O-negative" {{ ("O-negative" == $item->blood_group) ? 'selected' : '' }}>O-negative</option>
                                    </select>
                                                <label class="col-form-label">Marital Status
                                            <span class="form-info"> * </span>
                                            <span data-skin="dark" data-toggle="kt-tooltip" data-placement="top"
                                                    title="" data-original-title="Marital Status">
                                                <i class="fa fa-question-circle"></i>
                                            </span>
                                        </label>
                                    <select class="form-control" name="marital_status" id="marital_status">
                                        <option value="">Select</option>
                                        <option value="Single" {{ ("Single" == $item->marital_status) ? 'selected' : '' }}>Single</option>
                                        <option value="Married" {{ ("Married" == $item->marital_status) ? 'selected' : '' }}>Married</option>
                                        <option value="Divorced" {{ ("Divorced" == $item->marital_status) ? 'selected' : '' }}>Divorced</option>
                                        <option value="Widowed" {{ ("Widowed" == $item->marital_status) ? 'selected' : '' }}>Widowed</option>
                                    </select>
                                    </div>
                                    <div class="col-lg-6">
                                    </div>
                                </div>                                               
                                    <!-- One additional row added below to adjust page height -->
                                    
                                    
                                     <div class="form-group row">
                                    <label class="col-form-label col-lg-3 col-sm-12">Patient Photo</label>
                                    <div class="col-lg-4 col-md-9 col-sm-12">
                                        <div class="kt-dropzone dropzoneDragArea dropzone"    id="dropzoneDragArea">
                                                <div class=" dropzone-previews kt-dropzone__msg dz-message needsclick">
                                                <h3 class="kt-dropzone__msg-title">Drop files here or click to upload.</h3>
                                           </div>
                                        </div>
                                    </div>
								</div>
                                     
                                    
                                    
                                    
                                    
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">
                                        </label>
                                        <div class="col-9 form-info">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-2"></div>
                    </div>
</form>
                </div>
            </div>
            <!--end::Portlet-->
        </div>
    </div>
@endsection
@section('scripts')
<!-- Adding a script for dropzone -->
<script>

$(document).ready(function(){
//alert("{{ url('am/patient/viewImage/'.$item->id) }}");
});

Dropzone.autoDiscover = false;
$('.dz-progress').hide();
// Dropzone.options.add_form = false;	
let token = $('meta[name="csrf-token"]').attr('content');
$(function() {
var myDropzone = new Dropzone("div#dropzoneDragArea", { 
	paramName: "file",
	url: "{{ url('am/patient/storeImage') }}",
	previewsContainer: 'div.dropzone-previews',
	addRemoveLinks: true,
	autoProcessQueue: false,
	uploadMultiple: false,
	parallelUploads: 1,
	maxFiles: 1,
	params: {
        _token: '{{csrf_token()}}' 
        
    },
	 // The setting up of the dropzone
	init: function() {
        $.getJSON("{{ url('am/patient/viewImage/'.$item->id) }}", function(data) {
    $.each(data, function (key, value) {
       // alert(JSON.stringify(data));
	var file = {name: value.name, size: value.size, status: 'success'};
	myDropzone.options.addedfile.call(myDropzone, file);
/*     myDropzone.on("addedfile", function(file) {
  file.previewElement.addEventListener("click", function() {
    myDropzone.removeFile(file);
  });
});
 */	myDropzone.options.thumbnail.call(myDropzone, file, value.path);
    Dropzone.options.myDropzone = {thumbnailWidth: 120,thumbnailHeight: 120,};
	myDropzone.emit("complete", file);
    myDropzone.files.push(file);
    //myDropzone._updateMaxFilesReachedClass();

}); 
}); 

        
	    var myDropzone = this;
	    //form submission code goes here
	    $("form[name='add_form']").submit(function(event) {
	    	//Make sure that the form isn't actully being sent.
	    	event.preventDefault();

	    	URL = "{{ url('am/patient/update') }}";
           
	    	formData = $('#add_form').serialize();
             //alert(JSON.stringify(formData));
	    	$.ajax({
	    		type: 'POST',
                paramName: "file",
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
	    		url: URL,
	    		data: formData,
	    		success: function(result){
	    			if(result.status == "success"){
	    				// fetch the useid 
	    				//var userid = result.user_id;
						/* $("#patient_code").val('10zz'); */ // inseting userid into hidden input field
	    				//process the queue
                       
	    				myDropzone.processQueue();

                        window.location.href = "{{ url('am/patient') }}";
	    			}else{
	    				console.log("error");
	    			}
	    		}
	    	});
	    });
	    //Gets triggered when we submit the image.
	    this.on('sending', function(file, xhr, formData){
            
	    //fetch the user id from hidden input field and send that userid with our image
	      let userid = document.getElementById('patient_code').value;
		  formData.append('patient_code', userid);
          
		});

        this.on("maxfilesexceeded", function (file) {
           
        this.removeAllFiles();
        this.addFile(file);
    });

	    this.on("success", function (file, response) {

            //reset the form
            $('#add_form')[0].reset();
            //reset dropzone
            $('.dropzone-previews').empty();
        });

        this.on("queuecomplete", function () {
        });
		
        // Listen to the sendingmultiple event. In this case, it's the sendingmultiple event instead
	    // of the sending event because uploadMultiple is set to true.
	    this.on("sendingmultiple", function() {
	      // Gets triggered when the form is actually being sent.
	      // Hide the success button or the complete form.
	    });
		
	    this.on("successmultiple", function(files, response) {
	      // Gets triggered when the files have successfully been sent.
	      // Redirect user or notify of success.
	    });
		
	    this.on("errormultiple", function(files, response) {
	      // Gets triggered when there was an error sending the files.
	      // Maybe show form again, and notify user of error
	    });
	}
	});
});
</script>
    <!--begin::Page Scripts(used by this page) -->
<!--     <script src=".{{ URL::asset('assets/backend/js/demo1/pages/crud/forms/widgets/dropzone.js') }}" type="text/javascript"></script>
 -->    
 <script src="{{ URL::asset('assets/backend/js/validations/patient.js') }}"
            type="text/javascript"></script>
    <!--end::Page Scripts -->

@endsection
               
