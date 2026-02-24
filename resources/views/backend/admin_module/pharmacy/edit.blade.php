@extends('backend.layouts.modern')

@section('title', 'Edit Medicine')

@section('content')
    <!-- Messages section -->
    @include('backend.layouts.includes.notification_alerts')

    <div class="max-w-7xl mx-auto">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6 border-b border-gray-100 pb-4">
                <h2 class="text-xl font-bold text-gray-800">Edit Medicine</h2>
                <a href="{{ url($url_prefix . '/pharmacys') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 focus:ring-4 focus:ring-gray-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Back to List
                </a>
            </div>

            <form action="{{ route('pharmacy_update') }}" method="POST" id="update_form" class="space-y-6" enctype="multipart/form-data">
                @csrf
            <input type="hidden" name="id" value="{{ $item['id'] }}">

            {{-- ====== SECTION 1: Basic Info ====== --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-3">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <i class="fas fa-pills text-primary-500"></i> Basic Information
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">Medicine name, category, and company details.</p>
                </div>

                <!-- Medicine Name -->
                <div>
                    <label for="title" class="block mb-2 text-sm font-medium text-gray-900">Medicine Name <span class="text-red-500">*</span></label>
                    <input type="text" name="title" id="title" value="{{ $item['title'] }}" 
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5" 
                           placeholder="e.g. Paracetamol 500mg Tab" required>
                </div>

                <!-- Generic Name -->
                <div>
                    <label for="generic_name" class="block mb-2 text-sm font-medium text-gray-900">Generic Name (INN)</label>
                    <input type="text" name="generic_name" id="generic_name" value="{{ $item['generic_name'] ?? '' }}"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                           placeholder="e.g. Paracetamol / Acetaminophen">
                </div>

                <!-- Brand Name -->
                <div>
                    <label for="brand_name" class="block mb-2 text-sm font-medium text-gray-900">Brand Name</label>
                    <input type="text" name="brand_name" id="brand_name" value="{{ $item['brand_name'] ?? '' }}"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                           placeholder="e.g. Tylenol, Calpol">
                </div>

                <!-- Strength -->
                <div>
                    <label for="strength" class="block mb-2 text-sm font-medium text-gray-900">Strength</label>
                    <input type="text" name="strength" id="strength" value="{{ $item['strength'] ?? '' }}"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                           placeholder="e.g. 500mg, 10mg/5ml">
                </div>

                <!-- Dosage Form -->
                <div>
                    <label for="form" class="block mb-2 text-sm font-medium text-gray-900">Dosage Form</label>
                    <select name="form" id="form" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        <option value="">Select Form</option>
                        @foreach(['Tablet','Capsule','Syrup','Suspension','Injection','Cream','Ointment','Drops','Inhaler','Sachet','Suppository','Patch','Gel','Powder','Solution','Spray','Lotion','Other'] as $f)
                            <option value="{{ $f }}" {{ ($item['form'] ?? '') == $f ? 'selected' : '' }}>{{ $f }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Category -->
                <div>
                    <label for="pharmacy_category_id" class="block mb-2 text-sm font-medium text-gray-900">Category <span class="text-red-500">*</span></label>
                    <select name="pharmacy_category_id" id="pharmacy_category_id" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5" required>
                        <option value="">Select Category</option>
                        @foreach($hospital_category as $data)
                            <option value="{{ $data['id'] }}" {{ ($data['id'] == $item['pharmacy_category_id']) ? 'selected' : '' }}>
                                {{ $data['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- ====== SECTION 2: Classification & Compliance ====== --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 border-t border-gray-100">
                <div class="md:col-span-3">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <i class="fas fa-shield-halved text-amber-500"></i> Classification & Compliance
                    </h3>
                    <p class="text-sm text-gray-500 mt-1">Drug schedule, medicine type, and regulatory details.</p>
                </div>

                <!-- Medicine Type -->
                <div>
                    <label for="medicine_type" class="block mb-2 text-sm font-medium text-gray-900">Medicine Type</label>
                    <select name="medicine_type" id="medicine_type" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        <option value="allopathy" {{ ($item['medicine_type'] ?? 'allopathy') == 'allopathy' ? 'selected' : '' }}>Allopathy</option>
                        <option value="ayurveda" {{ ($item['medicine_type'] ?? '') == 'ayurveda' ? 'selected' : '' }}>Ayurveda</option>
                        <option value="nutrition" {{ ($item['medicine_type'] ?? '') == 'nutrition' ? 'selected' : '' }}>Nutrition / Supplement</option>
                        <option value="psychology" {{ ($item['medicine_type'] ?? '') == 'psychology' ? 'selected' : '' }}>Psychology</option>
                    </select>
                </div>

                <!-- Drug Schedule -->
                <div>
                    <label for="schedule" class="block mb-2 text-sm font-medium text-gray-900">Drug Schedule</label>
                    <select name="schedule" id="schedule" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        <option value="OTC" {{ ($item['schedule'] ?? 'OTC') == 'OTC' ? 'selected' : '' }}>OTC (Over-the-Counter)</option>
                        <option value="H" {{ ($item['schedule'] ?? '') == 'H' ? 'selected' : '' }}>Schedule H</option>
                        <option value="H1" {{ ($item['schedule'] ?? '') == 'H1' ? 'selected' : '' }}>Schedule H1</option>
                        <option value="X" {{ ($item['schedule'] ?? '') == 'X' ? 'selected' : '' }}>Schedule X (Narcotics)</option>
                    </select>
                </div>

                <!-- Barcode -->
                <div>
                    <label for="barcode" class="block mb-2 text-sm font-medium text-gray-900">Barcode / EAN</label>
                    <div class="relative">
                        <input type="text" name="barcode" id="barcode" value="{{ $item['barcode'] ?? '' }}"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 pr-10"
                               placeholder="Scan or enter barcode">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <i class="fas fa-barcode text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <!-- HSN Code -->
                <div>
                    <label for="hsn_code" class="block mb-2 text-sm font-medium text-gray-900">HSN Code</label>
                    <input type="text" name="hsn_code" id="hsn_code" value="{{ $item['hsn_code'] ?? '' }}"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                           placeholder="e.g. 30049099">
                </div>

                <!-- Is Generic -->
                <div class="flex items-end">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_generic" value="1" class="sr-only peer" {{ ($item['is_generic'] ?? false) ? 'checked' : '' }}>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-primary-600"></div>
                        <span class="ml-3 text-sm font-medium text-gray-900">This is a generic drug</span>
                    </label>
                </div>

                <!-- Generic Group -->
                <div>
                    <label for="generic_group_id" class="block mb-2 text-sm font-medium text-gray-900">Generic Equivalent</label>
                    <select name="generic_group_id" id="generic_group_id" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5">
                        <option value="">None (standalone)</option>
                        @if(isset($generic_drugs))
                            @foreach($generic_drugs as $gd)
                                <option value="{{ $gd->id }}" {{ ($item['generic_group_id'] ?? '') == $gd->id ? 'selected' : '' }}>
                                    {{ $gd->title }} ({{ $gd->generic_name ?: $gd->title }})
                                </option>
                            @endforeach
                        @endif
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Link this branded drug to its generic equivalent.</p>
                </div>
            </div>

            {{-- ====== SECTION 3: Manufacturer & Pricing ====== --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 pt-6 border-t border-gray-100">
                <div class="md:col-span-3">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <i class="fas fa-industry text-blue-500"></i> Manufacturer & Pricing
                    </h3>
                </div>

                <!-- Manufacturer -->
                <div>
                    <label for="manufacturer" class="block mb-2 text-sm font-medium text-gray-900">Manufacturer</label>
                    <input type="text" name="manufacturer" id="manufacturer" value="{{ $item['manufacturer'] ?? '' }}"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                           placeholder="e.g. Sun Pharma, Cipla">
                </div>

                <!-- Company -->
                <div>
                    <label for="company_name" class="block mb-2 text-sm font-medium text-gray-900">Company / Distributor <span class="text-red-500">*</span></label>
                    <input type="text" name="company_name" id="company_name" value="{{ $item['company_name'] }}"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                           placeholder="Enter company name" required>
                </div>

                <!-- Unit -->
                <div>
                    <label for="unit" class="block mb-2 text-sm font-medium text-gray-900">Unit <span class="text-red-500">*</span></label>
                    <select name="unit" id="unit" 
                            class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5" required>
                        <option value="">Select Unit</option>
                        @foreach(['Strip','Box','Bottle','Vial','Ampoule','Tube','Sachet','Pack','Each','Dozen','Piece'] as $u)
                            <option value="{{ $u }}" {{ $item['unit'] == $u ? 'selected' : '' }}>{{ $u }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Quantity -->
                <div>
                    <label for="quantity" class="block mb-2 text-sm font-medium text-gray-900">Quantity <span class="text-red-500">*</span></label>
                    <input type="number" name="quantity" id="quantity" value="{{ $item['quantity'] }}"
                           class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5"
                           placeholder="Enter quantity" required>
                </div>

                <!-- Purchase Price -->
                <div>
                    <label for="price" class="block mb-2 text-sm font-medium text-gray-900">Purchase Price <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <input type="number" name="price" id="price" value="{{ $item['price'] }}" step="0.01"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 pr-12"
                               placeholder="0.00" required>
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Ks</span>
                        </div>
                    </div>
                </div>

                <!-- MRP -->
                <div>
                    <label for="mrp" class="block mb-2 text-sm font-medium text-gray-900">MRP (Selling Price)</label>
                    <div class="relative">
                        <input type="number" name="mrp" id="mrp" value="{{ $item['mrp'] ?? '' }}" step="0.01"
                               class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 pr-12"
                               placeholder="0.00">
                        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                            <span class="text-gray-500 sm:text-sm">Ks</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ====== SECTION 4: Photo & Notes ====== --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-gray-100">
                <div class="md:col-span-2">
                    <h3 class="text-lg font-medium text-gray-900 flex items-center gap-2">
                        <i class="fas fa-image text-green-500"></i> Photo & Notes
                    </h3>
                </div>

                <!-- Photo -->
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-900" for="photo">Upload Photo</label>
                    <div class="flex items-center justify-center w-full">
                        <label for="photo" class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 relative">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6 {{ $item['photo'] ? 'hidden' : '' }}" id="photo_preview_container">
                                <svg class="w-8 h-8 mb-4 text-gray-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 16">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 13h3a3 3 0 0 0 0-6h-.025A5.56 5.56 0 0 0 16 6.5 5.5 5.5 0 0 0 5.207 5.021C5.137 5.017 5.071 5 5 5a4 4 0 0 0 0 8h2.167M10 15V6m0 0L8 8m2-2 2 2"/>
                                </svg>
                                <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to update</span></p>
                                <p class="text-xs text-gray-500">PNG, JPG or GIF</p>
                            </div>
                            <img id="thumbnail_image" class="{{ $item['photo'] ? '' : 'hidden' }} h-40 object-contain" 
                                 src="{{ $item['photo'] ? asset('uploads/pharmacy/'.$item['photo']) : '#' }}" 
                                 alt="Image Preview" />
                            <input id="photo" name="photo" type="file" class="hidden" accept="image/png,image/jpeg,image/gif" />
                        </label>
                    </div> 
                </div>

                <!-- Note -->
                <div>
                    <label for="note" class="block mb-2 text-sm font-medium text-gray-900">Note</label>
                    <textarea name="note" id="note" rows="7" 
                              class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5" 
                              placeholder="Optional notes...">{{ $item['note'] }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-100">
                <a href="{{ url($url_prefix . '/pharmacys') }}" class="text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-medium rounded-lg text-sm px-5 py-2.5">
                    Cancel
                </a>
                <button type="submit" id="update_button" class="text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5">
                    <i class="fas fa-check mr-2"></i> Update Medicine
                </button>
            </div>

            </form>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.getElementById('photo').addEventListener('change', function() {
        if (this.files && this.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var img = document.getElementById('thumbnail_image');
                img.src = e.target.result;
                img.classList.remove('hidden');
                document.getElementById('photo_preview_container').classList.add('hidden');
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
@endsection