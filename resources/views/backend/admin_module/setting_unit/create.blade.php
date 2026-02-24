@extends('backend.layouts.modern')

@section('content')
<div class="space-y-6">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Add Unit</h1>
            <p class="mt-1 text-sm text-slate-500">Create a new Unit record.</p>
        </div>
        <a href="{{ url($url_prefix . '/setting_unit') }}"
           class="inline-flex items-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
            <i class="fas fa-arrow-left mr-2 text-slate-400"></i> Back
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden max-w-2xl">
        {!! Form::open(['route'=>'unit_add', 'id' => 'add_form', 'class' => 'kt-form', 'files' => true, 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
        <div class="p-6 space-y-5">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Unit Name <span class="text-red-500">*</span></label>
                <input type="text" name="unit" id="unit" value="{{ old('unit') }}" placeholder="Enter Unit Name" 
                    class="block w-full rounded-lg border-slate-300 text-sm focus:ring-primary-500 focus:border-primary-500">
            </div>
        </div>
        <div class="border-t border-slate-200 px-6 py-4 bg-slate-50 flex items-center justify-end gap-3">
            <a href="{{ url($url_prefix . '/setting_unit') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-white transition-colors">Cancel</a>
            <button type="submit" class="rounded-lg bg-primary-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 transition-colors">
                <i class="fas fa-check mr-2"></i> Save
            </button>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ URL::asset('assets/backend/js/validations/setting_unit.js') }}" type="text/javascript"></script>
@endsection
