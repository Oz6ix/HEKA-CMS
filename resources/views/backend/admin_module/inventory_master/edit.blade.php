@extends('backend.layouts.modern')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Inventory Item</h1>
            <p class="text-slate-500 mt-1">Update inventory item master record.</p>
        </div>
        <a href="{{ url($url_prefix . '/inventory_masters') }}" class="inline-flex items-center justify-center rounded-md bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 transition-all">
            <i class="fas fa-arrow-left mr-2 text-slate-400"></i> Back to List
        </a>
    </div>

    @include('backend.layouts.includes.notification_alerts')

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden" 
         x-data="{ categoryId: '{{ old('inventory_category_id', $item['inventory_category_id']) }}' }">
        
        <form action="{{ route('inventory_master_update') }}" method="POST" enctype="multipart/form-data" class="p-6">
            @csrf
            <input type="hidden" name="id" value="{{ $item['id'] }}">
            
            <div class="space-y-8">
                <!-- Basic Information Section -->
                <div>
                    <h3 class="text-base font-semibold leading-7 text-slate-900 border-b border-slate-200 pb-2 mb-6">Item Information</h3>
                    
                    <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-2">
                        <!-- Item Name -->
                        <div class="col-span-2">
                            <label for="item_name" class="block text-sm font-medium leading-6 text-slate-900">Item Name <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <input type="text" name="item_name" id="item_name" value="{{ old('item_name', $item['item_name']) }}" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" placeholder="Enter inventory item name" required>
                                @error('item_name')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Item Category -->
                        <div class="col-span-1">
                            <label for="inventory_category_id" class="block text-sm font-medium leading-6 text-slate-900">Category <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <select id="inventory_category_id" name="inventory_category_id" x-model="categoryId" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" required>
                                    <option value="">Select Categories</option>
                                    @foreach($inventory_category as $data)
                                        <option value="{{ $data['id'] }}" {{ old('inventory_category_id', $item['inventory_category_id']) == $data['id'] ? 'selected' : '' }}>{{ $data['inventory_name'] }}</option>
                                    @endforeach
                                </select>
                                @error('inventory_category_id')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- Item Unit -->
                        <div class="col-span-1">
                            <label for="inventory_unit" class="block text-sm font-medium leading-6 text-slate-900">Unit <span class="text-red-500">*</span></label>
                            <div class="mt-2">
                                <select id="inventory_unit" name="inventory_unit" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" required>
                                    <option value="">Select Unit</option>
                                    @foreach($units as $data)
                                        <option value="{{ $data['id'] }}" {{ old('inventory_unit', $item['inventory_unit']) == $data['id'] ? 'selected' : '' }}>{{ $data['unit'] }}</option>
                                    @endforeach
                                </select>
                                @error('inventory_unit')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Pharmacy Specific Fields (Conditional) -->
                <div x-show="categoryId == 3" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                    <h3 class="text-base font-semibold leading-7 text-slate-900 border-b border-slate-200 pb-2 mb-6">Pharmacy Details</h3>
                    
                    <div class="grid grid-cols-1 gap-x-6 gap-y-6 sm:grid-cols-2">
                        <!-- Generic -->
                        <div class="col-span-1">
                            <label for="pharmacy_generic" class="block text-sm font-medium leading-6 text-slate-900">Generic</label>
                            <div class="mt-2">
                                <select id="pharmacy_generic" name="pharmacy_generic" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                                    <option value="">Select Generic</option>
                                    @foreach($pharmacy_generic as $data)
                                        <option value="{{ $data['id'] }}" {{ old('pharmacy_generic', $item['pharmacy_generic']) == $data['id'] ? 'selected' : '' }}>{{ $data['generic'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Dosage -->
                        <div class="col-span-1">
                            <label for="pharmacy_dosage" class="block text-sm font-medium leading-6 text-slate-900">Dosage</label>
                            <div class="mt-2">
                                <select id="pharmacy_dosage" name="pharmacy_dosage" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                                    <option value="">Select Dosage</option>
                                    @foreach($dosages as $dosage)
                                        <option value="{{ $dosage['id'] }}" {{ old('pharmacy_dosage', $item['pharmacy_dosage']) == $dosage['id'] ? 'selected' : '' }}>{{ $dosage['dosage'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <!-- Route -->
                        <div class="col-span-2">
                            <label for="route" class="block text-sm font-medium leading-6 text-slate-900">Route</label>
                            <div class="mt-2">
                                <input type="text" name="route" id="route" value="{{ old('route', $item['route']) }}" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" placeholder="Enter route (e.g. Oral, IV)">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Description -->
                <div>
                    <label for="description" class="block text-sm font-medium leading-6 text-slate-900">Description</label>
                    <div class="mt-2">
                        <textarea id="description" name="description" rows="4" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" placeholder="Enter description (optional)">{!! old('description', $item['description']) !!}</textarea>
                    </div>
                </div>
            </div>

            <!-- Form Actions -->
            <div class="mt-8 flex items-center justify-end gap-x-6 bg-slate-50 px-4 py-4 -mx-6 -mb-6 border-t border-slate-200">
                <a href="{{ url($url_prefix . '/inventory_masters') }}" class="text-sm font-semibold leading-6 text-slate-900 hover:text-slate-700">Cancel</a>
                <button type="submit" class="rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">Update Item</button>
            </div>
        </form>
    </div>
</div>
@endsection