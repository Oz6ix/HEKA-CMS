@extends('backend.layouts.modern')

@section('title', 'New Appointment')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-800">New Appointment</h1>
        <a href="{{ url($url_prefix . '/appointment') }}" class="flex items-center gap-2 px-4 py-2 bg-white text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
            <i class="fas fa-arrow-left"></i>
            <span>Back to List</span>
        </a>
    </div>

    @include('backend.layouts.includes.notification_alerts')

    <form action="{{ route('appointment_add') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf
        
        <!-- Primary Appointment Info -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
            <h2 class="text-lg font-semibold text-slate-800 mb-4 border-b border-slate-100 pb-2">Appointment Details</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                <!-- Patient * -->
                <!-- Patient * (Searchable) -->
                <div x-data="{
                    open: false,
                    search: '',
                    selectedId: '{{ old('patient_id', $id != 0 ? $id : '') }}',
                    patients: {{ json_encode($patient_item) }},
                    get filteredPatients() {
                        if (this.search === '') return this.patients;
                        return this.patients.filter(p => {
                            const term = this.search.toLowerCase();
                            return p.name.toLowerCase().includes(term) || 
                                   p.patient_code.toLowerCase().includes(term) ||
                                   (p.phone && p.phone.includes(term));
                        });
                    },
                    get selectedName() {
                        if (!this.selectedId) return 'Select Patient';
                        const p = this.patients.find(x => x.id == this.selectedId);
                        return p ? (p.patient_code + ' - ' + p.name) : 'Select Patient';
                    },
                    select(id) {
                        this.selectedId = id;
                        this.open = false;
                        this.search = '';
                    }
                }" class="relative">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Patient <span class="text-red-500">*</span></label>
                    
                    <!-- Hidden Input for Form Submission -->
                    <input type="hidden" name="patient_id" x-model="selectedId" required>

                    <!-- Trigger Button -->
                    <button type="button" @click="open = !open" @click.away="open = false" 
                        class="w-full bg-white border border-slate-300 rounded-lg px-3 py-2 text-left shadow-sm focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500 flex justify-between items-center">
                        <span x-text="selectedName" :class="{'text-slate-500': !selectedId, 'text-slate-900': selectedId}"></span>
                        <i class="fas fa-chevron-down text-slate-400 text-xs"></i>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute z-10 mt-1 w-full bg-white shadow-lg rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto max-h-60 sm:text-sm">
                        
                        <!-- Search Input -->
                        <div class="sticky top-0 z-10 bg-white px-2 py-2 border-b border-slate-100">
                             <div class="relative">
                                <i class="fas fa-search absolute left-2 top-2.5 text-slate-400"></i>
                                <input type="text" x-model="search" 
                                    class="w-full pl-8 pr-3 py-1.5 border border-slate-300 rounded-md leading-5 bg-white placeholder-slate-500 focus:outline-none focus:placeholder-slate-400 focus:ring-1 focus:ring-primary-500 focus:border-primary-500 sm:text-sm" 
                                    placeholder="Search by name, ID or phone..." autofocus>
                            </div>
                        </div>

                        <!-- Options List -->
                        <ul>
                            <template x-for="patient in filteredPatients" :key="patient.id">
                                <li @click="select(patient.id)" 
                                    class="cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-primary-50 transition-colors text-slate-900 group">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-6 w-6 rounded-full bg-slate-100 flex items-center justify-center text-xs text-slate-500 mr-2 group-hover:bg-primary-100 group-hover:text-primary-600">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <span class="block truncate font-medium" x-text="patient.name"></span>
                                        <span class="ml-2 text-slate-500 text-xs" x-text="'ID: ' + patient.patient_code"></span>
                                        <span class="ml-auto text-slate-400 text-xs mr-2" x-text="patient.phone"></span>
                                    </div>
                                    
                                    <!-- Checkmark -->
                                    <span x-show="selectedId == patient.id" class="absolute inset-y-0 right-0 flex items-center pr-4 text-primary-600">
                                        <i class="fas fa-check"></i>
                                    </span>
                                </li>
                            </template>
                            
                            <!-- No Results -->
                            <li x-show="filteredPatients.length === 0" class="cursor-default select-none relative py-2 pl-3 pr-9 text-slate-500 italic text-center text-xs py-4">
                                No patients found.
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Doctor * -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Consultant Doctor <span class="text-red-500">*</span></label>
                    <select name="doctor_staff_id" required class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                        <option value="">Select Doctor</option>
                        @foreach($doctor_item as $data)
                            <option value="{{ $data['id'] }}" {{ old('doctor_staff_id') == $data['id'] ? 'selected' : '' }}>
                                {{ $data['name'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                 <!-- Appointment Date * -->
                 <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Appointment Date <span class="text-red-500">*</span></label>
                    <input type="date" name="appointment_date" required class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500" value="{{ date('Y-m-d') }}">
                </div>

                <!-- Case No (Read Only) -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Case No.</label>
                    <input type="text" name="case_number" readonly value="{{ $case_number }}" class="w-full bg-slate-50 text-slate-500 rounded-lg border-slate-300 focus:ring-0 cursor-not-allowed">
                </div>

                <!-- Acuity -->
                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-1">Acuity</label>
                    <select name="casualty_id" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                        <option value="">Select Acuity</option>
                        @foreach($casualty_item as $data)
                            <option value="{{ $data['id'] }}" {{ old('casualty_id') == $data['id'] ? 'selected' : '' }}>
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
                            <option value="{{ $data['id'] }}" {{ old('tpa_id') == $data['id'] ? 'selected' : '' }}>
                                {{ $data['tpa'] }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Reference -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700 mb-1">Reference</label>
                    <input type="text" name="reference" value="{{ old('reference') }}" placeholder="Enter reference name" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                </div>
            </div>
        </div>

        <!-- Vitals & Symptoms -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Vitals -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6 h-full">
                <h2 class="text-lg font-semibold text-slate-800 mb-4 border-b border-slate-100 pb-2">Vitals</h2>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Height (cm)</label>
                        <input type="text" name="height" value="{{ old('height') }}" placeholder="e.g. 170" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                        <input type="hidden" name="height_unit" value="cm">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Weight (kg)</label>
                        <input type="text" name="weight" value="{{ old('weight') }}" placeholder="e.g. 70" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                        <input type="hidden" name="weight_unit" value="kg">
                    </div>

                    <!-- BP -->
                     <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Blood Pressure (Systolic / Diastolic)</label>
                        <div class="flex items-center gap-2">
                            <input type="text" name="systolic_bp" value="{{ old('systolic_bp') }}" placeholder="Systolic" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                            <span class="text-slate-400">/</span>
                            <input type="text" name="diastolic_bp" value="{{ old('diastolic_bp') }}" placeholder="Diastolic" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                        </div>
                    </div>

                    <!-- Pulse -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Pulse</label>
                         <input type="text" name="pulse" value="{{ old('pulse') }}" placeholder="Enter pulse" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                    </div>

                     <!-- Temperature -->
                     <div class="grid grid-cols-3 gap-2">
                        <div class="col-span-2">
                            <label class="block text-sm font-medium text-slate-700 mb-1">Temperature</label>
                            <input type="text" name="temperature" value="{{ old('temperature') }}" placeholder="Value" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                        </div>
                        <div>
                             <label class="block text-sm font-medium text-slate-700 mb-1">Unit</label>
                            <select name="temperature_unit" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                                @foreach(config('global.temperature') as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- SPO2 -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">SPO2</label>
                         <input type="text" name="spo2" value="{{ old('spo2') }}" placeholder="Enter SPO2" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                    </div>

                     <!-- Respiration -->
                     <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Respiration</label>
                         <input type="text" name="respiration" value="{{ old('respiration') }}" placeholder="Enter respiration" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                    </div>
                     <!-- RBS -->
                     <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">RBS</label>
                         <input type="text" name="rbs" value="{{ old('rbs') }}" placeholder="Enter RBS" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
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
                                <option value="{{ $data['id'] }}" {{ old('symptom_type_id') == $data['id'] ? 'selected' : '' }}>
                                    {{ $data['symptom'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Symptom -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Symptom Title</label>
                        <input type="text" name="symptom" value="{{ old('symptom') }}" placeholder="Enter symptom" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500">
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Description</label>
                        <textarea name="description" rows="4" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500" placeholder="Enter description">{!! old('description') !!}</textarea>
                    </div>

                    <!-- Note -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Notes</label>
                        <textarea name="note" rows="4" class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500" placeholder="Enter note">{!! old('note') !!}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- Patient Reports Upload -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden" x-data="{ showReports: false }">
            <button type="button" @click="showReports = !showReports" class="w-full flex items-center justify-between p-6 hover:bg-slate-50 transition-colors">
                <h2 class="text-lg font-semibold text-slate-800 flex items-center gap-2">
                    <i class="fas fa-file-medical text-amber-500"></i> Patient Reports
                    <span class="text-xs font-normal text-slate-400">(External lab / imaging results)</span>
                </h2>
                <i class="fas text-slate-400" :class="showReports ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
            </button>
            <div x-show="showReports" x-cloak class="border-t border-slate-200 p-6 space-y-4">
                <p class="text-xs text-slate-500">
                    <i class="fas fa-info-circle mr-1 text-primary-400"></i>
                    Upload any external lab reports, imaging results, or referral documents the patient brings.
                </p>
                <div class="flex items-center gap-3 flex-wrap">
                    <label for="appt_report_input" class="inline-flex items-center rounded-lg bg-primary-600 px-4 py-2 text-xs font-semibold text-white cursor-pointer hover:bg-primary-500 transition-colors">
                        <i class="fas fa-folder-open mr-1.5"></i> Browse Files
                    </label>
                    <label for="appt_camera_input" class="inline-flex items-center rounded-lg bg-amber-600 px-4 py-2 text-xs font-semibold text-white cursor-pointer hover:bg-amber-500 transition-colors">
                        <i class="fas fa-camera mr-1.5"></i> Take Photo
                    </label>
                    <select id="appt_report_category" class="rounded-lg border-slate-300 text-xs py-1.5 px-3">
                        <option value="external_lab">External Lab Result</option>
                        <option value="external_imaging">External Imaging</option>
                        <option value="referral">Referral Report</option>
                        <option value="other">Other</option>
                    </select>
                    <input type="file" id="appt_report_input" class="hidden" name="patient_reports[]" accept="image/*,.pdf" multiple>
                    <input type="file" id="appt_camera_input" class="hidden" name="patient_camera_reports[]" accept="image/*" capture="environment">
                    <input type="hidden" name="report_category" id="report_category_hidden" value="external_lab">
                </div>
                <!-- Preview list -->
                <div id="report_preview_list" class="space-y-2"></div>
            </div>
        </div>

        <!-- Submit -->
        <div class="flex justify-end p-4 bg-slate-50 rounded-xl border border-slate-200">
            <button type="submit" class="px-6 py-2 bg-primary-600 text-white rounded-lg hover:bg-primary-700 transition-colors shadow-lg shadow-primary-600/30 flex items-center gap-2">
                <i class="fas fa-save"></i>
                <span>Save Appointment</span>
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<style>[x-cloak] { display: none !important; }</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Sync category dropdown to hidden input
    var catSelect = document.getElementById('appt_report_category');
    var catHidden = document.getElementById('report_category_hidden');
    if (catSelect && catHidden) {
        catSelect.addEventListener('change', function() { catHidden.value = this.value; });
    }
    // File preview
    function showPreview(input) {
        var list = document.getElementById('report_preview_list');
        if (!input.files) return;
        Array.from(input.files).forEach(function(f) {
            var div = document.createElement('div');
            div.className = 'flex items-center gap-2 rounded-lg bg-emerald-50 border border-emerald-200 px-3 py-2 text-sm text-emerald-700';
            div.innerHTML = '<i class="fas fa-check-circle"></i> ' + f.name + ' <span class="text-xs text-emerald-400">(' + (f.size / 1024).toFixed(1) + ' KB)</span>';
            list.appendChild(div);
        });
    }
    var reportInput = document.getElementById('appt_report_input');
    if (reportInput) reportInput.addEventListener('change', function() { showPreview(this); });
    var cameraInput = document.getElementById('appt_camera_input');
    if (cameraInput) cameraInput.addEventListener('change', function() { showPreview(this); });
});
</script>
@endsection
