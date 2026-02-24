@extends('backend.layouts.modern')

@section('title', 'External Prescription')

@section('content')
<div class="max-w-4xl mx-auto">
    @include('backend.layouts.includes.notification_alerts')

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">
                <i class="fas fa-file-prescription text-amber-500 mr-2"></i> External Prescription
            </h1>
            <p class="text-slate-500 mt-1">Enter a walk-in prescription or upload a handwritten Rx.</p>
        </div>
        <a href="{{ url($url_prefix . '/pharmacy_sales') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
            <i class="fas fa-arrow-left mr-2"></i> Back to Sales
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('pharmacy_sales_external_store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-8">
            @csrf

            <!-- Patient & Doctor Info -->
            <div>
                <h3 class="text-base font-semibold text-slate-900 border-b border-slate-200 pb-2 mb-6">
                    <i class="fas fa-user-injured text-blue-500 mr-2"></i> Patient & Doctor Information
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Patient Name <span class="text-red-500">*</span></label>
                        <input type="text" name="patient_name" value="{{ old('patient_name') }}" required
                               class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" placeholder="Patient name">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Patient Phone</label>
                        <input type="text" name="patient_phone" value="{{ old('patient_phone') }}"
                               class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" placeholder="Phone number">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Doctor Name</label>
                        <input type="text" name="doctor_name" value="{{ old('doctor_name') }}"
                               class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" placeholder="Prescribing doctor">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Doctor License No.</label>
                        <input type="text" name="doctor_license_no" value="{{ old('doctor_license_no') }}"
                               class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" placeholder="SLMC / Registration no.">
                    </div>
                </div>
            </div>

            <!-- Prescription Details -->
            <div>
                <h3 class="text-base font-semibold text-slate-900 border-b border-slate-200 pb-2 mb-6">
                    <i class="fas fa-notes-medical text-green-500 mr-2"></i> Prescription Details
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Prescription Date <span class="text-red-500">*</span></label>
                        <input type="date" name="rx_date" value="{{ old('rx_date', date('Y-m-d')) }}" required
                               class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Diagnosis</label>
                        <input type="text" name="diagnosis" value="{{ old('diagnosis') }}"
                               class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" placeholder="Brief diagnosis">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Instructions</label>
                        <textarea name="instructions" rows="3"
                                  class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" placeholder="Dosage instructions, special notes...">{{ old('instructions') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Rx Image Upload -->
            <div>
                <h3 class="text-base font-semibold text-slate-900 border-b border-slate-200 pb-2 mb-6">
                    <i class="fas fa-camera text-purple-500 mr-2"></i> Prescription Image
                </h3>
                <div x-data="{ previewUrl: null }">
                    <label for="rx_image" class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100">
                        <div x-show="!previewUrl" class="flex flex-col items-center justify-center pt-5 pb-6">
                            <i class="fas fa-cloud-arrow-up text-3xl text-gray-400 mb-3"></i>
                            <p class="mb-2 text-sm text-gray-500"><span class="font-semibold">Click to upload</span> handwritten Rx</p>
                            <p class="text-xs text-gray-400">JPG, PNG, GIF, or WebP (Max 5MB)</p>
                        </div>
                        <img x-show="previewUrl" :src="previewUrl" class="h-44 object-contain" />
                        <input id="rx_image" name="rx_image" type="file" class="hidden" accept="image/*" 
                               @change="previewUrl = URL.createObjectURL($event.target.files[0])">
                    </label>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3 pt-4 border-t">
                <a href="{{ url($url_prefix . '/pharmacy_sales') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700">
                    <i class="fas fa-save mr-2"></i> Save Prescription
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
