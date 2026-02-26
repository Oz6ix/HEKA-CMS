@extends('backend.layouts.modern')

@section('content')
@php
    $patient = $patient_details[0]->patient ?? null;
    $basics = $item_basic->first();
    $prev = $previousDiagnosis ?? null;
    $age = $patient && $patient->dob ? \Carbon\Carbon::parse($patient->dob)->age : 'N/A';
    $isReadOnly = $isDiagnosed && !request()->has('edit');
    $canEdit = $isDiagnosed && !$isBilled;
@endphp

<!-- Patient Header with Alert Ribbon -->
<div class="mb-5">
    @if($patient && $patient->blood_group)
    <div class="bg-red-50 border border-red-200 rounded-t-xl px-4 py-2 flex items-center gap-3">
        <span class="inline-flex items-center rounded-md bg-red-100 px-2.5 py-1 text-xs font-semibold text-red-800 ring-1 ring-inset ring-red-200">
            <i class="fas fa-tint mr-1"></i> {{ $patient->blood_group }}
        </span>
        <span class="text-xs text-red-600 font-medium"><i class="fas fa-exclamation-triangle mr-1"></i> Always verify allergies before prescribing</span>
    </div>
    @endif

    <div class="{{ $patient && $patient->blood_group ? 'rounded-b-xl' : 'rounded-xl' }} px-6 py-4" style="background: linear-gradient(135deg, #1e293b, #334155);">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="h-14 w-14 rounded-full flex items-center justify-center text-xl font-bold shadow-lg" style="background: linear-gradient(135deg, #60a5fa, #2563eb); color: #fff; box-shadow: 0 0 0 3px rgba(255,255,255,0.2);">
                    {{ strtoupper(substr($patient->name ?? 'U', 0, 1)) }}
                </div>
                <div>
                    <h1 class="text-xl font-bold" style="color: #fff;">{{ $patient->name ?? 'Unknown' }}</h1>
                    <div class="flex items-center gap-3 mt-1 text-sm" style="color: #cbd5e1;">
                        <span><i class="fas fa-id-badge mr-1" style="color: #94a3b8;"></i>{{ $patient->patient_code ?? 'N/A' }}</span>
                        <span style="color: #64748b;">•</span>
                        <span><i class="fas fa-venus-mars mr-1" style="color: #94a3b8;"></i>{{ $patient->gender ?? 'N/A' }}</span>
                        <span style="color: #64748b;">•</span>
                        <span><i class="fas fa-birthday-cake mr-1" style="color: #94a3b8;"></i>{{ $age }} yrs</span>
                        @if($patient && $patient->phone)
                        <span style="color: #64748b;">•</span>
                        <span><i class="fas fa-phone mr-1" style="color: #94a3b8;"></i>{{ $patient->phone }}</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="flex items-center gap-6">
                <div class="text-right">
                    <p class="uppercase tracking-wider font-semibold" style="font-size: 10px; color: #94a3b8;">Visit Date</p>
                    <p class="text-sm font-semibold mt-0.5" style="color: #fff;">{{ date('d M Y', strtotime($patient_details[0]->appointment_date)) }}</p>
                </div>
                <div class="w-px h-8" style="background: #475569;"></div>
                <div class="text-right">
                    <p class="uppercase tracking-wider font-semibold" style="font-size: 10px; color: #94a3b8;">Consultant</p>
                    <p class="text-sm font-semibold mt-0.5" style="color: #fff;">Dr. {{ $patient_details[0]->staff_doctor->name ?? 'Unknown' }}</p>
                </div>
                <div class="w-px h-8" style="background: #475569;"></div>
                <div class="text-right">
                    <p class="uppercase tracking-wider font-semibold" style="font-size: 10px; color: #94a3b8;">Status</p>
                    @if($isDiagnosed)
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-bold mt-0.5" style="background: rgba(34,197,94,0.2); color: #86efac;"><i class="fas fa-check-circle mr-1"></i>Completed</span>
                    @else
                        <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-bold mt-0.5" style="background: rgba(245,158,11,0.2); color: #fcd34d;"><i class="fas fa-edit mr-1"></i>In Progress</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Read-only Banner -->
@if($isReadOnly)
<div class="mb-4 rounded-xl border {{ $isBilled ? 'border-red-200 bg-red-50' : 'border-amber-200 bg-amber-50' }} px-5 py-3 flex items-center justify-between">
    <div class="flex items-center gap-2">
        <i class="fas fa-lock text-{{ $isBilled ? 'red' : 'amber' }}-500"></i>
        <span class="text-sm font-medium text-{{ $isBilled ? 'red' : 'amber' }}-700">
            @if($isBilled)
                This record is locked — patient has been billed.
            @else
                This consultation has been completed. View-only mode.
            @endif
        </span>
    </div>
    @if($canEdit)
    <a href="{{ url($url_prefix . '/emr/workbench/' . $patient_details[0]->id) }}?edit=1"
       class="inline-flex items-center rounded-lg bg-amber-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-amber-500 transition-all">
        <i class="fas fa-edit mr-1.5"></i> Edit Record
    </a>
    @endif
</div>
@endif

<!-- Main Two-Column Layout -->
<div class="flex gap-5" x-data="{ activeTab: 'soap', editVitals: false }">

    <!-- LEFT: Vitals Sidebar -->
    <div class="w-72 flex-shrink-0 space-y-4">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="flex items-center justify-between px-4 py-3 bg-slate-50 border-b border-slate-200">
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500">Vitals</h3>
                @if(!$isReadOnly)
                <button @click="editVitals = !editVitals" type="button"
                    class="text-xs text-primary-600 hover:text-primary-700 font-medium transition-colors"
                    x-text="editVitals ? 'Done' : 'Edit'"></button>
                @endif
            </div>
            <div class="p-3 space-y-2">
                <!-- BP -->
                <div class="rounded-lg bg-gradient-to-r from-rose-50 to-rose-100/50 p-3 border border-rose-200/60">
                    <div class="flex items-center gap-2">
                        <div class="h-8 w-8 rounded-lg bg-rose-500/10 flex items-center justify-center">
                            <i class="fas fa-heartbeat text-rose-500 text-sm"></i>
                        </div>
                        <div>
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-rose-400">Blood Pressure</p>
                            <div x-show="!editVitals">
                                <p class="text-lg font-bold text-rose-700">
                                    {{ ($basics->systolic_bp ?? '--') }}<span class="text-rose-400">/</span>{{ ($basics->diastolic_bp ?? '--') }}
                                    <span class="text-xs font-normal text-rose-400 ml-0.5">mmHg</span>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div x-show="editVitals" x-cloak class="mt-2 flex gap-1">
                        <input type="text" name="systolic_bp" value="{{ $basics->systolic_bp ?? '' }}" placeholder="Sys" class="w-1/2 rounded-md border-rose-200 text-sm py-1 px-2 focus:ring-rose-400 focus:border-rose-400">
                        <span class="text-rose-300 self-center">/</span>
                        <input type="text" name="diastolic_bp" value="{{ $basics->diastolic_bp ?? '' }}" placeholder="Dia" class="w-1/2 rounded-md border-rose-200 text-sm py-1 px-2 focus:ring-rose-400 focus:border-rose-400">
                    </div>
                </div>

                <!-- Pulse -->
                <div class="rounded-lg bg-gradient-to-r from-blue-50 to-blue-100/50 p-3 border border-blue-200/60">
                    <div class="flex items-center gap-2">
                        <div class="h-8 w-8 rounded-lg bg-blue-500/10 flex items-center justify-center">
                            <i class="fas fa-wave-square text-blue-500 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-blue-400">Pulse</p>
                            <p x-show="!editVitals" class="text-lg font-bold text-blue-700">{{ $basics->pulse ?? '--' }} <span class="text-xs font-normal text-blue-400">bpm</span></p>
                            <input x-show="editVitals" x-cloak type="text" name="pulse" value="{{ $basics->pulse ?? '' }}" placeholder="bpm" class="mt-1 w-full rounded-md border-blue-200 text-sm py-1 px-2 focus:ring-blue-400 focus:border-blue-400">
                        </div>
                    </div>
                </div>

                <!-- Temperature -->
                <div class="rounded-lg bg-gradient-to-r from-amber-50 to-amber-100/50 p-3 border border-amber-200/60">
                    <div class="flex items-center gap-2">
                        <div class="h-8 w-8 rounded-lg bg-amber-500/10 flex items-center justify-center">
                            <i class="fas fa-thermometer-half text-amber-500 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-amber-400">Temperature</p>
                            <p x-show="!editVitals" class="text-lg font-bold text-amber-700">{{ $basics->temperature ?? '--' }} <span class="text-xs font-normal text-amber-400">°C</span></p>
                            <input x-show="editVitals" x-cloak type="text" name="temperature" value="{{ $basics->temperature ?? '' }}" placeholder="°C" class="mt-1 w-full rounded-md border-amber-200 text-sm py-1 px-2 focus:ring-amber-400 focus:border-amber-400">
                        </div>
                    </div>
                </div>

                <!-- SpO2 -->
                <div class="rounded-lg bg-gradient-to-r from-emerald-50 to-emerald-100/50 p-3 border border-emerald-200/60">
                    <div class="flex items-center gap-2">
                        <div class="h-8 w-8 rounded-lg bg-emerald-500/10 flex items-center justify-center">
                            <i class="fas fa-lungs text-emerald-500 text-sm"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-[10px] font-semibold uppercase tracking-wider text-emerald-400">SpO2</p>
                            <p x-show="!editVitals" class="text-lg font-bold text-emerald-700">{{ $basics->spo2 ?? '--' }} <span class="text-xs font-normal text-emerald-400">%</span></p>
                            <input x-show="editVitals" x-cloak type="text" name="spo2" value="{{ $basics->spo2 ?? '' }}" placeholder="%" class="mt-1 w-full rounded-md border-emerald-200 text-sm py-1 px-2 focus:ring-emerald-400 focus:border-emerald-400">
                        </div>
                    </div>
                </div>

                <!-- Height / Weight -->
                <div class="grid grid-cols-2 gap-2">
                    <div class="rounded-lg bg-slate-50 p-3 border border-slate-200">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Height</p>
                        <p x-show="!editVitals" class="text-base font-bold text-slate-700">{{ $basics->height ?? '--' }} <span class="text-[10px] font-normal text-slate-400">cm</span></p>
                        <input x-show="editVitals" x-cloak type="text" name="height" value="{{ $basics->height ?? '' }}" placeholder="cm" class="mt-1 w-full rounded-md border-slate-200 text-sm py-1 px-2 focus:ring-primary-400 focus:border-primary-400">
                    </div>
                    <div class="rounded-lg bg-slate-50 p-3 border border-slate-200">
                        <p class="text-[10px] font-semibold uppercase tracking-wider text-slate-400">Weight</p>
                        <p x-show="!editVitals" class="text-base font-bold text-slate-700">{{ $basics->weight ?? '--' }} <span class="text-[10px] font-normal text-slate-400">kg</span></p>
                        <input x-show="editVitals" x-cloak type="text" name="weight" value="{{ $basics->weight ?? '' }}" placeholder="kg" class="mt-1 w-full rounded-md border-slate-200 text-sm py-1 px-2 focus:ring-primary-400 focus:border-primary-400">
                    </div>
                </div>
            </div>
        </div>

        <!-- Patient Info -->
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-4">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 mb-3">Patient Info</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between"><span class="text-slate-400">Blood Group</span><span class="font-semibold text-slate-700">{{ $patient->blood_group ?? 'N/A' }}</span></div>
                <div class="flex justify-between"><span class="text-slate-400">Phone</span><span class="font-semibold text-slate-700">{{ $patient->phone ?? 'N/A' }}</span></div>
                <div class="flex justify-between"><span class="text-slate-400">Email</span><span class="font-semibold text-slate-700 text-xs">{{ $patient->email ?? 'N/A' }}</span></div>
                <div class="flex justify-between"><span class="text-slate-400">Guardian</span><span class="font-semibold text-slate-700">{{ $patient->guardian_name ?? 'N/A' }}</span></div>
            </div>
        </div>

        <!-- History Link -->
        <a href="{{ url($url_prefix . '/diagnosis/history/' . ($patient->id ?? '')) }}" target="_blank"
           class="block bg-white rounded-xl shadow-sm border border-slate-200 p-4 hover:bg-slate-50 transition-colors group">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-2">
                    <i class="fas fa-clock-rotate-left text-primary-500"></i>
                    <span class="text-sm font-semibold text-slate-700 group-hover:text-primary-600 transition-colors">View Full History</span>
                </div>
                <i class="fas fa-arrow-up-right-from-square text-xs text-slate-400 group-hover:text-primary-500 transition-colors"></i>
            </div>
        </a>
    </div>

    <!-- RIGHT: Clinical Workspace -->
    <div class="flex-1 min-w-0">
        <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
            <!-- Tab Navigation -->
            <div class="border-b border-slate-200 bg-slate-50/50">
                <nav class="-mb-px flex">
                    <button @click="activeTab = 'soap'" :class="activeTab === 'soap' ? 'border-primary-500 text-primary-600 bg-white' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="flex-1 py-3.5 text-center border-b-2 font-medium text-sm flex items-center justify-center gap-2 transition-all">
                        <i class="fas fa-file-medical text-xs"></i> SOAP Notes
                    </button>
                    <button @click="activeTab = 'prescriptions'" :class="activeTab === 'prescriptions' ? 'border-primary-500 text-primary-600 bg-white' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="flex-1 py-3.5 text-center border-b-2 font-medium text-sm flex items-center justify-center gap-2 transition-all">
                        <i class="fas fa-pills text-xs"></i> Prescriptions
                    </button>
                    <button @click="activeTab = 'laborders'" :class="activeTab === 'laborders' ? 'border-primary-500 text-primary-600 bg-white' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="flex-1 py-3.5 text-center border-b-2 font-medium text-sm flex items-center justify-center gap-2 transition-all">
                        <i class="fas fa-flask text-xs"></i> Lab Orders
                    </button>
                    <button @click="activeTab = 'documents'" :class="activeTab === 'documents' ? 'border-primary-500 text-primary-600 bg-white' : 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300'" class="flex-1 py-3.5 text-center border-b-2 font-medium text-sm flex items-center justify-center gap-2 transition-all">
                        <i class="fas fa-paperclip text-xs"></i> Documents
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->
            <div class="p-6">
                <form id="emr_form">
                    @csrf
                    <input type="hidden" name="id" value="{{ $patient_details[0]->id }}">
                    <input type="hidden" name="patient_id" value="{{ $patient_details[0]->patient_id }}">
                    <input type="hidden" name="appointment_basic_id" value="{{ $item_basic->first()->id ?? 0 }}">
                    <input type="hidden" name="treatment_and_intervention_id" value="1">

                    <!-- SOAP Notes Tab -->
                    <div x-show="activeTab === 'soap'" class="space-y-5">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="inline-flex items-center justify-center h-6 w-6 rounded-md bg-blue-100 text-blue-700 text-xs font-bold">S</span>
                                <label class="text-sm font-semibold text-slate-700">Subjective — Chief Complaint & Symptoms</label>
                            </div>
                            <textarea name="symptom" rows="3" placeholder="Patient reports..." {{ $isReadOnly ? 'readonly' : '' }}
                                class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500 text-sm placeholder:text-slate-400 {{ $isReadOnly ? 'bg-slate-50' : '' }}">{{ $basics->symptom ?? ($prev->symptom ?? '') }}</textarea>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="inline-flex items-center justify-center h-6 w-6 rounded-md bg-emerald-100 text-emerald-700 text-xs font-bold">O</span>
                                <label class="text-sm font-semibold text-slate-700">Objective — Physical Examination & Findings</label>
                            </div>
                            <textarea name="description" rows="3" placeholder="Examination findings, observations..." {{ $isReadOnly ? 'readonly' : '' }}
                                class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500 text-sm placeholder:text-slate-400 {{ $isReadOnly ? 'bg-slate-50' : '' }}">{{ $basics->description ?? ($prev->description ?? '') }}</textarea>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="inline-flex items-center justify-center h-6 w-6 rounded-md bg-amber-100 text-amber-700 text-xs font-bold">A</span>
                                <label class="text-sm font-semibold text-slate-700">Assessment — Diagnosis</label>
                            </div>
                            <textarea name="diagnosis" rows="3" placeholder="Clinical diagnosis, differential diagnoses..." {{ $isReadOnly ? 'readonly' : '' }}
                                class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500 text-sm placeholder:text-slate-400 {{ $isReadOnly ? 'bg-slate-50' : '' }}">{{ $existingDiagnosis->diagnosis ?? ($prev->diagnosis ?? '') }}</textarea>
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="inline-flex items-center justify-center h-6 w-6 rounded-md bg-purple-100 text-purple-700 text-xs font-bold">P</span>
                                <label class="text-sm font-semibold text-slate-700">Plan — Treatment & Notes</label>
                            </div>
                            <textarea name="note" rows="3" placeholder="Treatment plan, follow-up instructions, referrals..." {{ $isReadOnly ? 'readonly' : '' }}
                                class="w-full rounded-lg border-slate-300 focus:border-primary-500 focus:ring-primary-500 text-sm placeholder:text-slate-400 {{ $isReadOnly ? 'bg-slate-50' : '' }}">{{ $basics->note ?? ($prev->note ?? '') }}</textarea>
                        </div>
                    </div>

                    <!-- Prescriptions Tab — Card-Based "Prescription Pad" -->
                    <div x-show="activeTab === 'prescriptions'" x-cloak class="space-y-4">
                        <div class="flex justify-between items-center">
                            <h3 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                                <i class="fas fa-prescription text-primary-500 text-lg"></i> Prescription Orders
                            </h3>
                            @if(!$isReadOnly)
                            <button type="button" id="add_rx_card" class="inline-flex items-center rounded-lg bg-primary-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-primary-500 transition-colors">
                                <i class="fas fa-plus mr-1.5"></i> Add Medicine
                            </button>
                            @endif
                        </div>

                        <!-- Existing Prescriptions (read-only when diagnosed) -->
                        @if($isDiagnosed && count($existingPrescriptions) > 0)
                        <div class="space-y-3">
                            @foreach($existingPrescriptions as $rx)
                            <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                                <div class="flex items-start gap-3">
                                    <span class="inline-flex items-center justify-center h-8 w-8 rounded-lg bg-primary-100 text-primary-600 text-sm font-bold flex-shrink-0">℞</span>
                                    <div class="flex-1">
                                        <p class="text-sm font-bold text-slate-900">{{ $rx->drug_name }}</p>
                                        <div class="flex flex-wrap gap-3 mt-2 text-xs text-slate-600">
                                            <span class="inline-flex items-center gap-1"><i class="fas fa-hashtag text-slate-400"></i> Qty: {{ $rx->quantity }}</span>
                                            <span class="inline-flex items-center gap-1"><i class="fas fa-calendar-day text-slate-400"></i> {{ $rx->no_of_days }} days</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif

                        <!-- Rx Card Container (for new prescriptions) -->
                        @if(!$isReadOnly)
                        <div id="rx_cards_container" class="space-y-3"></div>
                        @endif
                    </div>

                    <!-- Lab Orders Tab -->
                    <div x-show="activeTab === 'laborders'" x-cloak class="space-y-6">
                        <!-- Existing Tests with Results Entry -->
                        @if($isDiagnosed && count($existingTests) > 0)
                        <div>
                            <h3 class="text-sm font-semibold text-slate-700 flex items-center gap-2 mb-3">
                                <i class="fas fa-clipboard-list text-violet-500"></i> Ordered Tests & Results
                            </h3>
                            <div class="space-y-3">
                                @foreach($existingTests as $test)
                                <div class="rounded-xl border {{ $test->result_value ? ($test->interpretation === 'critical' ? 'border-red-300 bg-red-50/30' : ($test->interpretation === 'abnormal' ? 'border-amber-300 bg-amber-50/30' : 'border-emerald-300 bg-emerald-50/30')) : 'border-slate-200 bg-white' }} overflow-hidden" id="test_card_{{ $test->id }}">
                                    <div class="flex items-center justify-between px-4 py-3">
                                        <div class="flex items-center gap-3">
                                            <i class="fas fa-vial {{ $test->pathology_test_id ? 'text-violet-400' : 'text-cyan-400' }}"></i>
                                            <span class="text-sm font-semibold text-slate-800">{{ $test->test_name }}</span>
                                            @if($test->result_value)
                                            <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[10px] font-bold
                                                {{ $test->interpretation === 'critical' ? 'bg-red-100 text-red-700' : ($test->interpretation === 'abnormal' ? 'bg-amber-100 text-amber-700' : 'bg-emerald-100 text-emerald-700') }}">
                                                {{ $test->interpretation === 'critical' ? '🔴 Critical' : ($test->interpretation === 'abnormal' ? '🟡 Abnormal' : '🟢 Normal') }}
                                            </span>
                                            @else
                                            <span class="inline-flex items-center rounded-full bg-slate-100 px-2 py-0.5 text-[10px] font-semibold text-slate-500">⏳ Pending</span>
                                            @endif
                                        </div>
                                        @if(!$test->result_value)
                                        <button type="button" onclick="toggleResultForm({{ $test->id }})" class="inline-flex items-center rounded-lg bg-primary-600 px-3 py-1 text-xs font-semibold text-white hover:bg-primary-500 transition-colors">
                                            <i class="fas fa-edit mr-1"></i> Enter Results
                                        </button>
                                        @endif
                                    </div>
                                    @if($test->result_value)
                                    <div class="px-4 pb-3 grid grid-cols-4 gap-3 text-xs">
                                        <div><span class="text-slate-400 block">Value</span><span class="font-bold text-slate-800">{{ $test->result_value }} {{ $test->result_unit }}</span></div>
                                        <div><span class="text-slate-400 block">Ref. Range</span><span class="font-medium text-slate-600">{{ $test->reference_range ?: '—' }}</span></div>
                                        <div><span class="text-slate-400 block">Date</span><span class="font-medium text-slate-600">{{ $test->result_date ? date('M d, Y', strtotime($test->result_date)) : '—' }}</span></div>
                                        <div><span class="text-slate-400 block">Notes</span><span class="font-medium text-slate-600">{{ $test->result_notes ?: '—' }}</span></div>
                                    </div>
                                    @endif
                                    <!-- Hidden Result Entry Form -->
                                    <div class="hidden border-t border-slate-200 bg-slate-50 px-4 py-3" id="result_form_{{ $test->id }}">
                                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-3">
                                            <div>
                                                <label class="block text-[10px] font-semibold text-slate-400 uppercase mb-1">Result Value *</label>
                                                <input type="text" id="rv_{{ $test->id }}" class="w-full rounded-lg border-slate-300 text-sm py-1.5 px-2" placeholder="e.g. 12.5 or Positive">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-semibold text-slate-400 uppercase mb-1">Unit</label>
                                                <input type="text" id="ru_{{ $test->id }}" class="w-full rounded-lg border-slate-300 text-sm py-1.5 px-2" placeholder="e.g. mg/dL">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-semibold text-slate-400 uppercase mb-1">Reference Range</label>
                                                <input type="text" id="rr_{{ $test->id }}" class="w-full rounded-lg border-slate-300 text-sm py-1.5 px-2" placeholder="e.g. 4.0-11.0">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-semibold text-slate-400 uppercase mb-1">Interpretation *</label>
                                                <select id="ri_{{ $test->id }}" class="w-full rounded-lg border-slate-300 text-sm py-1.5">
                                                    <option value="normal">🟢 Normal</option>
                                                    <option value="abnormal">🟡 Abnormal</option>
                                                    <option value="critical">🔴 Critical</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="grid grid-cols-2 gap-3 mb-3">
                                            <div>
                                                <label class="block text-[10px] font-semibold text-slate-400 uppercase mb-1">Report Date</label>
                                                <input type="date" id="rd_{{ $test->id }}" class="w-full rounded-lg border-slate-300 text-sm py-1.5 px-2" value="{{ date('Y-m-d') }}">
                                            </div>
                                            <div>
                                                <label class="block text-[10px] font-semibold text-slate-400 uppercase mb-1">Notes</label>
                                                <input type="text" id="rn_{{ $test->id }}" class="w-full rounded-lg border-slate-300 text-sm py-1.5 px-2" placeholder="Doctor's comments">
                                            </div>
                                        </div>
                                        <div class="flex gap-2">
                                            <button type="button" onclick="saveTestResult({{ $test->id }})" class="inline-flex items-center rounded-lg bg-emerald-600 px-4 py-1.5 text-xs font-semibold text-white hover:bg-emerald-500">
                                                <i class="fas fa-check mr-1"></i> Save Result
                                            </button>
                                            <button type="button" onclick="toggleResultForm({{ $test->id }})" class="inline-flex items-center rounded-lg border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-600 hover:bg-slate-100">
                                                Cancel
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif

                        @if(!$isReadOnly)
                        <!-- Pathology Section -->
                        <div>
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                                    <i class="fas fa-vial text-violet-500"></i> Pathology Tests
                                </h3>
                                <button type="button" id="add_pathology_row" class="inline-flex items-center rounded-lg bg-violet-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-violet-500 transition-colors">
                                    <i class="fas fa-plus mr-1.5"></i> Add Test
                                </button>
                            </div>
                            <div id="pathology_container" class="space-y-2"></div>
                        </div>

                        <div class="border-t border-dashed border-slate-200"></div>

                        <!-- Radiology Section -->
                        <div>
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                                    <i class="fas fa-x-ray text-cyan-500"></i> Radiology Tests
                                </h3>
                                <button type="button" id="add_radiology_row" class="inline-flex items-center rounded-lg bg-cyan-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm hover:bg-cyan-500 transition-colors">
                                    <i class="fas fa-plus mr-1.5"></i> Add Test
                                </button>
                            </div>
                            <div id="radiology_container" class="space-y-2"></div>
                        </div>
                        @endif
                    </div>

                    <!-- Documents Tab -->
                    <div x-show="activeTab === 'documents'" x-cloak class="space-y-6">
                        <!-- Upload Zone -->
                        <div>
                            <div class="flex justify-between items-center mb-3">
                                <h3 class="text-sm font-semibold text-slate-700 flex items-center gap-2">
                                    <i class="fas fa-cloud-upload-alt text-primary-500"></i> Upload Documents
                                </h3>
                            </div>
                            <div class="rounded-xl border-2 border-dashed border-slate-300 bg-slate-50/50 p-6 text-center hover:border-primary-400 hover:bg-primary-50/30 transition-colors" id="drop_zone">
                                <i class="fas fa-cloud-upload-alt text-3xl text-slate-300 mb-2"></i>
                                <p class="text-sm text-slate-500 mb-3">Drag & drop files here, or use the buttons below</p>
                                <div class="flex items-center justify-center gap-3 flex-wrap">
                                    <label for="doc_file_input" class="inline-flex items-center rounded-lg bg-primary-600 px-4 py-2 text-xs font-semibold text-white cursor-pointer hover:bg-primary-500 transition-colors">
                                        <i class="fas fa-folder-open mr-1.5"></i> Browse Files
                                    </label>
                                    <label for="doc_camera_input" class="inline-flex items-center rounded-lg bg-amber-600 px-4 py-2 text-xs font-semibold text-white cursor-pointer hover:bg-amber-500 transition-colors">
                                        <i class="fas fa-camera mr-1.5"></i> Take Photo
                                    </label>
                                    <input type="file" id="doc_file_input" class="hidden" accept="image/*,.pdf" multiple>
                                    <input type="file" id="doc_camera_input" class="hidden" accept="image/*" capture="environment">
                                </div>
                                <div class="mt-3 flex items-center justify-center gap-3">
                                    <select id="doc_category" class="rounded-lg border-slate-300 text-xs py-1.5 px-3">
                                        @foreach($documentCategories as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                        @endforeach
                                    </select>
                                    <input type="text" id="doc_notes" class="rounded-lg border-slate-300 text-xs py-1.5 px-3 w-48" placeholder="Notes (optional)">
                                </div>
                            </div>
                            <!-- Upload Progress -->
                            <div id="upload_progress" class="hidden mt-3 rounded-lg bg-blue-50 border border-blue-200 p-3 text-sm text-blue-700">
                                <i class="fas fa-spinner fa-spin mr-2"></i> <span id="upload_status">Uploading...</span>
                            </div>
                        </div>

                        <!-- Document Gallery -->
                        <div>
                            <h3 class="text-sm font-semibold text-slate-700 flex items-center gap-2 mb-3">
                                <i class="fas fa-images text-slate-500"></i> Uploaded Documents
                                <span class="text-xs font-normal text-slate-400">({{ count($patientDocuments) }} files)</span>
                            </h3>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-3" id="doc_gallery">
                                @forelse($patientDocuments as $doc)
                                <div class="rounded-xl border border-slate-200 bg-white overflow-hidden group relative" id="doc_item_{{ $doc->id }}">
                                    @if($doc->file_type === 'image')
                                    <a href="{{ asset('uploads/patient_documents/' . $doc->file_name) }}" target="_blank">
                                        <div class="h-28 bg-slate-100 flex items-center justify-center overflow-hidden">
                                            <img src="{{ asset('uploads/patient_documents/' . $doc->file_name) }}" class="w-full h-full object-cover" alt="">
                                        </div>
                                    </a>
                                    @else
                                    <a href="{{ asset('uploads/patient_documents/' . $doc->file_name) }}" target="_blank">
                                        <div class="h-28 bg-red-50 flex items-center justify-center">
                                            <i class="fas fa-file-pdf text-4xl text-red-400"></i>
                                        </div>
                                    </a>
                                    @endif
                                    <div class="p-2">
                                        <span class="inline-flex items-center rounded-full px-1.5 py-0.5 text-[9px] font-semibold
                                            {{ in_array($doc->category, ['external_lab','external_imaging']) ? 'bg-orange-100 text-orange-700' : 'bg-primary-100 text-primary-700' }}">
                                            {{ $documentCategories[$doc->category] ?? $doc->category }}
                                        </span>
                                        <p class="text-[10px] text-slate-400 mt-1 truncate" title="{{ $doc->original_name }}">{{ $doc->original_name }}</p>
                                        @if($doc->notes)
                                        <p class="text-[10px] text-slate-500 mt-0.5 truncate">{{ $doc->notes }}</p>
                                        @endif
                                    </div>
                                    <button type="button" onclick="deleteDocument({{ $doc->id }})" class="absolute top-1 right-1 h-6 w-6 rounded-full bg-red-500 text-white text-xs opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                                @empty
                                <div class="col-span-3 text-center py-8" id="no_docs_msg">
                                    <i class="fas fa-folder-open text-2xl text-slate-300 mb-2"></i>
                                    <p class="text-sm text-slate-400">No documents uploaded yet</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <!-- Sticky Action Bar -->
                    <div class="mt-6 pt-5 border-t border-slate-200 flex items-center justify-between">
                        <a href="{{ url($url_prefix . '/emr/list') }}" class="text-sm font-medium text-slate-500 hover:text-slate-700 transition-colors">
                            <i class="fas fa-arrow-left mr-1"></i> Back to Queue
                        </a>
                        @if(!$isReadOnly)
                        <div class="flex items-center gap-3">
                            <button type="button" id="save_draft" class="rounded-lg border border-blue-300 bg-blue-50 px-5 py-2 text-sm font-semibold text-blue-700 hover:bg-blue-100 transition-colors flex items-center gap-2">
                                <i class="fas fa-save"></i> Save Draft
                            </button>
                            <button type="button" id="save_emr" class="rounded-lg bg-emerald-600 px-6 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 transition-all flex items-center gap-2">
                                <i class="fas fa-check-circle"></i> Save & Complete
                            </button>
                        </div>
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<style>
    .rx-autocomplete-list {
        position: absolute; top: 100%; left: 0; right: 0; z-index: 50;
        background: white; border: 1px solid #e2e8f0; border-radius: 0.75rem;
        box-shadow: 0 10px 25px rgba(0,0,0,0.1); max-height: 200px; overflow-y: auto;
    }
    .rx-autocomplete-item {
        padding: 8px 12px; cursor: pointer; font-size: 13px; border-bottom: 1px solid #f1f5f9;
    }
    .rx-autocomplete-item:hover { background: #f0f9ff; }
    .rx-autocomplete-item .drug-name { font-weight: 600; color: #1e293b; }
    .rx-autocomplete-item .drug-generic { font-size: 11px; color: #64748b; }
    [x-cloak] { display: none !important; }
    #drop_zone.drag-over { border-color: #6366f1; background: #eef2ff; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var rxIndex = 0;
    var testPathIndex = 0;
    var testRadIndex = 100;
    var searchDrugUrl = "{{ url($url_prefix . '/emr/ajax-search-drugs') }}";
    var searchTestUrl = "{{ url($url_prefix . '/emr/ajax-search-tests') }}";

    // === Rx Card Builder ===
    function addRxCard() {
        var container = document.getElementById('rx_cards_container');
        if (!container) return;
        var idx = rxIndex++;
        var card = document.createElement('div');
        card.className = 'rounded-xl border-2 border-slate-200 bg-white p-4 relative group hover:border-primary-300 transition-colors';
        card.id = 'rx_card_' + idx;
        card.innerHTML = `
            <button type="button" class="remove_rx absolute top-2 right-2 text-slate-300 hover:text-red-500 transition-colors" title="Remove"><i class="fas fa-times-circle text-lg"></i></button>
            <div class="flex items-start gap-3">
                <span class="inline-flex items-center justify-center h-9 w-9 rounded-lg bg-gradient-to-br from-primary-100 to-primary-200 text-primary-700 text-base font-bold flex-shrink-0 mt-1">℞</span>
                <div class="flex-1 space-y-3">
                    <div class="relative">
                        <input type="text" class="rx-drug-search w-full rounded-lg border-slate-300 text-sm py-2 pl-3 pr-8 focus:ring-primary-500 focus:border-primary-500 font-semibold"
                               placeholder="Type drug name..." autocomplete="off" data-idx="${idx}">
                        <input type="hidden" name="prescription[${idx}][drug_id]" id="drug_id_${idx}" value="0">
                        <input type="hidden" name="prescription[${idx}][pharmacy_name]" id="drug_name_${idx}">
                        <div class="rx-autocomplete-list hidden" id="rx_list_${idx}"></div>
                    </div>
                    <div class="text-xs text-slate-500 hidden" id="rx_info_${idx}"></div>
                    <div class="grid grid-cols-4 gap-3">
                        <div>
                            <label class="block text-[10px] font-semibold text-slate-400 uppercase mb-1">Qty</label>
                            <input type="text" name="prescription[${idx}][quantity]" placeholder="1" class="w-full rounded-lg border-slate-300 text-sm py-1.5 px-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                        <div>
                            <label class="block text-[10px] font-semibold text-slate-400 uppercase mb-1">Unit</label>
                            <select name="prescription[${idx}][unit_id]" class="w-full rounded-lg border-slate-300 text-sm py-1.5 focus:ring-primary-500 focus:border-primary-500">
                                @foreach($unit_item as $unit)
                                <option value="{{ $unit->id }}">{{ $unit->unit }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-semibold text-slate-400 uppercase mb-1">Frequency</label>
                            <select name="prescription[${idx}][frequency_id]" class="w-full rounded-lg border-slate-300 text-sm py-1.5 focus:ring-primary-500 focus:border-primary-500">
                                @foreach($frequency_item as $freq)
                                <option value="{{ $freq->id }}">{{ $freq->frequency }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-[10px] font-semibold text-slate-400 uppercase mb-1">Days</label>
                            <input type="text" name="prescription[${idx}][no_of_days]" placeholder="5" class="w-full rounded-lg border-slate-300 text-sm py-1.5 px-2 focus:ring-primary-500 focus:border-primary-500">
                        </div>
                    </div>
                </div>
            </div>`;
        container.appendChild(card);
        initRxAutocomplete(card.querySelector('.rx-drug-search'));
    }

    var addRxBtn = document.getElementById('add_rx_card');
    if (addRxBtn) addRxBtn.addEventListener('click', addRxCard);

    // === Drug Autocomplete ===
    var debounceTimer;
    function initRxAutocomplete(input) {
        var idx = input.getAttribute('data-idx');
        input.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            var q = this.value.trim();
            var list = document.getElementById('rx_list_' + idx);
            if (q.length < 1) { list.classList.add('hidden'); return; }
            debounceTimer = setTimeout(function() {
                fetch(searchDrugUrl + '?q=' + encodeURIComponent(q))
                    .then(r => r.json()).then(function(drugs) {
                        list.innerHTML = '';
                        if (drugs.length === 0) {
                            list.innerHTML = '<div class="rx-autocomplete-item text-slate-400">No drugs found</div>';
                        }
                        drugs.forEach(function(d) {
                            var item = document.createElement('div');
                            item.className = 'rx-autocomplete-item';
                            item.innerHTML = '<div class="drug-name">' + d.item_name + '</div>' +
                                (d.pharmacy_generic ? '<div class="drug-generic">' + d.pharmacy_generic + (d.pharmacy_dosage ? ' • ' + d.pharmacy_dosage : '') + (d.route ? ' • Route: ' + d.route : '') + '</div>' : '');
                            item.addEventListener('click', function() {
                                input.value = d.item_name;
                                document.getElementById('drug_id_' + idx).value = d.id;
                                document.getElementById('drug_name_' + idx).value = d.item_name;
                                var info = document.getElementById('rx_info_' + idx);
                                if (d.pharmacy_generic || d.pharmacy_dosage) {
                                    info.innerHTML = '<i class="fas fa-info-circle mr-1 text-primary-400"></i>' +
                                        (d.pharmacy_generic || '') + (d.pharmacy_dosage ? ' &bull; ' + d.pharmacy_dosage : '') + (d.route ? ' &bull; Route: ' + d.route : '');
                                    info.classList.remove('hidden');
                                }
                                list.classList.add('hidden');
                            });
                            list.appendChild(item);
                        });
                        list.classList.remove('hidden');
                    });
            }, 200);
        });
        input.addEventListener('blur', function() {
            setTimeout(function() { document.getElementById('rx_list_' + idx).classList.add('hidden'); }, 200);
        });
    }

    // === Lab Test Rows with Autocomplete ===
    function addTestRow(containerId, prefix, idx, colorClass) {
        var container = document.getElementById(containerId);
        if (!container) return;
        var row = document.createElement('div');
        row.className = 'flex items-center gap-2 group';
        row.innerHTML = `
            <div class="flex-1 relative">
                <input type="text" class="test-search w-full rounded-lg border-slate-300 text-sm py-2 pl-3 focus:ring-${colorClass}-500 focus:border-${colorClass}-500"
                       name="mts[${idx}][test_name]" placeholder="Type test name..." autocomplete="off" data-idx="${idx}">
                <input type="hidden" name="mts[${idx}][index]" value="${idx}">
                <div class="rx-autocomplete-list hidden" id="test_list_${idx}"></div>
            </div>
            <select name="mts[${idx}][reffered_center_id]" class="w-36 rounded-lg border-slate-300 text-sm py-2 focus:ring-${colorClass}-500 focus:border-${colorClass}-500">
                <option value="">Center</option>
                @foreach($center_item as $center)
                <option value="{{ $center->id }}">{{ $center->center }}</option>
                @endforeach
            </select>
            <button type="button" class="text-slate-300 hover:text-red-500 remove_row transition-colors"><i class="fas fa-times-circle"></i></button>`;
        container.appendChild(row);
        initTestAutocomplete(row.querySelector('.test-search'));
    }

    function initTestAutocomplete(input) {
        var idx = input.getAttribute('data-idx');
        input.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            var q = this.value.trim();
            var list = document.getElementById('test_list_' + idx);
            if (q.length < 1) { list.classList.add('hidden'); return; }
            debounceTimer = setTimeout(function() {
                fetch(searchTestUrl + '?q=' + encodeURIComponent(q))
                    .then(r => r.json()).then(function(tests) {
                        list.innerHTML = '';
                        if (tests.length === 0) {
                            list.innerHTML = '<div class="rx-autocomplete-item text-slate-400">No tests found</div>';
                        }
                        tests.forEach(function(t) {
                            var item = document.createElement('div');
                            item.className = 'rx-autocomplete-item';
                            item.innerHTML = '<span class="drug-name">' + t.test_name + '</span>' +
                                '<span class="ml-2 text-[10px] px-1.5 py-0.5 rounded-full ' +
                                (t.test_type === 'pathology' ? 'bg-violet-100 text-violet-600' : 'bg-cyan-100 text-cyan-600') +
                                '">' + t.test_type + '</span>';
                            item.addEventListener('click', function() {
                                input.value = t.test_name;
                                list.classList.add('hidden');
                            });
                            list.appendChild(item);
                        });
                        list.classList.remove('hidden');
                    });
            }, 200);
        });
        input.addEventListener('blur', function() {
            setTimeout(function() { document.getElementById('test_list_' + idx).classList.add('hidden'); }, 200);
        });
    }

    var addPathBtn = document.getElementById('add_pathology_row');
    if (addPathBtn) addPathBtn.addEventListener('click', function() { addTestRow('pathology_container', 'path', testPathIndex++, 'violet'); });

    var addRadBtn = document.getElementById('add_radiology_row');
    if (addRadBtn) addRadBtn.addEventListener('click', function() { addTestRow('radiology_container', 'rad', testRadIndex++, 'cyan'); });

    // === Remove Row / Card ===
    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove_row')) e.target.closest('.flex').remove();
        if (e.target.closest('.remove_rx')) e.target.closest('[id^="rx_card_"]').remove();
    });

    // === Save Draft ===
    var draftBtn = document.getElementById('save_draft');
    if (draftBtn) {
        draftBtn.addEventListener('click', function() {
            var form = document.getElementById('emr_form');
            // Collect vitals
            var vitalFields = ['systolic_bp', 'diastolic_bp', 'pulse', 'temperature', 'spo2', 'height', 'weight'];
            vitalFields.forEach(function(field) {
                var input = document.querySelector('[name="' + field + '"]');
                if (input && !form.querySelector('input[name="' + field + '"][type="hidden"]')) {
                    var hidden = document.createElement('input');
                    hidden.type = 'hidden'; hidden.name = field; hidden.value = input.value;
                    form.appendChild(hidden);
                }
            });
            var formData = new FormData(form);
            draftBtn.disabled = true;
            draftBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';
            fetch("{{ url($url_prefix . '/emr/save-draft') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value, 'Accept': 'application/json' },
                body: formData
            }).then(r => r.json()).then(function(data) {
                if (data.status === 'success') {
                    draftBtn.innerHTML = '<i class="fas fa-check mr-2"></i> Draft Saved!';
                    draftBtn.classList.remove('border-blue-300', 'bg-blue-50', 'text-blue-700');
                    draftBtn.classList.add('border-green-300', 'bg-green-50', 'text-green-700');
                    setTimeout(function() {
                        draftBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Save Draft';
                        draftBtn.classList.remove('border-green-300', 'bg-green-50', 'text-green-700');
                        draftBtn.classList.add('border-blue-300', 'bg-blue-50', 'text-blue-700');
                        draftBtn.disabled = false;
                    }, 2000);
                } else {
                    alert('Error saving draft');
                    draftBtn.disabled = false;
                    draftBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Save Draft';
                }
            }).catch(function(e) {
                alert('Error: ' + e.message);
                draftBtn.disabled = false;
                draftBtn.innerHTML = '<i class="fas fa-save mr-2"></i> Save Draft';
            });
        });
    }

    // === Save & Complete ===
    var saveBtn = document.getElementById('save_emr');
    if (saveBtn) {
        saveBtn.addEventListener('click', function() {
            var form = document.getElementById('emr_form');
            var vitalFields = ['systolic_bp', 'diastolic_bp', 'pulse', 'temperature', 'spo2', 'height', 'weight'];
            vitalFields.forEach(function(field) {
                var input = document.querySelector('[name="' + field + '"]');
                if (input && !form.querySelector('input[name="' + field + '"][type="hidden"]')) {
                    var hidden = document.createElement('input');
                    hidden.type = 'hidden'; hidden.name = field; hidden.value = input.value;
                    form.appendChild(hidden);
                }
            });
            var formData = new FormData(form);
            saveBtn.disabled = true;
            saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Saving...';
            fetch("{{ url($url_prefix . '/emr/store') }}", {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value, 'Accept': 'application/json' },
                body: formData
            }).then(r => r.json()).then(function(data) {
                if (data.status === 'success') {
                    saveBtn.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Saved!';
                    saveBtn.classList.remove('bg-emerald-600');
                    saveBtn.classList.add('bg-green-500');
                    setTimeout(function() { window.location.href = "{{ url($url_prefix . '/emr/list') }}"; }, 1000);
                } else if(data.validation) {
                    var msgs = Array.isArray(data.validation) ? data.validation : Object.values(data.validation);
                    alert('Validation Error:\n' + msgs.join('\n'));
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Save & Complete';
                } else {
                    alert('Error: ' + (data.message || 'An error occurred'));
                    saveBtn.disabled = false;
                    saveBtn.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Save & Complete';
                }
            }).catch(function(error) {
                alert('Server error: ' + error.message);
                saveBtn.disabled = false;
                saveBtn.innerHTML = '<i class="fas fa-check-circle mr-2"></i> Save & Complete';
            });
        });
    }

    // === Document Upload ===
    var uploadUrl = "{{ url($url_prefix . '/emr/upload-document') }}";
    var deleteUrl = "{{ url($url_prefix . '/emr/delete-document') }}";
    var resultUrl = "{{ url($url_prefix . '/emr/save-test-result') }}";
    var csrfToken = document.querySelector('input[name="_token"]').value;
    var patientId = document.querySelector('input[name="patient_id"]').value;
    var appointmentId = document.querySelector('input[name="id"]').value;

    function handleFileUpload(files) {
        if (!files || files.length === 0) return;
        var category = document.getElementById('doc_category').value;
        var notes = document.getElementById('doc_notes').value;
        var progress = document.getElementById('upload_progress');
        var status = document.getElementById('upload_status');
        progress.classList.remove('hidden');
        var uploaded = 0;
        Array.from(files).forEach(function(file) {
            var fd = new FormData();
            fd.append('file', file);
            fd.append('patient_id', patientId);
            fd.append('appointment_id', appointmentId);
            fd.append('category', category);
            fd.append('notes', notes);
            status.textContent = 'Uploading ' + file.name + '...';
            fetch(uploadUrl, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: fd
            }).then(r => r.json()).then(function(data) {
                uploaded++;
                if (data.status === 'success') {
                    addDocToGallery(data.document);
                }
                if (uploaded >= files.length) {
                    progress.classList.add('hidden');
                    document.getElementById('doc_notes').value = '';
                }
            }).catch(function(e) {
                uploaded++;
                if (uploaded >= files.length) progress.classList.add('hidden');
                alert('Upload failed: ' + e.message);
            });
        });
    }

    function addDocToGallery(doc) {
        var gallery = document.getElementById('doc_gallery');
        var noMsg = document.getElementById('no_docs_msg');
        if (noMsg) noMsg.remove();
        var cats = @json($documentCategories);
        var isImage = (doc.file_type === 'image');
        var div = document.createElement('div');
        div.className = 'rounded-xl border border-slate-200 bg-white overflow-hidden group relative';
        div.id = 'doc_item_' + doc.id;
        var fileUrl = "{{ asset('uploads/patient_documents') }}/" + doc.file_name;
        div.innerHTML = (isImage
            ? '<a href="'+fileUrl+'" target="_blank"><div class="h-28 bg-slate-100 flex items-center justify-center overflow-hidden"><img src="'+fileUrl+'" class="w-full h-full object-cover"></div></a>'
            : '<a href="'+fileUrl+'" target="_blank"><div class="h-28 bg-red-50 flex items-center justify-center"><i class="fas fa-file-pdf text-4xl text-red-400"></i></div></a>') +
            '<div class="p-2"><span class="inline-flex items-center rounded-full px-1.5 py-0.5 text-[9px] font-semibold bg-primary-100 text-primary-700">'+(cats[doc.category]||doc.category)+'</span>' +
            '<p class="text-[10px] text-slate-400 mt-1 truncate">'+doc.original_name+'</p></div>' +
            '<button type="button" onclick="deleteDocument('+doc.id+')" class="absolute top-1 right-1 h-6 w-6 rounded-full bg-red-500 text-white text-xs opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center"><i class="fas fa-times"></i></button>';
        gallery.prepend(div);
    }

    // File input handlers
    var fileInput = document.getElementById('doc_file_input');
    if (fileInput) fileInput.addEventListener('change', function() { handleFileUpload(this.files); this.value = ''; });
    var cameraInput = document.getElementById('doc_camera_input');
    if (cameraInput) cameraInput.addEventListener('change', function() { handleFileUpload(this.files); this.value = ''; });

    // Drag and drop
    var dropZone = document.getElementById('drop_zone');
    if (dropZone) {
        dropZone.addEventListener('dragover', function(e) { e.preventDefault(); dropZone.classList.add('drag-over'); });
        dropZone.addEventListener('dragleave', function() { dropZone.classList.remove('drag-over'); });
        dropZone.addEventListener('drop', function(e) { e.preventDefault(); dropZone.classList.remove('drag-over'); handleFileUpload(e.dataTransfer.files); });
    }

    // Delete document
    window.deleteDocument = function(id) {
        if (!confirm('Remove this document?')) return;
        fetch(deleteUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify({ document_id: id })
        }).then(r => r.json()).then(function(data) {
            if (data.status === 'success') {
                var el = document.getElementById('doc_item_' + id);
                if (el) el.remove();
            }
        });
    };

    // === Lab Results ===
    window.toggleResultForm = function(testId) {
        var form = document.getElementById('result_form_' + testId);
        form.classList.toggle('hidden');
    };

    window.saveTestResult = function(testId) {
        var val = document.getElementById('rv_' + testId).value;
        if (!val) { alert('Please enter a result value'); return; }
        fetch(resultUrl, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json', 'Content-Type': 'application/json' },
            body: JSON.stringify({
                test_id: testId,
                result_value: val,
                result_unit: document.getElementById('ru_' + testId).value,
                reference_range: document.getElementById('rr_' + testId).value,
                interpretation: document.getElementById('ri_' + testId).value,
                result_date: document.getElementById('rd_' + testId).value,
                result_notes: document.getElementById('rn_' + testId).value
            })
        }).then(r => r.json()).then(function(data) {
            if (data.status === 'success') {
                location.reload();
            } else {
                alert('Error: ' + (data.message || 'Failed to save'));
            }
        });
    };
});
</script>
@endsection
