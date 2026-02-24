@extends('backend.layouts.modern')

@section('content')
@php
    $patient = $patient_details[0]->patient ?? null;
    $age = $patient && $patient->dob ? \Carbon\Carbon::parse($patient->dob)->age : 'N/A';
@endphp

<div class="space-y-6">
    <!-- Back -->
    <div>
        <a href="javascript:history.back()" class="inline-flex items-center text-sm font-medium text-slate-500 hover:text-slate-700 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i> Back to Workbench
        </a>
    </div>

    <!-- Patient Header -->
    <div class="bg-gradient-to-r from-slate-800 to-slate-700 rounded-xl px-6 py-5">
        <div class="flex items-center gap-4">
            <div class="h-14 w-14 rounded-full bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white text-xl font-bold shadow-lg ring-2 ring-white/20">
                {{ strtoupper(substr($patient->name ?? 'U', 0, 1)) }}
            </div>
            <div>
                <h1 class="text-xl font-bold text-white">{{ $patient->name ?? 'Unknown' }}</h1>
                <div class="flex items-center gap-3 mt-1 text-sm text-slate-300">
                    <span><i class="fas fa-id-badge mr-1 text-slate-400"></i>{{ $patient->patient_code ?? 'N/A' }}</span>
                    <span class="text-slate-500">•</span>
                    <span><i class="fas fa-venus-mars mr-1 text-slate-400"></i>
                        @if($patient->gender == 1) Male @elseif($patient->gender == 2) Female @else {{ $patient->gender ?? 'N/A' }} @endif
                    </span>
                    <span class="text-slate-500">•</span>
                    <span><i class="fas fa-birthday-cake mr-1 text-slate-400"></i>{{ $age }} yrs</span>
                    @if($patient->phone)
                    <span class="text-slate-500">•</span>
                    <span><i class="fas fa-phone mr-1 text-slate-400"></i>{{ $patient->phone }}</span>
                    @endif
                    @if($patient->blood_group)
                    <span class="text-slate-500">•</span>
                    <span class="inline-flex items-center rounded-md bg-red-500/20 px-2 py-0.5 text-xs font-bold text-red-300">
                        <i class="fas fa-tint mr-1"></i>{{ $patient->blood_group }}
                    </span>
                    @endif
                </div>
            </div>
            <div class="ml-auto text-right">
                <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Total Visits</p>
                <p class="text-2xl font-bold text-white">{{ count($items ?? []) }}</p>
            </div>
        </div>
    </div>

    <!-- Timeline -->
    @if(isset($items) && count($items) > 0)
    <div class="relative">
        <!-- Timeline Line -->
        <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-slate-200"></div>

        <div class="space-y-6">
            @foreach($items as $index => $item)
            @php
                $visitDate = $item->appointment->appointment_date ?? null;
                $isLatest = ($index === 0);
            @endphp
            <div class="relative pl-20">
                <!-- Timeline Dot -->
                <div class="absolute left-6 top-6 h-5 w-5 rounded-full border-4 {{ $isLatest ? 'bg-primary-500 border-primary-200' : 'bg-slate-300 border-white' }} shadow-sm"></div>

                <!-- Date Label -->
                <div class="absolute left-0 top-5 text-right" style="width: 50px;">
                    @if($visitDate)
                    <p class="text-[10px] font-bold text-slate-500 uppercase leading-tight">{{ date('d', strtotime($visitDate)) }}</p>
                    <p class="text-[10px] text-slate-400 uppercase">{{ date('M', strtotime($visitDate)) }}</p>
                    <p class="text-[10px] text-slate-400">{{ date('Y', strtotime($visitDate)) }}</p>
                    @endif
                </div>

                <!-- Visit Card -->
                <div class="bg-white rounded-xl shadow-sm border {{ $isLatest ? 'border-primary-200 ring-1 ring-primary-100' : 'border-slate-200' }} overflow-hidden">
                    <!-- Card Header -->
                    <div class="px-5 py-3 border-b border-slate-100 flex items-center justify-between {{ $isLatest ? 'bg-primary-50/50' : 'bg-slate-50/50' }}">
                        <div class="flex items-center gap-3">
                            @if($isLatest)
                            <span class="inline-flex items-center rounded-full bg-primary-100 px-2 py-0.5 text-[10px] font-bold text-primary-700 ring-1 ring-inset ring-primary-200">LATEST</span>
                            @endif
                            <span class="text-sm font-semibold text-slate-900">
                                Case: {{ $item->appointment->case_number ?? 'N/A' }}
                            </span>
                        </div>
                        <div class="flex items-center gap-3 text-xs text-slate-500">
                            <span><i class="fas fa-user-md mr-1 text-slate-400"></i>Dr. {{ $item->staff_doctor->name ?? 'Unknown' }}</span>
                            <a href="{{ url($url_prefix . '/diagnosis/history_view/' . $item->id) }}"
                               class="inline-flex items-center rounded-lg bg-primary-600 px-3 py-1 text-xs font-semibold text-white hover:bg-primary-500 transition-all">
                                <i class="fas fa-eye mr-1"></i> View Details
                            </a>
                        </div>
                    </div>

                    <!-- Card Body -->
                    <div class="px-5 py-4">
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <!-- Diagnosis -->
                            <div>
                                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Diagnosis</p>
                                <p class="text-sm font-medium text-slate-800">{{ $item->diagnosis ?: '—' }}</p>
                            </div>
                            <!-- Treatment -->
                            <div>
                                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Treatment</p>
                                <p class="text-sm font-medium text-slate-800">{{ $item->treatment->title ?? '—' }}</p>
                            </div>
                            <!-- Date -->
                            <div>
                                <p class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider mb-1">Visit Date</p>
                                <p class="text-sm font-medium text-slate-800">
                                    {{ $visitDate ? date('M d, Y', strtotime($visitDate)) : '—' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-12 text-center">
        <div class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-slate-100 mb-4">
            <i class="fas fa-history text-2xl text-slate-400"></i>
        </div>
        <h3 class="text-sm font-semibold text-slate-700 mb-1">No History Found</h3>
        <p class="text-sm text-slate-500">This patient has no previous consultations on record.</p>
    </div>
    @endif
</div>
@endsection
