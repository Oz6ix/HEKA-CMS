@extends('backend.layouts.modern')

@section('title', 'Medical Certificates')

@section('content')
<div class="max-w-7xl mx-auto">
    @include('backend.layouts.includes.notification_alerts')

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800"><i class="fas fa-file-medical text-green-500 mr-2"></i> Medical Certificates</h1>
            <p class="text-slate-500 mt-1">Generate and print medical certificates.</p>
        </div>
        <a href="{{ route('medical_certificate.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700">
            <i class="fas fa-plus mr-2"></i> New Certificate
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-4 py-3">Certificate No</th>
                    <th class="px-4 py-3">Patient</th>
                    <th class="px-4 py-3">Doctor</th>
                    <th class="px-4 py-3">Type</th>
                    <th class="px-4 py-3">Issue Date</th>
                    <th class="px-4 py-3">Validity</th>
                    <th class="px-4 py-3">Fit</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($certificates as $cert)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $cert->certificate_no }}</td>
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $cert->patient->first_name ?? '' }} {{ $cert->patient->last_name ?? '' }}</td>
                    <td class="px-4 py-3 text-gray-600">Dr. {{ $cert->doctor->first_name ?? '' }} {{ $cert->doctor->last_name ?? '' }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700">{{ $cert->type_label }}</span>
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $cert->issue_date?->format('d M Y') }}</td>
                    <td class="px-4 py-3 text-gray-500 text-xs">
                        @if($cert->valid_from && $cert->valid_to)
                            {{ $cert->valid_from->format('d M') }} – {{ $cert->valid_to->format('d M Y') }}
                        @else
                            -
                        @endif
                    </td>
                    <td class="px-4 py-3">
                        @if($cert->is_fit)
                            <span class="text-green-600"><i class="fas fa-check-circle"></i> Fit</span>
                        @else
                            <span class="text-red-600"><i class="fas fa-times-circle"></i> Unfit</span>
                        @endif
                    </td>
                    <td class="px-4 py-3 flex gap-2">
                        <a href="{{ route('medical_certificate.print', $cert->id) }}" class="text-primary-600 hover:text-primary-800 text-xs"><i class="fas fa-print"></i></a>
                        <a href="{{ route('medical_certificate.delete', $cert->id) }}" onclick="return confirm('Delete?')" class="text-red-500 hover:text-red-700 text-xs"><i class="fas fa-trash-can"></i></a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-12 text-center text-gray-400">
                        <i class="fas fa-file-medical text-3xl mb-2"></i>
                        <p class="text-sm">No certificates yet</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
