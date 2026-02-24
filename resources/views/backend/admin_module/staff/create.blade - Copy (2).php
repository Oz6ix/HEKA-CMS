@extends('backend.layouts.admin')
@section('breadcrumb')    
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <a href="{{ url($url_prefix . '/staffs') }}" class="kt-subheader__breadcrumbs-link">
        Staff </a>
    <span class="kt-subheader__breadcrumbs-separator"></span>
    <span class="kt-subheader__breadcrumbs-link kt-subheader__breadcrumbs-link--active">Add</span>
@endsection
@section('content')


<style type="text/css">

  .dropzone {
    min-height: 102px;
    border: 2px solid rgba(0, 0, 0, 0.3);
    background: whitesmoke!important;
    padding: 20px 20px;
}
  

</style>

    <!-- Messages section -->
    @include('backend.layouts.includes.notification_alerts')
    <div class="alert alert-light alert-elevate" role="alert">
        <div class="alert-icon"><i class="flaticon-information kt-font-brand"></i></div>
        <div class="alert-text">
            Add a new staff.
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <!--begin::Portlet-->
            <div class="kt-portlet kt-portlet--last kt-portlet--head-lg kt-portlet--responsive-mobile"
                 id="kt_page_portlet">
                <div class="kt-portlet__head kt-portlet__head--lg">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">Input Details</h3>
                    </div>
                    <div class="kt-portlet__head-toolbar">
                        <a href="{{ url($url_prefix . '/staffs') }}" class="btn btn-clean kt-margin-r-10">
                            <i class="la la-arrow-left"></i>
                            <span class="kt-hidden-mobile">Back to List</span>
                        </a>
                        <div class="btn-group">
                            <button type="button" class="btn btn-brand button-submit" id="add_button">
                                <i class="la la-check"></i>
                                <span class="kt-hidden-mobile">Save</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="kt-portlet__body">
                   {!! Form::open(['id' => 'add_form','name' => 'add_form', 'class' => 'kt-form ', 'files' => true, 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}                   

                    <div class="row">                   
                        <div class="col-md-6">
                            <div class="kt-section kt-section--first">
                                <div class="kt-section__body">
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">
                                        </label>
                                        <div class="col-9 form-info">
                                         
                                        </div>
                                    </div>
                                    <h3 class="kt-section__title kt-section__title-lg">Staff Info:</h3>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Staff ID
                                            <span class="form-info"> * </span>
                                           
                                        </label>
                                        <div class="col-9">
                                            <input type="text" class="form-control" name="staff_code" id="staff_code"
                                                   placeholder="Enter staff id"
                                                   value="{{$staff_code}}"/>
                                        </div>
                                      </div> 

                                      <div class="form-group row">
                                        <label class="col-3 col-form-label">Role
                                            <span class="form-info"> * </span>
                                            
                                        </label>
                                        <div class="col-9">
                                             <select class="form-control kt-select2" id="select2_role" >
                                              <option value="">--- Select Role---</option>
                                                @foreach($staff_role_item as $data)
                                                    <option value="{{ $data['id'] }}">
                                                        {{ $data['role'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="role_id" id="role_id"
                                                   value="{{ old('role_id') }}"/>
                                        </div>
                                      </div> 

                                      <div class="form-group row">
                                        <label class="col-3 col-form-label">Designation
                                            <span class="form-info"> * </span>
                                            
                                        </label>
                                        <div class="col-9">
                                              <select class="form-control kt-select2" id="select2_designation" >
                                                <option value="">--- Select Designation---</option>
                                                @foreach($staff_designation_item as $data)
                                                    <option value="{{ $data['id'] }}">
                                                        {{ $data['designation'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="designation_id" id="designation_id"
                                                   value="{{ old('designation_id') }}"/>
                                        </div>
                                      </div> 

                                      <div class="form-group row">
                                        <label class="col-3 col-form-label">Department
                                            <span class="form-info"> * </span>
                                          
                                        </label>
                                        <div class="col-9">
                                              <select class="form-control kt-select2" id="select2_department" >
                                                <option value="">--- Select Department---</option>
                                                @foreach($staff_department_item as $data)
                                                    <option value="{{ $data['id'] }}">
                                                        {{ $data['department'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="department_id" id="department_id"
                                                   value="{{ old('department_id') }}"/>
                                        </div>
                                      </div> 


                                      <div class="form-group row">
                                        <label class="col-3 col-form-label">Name
                                            <span class="form-info"> * </span>
                                           
                                        </label>
                                        <div class="col-9">
                                            <input type="text" class="form-control" name="name" id="name"
                                                   placeholder="Enter staff name"
                                                   value="{{ old('name') }}"/>
                                        </div>
                                      </div> 
                                      <div class="form-group row">
                                        <label class="col-3 col-form-label">Phone no.
                                            <span class="form-info"> * </span>
                                           
                                        </label>
                                        <div class="col-9">
                                            <input type="text" class="form-control" name="phone" id="phone"
                                                   placeholder="Enter phone number"
                                                   value="{{ old('phone') }}"/>
                                        </div>
                                      </div> 


                                      <div class="form-group row">
                                        <label class="col-3 col-form-label">Alternative Phone no.
                                            <span class="form-info">  </span>
                                           
                                        </label>
                                        <div class="col-9">
                                            <input type="text" class="form-control" name="phone_alternative" id="phone_alternative" placeholder="Enter alternative phone number"
                                                   value="{{ old('phone_alternative') }}"/>
                                        </div>
                                      </div> 

                                      <div class="form-group row">
                                        <label class="col-3 col-form-label">Email
                                            <span class="form-info"> * </span>
                                            
                                        </label>
                                        <div class="col-9">
                                            <input type="text" class="form-control" name="email" id="email"
                                                   placeholder="Enter email address"
                                                   value="{{ old('email') }}"/>
                                        </div>
                                      </div> 

                                      <div class="form-group row">
                                      <label class="col-3 col-form-label">Staff Photo
                                      <span class="form-info"> * </span>
                                      </label>
                                      <div class="col-9 dzsp_zone">  
                                      <div class="kt-dropzone dropzoneDragArea dropzone"    id="dropzoneDragArea">
                                      <div class=" dropzone-previews kt-dropzone__msg dz-message needsclick">
                                      <h3 class="kt-dropzone__msg-title">Drop files here or click to upload.</h3>
                                      </div>
                                      </div>
                                      </div>
                                      </div>


                                       




                                      <div class="form-group row">
                                        <label class="col-3 col-form-label">Current Address
                                            <span class="form-info"> * </span>
                                           
                                        </label>
                                        <div class="col-9">
                                            <textarea class="form-control" name="current_address" id="current_address" rows="5" spellcheck="false" placeholder="Enter current address">{!! old('current_address') !!}</textarea>                                            
                                        </div>
                                      </div> 
                                      <div class="form-group row">
                                        <label class="col-3 col-form-label">Permanent Address
                                            <span class="form-info"> * </span>
                                           
                                        </label>
                                        <div class="col-9">
                                           <textarea class="form-control" name="permanent_address" id="permanent_address" rows="5" spellcheck="false" placeholder="Enter permanent address">{!! old('permanent_address') !!}</textarea>
                                            
                                        </div>
                                      </div> 
                                      <div class="form-group row">
                                        <label class="col-3 col-form-label">Facebook
                                            <span class="form-info">  </span>
                                           
                                        </label>
                                        <div class="col-9">
                                            <input type="text" class="form-control" name="facebook_url" id="facebook_url"
                                                   placeholder="Enter facebook id"
                                                   value="{{ old('facebook_url') }}"/>
                                        </div>
                                      </div> 
                                      <div class="form-group row">
                                        <label class="col-3 col-form-label">LinkedIn
                                            <span class="form-info">  </span>
                                          
                                        </label>
                                        <div class="col-9">
                                            <input type="text" class="form-control" name="linkedin_url" id="linkedin_url"
                                                   placeholder="Enter linkedin url"
                                                   value="{{ old('linkedin_url') }}"/>
                                        </div>
                                      </div> 

                                      <div class="form-group row">
                                        <label class="col-3 col-form-label">Twitter
                                            <span class="form-info">  </span>
                                           
                                        </label>
                                        <div class="col-9">
                                            <input type="text" class="form-control" name="twitter_url" id="twitter_url"
                                                   placeholder="Enter twitter url"
                                                   value="{{ old('twitter_url') }}"/>
                                        </div>
                                      </div> 

                                       <div class="form-group row">
                                        <label class="col-3 col-form-label">Instagram
                                            <span class="form-info">  </span>
                                           
                                        </label>
                                        <div class="col-9">
                                            <input type="text" class="form-control" name="instagram_url" id="instagram_url"
                                                   placeholder="Enter instagram url"
                                                   value="{{ old('instagram_url') }}"/>
                                        </div>
                                      </div> 
                                 
                                    <!-- One additional row added below to adjust page height -->
                                </div>
                            </div>
                        </div>

                         <div class="col-md-6">
                            <div class="kt-section kt-section--first">
                                <div class="kt-section__body">
                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">
                                        </label>
                                        <div class="col-9 form-info">
                                            * = Required
                                        </div>
                                    </div>
                                    <h3 class="kt-section__title kt-section__title-lg">&nbsp;</h3>

                                    <div class="form-group row">
                                        <label class="col-3 col-form-label">Specialist
                                            <span class="form-info"> * </span>
                                           
                                        </label>
                                        <div class="col-9">
                                            <select class="form-control kt-select2" id="select2_specialist" >
                                              <option value="">--- Select Specialist---</option>
                                                @foreach($staff_specialist_item as $data)
                                                    <option value="{{ $data['id'] }}">
                                                        {{ $data['specialist'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="specialist_id" id="specialist_id"
                                                   value="{{ old('specialist_id') }}"/>
                                        </div>
                                      </div> 

                                      <div class="form-group row">
                                        <label class="col-3 col-form-label">Gender
                                            <span class="form-info"> * </span>
                                          
                                        </label>
                                        <div class="col-9">
                                            <select class="form-control" name="gender" id="gender">
                                            <option disabled selected value>
                                            Select an option
                                            </option>  
                                            @foreach(config('global.gender') as $key => $value)
                                            <option value="{{ $key }}" >{{ $value }}</option>
                                            @endforeach 
                                            </select> 

                                        </div>
                                      </div> 



                                      <div class="form-group row">
                                        <label class="col-3 col-form-label">Marital Status
                                            <span class="form-info"> * </span>
                                         
                                        </label>
                                        <div class="col-9">
                                            <select class="form-control" name="maritial_status" id="maritial_status">
                                            <option disabled selected value>
                                            Select an option
                                            </option>  
                                            @foreach(config('global.maritial_status') as $key => $value)
                                            <option value="{{ $key }}" >{{ $value }}</option>
                                            @endforeach 
                                            </select> 
                                        </div>
                                      </div> 

                                      <div class="form-group row">
                                        <label class="col-3 col-form-label">Blood Group
                                            <span class="form-info">  </span>
                                           
                                        </label>
                                        <div class="col-9">

                                          <select class="form-control kt-select2" id="select2_blood_group" >
                                                <option value="">--- Select Blood Group---</option>
                                                @foreach($blood_group_item as $data)
                                                    <option value="{{ $data['id'] }}">
                                                        {{ $data['blood_group'] }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            <input type="hidden" name="blood_group" id="blood_group"
                                                   value="{{ old('blood_group') }}"/>                                         
                                        </div>
                                      </div> 


                                      <div class="form-group row">
                                        <label class="col-3 col-form-label">Date of Birth
                                            <span class="form-info"> * </span>
                                          
                                        </label>
                                        <div class="col-9">                                           

                                            <input type="date"  placeholder="Enter date of birth" class="form-control" name="dob" id="dob" value=""  min="1935-01-01" max="2050-12-31"> 

                                        </div>
                                      </div> 
                                      <div class="form-group row">
                                        <label class="col-3 col-form-label">Date of Joining
                                            <span class="form-info"> * </span>
                                           
                                        </label>
                                        <div class="col-9">                                            
                                            <input type="date"  placeholder="Enter date of joining" class="form-control" name="date_join" id="date_join" value=""  min="1935-01-01" max="2050-12-31"> 
                                        </div>
                                      </div> 
                                      <div class="form-group row">
                                        <label class="col-3 col-form-label">Qualification
                                            <span class="form-info">  </span>
                                          
                                        </label>
                                        <div class="col-9">
                                            <textarea class="form-control" name="qualification" id="qualification" rows="5" spellcheck="false" placeholder="Enter qualification">{!! old('qualification') !!}</textarea>
                                        </div>
                                      </div> 

                                      <div class="form-group row">
                                        <label class="col-3 col-form-label">Work Experience
                                            <span class="form-info">  </span>
                                           
                                        </label>
                                        <div class="col-9">
                                            <textarea class="form-control" name="work_experience" id="work_experience" rows="5" spellcheck="false" placeholder="Enter work experience">{!! old('work_experience') !!}</textarea>

                                        </div>
                                      </div> 


                                      <div class="form-group row">
                                        <label class="col-3 col-form-label">Note 
                                        </label>
                                        <div class="col-9">
                                          <textarea class="form-control" name="note" id="note" rows="5" spellcheck="false" placeholder="Enter note">{!! old('note') !!}</textarea>
                                        </div>
                                      </div> 


                                      <div class="form-group row">
                                      <label class="col-3 col-form-label">Resume
                                      <span class="form-info">  </span>

                                      </label>
                                      <div class="col-9 dzsp_zone">
                                       <div class="kt-dropzone dropzoneDragArea dropzone"    id="dropzoneDragAreasecond">
                                            <div class=" dropzone-previews-second kt-dropzone__msg dz-message needsclick">
                                            <h3 class="kt-dropzone__msg-title">Drop files here or click to upload.</h3>
                                            </div>
                                            </div>
                                      </div>
                                      </div>

                                      <div class="form-group row">
                                      <label class="col-3 col-form-label">Document
                                      <span class="form-info"> * </span>
                                      </label>
                                      <div class="col-9 dzsp_zone">
                                       <div class="kt-dropzone dropzoneDragArea dropzone"    id="dropzoneDragAreathird">
                                            <div class=" dropzone-previews-third kt-dropzone__msg dz-message needsclick">
                                            <h3 class="kt-dropzone__msg-title">Drop files here or click to upload.</h3>
                                            </div>
                                            </div>
                                      </div>
                                      </div> 
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
@section('scripts')

<script>
Dropzone.autoDiscover = false;
// Dropzone.options.add_form = false; 
//let token = $('meta[name="csrf-token"]').attr('content');
$(function() {
  var myDropzone = new Dropzone("div#dropzoneDragArea", { 
  paramName: "file",
  url: "{{ route('staffImage') }}",
  previewsContainer: 'div.dropzone-previews',
  addRemoveLinks: true,
  autoProcessQueue: false,
  uploadMultiple: false,
  parallelUploads: 1,
  maxFiles: 1,
  acceptedFiles: "image/*",
  params: {
        _token: '{{csrf_token()}}' 
        
    },
   // The setting up of the dropzone
  init: function() { 
      var myDropzone = this;
      //form submission code goes here
      $("form[name='add_form']").submit(function(event) {  
        //Make sure that the form isn't actully being sent.
        event.preventDefault();
        URL = "{{ route('staff_add') }}";

        //alert(URL);
           
        formData = $('#add_form').serialize();
             //alert(JSON.stringify(formData));
        $.ajax({
          type: 'POST',
                paramName: "file",
          url: URL,
          data: formData,
          success: function(result){
            if(result.status == "success"){
              // fetch the useid                      
              //process the queue
              myDropzone.processQueue();
                        window.location.href = "{{ url('am/staffs') }}";
            }else{
              console.log("error");
            }
          }
        });
      });
      //Gets triggered when we submit the image.
      this.on('sending', function(file, xhr, formData){
      //fetch the user id from hidden input field and send that userid with our image
        let userid = document.getElementById('staff_code').value;
       formData.append('staff_code', userid);
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





<script>
Dropzone.autoDiscover = false;
// Dropzone.options.add_form = false; 
//let token = $('meta[name="csrf-token"]').attr('content');
$(function() {
  var myDropzone = new Dropzone("div#dropzoneDragAreasecond", { 
  paramName: "file",
  url: "{{ route('storeResume') }}",
  previewsContainer: 'div.dropzone-previews-second',
  addRemoveLinks: true,
  autoProcessQueue: false,
  uploadMultiple: false,
  parallelUploads: 20,
  maxFiles: 1,
  maxFilesize: 10,
  acceptedFiles: "image/*,application/pdf,.doc,.docx,.xls,.xlsx,.csv,.tsv,.ppt,.pptx,.pages,.odt,.rtf",
  "error": function(file, message, xhr) {
       if (xhr == null) this.removeFile(file); // perhaps not remove on xhr errors
       alert(message);
    },
  params: {
        _token: '{{csrf_token()}}' 
        
    },
   // The setting up of the dropzone
  init: function() { 
      var myDropzone = this;
      //form submission code goes here
      $("form[name='add_form']").submit(function(event) {  
        //Make sure that the form isn't actully being sent.
        event.preventDefault();
        URL = "{{ route('staff_add') }}";  
        formData = $('#add_form').serialize();
             //alert(JSON.stringify(formData));
        $.ajax({
          type: 'POST',
                paramName: "file",
          url: URL,
          data: formData,
          success: function(result){
            if(result.status == "success"){
              // fetch the useid 
              //var userid = result.user_id;            
              //process the queue
              myDropzone.processQueue();
                        window.location.href = "{{ url('am/staffs') }}";
            }else{
              console.log("error");
            }
          }
        });
      });

      //Gets triggered when we submit the image.
      this.on('sending', function(file, xhr, formData){
      //fetch the user id from hidden input field and send that userid with our image
        let userid = document.getElementById('staff_code').value;
       formData.append('staff_code', userid);
    });

      this.on("maxfilesexceeded", function (file) {
      this.removeAllFiles();
      this.addFile(file);
      });    
      this.on("success", function (file, response) {
            //reset the form
            $('#add_form')[0].reset();
            //reset dropzone
            $('.dropzone-previews-second').empty();
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



<script>
Dropzone.autoDiscover = false;
// Dropzone.options.add_form = false; 
//let token = $('meta[name="csrf-token"]').attr('content');
$(function() {
  
  var myDropzone = new Dropzone("div#dropzoneDragAreathird", { 
  paramName: "file",
  url: "{{ route('staffDocument') }}",
  previewsContainer: 'div.dropzone-previews-third',
  addRemoveLinks: true,
  autoProcessQueue: false,
  uploadMultiple: false,
  parallelUploads: 20,
  maxFiles: 1,
  maxFilesize: 10,
  acceptedFiles: "image/*,application/pdf,.doc,.docx,.xls,.xlsx,.csv,.tsv,.ppt,.pptx,.pages,.odt,.rtf",
  "error": function(file, message, xhr) {
       if (xhr == null) this.removeFile(file); // perhaps not remove on xhr errors
       alert(message);
    },

  params: {
        _token: '{{csrf_token()}}' 
        
    },
   // The setting up of the dropzone
  init: function() { 
      var myDropzone = this;
      //form submission code goes here
      $("form[name='add_form']").submit(function(event) {   
        //Make sure that the form isn't actully being sent.
        event.preventDefault();
        URL = "{{ route('staff_add') }}";  
        formData = $('#add_form').serialize();
             //alert(JSON.stringify(formData));
        $.ajax({
          type: 'POST',
                paramName: "file",
          url: URL,
          data: formData,
          success: function(result){
            if(result.status == "success"){
              // fetch the useid 
              //var userid = result.user_id;           
              //process the queue
              myDropzone.processQueue();
                        window.location.href = "{{ url('am/staffs') }}";
            }else{
              console.log("error");
            }
          }
        });
      });

      //Gets triggered when we submit the image.
      this.on('sending', function(file, xhr, formData){
      //fetch the user id from hidden input field and send that userid with our image
        let userid = document.getElementById('staff_code').value;
       formData.append('staff_code', userid);
    });

      this.on("maxfilesexceeded", function (file) {
      this.removeAllFiles();
      this.addFile(file);
      });    
      this.on("success", function (file, response) {
            //reset the form
            $('#add_form')[0].reset();
            //reset dropzone
            $('.dropzone-previews-third').empty();
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
   <script src="{{ URL::asset('resources/assets/backend/js/validations/staffs.js') }}" type="text/javascript"></script>     
    <script src="{{ URL::asset('resources/assets/backend/js/scripts/staffs.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('resources/assets/backend/js/demo1/pages/crud/forms/widgets/bootstrap-datepicker.js') }}" type="text/javascript"></script>
    <script src="{{ URL::asset('resources/assets/backend/js/demo1/pages/crud/forms/widgets/summernote.js') }}" type="text/javascript"></script>
    <!--end::Page Scripts -->
@endsection
