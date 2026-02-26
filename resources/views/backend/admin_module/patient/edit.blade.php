@extends('backend.layouts.modern')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Patient Registration</h1>
            <p class="text-slate-500 mt-1">Update patient record details.</p>
        </div>
        <div>
            <a href="{{ route('patient.index') }}" class="text-slate-500 hover:text-slate-700 font-medium text-sm flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to List
            </a>
        </div>
    </div>

    <!-- Main Form -->
    <form id="edit_form" name="add_form" method="POST" action="{{ route('patient_update') }}" class="space-y-6" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{ $item->id }}">
        <input type="hidden" name="patient_photo_check" id="patient_photo_check" value="0">
        <input type="hidden" name="patient_folder_name" value="{{ $item->patient_folder_name }}">

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Personal Info -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Basic Information Card -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                     <h3 class="text-base font-semibold leading-6 text-slate-900 border-b border-slate-100 pb-4 mb-4">
                        <i class="fas fa-user-circle text-primary-500 mr-2"></i> Personal Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Patient Code -->
                        <div>
                            <label for="patient_code" class="block text-sm font-medium leading-6 text-slate-900">Patient ID</label>
                            <div class="mt-2">
                                <input type="text" name="patient_code" id="patient_code" readonly 
                                    class="block w-full rounded-md border-0 py-1.5 text-slate-500 bg-slate-50 shadow-sm ring-1 ring-inset ring-slate-300 sm:text-sm sm:leading-6 font-mono"
                                    value="{{ $item->patient_code }}">
                            </div>
                        </div>

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium leading-6 text-slate-900">Full Name <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <input type="text" name="name" id="name" required value="{{ $item->name }}"
                                    class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>

                        <!-- Phone -->
                         <div>
                            <label for="phone" class="block text-sm font-medium leading-6 text-slate-900">Phone Number <span class="text-red-500">*</span></label>
                            <div class="mt-2 relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-slate-500 sm:text-sm">+95</span>
                                </div>
                                <input type="text" name="phone" id="phone" required value="{{ $item->phone }}"
                                    class="block w-full rounded-md border-0 py-1.5 pl-12 text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>

                         <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium leading-6 text-slate-900">Email Address</label>
                            <div class="mt-2">
                                <input type="email" name="email" id="email" value="{{ $item->email }}" data-original="{{ $item->email }}"
                                    class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>

                        <!-- Date of Birth -->
                        <div>
                            <label for="dob" class="block text-sm font-medium leading-6 text-slate-900">Date of Birth <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <input type="date" name="dob" id="dob" required max="{{ date('Y-m-d') }}" value="{{ $item->dob }}"
                                    class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>

                         <!-- Age (Auto Calc) -->
                        <div class="flex gap-2">
                             <div class="w-1/2">
                                <label for="age_year" class="block text-sm font-medium leading-6 text-slate-900">Age (Years)</label>
                                <div class="mt-2">
                                    <input type="text" name="age_year" id="age_year" readonly value="{{ $item->age_year }}"
                                        class="block w-full rounded-md border-0 py-1.5 text-slate-500 bg-slate-50 shadow-sm ring-1 ring-inset ring-slate-300 sm:text-sm sm:leading-6">
                                </div>
                            </div>
                             <div class="w-1/2">
                                <label for="age_month" class="block text-sm font-medium leading-6 text-slate-900">Months</label>
                                <div class="mt-2">
                                    <input type="text" name="age_month" id="age_month" readonly value="{{ $item->age_month }}"
                                        class="block w-full rounded-md border-0 py-1.5 text-slate-500 bg-slate-50 shadow-sm ring-1 ring-inset ring-slate-300 sm:text-sm sm:leading-6">
                                </div>
                            </div>
                        </div>

                        <!-- Gender -->
                        <div>
                            <label for="gender" class="block text-sm font-medium leading-6 text-slate-900">Gender <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <select id="gender" name="gender" required class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Select Gender</option>
                                    @foreach(config('global.gender') as $key => $value)
                                        <option value="{{ $key }}" {{ $key == $item->gender ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Marital Status -->
                        <div>
                            <label for="marital_status" class="block text-sm font-medium leading-6 text-slate-900">Marital Status</label>
                            <div class="mt-2">
                                <select id="marital_status" name="marital_status" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                                     <option value="" disabled>Select Status</option>
                                    @foreach(config('global.maritial_status') as $key => $value)
                                        <option value="{{ $key }}" {{ $key == $item->marital_status ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Guardian & Emergency -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-base font-semibold leading-6 text-slate-900 border-b border-slate-100 pb-4 mb-4">
                        <i class="fas fa-users text-primary-500 mr-2"></i> Guardian & Emergency Info
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="guardian_name" class="block text-sm font-medium leading-6 text-slate-900">Guardian Name <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <input type="text" name="guardian_name" id="guardian_name" required value="{{ $item->guardian_name }}"
                                    class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                         <div>
                            <label for="phone_alternative" class="block text-sm font-medium leading-6 text-slate-900">Alt. Phone</label>
                            <div class="mt-2 relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-slate-500 sm:text-sm">+95</span>
                                </div>
                                <input type="text" name="phone_alternative" id="phone_alternative" value="{{ $item->phone_alternative }}"
                                    class="block w-full rounded-md border-0 py-1.5 pl-12 text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        <div class="md:col-span-2">
                            <label for="address" class="block text-sm font-medium leading-6 text-slate-900">Address</label>
                            <div class="mt-2">
                                <textarea name="address" id="address" rows="3" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">{{ $item->address }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Right Column: Medical & Photo -->
            <div class="space-y-6">
                <!-- Medical Info -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-base font-semibold leading-6 text-slate-900 border-b border-slate-100 pb-4 mb-4">
                        <i class="fas fa-briefcase-medical text-primary-500 mr-2"></i> Medical Details
                    </h3>
                    <div class="space-y-6">
                        <div>
                            <label for="blood_group" class="block text-sm font-medium leading-6 text-slate-900">Blood Group <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <select id="blood_group" name="blood_group" required class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled>Select Blood Group</option>
                                    @foreach($blood_group_item as $data)
                                        <option value="{{ $data['id'] }}" {{ $data['id'] == $item->blood_group ? 'selected' : '' }}>{{ $data['blood_group'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div>
                            <label for="any_known_allergies" class="block text-sm font-medium leading-6 text-slate-900">Allergies</label>
                            <div class="mt-2">
                                <textarea name="any_known_allergies" id="any_known_allergies" rows="4" 
                                    class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">{{ $item->any_known_allergies }}</textarea>
                            </div>
                        </div>

                         <div>
                            <label for="remark" class="block text-sm font-medium leading-6 text-slate-900">Remarks</label>
                            <div class="mt-2">
                                <textarea name="remark" id="remark" rows="3" 
                                    class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">{{ $item->remark }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Photo Upload -->
                 <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-base font-semibold leading-6 text-slate-900 border-b border-slate-100 pb-4 mb-4">
                        <i class="fas fa-camera text-primary-500 mr-2"></i> Patient Photo
                    </h3>
                    <div id="dropzoneDragArea" class="dropzone rounded-lg border-2 border-dashed border-slate-300 bg-slate-50 hover:bg-slate-100 transition-colors cursor-pointer flex flex-col items-center justify-center p-6 text-center">
                         <div class="dz-message needsclick">
                            <i class="fas fa-cloud-upload-alt text-4xl text-slate-400 mb-3"></i>
                            <h3 class="text-sm font-medium text-slate-900">Click or Drag to Upload</h3>
                            <p class="text-xs text-slate-500 mt-1">PNG, JPG up to 5MB</p>
                        </div>
                        <div class="dropzone-previews mt-4 w-full"></div>
                    </div>
                </div>

            </div>
        </div>

        <div class="flex items-center justify-end border-t border-slate-200 pt-6">
            <button type="button" onclick="window.location='{{ route('patient.index') }}'" class="text-sm font-semibold leading-6 text-slate-900 mr-6">Cancel</button>
            <button type="submit" id="add_button" class="rounded-md bg-primary-600 px-8 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition-all flex items-center">
                <i class="fas fa-check mr-2"></i> Update Patient
            </button>
        </div>
    </form>
</div>
@include('backend.layouts.includes.admin_modal_popup_alert') 
@endsection

@section('scripts')
<script>
    Dropzone.autoDiscover = false;
    
    $(document).ready(function() {
        // Age Calculator
        $('#dob').on('change', function() {
            var dob = new Date($(this).val());
            var today = new Date();
            var age = Math.floor((today - dob) / (365.25 * 24 * 60 * 60 * 1000));
            $("#age_year").val(age);
            
            var months = (today.getFullYear() - dob.getFullYear()) * 12;
            months -= dob.getMonth();
            months += today.getMonth();
            months = months % 12; // Adjust to 0-11
            if(months < 0) months += 12;
            
            $("#age_month").val(months);
        });

        // Email Duplicate Check
        $("#email").on('change', function() {
            var email = $(this).val();
            var original = $(this).data('original');
            if(email && email !== original) {
                $.ajax({
                    url: "{{ route('patient_duplicate_email') }}/" + email,
                    type: "GET",
                    success: function(response) {
                        if (response == 1) {
                            $('#popupmodel').modal('show');
                            $('.modelTitleClass').html("Duplicate Email");
                            $('#popupContent').html("This email address is already registered.");
                            $("#email").val(original);
                        }
                    }
                });
            }
        });

        // Dropzone & Form Submit
        var myDropzone = new Dropzone("div#dropzoneDragArea", { 
            paramName: "file",
            url: "{{ route('storeImage') }}",
            previewsContainer: 'div.dropzone-previews',
            addRemoveLinks: true,
            autoProcessQueue: false,
            uploadMultiple: false,
            parallelUploads: 1,
            maxFiles: 1,
            acceptedFiles: "image/*",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            init: function() {
                var dz = this;
                
                // Preload existing image
                $.getJSON("{{ url($url_prefix.'/patient/viewImage/'.$item->id) }}", function(data) {
                    if(data && data.length > 0 && data[0] != "") {
                        var file = { name: data[0].name, size: 12345, status: 'success', accepted: true }; // Dummy size
                        dz.emit("addedfile", file);
                        dz.emit("thumbnail", file, data[0].path);
                        dz.emit("complete", file);
                        dz.files.push(file);
                    }
                });

                // Form Submit Handler
                $("form[name='add_form']").submit(function(e) {
                    e.preventDefault();
                    var formData = new FormData(this);
                    var submitBtn = $('#add_button');
                    submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin mr-2"></i> Updating...');

                    $.ajax({
                        type: 'POST',
                        url: "{{ url($url_prefix.'/patient/update') }}",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(result) {
                            if(result.status == "success") {
                                // Process Image Upload if new file added
                                if (dz.getQueuedFiles().length > 0) {
                                    dz.processQueue();
                                } else {
                                     finishUpdate();
                                }
                            } else if(result.status == "duplicate") {
                                toastr.warning(result.message);
                                submitBtn.prop('disabled', false).html('<i class="fas fa-check mr-2"></i> Update Patient');
                            } else {
                                toastr.error("Error updating patient");
                                submitBtn.prop('disabled', false).html('<i class="fas fa-check mr-2"></i> Update Patient');
                            }
                        },
                        error: function(xhr) {
                            var errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                toastr.error(value[0]);
                            });
                             submitBtn.prop('disabled', false).html('<i class="fas fa-check mr-2"></i> Update Patient');
                        }
                    });
                });

                // Attach Patient ID to Image Upload
                this.on('sending', function(file, xhr, formData){
                    formData.append('patient_code', $('#patient_code').val());
                });

                this.on("removedfile", function(file) {
                    if(file.status == 'success') {
                        $('#patient_photo_check').val('1'); // Mark for deletion
                    }
                });

                // On Image Upload Success/Complete
                this.on("queuecomplete", function () {
                    finishUpdate();
                });
            }
        });

        function finishUpdate() {
            toastr.success("Patient updated successfully!");
            setTimeout(function() {
                window.location.href = "{{ route('patient.index') }}";
            }, 1500);
        }
    });
</script>
@endsection
