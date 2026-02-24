@extends('backend.layouts.modern')

@section('content')
    <!-- Messages section -->
    
    
    <div class="space-y-6">
        <div class="sm:col-span-3">
            <!--begin::Portlet-->
            <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                    <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                        <h2 class="text-base font-semibold text-slate-900">View Details</h2>
                    </div>
                    <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                        <a href="{{ url($url_prefix . '/user_groups') }}" class="inline-flex items-center rounded-md bg-slate-100 px-3 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200">
                            <i class="fas fa-arrow-left"></i>
                            <span class="kt-hidden-mobile">Back to List</span>
                        </a>
                        <a href="{{ url($url_prefix . '/user_group/edit/'.$item['id']) }}"
                           class="btn btn-default btn-icon-sm">
                            <i class="la la-edit"></i>
                            <span class="kt-hidden-mobile">Edit</span>
                        </a>&nbsp;&nbsp;
                        <a class="text-red-600 hover:text-red-900 text-sm" href="javascript:;"
                           onclick="delete_record('{{ url($url_prefix . '/user_group/delete/'.$item['id']) }}');">
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
                                    <h3 class="kt-section__title kt-section__title-lg">Group Info:</h3>
                                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="block text-sm font-medium text-slate-700">Group Title
                                        </label>
                                        <div class="sm:col-span-3">
                                            <label class="col-form-label"><strong>{{ $item['title'] }}</strong></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="kt-separator kt-separator--border-dashed kt-separator--space-lg"></div>
                            <div>
                                <div>
                                    <h3 class="kt-section__title kt-section__title-lg">Group Permissions:</h3>
                                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="col-3 col-form-label pd-sp-2">Users</label>
                                        <div class="sm:col-span-3">
                                            <div class="kt-checkbox-list">
                                                                                                
                                            </div>
                                        </div>
                                    </div>


                                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="col-3 col-form-label pd-sp-2">Staffs</label>
                                        <div class="sm:col-span-3">
                                            <div class="kt-checkbox-list">
                                                                                              
                                            </div>
                                        </div>
                                    </div>



                                     <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="col-3 col-form-label pd-sp-2">Patients</label>
                                        <div class="sm:col-span-3">
                                            <div class="kt-checkbox-list">
                                                                                              
                                            </div>
                                        </div>
                                    </div>


                                     <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="col-3 col-form-label pd-sp-2">Appointments</label>
                                        <div class="sm:col-span-3">
                                            <div class="kt-checkbox-list">
                                                                                              
                                            </div>
                                        </div>
                                    </div>


                                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="col-3 col-form-label pd-sp-2">Bills</label>
                                        <div class="sm:col-span-3">
                                            <div class="kt-checkbox-list">
                                                                                              
                                            </div>
                                        </div>
                                    </div>



                                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="col-3 col-form-label pd-sp-2">Inventory</label>
                                        <div class="sm:col-span-3">
                                            <div class="kt-checkbox-list">
                                                                                              
                                            </div>
                                        </div>
                                    </div>
                                  
                                    
                                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="col-3 col-form-label pd-sp-2">Reports</label>
                                        <div class="sm:col-span-3">
                                            <div class="kt-checkbox-list">
                                                
                                                                                                
                                            </div>
                                        </div>
                                    </div>
                                    <div class="sm:grid sm:grid-cols-4 sm:items-start sm:gap-4">
                                        <label class="col-3 col-form-label pd-sp-2">Settings</label>
                                        <div class="sm:col-span-3">
                                            <div class="kt-checkbox-list">
                                                
                                                

                                                

                                                

                                                 

                                                 


                                                 


                                                 


                                                                                               


                                            </div>
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
