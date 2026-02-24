@extends('backend.layouts.modern')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">New Staff Registration</h1>
            <p class="text-slate-500 mt-1">Onboard a new staff member, doctor, or administrator.</p>
        </div>
        <div>
            <a href="{{ route('staffs.index') }}" class="text-slate-500 hover:text-slate-700 font-medium text-sm flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Back to List
            </a>
        </div>
    </div>

    <form id="add_form" action="{{ route('staff.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Left Column: Core Info -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Professional Details -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                    <h3 class="text-base font-semibold leading-6 text-slate-900 border-b border-slate-100 pb-4 mb-4">
                        <i class="fas fa-user-tie text-primary-500 mr-2"></i> Professional Details
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Staff ID -->
                        <div>
                            <label for="staff_code" class="block text-sm font-medium leading-6 text-slate-900">Staff ID <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <input type="text" name="staff_code" id="staff_code" readonly 
                                    class="block w-full rounded-md border-0 py-1.5 text-slate-500 bg-slate-50 shadow-sm ring-1 ring-inset ring-slate-300 sm:text-sm sm:leading-6 font-mono"
                                    value="{{ $staff_code }}">
                            </div>
                        </div>

                        <!-- Department -->
                         <div>
                            <label for="department_id" class="block text-sm font-medium leading-6 text-slate-900">Department <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <select id="department_id" name="department_id" required class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled selected>Select Department</option>
                                    @foreach($staff_department_item as $data)
                                        <option value="{{ $data['id'] }}" {{ old('department_id') == $data['id'] ? 'selected' : '' }}>{{ $data['department'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Role -->
                         <div>
                            <label for="role_id" class="block text-sm font-medium leading-6 text-slate-900">Role <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <select id="role_id" name="role_id" required class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled selected>Select Role</option>
                                    @foreach($staff_role_item as $data)
                                        <option value="{{ $data['id'] }}" {{ old('role_id') == $data['id'] ? 'selected' : '' }}>{{ $data['role'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                         <!-- Designation -->
                         <div>
                            <label for="designation_id" class="block text-sm font-medium leading-6 text-slate-900">Designation <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <select id="designation_id" name="designation_id" required class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled selected>Select Designation</option>
                                    @foreach($staff_designation_item as $data)
                                        <option value="{{ $data['id'] }}" {{ old('designation_id') == $data['id'] ? 'selected' : '' }}>{{ $data['designation'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Specialist -->
                         <div>
                            <label for="specialist_id" class="block text-sm font-medium leading-6 text-slate-900">Specialist <span class="text-slate-400 font-normal">(Optional)</span></label>
                            <div class="mt-2">
                                <select id="specialist_id" name="specialist_id" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled selected>Select Specialist</option>
                                    @foreach($staff_specialist_item as $data)
                                        <option value="{{ $data['id'] }}" {{ old('specialist_id') == $data['id'] ? 'selected' : '' }}>{{ $data['specialist'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                         <!-- Date Joined -->
                         <div>
                            <label for="date_join" class="block text-sm font-medium leading-6 text-slate-900">Date of Joining</label>
                            <div class="mt-2">
                                <input type="date" name="date_join" id="date_join" value="{{ old('date_join') }}"
                                    class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Personal Information -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                     <h3 class="text-base font-semibold leading-6 text-slate-900 border-b border-slate-100 pb-4 mb-4">
                        <i class="fas fa-user-circle text-primary-500 mr-2"></i> Personal Information
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Name -->
                        <div class="md:col-span-2">
                            <label for="name" class="block text-sm font-medium leading-6 text-slate-900">Full Name <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <input type="text" name="name" id="name" required value="{{ old('name') }}"
                                    class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6"
                                    placeholder="e.g. Dr. Sarah Smith">
                            </div>
                        </div>

                        <!-- Phone -->
                         <div>
                            <label for="phone" class="block text-sm font-medium leading-6 text-slate-900">Phone Number <span class="text-red-500">*</span></label>
                            <div class="mt-2 relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-slate-500 sm:text-sm">+95</span>
                                </div>
                                <input type="text" name="phone" id="phone" required value="{{ old('phone') }}"
                                    class="block w-full rounded-md border-0 py-1.5 pl-12 text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>

                         <!-- Alt Phone -->
                         <div>
                            <label for="phone_alternative" class="block text-sm font-medium leading-6 text-slate-900">Alt Phone Number</label>
                            <div class="mt-2 relative rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-slate-500 sm:text-sm">+95</span>
                                </div>
                                <input type="text" name="phone_alternative" id="phone_alternative" value="{{ old('phone_alternative') }}"
                                    class="block w-full rounded-md border-0 py-1.5 pl-12 text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>

                         <!-- Email -->
                        <div class="md:col-span-2">
                            <label for="email" class="block text-sm font-medium leading-6 text-slate-900">Email Address <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <input type="email" name="email" id="email" required value="{{ old('email') }}"
                                    class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>
                        
                        <!-- DOB -->
                        <div>
                            <label for="dob" class="block text-sm font-medium leading-6 text-slate-900">Date of Birth</label>
                            <div class="mt-2">
                                <input type="date" name="dob" id="dob" value="{{ old('dob') }}"
                                    class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                            </div>
                        </div>

                        <!-- Gender -->
                        <div>
                            <label for="gender" class="block text-sm font-medium leading-6 text-slate-900">Gender</label>
                            <div class="mt-2">
                                <select id="gender" name="gender" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled selected>Select Gender</option>
                                    @foreach(config('global.gender') as $key => $value)
                                        <option value="{{ $key }}" {{ old('gender') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                         <!-- Marital Status -->
                        <div>
                             <label for="maritial_status" class="block text-sm font-medium leading-6 text-slate-900">Marital Status</label>
                            <div class="mt-2">
                                <select id="maritial_status" name="maritial_status" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled selected>Select Status</option>
                                    @foreach(config('global.maritial_status') as $key => $value)
                                        <option value="{{ $key }}" {{ old('maritial_status') == $key ? 'selected' : '' }}>{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                         <!-- Blood Group -->
                        <div>
                             <label for="blood_group" class="block text-sm font-medium leading-6 text-slate-900">Blood Group</label>
                            <div class="mt-2">
                                <select id="blood_group" name="blood_group" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                                    <option value="" disabled selected>Select Blood Group</option>
                                    @foreach($blood_group_item as $data)
                                        <option value="{{ $data['id'] }}" {{ old('blood_group') == $data['id'] ? 'selected' : '' }}>{{ $data['blood_group'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Current Address -->
                        <div class="md:col-span-2">
                            <label for="current_address" class="block text-sm font-medium leading-6 text-slate-900">Current Address</label>
                            <div class="mt-2">
                                <textarea name="current_address" id="current_address" rows="2" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">{{ old('current_address') }}</textarea>
                            </div>
                        </div>
                        
                        <!-- Permanent Address -->
                        <div class="md:col-span-2">
                            <label for="permanent_address" class="block text-sm font-medium leading-6 text-slate-900">Permanent Address</label>
                            <div class="mt-2">
                                <textarea name="permanent_address" id="permanent_address" rows="2" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">{{ old('permanent_address') }}</textarea>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!-- Right Column: Files & Admin -->
            <div class="space-y-6">
                <!-- Admin Access -->
                <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                     <h3 class="text-base font-semibold leading-6 text-slate-900 border-b border-slate-100 pb-4 mb-4">
                        <i class="fas fa-shield-alt text-primary-500 mr-2"></i> System Access
                    </h3>
                    
                    <div class="flex items-start">
                        <div class="flex h-6 items-center">
                            <input id="permission_admin_access" name="permission_admin_access" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-primary-600 focus:ring-primary-600">
                        </div>
                        <div class="ml-3">
                            <label for="permission_admin_access" class="text-sm font-medium leading-6 text-slate-900">Enable Admin Login</label>
                            <p class="text-xs text-slate-500">Allow this staff member to log in to the system dashboard.</p>
                        </div>
                    </div>
                    
                    <div id="admin_group_container" class="mt-4 hidden">
                         <label for="group_id" class="block text-sm font-medium leading-6 text-slate-900">User Group (Permissions)</label>
                        <div class="mt-2">
                            <select id="group_id" name="group_id" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                                <option value="" disabled selected>Select Group</option>
                                @foreach($groups as $data)
                                    <option value="{{ $data['id'] }}" {{ old('group_id') == $data['id'] ? 'selected' : '' }}>{{ $data['title'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Documents -->
                 <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                     <h3 class="text-base font-semibold leading-6 text-slate-900 border-b border-slate-100 pb-4 mb-4">
                        <i class="fas fa-file-upload text-primary-500 mr-2"></i> Documents
                    </h3>
                    
                    <div class="space-y-6">
                        <!-- Profile Photo -->
                        <div>
                            <label class="block text-sm font-medium leading-6 text-slate-900">Profile Photo</label>
                            <div class="mt-2 flex items-center gap-x-3">
                                <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-300 overflow-hidden" id="photo_preview">
                                    <i class="fas fa-user text-xl"></i>
                                </div>
                                <input type="file" name="staff_image" id="staff_image" accept="image/*" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                            </div>
                        </div>

                         <!-- Resume -->
                         <div>
                            <label class="block text-sm font-medium leading-6 text-slate-900">Resume / CV</label>
                            <div class="mt-2">
                                <input type="file" name="resume" id="resume" accept=".pdf,.doc,.docx" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-slate-50 file:text-slate-700 hover:file:bg-slate-100">
                            </div>
                            <p class="text-xs text-slate-500 mt-1">PDF, DOC, DOCX up to 10MB</p>
                        </div>

                         <!-- Other Docs -->
                          <div>
                            <label class="block text-sm font-medium leading-6 text-slate-900">Other Documents</label>
                            <div class="mt-2">
                                <input type="file" name="document" id="document" accept=".pdf,.doc,.docx" class="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-slate-50 file:text-slate-700 hover:file:bg-slate-100">
                            </div>
                             <p class="text-xs text-slate-500 mt-1">Certificates, ID Copies etc.</p>
                        </div>
                    </div>
                </div>

                <!-- Social Links -->
                 <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
                     <h3 class="text-base font-semibold leading-6 text-slate-900 border-b border-slate-100 pb-4 mb-4">
                        <i class="fas fa-share-alt text-primary-500 mr-2"></i> Social Profiles
                    </h3>
                     <div class="space-y-4">
                         <div class="relative rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fab fa-facebook-f text-slate-400"></i>
                            </div>
                            <input type="text" name="facebook_url" id="facebook_url" class="block w-full rounded-md border-0 py-1.5 pl-10 text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" placeholder="Facebook URL">
                        </div>
                         <div class="relative rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fab fa-linkedin-in text-slate-400"></i>
                            </div>
                            <input type="text" name="linkedin_url" id="linkedin_url" class="block w-full rounded-md border-0 py-1.5 pl-10 text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" placeholder="LinkedIn URL">
                        </div>
                         <div class="relative rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fab fa-twitter text-slate-400"></i>
                            </div>
                            <input type="text" name="twitter_url" id="twitter_url" class="block w-full rounded-md border-0 py-1.5 pl-10 text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" placeholder="Twitter URL">
                        </div>
                         <div class="relative rounded-md shadow-sm">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                <i class="fab fa-instagram text-slate-400"></i>
                            </div>
                            <input type="text" name="instagram_url" id="instagram_url" class="block w-full rounded-md border-0 py-1.5 pl-10 text-slate-900 ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" placeholder="Instagram URL">
                        </div>
                    </div>
                 </div>

            </div>
        </div>
        
        <div class="flex items-center justify-end border-t border-slate-200 pt-6">
            <button type="button" onclick="window.location='{{ route('staffs.index') }}'" class="text-sm font-semibold leading-6 text-slate-900 mr-6">Cancel</button>
            <button type="submit" class="rounded-md bg-primary-600 px-8 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition-all flex items-center">
                <i class="fas fa-save mr-2"></i> Save Staff Member
            </button>
        </div>

    </form>
</div>
@include('backend.layouts.includes.admin_modal_popup_alert') 
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        // Toggle Admin Group Select
        $('#permission_admin_access').on('change', function() {
            if($(this).is(':checked')) {
                $('#admin_group_container').removeClass('hidden').slideDown();
            } else {
                 $('#admin_group_container').slideUp();
            }
        });

        // Email Duplicate Check
        $("#email").on('change', function() {
            var email = $(this).val();
            if(email) {
                $.ajax({
                    url: "{{ route('staff_duplicate_email') }}/" + email,
                    type: "GET",
                    success: function(response) {
                        if (response == 1) {
                            $('#popupmodel').modal('show');
                            $('.modelTitleClass').html("Duplicate Email");
                            $('#popupContent').html("This email address is already registered.");
                            $("#email").val('');
                        }
                    }
                });
            }
        });

        // Validate Admin Role Selection before submit
        $('form').on('submit', function(e) {
            if($('#permission_admin_access').is(':checked')) {
                if(!$('#group_id').val()) {
                    e.preventDefault();
                    toastr.error('Please select a User Group for admin access.');
                    $('#group_id').focus();
                }
            }
        });

         // Simple Image Preview
        $('#staff_image').on('change', function(){
            const file = this.files[0];
            if (file){
                let reader = new FileReader();
                reader.onload = function(event){
                    $('#photo_preview').html('<img src="' + event.target.result + '" class="h-full w-full object-cover">');
                }
                reader.readAsDataURL(file);
            }
        });
    });
</script>
@endsection
