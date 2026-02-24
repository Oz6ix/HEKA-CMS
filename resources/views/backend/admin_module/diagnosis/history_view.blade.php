@extends('backend.layouts.modern')

@section('content')
@php
    $patient = $patient_details[0]->patient ?? null;
    $basics = $item_basic[0] ?? null;
    $diagnosis = $item_diagnosis ?? null;
    $age = $patient && $patient->dob ? \Carbon\Carbon::parse($patient->dob)->age : 'N/A';
@endphp

<div class="space-y-6 max-w-5xl mx-auto">
    <!-- Back -->
    <a href="javascript:history.back()" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-slate-700 transition-colors">
        <i class="fas fa-arrow-left mr-2"></i> Back to History
    </a>

    <!-- Patient & Visit Header -->
    <div class="bg-gradient-to-r from-slate-800 to-slate-700 rounded-xl px-6 py-5">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="h-12 w-12 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white text-lg font-bold shadow-lg ring-2 ring-white/20">
                    {{ strtoupper(substr($patient->name ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-lg font-bold text-white">{{ $patient->name ?? 'Unknown' }}</h1>
                    <div class="flex items-center gap-3 mt-0.5 text-sm text-slate-300">
                        <span>{{ $patient->patient_code ?? 'N/A' }}</span>
                        <span class="text-slate-500">•</span>
                        <span>@if($patient->gender == 1) Male @elseif($patient->gender == 2) Female @else N/A @endif</span>
                        <span class="text-slate-500">•</span>
                        <span>{{ $age }} yrs</span>
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-4 text-right">
                @foreach($patient_details as $detail)
                <div>
                    <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Case #</p>
                    <p class="text-sm font-semibold text-primary-400">{{ $detail->case_number ?? 'N/A' }}</p>
                </div>
                <div class="w-px h-8 bg-slate-600"></div>
                <div>
                    <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Visit Date</p>
                    <p class="text-sm font-semibold text-white">{{ date('M d, Y', strtotime($detail->appointment_date)) }}</p>
                </div>
                <div class="w-px h-8 bg-slate-600"></div>
                <div>
                    <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Doctor</p>
                    <p class="text-sm font-semibold text-white">Dr. {{ $detail->staff_doctor->name ?? 'Unknown' }}</p>
                </div>
                @break
                @endforeach
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        <!-- LEFT: Main Content -->
        <div class="lg:col-span-2 space-y-5">

            <!-- Vitals Card -->
            @if($basics)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-slate-100 bg-rose-50/50">
                    <h3 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                        <i class="fas fa-heartbeat text-rose-500"></i> Vitals
                    </h3>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                        <div class="rounded-lg bg-rose-50 p-3 text-center border border-rose-100">
                            <p class="text-[10px] font-semibold uppercase text-rose-400 mb-1">Blood Pressure</p>
                            <p class="text-lg font-bold text-rose-700">{{ $basics['systolic_bp'] ?? '--' }}<span class="text-rose-400">/</span>{{ $basics['diastolic_bp'] ?? '--' }}</p>
                            <p class="text-[10px] text-rose-400">mmHg</p>
                        </div>
                        <div class="rounded-lg bg-blue-50 p-3 text-center border border-blue-100">
                            <p class="text-[10px] font-semibold uppercase text-blue-400 mb-1">Pulse</p>
                            <p class="text-lg font-bold text-blue-700">{{ $basics['pulse'] ?? '--' }}</p>
                            <p class="text-[10px] text-blue-400">bpm</p>
                        </div>
                        <div class="rounded-lg bg-amber-50 p-3 text-center border border-amber-100">
                            <p class="text-[10px] font-semibold uppercase text-amber-400 mb-1">Temperature</p>
                            <p class="text-lg font-bold text-amber-700">{{ $basics['temperature'] ?? '--' }}</p>
                            <p class="text-[10px] text-amber-400">°C</p>
                        </div>
                        <div class="rounded-lg bg-emerald-50 p-3 text-center border border-emerald-100">
                            <p class="text-[10px] font-semibold uppercase text-emerald-400 mb-1">SpO2</p>
                            <p class="text-lg font-bold text-emerald-700">{{ $basics['spo2'] ?? '--' }}</p>
                            <p class="text-[10px] text-emerald-400">%</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-4 gap-4 mt-3">
                        <div class="rounded-lg bg-slate-50 p-2 text-center border border-slate-100">
                            <p class="text-[10px] font-semibold uppercase text-slate-400">Height</p>
                            <p class="text-sm font-bold text-slate-700">{{ $basics['height'] ?? '--' }} cm</p>
                        </div>
                        <div class="rounded-lg bg-slate-50 p-2 text-center border border-slate-100">
                            <p class="text-[10px] font-semibold uppercase text-slate-400">Weight</p>
                            <p class="text-sm font-bold text-slate-700">{{ $basics['weight'] ?? '--' }} kg</p>
                        </div>
                        <div class="rounded-lg bg-slate-50 p-2 text-center border border-slate-100">
                            <p class="text-[10px] font-semibold uppercase text-slate-400">Respiration</p>
                            <p class="text-sm font-bold text-slate-700">{{ $basics['respiration'] ?? '--' }}</p>
                        </div>
                        <div class="rounded-lg bg-slate-50 p-2 text-center border border-slate-100">
                            <p class="text-[10px] font-semibold uppercase text-slate-400">RBS</p>
                            <p class="text-sm font-bold text-slate-700">{{ $basics['rbs'] ?? '--' }}</p>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <!-- Diagnosis -->
            @if($diagnosis)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-slate-100 bg-amber-50/50">
                    <h3 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                        <i class="fas fa-stethoscope text-amber-500"></i> Diagnosis & Assessment
                    </h3>
                </div>
                <div class="p-5 space-y-4">
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Diagnosis</p>
                        <p class="text-sm text-slate-800 whitespace-pre-line">{{ $diagnosis->diagnosis ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Treatment & Intervention</p>
                        <p class="text-sm text-slate-800">{{ $diagnosis->treatment->title ?? '—' }}</p>
                    </div>
                </div>
            </div>
            @endif

            <!-- Clinical Notes (SOAP) -->
            @if($basics)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-slate-100 bg-blue-50/50">
                    <h3 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                        <i class="fas fa-file-medical text-blue-500"></i> Clinical Notes
                    </h3>
                </div>
                <div class="p-5 space-y-4">
                    @if($basics['symptom'])
                    <div class="rounded-lg border border-blue-100 bg-blue-50/30 p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="inline-flex items-center justify-center h-5 w-5 rounded bg-blue-100 text-blue-700 text-[10px] font-bold">S</span>
                            <p class="text-xs font-semibold text-blue-600 uppercase">Subjective – Symptoms</p>
                        </div>
                        <p class="text-sm text-slate-700 whitespace-pre-line">{{ $basics['symptom'] }}</p>
                    </div>
                    @endif
                    @if($basics['description'])
                    <div class="rounded-lg border border-emerald-100 bg-emerald-50/30 p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="inline-flex items-center justify-center h-5 w-5 rounded bg-emerald-100 text-emerald-700 text-[10px] font-bold">O</span>
                            <p class="text-xs font-semibold text-emerald-600 uppercase">Objective – Examination</p>
                        </div>
                        <p class="text-sm text-slate-700 whitespace-pre-line">{!! $basics['description'] !!}</p>
                    </div>
                    @endif
                    @if($basics['note'])
                    <div class="rounded-lg border border-purple-100 bg-purple-50/30 p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="inline-flex items-center justify-center h-5 w-5 rounded bg-purple-100 text-purple-700 text-[10px] font-bold">P</span>
                            <p class="text-xs font-semibold text-purple-600 uppercase">Plan – Notes</p>
                        </div>
                        <p class="text-sm text-slate-700 whitespace-pre-line">{!! $basics['note'] !!}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Prescriptions -->
            @if(isset($item_prescription) && count($item_prescription) > 0)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-slate-100 bg-primary-50/50">
                    <h3 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                        <i class="fas fa-prescription text-primary-500"></i> Prescriptions
                        <span class="text-xs font-normal text-slate-400">({{ count($item_prescription) }} items)</span>
                    </h3>
                </div>
                <div class="p-5 space-y-3">
                    @foreach($item_prescription as $rx)
                    <div class="flex items-start gap-3 rounded-lg border border-slate-100 bg-slate-50/50 p-3">
                        <span class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-primary-100 text-primary-600 text-sm font-bold flex-shrink-0">℞</span>
                        <div class="flex-1">
                            <p class="text-sm font-bold text-slate-900">{{ $rx['drug_name'] }}</p>
                            <div class="flex flex-wrap gap-3 mt-1.5 text-xs text-slate-500">
                                <span class="inline-flex items-center gap-1 bg-white px-2 py-0.5 rounded-full border border-slate-200">
                                    <i class="fas fa-hashtag text-slate-300"></i> Qty: {{ $rx['quantity'] }}
                                </span>
                                <span class="inline-flex items-center gap-1 bg-white px-2 py-0.5 rounded-full border border-slate-200">
                                    <i class="fas fa-box text-slate-300"></i> {{ $rx['unit']['unit'] ?? 'unit' }}
                                </span>
                                <span class="inline-flex items-center gap-1 bg-white px-2 py-0.5 rounded-full border border-slate-200">
                                    <i class="fas fa-clock text-slate-300"></i> {{ $rx['frequency']['frequency'] ?? '' }}
                                </span>
                                <span class="inline-flex items-center gap-1 bg-white px-2 py-0.5 rounded-full border border-slate-200">
                                    <i class="fas fa-calendar-day text-slate-300"></i> {{ $rx['no_of_days'] }} days
                                </span>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Medical Tests -->
            @if(isset($item_medical_test) && count($item_medical_test) > 0)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-slate-100 bg-violet-50/50">
                    <h3 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                        <i class="fas fa-flask text-violet-500"></i> Medical Tests
                    </h3>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @foreach($item_medical_test as $test)
                        <div class="flex items-center gap-3 rounded-lg border border-slate-100 bg-slate-50/50 p-3">
                            <i class="fas fa-vial text-violet-400"></i>
                            <div>
                                <p class="text-sm font-medium text-slate-700">{{ $test['test_name'] }}</p>
                                @if(isset($test['center']) && $test['center'])
                                <p class="text-xs text-slate-400">Center: {{ $test['center']['center'] ?? '—' }}</p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Consumables Used -->
            @if(isset($item_medical_consumable) && count($item_medical_consumable) > 0)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-slate-100 bg-rose-50/50">
                    <h3 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                        <i class="fas fa-syringe text-rose-500"></i> Medical Consumables Used
                    </h3>
                </div>
                <div class="p-5">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        @foreach($item_medical_consumable as $consumable)
                        <div class="flex items-center justify-between rounded-lg border border-slate-100 bg-slate-50/50 p-3">
                            <span class="text-sm font-medium text-slate-700">{{ $consumable['item_name'] }}</span>
                            <span class="text-xs text-slate-500">{{ $consumable['quantity'] }} {{ $consumable['unit']['unit'] ?? '' }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- RIGHT: Sidebar -->
        <div class="space-y-5">
            <!-- Brief Notes -->
            @if(isset($item_brief_note) && isset($item_brief_note[0]))
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                        <i class="fas fa-clipboard text-slate-500"></i> Clinical Brief
                    </h3>
                </div>
                <div class="p-4 space-y-4">
                    @if($item_brief_note[0]['cheif_complaint'])
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1 flex items-center gap-1">
                            Chief Complaint
                            @if($item_brief_note[0]['cheif_complaint_status'] == 1)
                            <i class="fas fa-exclamation-triangle text-red-400 text-[10px]"></i>
                            @endif
                        </p>
                        <p class="text-sm text-slate-700">{{ $item_brief_note[0]['cheif_complaint'] }}</p>
                    </div>
                    @endif
                    @if($item_brief_note[0]['history_of_present_illness'])
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1 flex items-center gap-1">
                            History of Present Illness
                            @if($item_brief_note[0]['history_of_present_illness_status'] == 1)
                            <i class="fas fa-exclamation-triangle text-red-400 text-[10px]"></i>
                            @endif
                        </p>
                        <p class="text-sm text-slate-700">{{ $item_brief_note[0]['history_of_present_illness'] }}</p>
                    </div>
                    @endif
                    @if($item_brief_note[0]['past_history'])
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1 flex items-center gap-1">
                            Past History
                            @if($item_brief_note[0]['past_history_status'] == 1)
                            <i class="fas fa-exclamation-triangle text-red-400 text-[10px]"></i>
                            @endif
                        </p>
                        <p class="text-sm text-slate-700">{{ $item_brief_note[0]['past_history'] }}</p>
                    </div>
                    @endif
                    @if($item_brief_note[0]['physical_examiniation'])
                    <div>
                        <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Physical Examination</p>
                        <p class="text-sm text-slate-700">{{ $item_brief_note[0]['physical_examiniation'] }}</p>
                    </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Reports -->
            @if(isset($item_reports) && count($item_reports) > 0)
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-5 py-3 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-sm font-bold text-slate-700 flex items-center gap-2">
                        <i class="fas fa-file-pdf text-red-500"></i> Uploaded Reports
                    </h3>
                </div>
                <div class="p-4">
                    <div class="grid grid-cols-3 gap-2">
                        @foreach($item_reports as $report)
                        <a href="{{ url('public/uploads/patient/' . $report['report_name']) }}" target="_blank"
                           class="flex flex-col items-center justify-center rounded-lg border border-slate-200 bg-slate-50 p-3 hover:bg-red-50 hover:border-red-200 transition-colors group">
                            <i class="fas fa-file-pdf text-red-500 text-2xl mb-1 group-hover:scale-110 transition-transform"></i>
                            <span class="text-[10px] text-slate-400 truncate max-w-full">Report</span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- Patient Details -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Patient Details</h3>
                <div class="space-y-2 text-sm">
                    <div class="flex justify-between"><span class="text-slate-400">ID</span><span class="font-semibold text-slate-700">{{ $patient->patient_code ?? 'N/A' }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-400">Phone</span><span class="font-semibold text-slate-700">{{ $patient->phone ?? 'N/A' }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-400">Email</span><span class="font-semibold text-slate-700 text-xs">{{ $patient->email ?? 'N/A' }}</span></div>
                    <div class="flex justify-between"><span class="text-slate-400">Guardian</span><span class="font-semibold text-slate-700">{{ $patient->guardian_name ?? 'N/A' }}</span></div>
                    @if($patient->blood_group)
                    <div class="flex justify-between"><span class="text-slate-400">Blood Group</span><span class="font-semibold text-red-600">{{ $patient->blood_group }}</span></div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
