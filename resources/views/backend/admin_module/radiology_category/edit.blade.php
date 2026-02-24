@extends('backend.layouts.modern')

@section('content')
<div class="space-y-6">
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Edit Radiology Category</h1>
            <p class="mt-1 text-sm text-slate-500">Update category details.</p>
        </div>
        <a href="{{ url($url_prefix . '/radiology_category') }}"
           class="inline-flex items-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors">
            <i class="fas fa-arrow-left mr-2 text-slate-400"></i> Back to Categories
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden max-w-2xl">
        {!! Form::open(['route'=>'radiology_category_update', 'id' => 'update_form', 'class' => 'kt-form', 'files' => true, 'method' => 'post', 'enctype' => 'multipart/form-data']) !!}
        <input type="hidden" name="id" value="{{ $item['id'] }}">
        <div class="p-6 space-y-5">
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Category Name <span class="text-red-500">*</span></label>
                <input type="text" name="name" id="name" value="{{ $item['name'] }}" data="{{ $item['name'] }}" placeholder="e.g. X-Ray"
                    class="block w-full rounded-lg border-slate-300 text-sm focus:ring-primary-500 focus:border-primary-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Parent Category</label>
                <select id="select2_charge_category" class="block w-full rounded-lg border-slate-300 text-sm focus:ring-primary-500 focus:border-primary-500">
                    <option value="0">None (Top-level)</option>
                    @foreach($parent_category as $data)
                        <option value="{{ $data['id'] }}" {{ ($data['id'] == $item['parent_id']) ? 'selected' : '' }}>{{ $data['name'] }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="parent_id" id="parent_id" value="{{ $item['parent_id'] }}">
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700 mb-1.5">Description</label>
                <textarea name="description" id="description" rows="3" placeholder="Brief description..."
                    class="block w-full rounded-lg border-slate-300 text-sm focus:ring-primary-500 focus:border-primary-500">{{ $item['description'] }}</textarea>
            </div>
        </div>
        <div class="border-t border-slate-200 px-6 py-4 bg-slate-50 flex items-center justify-end gap-3">
            <a href="{{ url($url_prefix . '/radiology_category') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-white transition-colors">Cancel</a>
            <button type="submit" class="rounded-lg bg-primary-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 transition-colors">
                <i class="fas fa-check mr-2"></i> Update Category
            </button>
        </div>
        {!! Form::close() !!}
    </div>
</div>
@endsection

@section('scripts')
<script src="{{ URL::asset('assets/backend/js/validations/radiology_category.js') }}" type="text/javascript"></script>
<script src="{{ URL::asset('assets/backend/js/scripts/radiology_category.js') }}" type="text/javascript"></script>
@endsection