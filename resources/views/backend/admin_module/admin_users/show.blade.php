@extends('backend.layouts.modern')



@section('content')
    <!-- Messages section -->
    
    

    <div class="space-y-6">
        <div class="col-lg-12">

            <!--begin::Portlet-->
            <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                    <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                        <h2 class="text-base font-semibold text-slate-900">View Details</h2>
                    </div>
                    <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                        <a href="{{ url($url_prefix . '/admin_users') }}" class="inline-flex items-center rounded-md bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200">
                            <i class="fas fa-arrow-left"></i>
                            <span class="kt-hidden-mobile">Back to List</span>
                        </a>
                        <a href="{{ url($url_prefix . '/admin_user/edit/'.$item['id']) }}"
                           class="btn btn-default btn-icon-sm">
                            <i class="la la-edit"></i>
                            <span class="kt-hidden-mobile">Edit</span>
                        </a>&nbsp;&nbsp;
                        <a class="text-red-600 hover:text-red-900 text-sm" href="javascript:;"
                           onclick="delete_record('{{ url($url_prefix . '/admin_user/delete/'.$item['id']) }}');">
                            <i class="fas fa-trash"></i>
                            <span class="kt-hidden-mobile">Delete</span>
                        </a>
                    </div>
                </div>
                <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                    {!! Form::open(['class' => 'kt-form']) !!}
                    <div class="space-y-6">
                        <div class="col-xl-2"></div>
                        <div class="col-xl-8">
                            <div>
                                <div>
                                    <h3 class="kt-section__title kt-section__title-lg">User Info:</h3>
                                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="block text-sm font-medium text-slate-700">Name
                                        </label>
                                        <div class="sm:col-span-3">
                                            <label class="col-form-label"><strong>{{ $item['name'] }}</strong></label>
                                        </div>
                                    </div>
                                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="block text-sm font-medium text-slate-700">Email Address
                                        </label>
                                        <div class="sm:col-span-3">
                                            <label class="col-form-label"><strong>{{ $item['email'] }}</strong></label>
                                        </div>
                                    </div>
                                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="block text-sm font-medium text-slate-700">Phone
                                        </label>
                                        <div class="sm:col-span-3">
                                            <label class="col-form-label"><strong>{{ $item['phone'] }}</strong></label>
                                        </div>
                                    </div>
                                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="block text-sm font-medium text-slate-700">User Group
                                        </label>
                                        <div class="sm:col-span-3">
                                            <label class="col-form-label"><strong>{{ $item->user_group['title'] }}</strong></label>
                                        </div>
                                    </div>
                                    <!-- Two additional rows added below to adjust page height -->
                                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="block text-sm font-medium text-slate-700">
                                        </label>
                                        <div class="sm:col-span-3">
                                            <label class="col-form-label"><strong></strong></label>
                                        </div>
                                    </div>
                                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="block text-sm font-medium text-slate-700">
                                        </label>
                                        <div class="sm:col-span-3">
                                            <label class="col-form-label"><strong></strong></label>
                                        </div>
                                    </div>
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
