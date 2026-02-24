@extends('backend.layouts.modern')

@section('content')
    <!-- Messages section -->
    
    
    <div class="space-y-6">
        <div class="col-md-6">
            <!--begin::Portlet-->
            <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                    <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                        <h3 class="kt-portlet__head-title">
                            Update Profile
                        </h3>
                    </div>
                </div>
                <!--begin::Form-->
                {!! Form::open(['url' => url($url_prefix . '/profile/update'), 'id' => 'update_form', 'class' => 'kt-form', 'files' => true, 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
                <input type="hidden" name="phone" value="{{$item['phone']}}">
                <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                        <label>Name
                            <span class="text-red-500 text-xs"> * </span>
                            
                        </label>
                        <input type="text" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="name" pattern="^[a-zA-Z0-9 ]+$" id="name" placeholder="Enter full name"
                               value="{{ $item['name'] }}"/>
                    </div>
                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                        <label>Email
                            <span class="text-red-500 text-xs"> * </span>
                            
                        </label>
                        <input type="text" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="email" id="email" placeholder="Enter email"
                               value="{{ $item['email'] }}"/>
                    </div>
                </div>
                <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                    <div class="kt-form__actions">
                        <button type="submit" class="inline-flex items-center rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500">
                            <i class="fas fa-check"></i>Update
                        </button>
                    </div>
                </div>
            {!! Form::close() !!}
            <!--end::Form-->
            </div>
            <!--end::Portlet-->
        </div>
        <div class="col-md-6">
            <!--begin::Portlet-->
            <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                    <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                        <h3 class="kt-portlet__head-title">
                            Update Password
                        </h3>
                    </div>
                </div>
                <!--begin::Form-->
                {!! Form::open(['url' => url($url_prefix . '/password/update'), 'id' => 'password_form', 'class' => 'kt-form', 'files' => true, 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
                <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                        <label>Current Password
                            <span class="text-red-500 text-xs"> * </span>
                            
                        </label>
                        <input type="password" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="current_password" id="current_password"
                               placeholder="Enter current password"/>
                    </div>
                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                        <label>New Password
                            <span class="text-red-500 text-xs"> * </span>
                            
                        </label>
                        <input type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="new_password" id="new_password"
                               placeholder="Enter new password"/>
                    </div>
                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                        <label>Confirm Password
                            <span class="text-red-500 text-xs"> * </span>
                           
                        </label>
                        <input type="password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"  class="block w-full rounded-md border-0 py-2 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" name="confirm_password" id="confirm_password"
                               placeholder="Confirm new password"/>
                    </div>
                </div>
                <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                    <div class="kt-form__actions">
                        <button type="submit" class="inline-flex items-center rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500">
                            <i class="fas fa-check"></i>Update
                        </button>
                    </div>
                </div>
            {!! Form::close() !!}
            <!--end::Form-->
            </div>
            <!--end::Portlet-->
        </div>
    </div>
@endsection
@section('scripts')
    <!--begin::Page Scripts(used by this page) -->
    <script src="{{ URL::asset('assets/backend/js/validations/admin_profile.js') }}"
            type="text/javascript"></script>
    <!--end::Page Scripts -->
@endsection
