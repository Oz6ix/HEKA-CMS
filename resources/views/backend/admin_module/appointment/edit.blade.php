@extends('backend.layouts.modern')

@section('title', 'Edit Appointment')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-800">Edit Appointment</h1>
        <a href="{{ url($url_prefix . '/appointment') }}" class="flex items-center gap-2 px-4 py-2 bg-white text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
            <i class="fas fa-arrow-left"></i>
            <span>Back to List</span>
        </a>
    </div>

    @include('backend.layouts.includes.notification_alerts')

    <form action="{{ route('appointment_update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        <input type="hidden" name="id" value="{{ $item['id'] }}">
        
        <!-- Primary Appointment Info -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-800 mb-4 border-b border-slate-100 pb-2">Appointment Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Patient * -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Patient <span class="text-red-500">*</span></label>
                    <select name="patient_id" required class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                        <option value="">Select Patient</option>
                        @foreach($patient_item as $data)
                            <option value="{{ $data['id'] }}" {{ $item['patient_id'] == $data['id'] ? 'selected' : '' }}>
                                {{ $data['patient_code'] }} - {{ $data['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Doctor * -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Consultant Doctor <span class="text-red-500">*</span></label>
                    <select name="doctor_staff_id" required class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                        <option value="">Select Doctor</option>
                        @foreach($doctor_item as $data)
                            <option value="{{ $data['id'] }}" {{ $item['doctor_staff_id'] == $data['id'] ? 'selected' : '' }}>
                                {{ $data['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                 <!-- Appointment Date * -->
                 <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Appointment Date <span class="text-red-500">*</span></label>
                    <input type="date" name="appointment_date" required class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500" value="{{ $item['appointment_date'] }}">
                </div>

                <!-- Case No (Read Only) -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Case No.</label>
                    <input type="text" name="case_number" readonly value="{{ $item['case_number'] }}" class="w-full bg-slate-50 text-slate-500 rounded-lg border-slate-300 focus:ring-0 cursor-not-allowed">
                </div>

                <!-- Acuity -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Acuity</label>
                    <select name="casualty_id" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                        <option value="">Select Acuity</option>
                        @foreach($casualty_item as $data)
                            <option value="{{ $data['id'] }}" {{ $item['casualty_id'] == $data['id'] ? 'selected' : '' }}>
                                {{ $data['casualty'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- TPA -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">TPA</label>
                    <select name="tpa_id" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                        <option value="">Select TPA</option>
                        @foreach($tpa_item as $data)
                            <option value="{{ $data['id'] }}" {{ $item['tpa_id'] == $data['id'] ? 'selected' : '' }}>
                                {{ $data['tpa'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Reference -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Reference</label>
                    <input type="text" name="reference" value="{{ $item['reference'] }}" placeholder="Enter reference name" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                </div>
            </div>
        </div>

        <!-- Vitals & Symptoms -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Vitals -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 h-full">
                <h2 class="text-lg font-semibold text-slate-800 mb-4 border-b border-slate-100 pb-2">Vitals</h2>
                <div class="space-y-4">
                    <!-- Height -->
                    <div class="grid grid-cols-3 gap-2">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Height</label>
                            <input type="text" name="height" value="{{ $item_basic['height'] }}" placeholder="Value" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Unit</label>
                            <select name="height_unit" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                                @foreach(config('global.height') as $key => $value)
                                    <option value="{{ $key }}" {{ $item_basic['height_unit'] == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Weight -->
                    <div class="grid grid-cols-3 gap-2">
                         <div class="col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Weight</label>
                            <input type="text" name="weight" value="{{ $item_basic['weight'] }}" placeholder="Value" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                        </div>
                        <div>
                             <label class="block text-sm font-medium text-slate-700 mb-1">Unit</label>
                            <select name="weight_unit" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                                @foreach(config('global.weight') as $key => $value)
                                    <option value="{{ $key }}" {{ $item_basic['weight_unit'] == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- BP -->
                     <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Blood Pressure (Systolic / Diastolic)</label>
                        <div class="flex items-center gap-2">
                            <input type="text" name="systolic_bp" value="{{ $item_basic['systolic_bp'] }}" placeholder="Systolic" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                            <span class="text-slate-400">/</span>
                            <input type="text" name="diastolic_bp" value="{{ $item_basic['diastolic_bp'] }}" placeholder="Diastolic" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                        </div>
                    </div>

                    <!-- Pulse -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Pulse</label>
                         <input type="text" name="pulse" value="{{ $item_basic['pulse'] }}" placeholder="Enter pulse" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                    </div>

                     <!-- Temperature -->
                     <div class="grid grid-cols-3 gap-2">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Temperature</label>
                            <input type="text" name="temperature" value="{{ $item_basic['temperature'] }}" placeholder="Value" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                        </div>
                        <div>
                             <label class="block text-sm font-medium text-slate-700 mb-1">Unit</label>
                            <select name="temperature_unit" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                                @foreach(config('global.temperature') as $key => $value)
                                    <option value="{{ $key }}" {{ $item_basic['temperature_unit'] == $key ? 'selected' : '' }}>{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- SPO2 -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">SPO2</label>
                         <input type="text" name="spo2" value="{{ $item_basic['spo2'] }}" placeholder="Enter SPO2" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                    </div>

                     <!-- Respiration -->
                     <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Respiration</label>
                         <input type="text" name="respiration" value="{{ $item_basic['respiration'] }}" placeholder="Enter respiration" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                    </div>
                     <!-- RBS -->
                     <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">RBS</label>
                         <input type="text" name="rbs" value="{{ $item_basic['rbs'] }}" placeholder="Enter RBS" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                    </div>
                </div>
            </div>

            <!-- Symptoms & Notes -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 h-full">
                <h2 class="text-lg font-semibold text-slate-800 mb-4 border-b border-slate-100 pb-2">Symptoms & Notes</h2>
                <div class="space-y-4">
                    <!-- Symptom Type -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Symptom Type</label>
                        <select name="symptom_type_id" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                            <option value="">Select Symptom Type</option>
                            @foreach($symptom_item as $data)
                                <option value="{{ $data['id'] }}" {{ $item_basic['symptom_type_id'] == $data['id'] ? 'selected' : '' }}>
                                    {{ $data['symptom'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Symptom -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Symptom Title</label>
                        <input type="text" name="symptom" value="{{ $item_basic['symptom'] }}" placeholder="Enter symptom" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                        <textarea name="description" rows="4" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500" placeholder="Enter description">{!! $item_basic['description'] !!}</textarea>
                    </div>

                    <!-- Note -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Notes</label>
                        <textarea name="note" rows="4" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500" placeholder="Enter note">{!! $item_basic['note'] !!}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Patient Reports -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden" x-data="{ showReports: true }">
            <button type="button" @click="showReports = !showReports" class="w-full flex items-center justify-between p-4 hover:bg-slate-50 transition-colors">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-violet-100 flex items-center justify-center">
                        <i class="fas fa-paperclip text-violet-600 text-sm"></i>
                    </div>
                    <span class="font-semibold text-slate-800">Patient Reports</span>
                    @if(isset($existingDocs) && $existingDocs->count() > 0)
                        <span class="px-2 py-0.5 text-xs font-medium bg-violet-100 text-violet-700 rounded-full">{{ $existingDocs->count() }}</span>
                    @endif
                </div>
                <i class="fas fa-chevron-down text-slate-400 transition-transform" :class="showReports && 'rotate-180'"></i>
            </button>
            <div x-show="showReports" x-collapse class="border-t border-slate-100 p-6 space-y-5">
                <!-- Existing Documents -->
                @if(isset($existingDocs) && $existingDocs->count() > 0)
                <div>
                    <h3 class="text-sm font-semibold text-slate-700 mb-3">Attached Documents</h3>
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                        @foreach($existingDocs as $doc)
                        <div class="group relative rounded-lg border border-slate-200 bg-slate-50 p-3 hover:border-primary-300 transition-colors">
                            @if($doc->file_type === 'image')
                                <img src="{{ asset('uploads/patient_documents/' . $doc->file_name) }}" alt="{{ $doc->original_name }}" class="w-full h-20 object-cover rounded-md mb-2">
                            @else
                                <div class="w-full h-20 flex items-center justify-center bg-red-50 rounded-md mb-2">
                                    <i class="fas fa-file-pdf text-3xl text-red-400"></i>
                                </div>
                            @endif
                            <p class="text-xs text-slate-600 truncate" title="{{ $doc->original_name }}">{{ $doc->original_name }}</p>
                            <span class="text-[10px] text-slate-400">{{ ucfirst(str_replace('_', ' ', $doc->category ?? 'other')) }}</span>
                            <a href="{{ asset('uploads/patient_documents/' . $doc->file_name) }}" target="_blank" class="absolute top-1 right-1 w-6 h-6 rounded-full bg-white shadow flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                <i class="fas fa-external-link-alt text-[10px] text-slate-500"></i>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Upload New -->
                <div>
                    <h3 class="text-sm font-semibold text-slate-700 mb-3">Upload New Reports</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm text-slate-600 mb-1">Category</label>
                            <select name="report_category" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500 text-sm">
                                <option value="external_lab">External Lab Result</option>
                                <option value="external_imaging">External Imaging</option>
                                <option value="referral">Referral Letter</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="flex items-end gap-2">
                            <div class="flex-1">
                                <label class="block text-sm text-slate-600 mb-1">Browse Files</label>
                                <input type="file" name="patient_reports[]" multiple accept="image/*,.pdf" class="w-full text-sm text-slate-600 file:mr-2 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-sm file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100">
                            </div>
                            <div>
                                <label class="flex items-center gap-1.5 px-3 py-2 bg-emerald-50 text-emerald-700 rounded-lg cursor-pointer hover:bg-emerald-100 transition-colors text-sm">
                                    <i class="fas fa-camera"></i>
                                    <span>Camera</span>
                                    <input type="file" name="patient_camera_reports[]" accept="image/*" capture="environment" class="hidden">
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit -->

        <div class="flex justify-end p-4 bg-slate-50 rounded-xl border border-slate-200">
            <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-lg shadow-primary-600/30 flex items-center gap-2">
                <i class="fas fa-save"></i>
                <span>Update Appointment</span>
            </button>
        </div>
    </form>
</div>
@endsection
