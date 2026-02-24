@extends('backend.layouts.modern')

@section('title', 'Medical Certificate - ' . $certificate->certificate_no)

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="flex items-center justify-between mb-6 print:hidden">
        <a href="{{ route('medical_certificates.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
            <i class="fas fa-arrow-left mr-2"></i> Back to List
        </a>
        <button onclick="window.print()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700">
            <i class="fas fa-print mr-2"></i> Print Certificate
        </button>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" id="certificate">
        <!-- Clinic Header -->
        <div class="px-10 py-6 border-b-2 border-primary-600 text-center">
            <h1 class="text-xl font-bold text-gray-800">{{ $settings->hospital_name ?? 'HEKA CLINIC' }}</h1>
            <p class="text-sm text-gray-500">{{ $settings->address ?? '' }}</p>
            @if($settings->phone ?? null)
                <p class="text-xs text-gray-400">Tel: {{ $settings->phone }} {{ $settings->email ? '| Email: ' . $settings->email : '' }}</p>
            @endif
        </div>

        <!-- Certificate Title -->
        <div class="px-10 py-4 text-center bg-gray-50 border-b">
            <h2 class="text-lg font-bold text-gray-800 uppercase tracking-wider">{{ $certificate->type_label }}</h2>
            <p class="text-xs text-gray-500 mt-1">Certificate No: <span class="font-mono">{{ $certificate->certificate_no }}</span></p>
        </div>

        <!-- Certificate Body -->
        <div class="px-10 py-8 space-y-6 text-sm leading-relaxed text-gray-700">
            <p>This is to certify that</p>
            
            <div class="bg-gray-50 rounded-lg p-4 border-l-4 border-primary-500">
                <p class="font-semibold text-gray-900 text-lg">{{ $certificate->patient->first_name ?? '' }} {{ $certificate->patient->last_name ?? '' }}</p>
                @if($certificate->patient->patient_id ?? null)
                    <p class="text-xs text-gray-500">Patient ID: {{ $certificate->patient->patient_id }}</p>
                @endif
            </div>

            @if($certificate->findings)
                <div>
                    <p class="font-semibold text-gray-800 mb-1">Clinical Findings:</p>
                    <p class="text-gray-600 pl-4 border-l-2 border-gray-200">{{ $certificate->findings }}</p>
                </div>
            @endif

            @if($certificate->purpose)
                <p>Purpose: <span class="font-medium">{{ $certificate->purpose }}</span></p>
            @endif

            <div class="py-4 border-y border-gray-200">
                @if($certificate->is_fit)
                    <p class="text-center text-green-700 font-bold text-lg">
                        <i class="fas fa-check-circle mr-2"></i> The above-named person is certified as <span class="underline">MEDICALLY FIT</span>
                    </p>
                @else
                    <p class="text-center text-red-700 font-bold text-lg">
                        <i class="fas fa-times-circle mr-2"></i> The above-named person is <span class="underline">NOT MEDICALLY FIT</span>
                    </p>
                @endif
            </div>

            @if($certificate->valid_from && $certificate->valid_to)
                <p>This certificate is valid from <strong>{{ $certificate->valid_from->format('d F Y') }}</strong> to <strong>{{ $certificate->valid_to->format('d F Y') }}</strong>.</p>
            @endif

            @if($certificate->recommendations)
                <div>
                    <p class="font-semibold text-gray-800 mb-1">Recommendations:</p>
                    <p class="text-gray-600 pl-4 border-l-2 border-gray-200">{{ $certificate->recommendations }}</p>
                </div>
            @endif

            @if($certificate->restrictions)
                <div>
                    <p class="font-semibold text-gray-800 mb-1">Restrictions:</p>
                    <p class="text-gray-600 pl-4 border-l-2 border-gray-200">{{ $certificate->restrictions }}</p>
                </div>
            @endif
        </div>

        <!-- Signature Section -->
        <div class="px-10 py-8">
            <div class="flex justify-between items-end">
                <div class="text-center">
                    <p class="text-xs text-gray-500">Date of Issue</p>
                    <p class="font-semibold mt-1">{{ $certificate->issue_date->format('d F Y') }}</p>
                </div>
                <div class="text-center">
                    <div class="w-48 border-b border-gray-400 mb-2 pt-12"></div>
                    <p class="font-semibold text-gray-800">Dr. {{ $certificate->doctor->first_name ?? '' }} {{ $certificate->doctor->last_name ?? '' }}</p>
                    <p class="text-xs text-gray-500">{{ $certificate->doctor->specialist ?? 'Medical Practitioner' }}</p>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-10 py-3 bg-gray-50 border-t text-center text-xs text-gray-400">
            This certificate is issued on request and is not valid for medico-legal purposes unless specifically stated.
        </div>
    </div>
</div>

<style>
    @media print {
        body * { visibility: hidden; }
        #certificate, #certificate * { visibility: visible; }
        #certificate { position: absolute; left: 0; top: 0; width: 100%; border-radius: 0; box-shadow: none; border: none; }
    }
</style>
@endsection
