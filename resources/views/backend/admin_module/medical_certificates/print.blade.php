@extends('backend.layouts.modern')

@section('title', 'Medical Certificate - ' . $certificate->certificate_no)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-6 no-print">
        <a href="{{ route('medical_certificates.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
            <i class="fas fa-arrow-left mr-2"></i> Back to List
        </a>
        <button onclick="window.print()" class="inline-flex items-center px-5 py-2.5 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 shadow-sm">
            <i class="fas fa-print mr-2"></i> Print Certificate
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" id="certificate">

        <!-- Clinic Letterhead -->
        <div class="px-10 pt-8 pb-6 border-b-2 border-slate-800">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <img src="{{ asset('assets/brand/heka-icon.png') }}" alt="Clinic Logo" class="h-16 w-16 object-contain">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900 tracking-tight">{{ $settings->hospital_name ?? 'HEKA CLINIC' }}</h1>
                        <p class="text-sm text-slate-600 mt-0.5">{{ $settings->hospital_address ?? '' }}</p>
                    </div>
                </div>
                <div class="text-right text-xs text-slate-500 space-y-0.5">
                    @if($settings->contact_phone ?? null)
                        <p><i class="fas fa-phone mr-1"></i> {{ $settings->contact_phone }}</p>
                    @endif
                    @if($settings->contact_email ?? null)
                        <p><i class="fas fa-envelope mr-1"></i> {{ $settings->contact_email }}</p>
                    @endif
                    @if($settings->alternative_phone ?? null)
                        <p><i class="fas fa-phone mr-1"></i> {{ $settings->alternative_phone }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Certificate Title Banner -->
        <div class="px-10 py-5 text-center bg-slate-50 border-b border-slate-200">
            <h2 class="text-lg font-bold text-slate-800 uppercase tracking-[0.2em]">{{ $certificate->type_label }}</h2>
            <div class="flex items-center justify-center gap-6 mt-2 text-xs text-slate-500">
                <span>Certificate No: <span class="font-mono font-medium text-slate-700">{{ $certificate->certificate_no }}</span></span>
                <span>Date: <span class="font-medium text-slate-700">{{ $certificate->issue_date->format('d F Y') }}</span></span>
            </div>
        </div>

        <!-- Certificate Body -->
        <div class="px-10 py-8 space-y-6 text-sm leading-relaxed text-slate-700">

            <!-- Opening Statement -->
            <p class="text-base">This is to certify that:</p>

            <!-- Patient Info Card -->
            <div class="bg-slate-50 rounded-lg p-5 border border-slate-200">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="font-bold text-slate-900 text-lg">{{ $certificate->patient->name ?? '' }}</p>
                        @if($certificate->patient->patient_id ?? null)
                            <p class="text-xs text-slate-500 mt-0.5">Patient ID: <span class="font-mono">{{ $certificate->patient->patient_code }}</span></p>
                        @endif
                        @if($certificate->patient->dob ?? null)
                            <p class="text-xs text-slate-500">Date of Birth: {{ \Carbon\Carbon::parse($certificate->patient->dob)->format('d F Y') }}</p>
                        @endif
                    </div>
                    @if($certificate->patient->gender ?? null)
                        <span class="px-3 py-1 text-xs font-medium rounded-full {{ $certificate->patient->gender == 'Male' ? 'bg-blue-100 text-blue-700' : 'bg-pink-100 text-pink-700' }}">
                            {{ $certificate->patient->gender }}
                        </span>
                    @endif
                </div>
            </div>

            @if($certificate->purpose)
                <div>
                    <p class="font-semibold text-slate-800 mb-1">Purpose of Examination:</p>
                    <p class="text-slate-600 pl-4 border-l-3 border-primary-400">{{ $certificate->purpose }}</p>
                </div>
            @endif

            @if($certificate->findings)
                <div>
                    <p class="font-semibold text-slate-800 mb-1">Clinical Findings:</p>
                    <p class="text-slate-600 pl-4 border-l-3 border-primary-400">{{ $certificate->findings }}</p>
                </div>
            @endif

            <!-- Fitness Status -->
            <div class="py-5 border-y-2 border-slate-200 my-6">
                @if($certificate->is_fit)
                    <p class="text-center font-bold text-lg">
                        <span class="inline-flex items-center gap-2 px-6 py-3 bg-green-50 text-green-800 rounded-lg border border-green-200">
                            <i class="fas fa-check-circle text-green-600"></i>
                            The above-named person is certified as <span class="underline decoration-2">MEDICALLY FIT</span>
                        </span>
                    </p>
                @else
                    <p class="text-center font-bold text-lg">
                        <span class="inline-flex items-center gap-2 px-6 py-3 bg-red-50 text-red-800 rounded-lg border border-red-200">
                            <i class="fas fa-times-circle text-red-600"></i>
                            The above-named person is <span class="underline decoration-2">NOT MEDICALLY FIT</span>
                        </span>
                    </p>
                @endif
            </div>

            @if($certificate->valid_from && $certificate->valid_to)
                <p>This certificate is valid from <strong>{{ $certificate->valid_from->format('d F Y') }}</strong> to <strong>{{ $certificate->valid_to->format('d F Y') }}</strong>.</p>
            @endif

            @if($certificate->recommendations)
                <div>
                    <p class="font-semibold text-slate-800 mb-1">Recommendations:</p>
                    <p class="text-slate-600 pl-4 border-l-3 border-primary-400">{{ $certificate->recommendations }}</p>
                </div>
            @endif

            @if($certificate->restrictions)
                <div>
                    <p class="font-semibold text-slate-800 mb-1">Restrictions:</p>
                    <p class="text-slate-600 pl-4 border-l-3 border-primary-400">{{ $certificate->restrictions }}</p>
                </div>
            @endif
        </div>

        <!-- Signature Section -->
        <div class="px-10 pt-6 pb-8">
            <div class="flex justify-between items-end">
                <!-- Date of Issue -->
                <div class="text-center">
                    <p class="text-xs text-slate-500 uppercase tracking-wider mb-1">Date of Issue</p>
                    <p class="font-semibold text-slate-800">{{ $certificate->issue_date->format('d F Y') }}</p>
                </div>

                <!-- Doctor Signature -->
                <div class="text-center">
                    <div class="w-56 mb-2 pt-16">
                        {{-- Signature space --}}
                    </div>
                    <div class="border-t-2 border-slate-400 pt-2">
                        <p class="font-bold text-slate-800">{{ $certificate->doctor->name ?? '' }}</p>
                        <p class="text-xs text-slate-500">{{ $certificate->doctor->specialist ?? 'Medical Practitioner' }}</p>
                        @if($certificate->doctor->registration_no ?? null)
                            <p class="text-xs text-slate-400 mt-0.5">Reg. No: {{ $certificate->doctor->registration_no }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Official Stamp Area -->
            <div class="mt-10 flex justify-start">
                <div class="border-2 border-dashed border-slate-300 rounded-lg px-8 py-6 text-center">
                    <p class="text-xs text-slate-400 uppercase tracking-wider">Official Stamp</p>
                </div>
            </div>
        </div>

        <!-- Footer Disclaimer -->
        <div class="px-10 py-3 bg-slate-50 border-t border-slate-200 text-center">
            <p class="text-[10px] text-slate-400">This certificate is issued on request and is not valid for medico-legal purposes unless specifically stated. This is a computer-generated document.</p>
        </div>
    </div>
</div>

<style>
    @media print {
        /* Hide everything except the certificate */
        .no-print, header, nav, aside, footer,
        [class*="sidebar"], [class*="bg-slate-900"],
        button[onclick*="print"] { display: none !important; }

        body {
            background: white !important;
            margin: 0;
            padding: 0;
            -webkit-print-color-adjust: exact;
            print-color-adjust: exact;
        }

        #certificate {
            border: none !important;
            border-radius: 0 !important;
            box-shadow: none !important;
            margin: 0 !important;
            max-width: 100% !important;
        }

        .max-w-3xl {
            max-width: 100% !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* Ensure good spacing on print */
        @page {
            margin: 15mm;
            size: A4 portrait;
        }
    }
</style>
@endsection
