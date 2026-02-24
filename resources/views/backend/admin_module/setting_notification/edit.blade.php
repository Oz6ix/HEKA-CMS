@extends('backend.layouts.modern')


@section('content')
    
    <div class="space-y-6">
        <div class=" col-md-9">
            <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                @include('backend.admin_module.setting_notification.includes.notification')                
            </div>
        </div>

         
    </div>
@endsection
@section('scripts')   
    <!--begin::Page Scripts(used by this page) -->   
    <script src="{{ URL::asset('assets/backend/js/validations/settings_notification.js') }}"
            type="text/javascript"></script> 
    <script src="{{ URL::asset('assets/backend/js/demo1/pages/crud/forms/widgets/form-repeater.js') }}"
            type="text/javascript"></script>
    
    <!--end::Page Scripts -->
@endsection