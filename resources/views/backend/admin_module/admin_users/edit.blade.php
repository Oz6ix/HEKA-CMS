@extends('backend.layouts.modern')

@section('content')
<div class="space-y-6">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Edit Admin User</h1>
            <p class="mt-1 text-sm text-slate-500">Update user account details.</p>
        </div>
        <a href="{{ url($url_prefix . '/admin_users') }}"
           class="inline-flex items-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
            <i class="fas fa-arrow-left mr-2 text-slate-400"></i> Back
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden max-w-2xl">
        {!! Form::open(['route'=>'admin_user_update', 'id' => 'update_form', 'class' => 'kt-form', 'files' => true, 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
        <input type="hidden" name="id" value="{{ $item['id'] }}">
        <div class="p-6 space-y-5">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Full Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ $item['name'] }}" placeholder="Enter full name"
                    class="block w-full rounded-lg border-slate-300 text-sm focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Email Address <span class="text-red-500">*</span></label>
                <input type="email" name="email" id="email" value="{{ $item['email'] }}" placeholder="Enter email address"
                    class="block w-full rounded-lg border-slate-300 text-sm focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Phone <span class="text-red-500">*</span></label>
                <div class="flex">
                    <span class="inline-flex items-center px-3 rounded-l-lg border border-r-0 border-slate-300 bg-slate-50 text-sm text-slate-500">+95</span>
                    <input type="text" name="phone" id="phone" value="{{ $item['phone'] }}" placeholder="Enter phone number"
                        class="block w-full rounded-none rounded-r-lg border-slate-300 text-sm focus:ring-primary-500 focus:border-primary-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">User Group <span class="text-red-500">*</span></label>
                <select name="group_id" id="group_id" class="block w-full rounded-lg border-slate-300 text-sm focus:ring-primary-500 focus:border-primary-500">
                    <option value="">Select group</option>
                    @foreach($groups as $data)
                        <option value="{{ $data->id }}" {{ ($data->id == $item['group_id']) ? 'selected' : '' }}>{{ $data->title }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="border-t border-slate-200 px-6 py-4 bg-slate-50 flex items-center justify-end gap-3">
            <a href="{{ url($url_prefix . '/admin_users') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-white transition-colors">Cancel</a>
            <button type="submit" class="rounded-lg bg-primary-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 transition-colors">
                <i class="fas fa-check mr-2"></i> Update
            </button>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ URL::asset('assets/backend/js/validations/admin_users.js') }}" type="text/javascript"></script>
@endsection