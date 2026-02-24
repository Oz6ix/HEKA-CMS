@extends('backend.layouts.modern')

@section('content')
<div class="space-y-6">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Edit Pathology Test</h1>
            <p class="mt-1 text-sm text-slate-500">Update test details, pricing, and parameters.</p>
        </div>
        <a href="{{ url($url_prefix . '/pathology') }}"
           class="inline-flex items-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
            <i class="fas fa-arrow-left mr-2 text-slate-400"></i> Back to Tests
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
        {!! Form::open(['route'=>'pathology_update', 'id' => 'update_form', 'class' => 'kt-form', 'files' => true, 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
        <input type="hidden" name="id" value="{{ $item['id'] }}">
        <div class="p-6 space-y-6">
            <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-3">Test Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Test Name <span class="text-red-500">*</span></label>
                    <input type="text" name="test" id="test" value="{{ $item['test'] }}" placeholder="e.g. Fasting Blood Sugar"
                        class="block w-full rounded-lg border-slate-300 text-sm focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Code <span class="text-red-500">*</span></label>
                    <input type="text" name="code" id="code" value="{{ $item['code'] }}" readonly
                        class="block w-full rounded-lg border-slate-300 bg-slate-50 text-sm text-slate-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Category <span class="text-red-500">*</span></label>
                    <select id="select2_pathology_category_id" class="block w-full rounded-lg border-slate-300 text-sm focus:ring-primary-500 focus:border-primary-500">
                        <option value="">Select category</option>
                        @foreach($pathology_category as $data)
                            <option value="{{ $data['id'] }}" {{ ($data['id'] == $item['pathology_category_id']) ? 'selected' : '' }}>{{ $data['name'] }}</option>
                        @endforeach
                    </select>
                    <input type="hidden" name="pathology_category_id" id="pathology_category_id" value="{{ $item['pathology_category_id'] }}">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Report Days <span class="text-red-500">*</span></label>
                    <input type="text" name="report_days" id="report_days" value="{{ $item['report_days'] }}" placeholder="e.g. 2"
                        class="block w-full rounded-lg border-slate-300 text-sm focus:ring-primary-500 focus:border-primary-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1.5">Charges (K) <span class="text-red-500">*</span></label>
                    <input type="text" name="charge" id="charge" value="{{ $item['charge'] }}" placeholder="e.g. 5000"
                        class="block w-full rounded-lg border-slate-300 text-sm focus:ring-primary-500 focus:border-primary-500">
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Note</label>
                <textarea name="note" id="note" rows="3" placeholder="Additional notes..."
                    class="block w-full rounded-lg border-slate-300 text-sm focus:ring-primary-500 focus:border-primary-500">{{ $item['note'] }}</textarea>
            </div>
        </div>

        <div class="border-t border-slate-200 p-6 bg-slate-50/50">
            <h3 class="text-sm font-semibold text-slate-900 uppercase tracking-wider mb-4">Test Parameters</h3>
            <div id="kt_repeater_1">
                <div class="form-group form-group-last row" id="kt_repeater_1">
                    <div data-repeater-list="parameters" class="space-y-3 w-full">
                        @if(isset($pathology_parameters) && !empty($pathology_parameters) && count($pathology_parameters) > 0)
                            @foreach($pathology_parameters as $parameter_value)
                            <div data-repeater-item class="flex items-end gap-3">
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Parameter Name</label>
                                    <input type="text" name="parameter_name" required placeholder="e.g. Hemoglobin" value="{{ $parameter_value['parameter_name'] }}"
                                        class="block w-full rounded-lg border-slate-300 text-sm focus:ring-primary-500 focus:border-primary-500">
                                    <input type="hidden" name="parameter_id" value="{{ $parameter_value['id'] }}">
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Range</label>
                                    <input type="text" name="range" required placeholder="e.g. 12-16 g/dL" value="{{ $parameter_value['range'] }}"
                                        class="block w-full rounded-lg border-slate-300 text-sm focus:ring-primary-500 focus:border-primary-500">
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Unit</label>
                                    <input type="text" name="unit" required placeholder="e.g. g/dL" value="{{ $parameter_value['unit'] }}"
                                        class="block w-full rounded-lg border-slate-300 text-sm focus:ring-primary-500 focus:border-primary-500">
                                </div>
                                <a href="javascript:;" data-repeater-delete="" class="mb-0.5 inline-flex items-center justify-center h-9 w-9 rounded-lg text-red-500 hover:bg-red-50 transition-colors">
                                    <i class="fas fa-trash-can"></i>
                                </a>
                            </div>
                            @endforeach
                        @else
                            <div data-repeater-item class="flex items-end gap-3">
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Parameter Name</label>
                                    <input type="text" name="parameter_name" required placeholder="e.g. Hemoglobin"
                                        class="block w-full rounded-lg border-slate-300 text-sm focus:ring-primary-500 focus:border-primary-500">
                                    <input type="hidden" name="parameter_id" value="">
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Range</label>
                                    <input type="text" name="range" required placeholder="e.g. 12-16 g/dL"
                                        class="block w-full rounded-lg border-slate-300 text-sm focus:ring-primary-500 focus:border-primary-500">
                                </div>
                                <div class="flex-1">
                                    <label class="block text-xs font-medium text-slate-600 mb-1">Unit</label>
                                    <input type="text" name="unit" required placeholder="e.g. g/dL"
                                        class="block w-full rounded-lg border-slate-300 text-sm focus:ring-primary-500 focus:border-primary-500">
                                </div>
                                <a href="javascript:;" data-repeater-delete="" class="mb-0.5 inline-flex items-center justify-center h-9 w-9 rounded-lg text-red-500 hover:bg-red-50 transition-colors">
                                    <i class="fas fa-trash-can"></i>
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="mt-3">
                    <a href="javascript:;" data-repeater-create="" class="inline-flex items-center rounded-lg border border-dashed border-slate-300 px-3 py-2 text-sm font-medium text-slate-600 hover:bg-white hover:border-primary-300 hover:text-primary-600 transition-colors">
                        <i class="fas fa-plus mr-2"></i> Add Parameter
                    </a>
                </div>
            </div>
        </div>

        <div class="border-t border-slate-200 px-6 py-4 bg-white flex items-center justify-end gap-3">
            <a href="{{ url($url_prefix . '/pathology') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">Cancel</a>
            <button type="submit" class="rounded-lg bg-primary-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 transition-colors">
                <i class="fas fa-check mr-2"></i> Update Test
            </button>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ URL::asset('assets/backend/js/demo1/pages/crud/forms/widgets/form-repeater.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/js/validations/pathology.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/js/scripts/pathology.js') }}" type="text/javascript"></script>
@endsection