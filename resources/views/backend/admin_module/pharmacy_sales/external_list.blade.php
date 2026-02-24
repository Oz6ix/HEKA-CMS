@extends('backend.layouts.modern')

@section('title', 'External Prescriptions')

@section('content')
<div class="max-w-7xl mx-auto">
    @include('backend.layouts.includes.notification_alerts')

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800">External Prescriptions</h1>
            <p class="text-slate-500 mt-1">Walk-in and outside prescriptions.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ url($url_prefix . '/pharmacy_sales') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                <i class="fas fa-arrow-left mr-2"></i> Back to Sales
            </a>
            <a href="{{ url($url_prefix . '/pharmacy_sales/external') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700">
                <i class="fas fa-plus mr-2"></i> New Rx
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-4 py-3">Rx Code</th>
                    <th class="px-4 py-3">Patient</th>
                    <th class="px-4 py-3">Doctor</th>
                    <th class="px-4 py-3">Diagnosis</th>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Rx Image</th>
                </tr>
            </thead>
            <tbody>
                @forelse($prescriptions as $rx)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3 font-mono text-xs text-gray-600">{{ $rx->rx_code }}</td>
                    <td class="px-4 py-3">
                        <div class="font-medium text-gray-900">{{ $rx->patient_name }}</div>
                        @if($rx->patient_phone)
                            <div class="text-xs text-gray-400">{{ $rx->patient_phone }}</div>
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $rx->doctor_name ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600 max-w-xs truncate">{{ $rx->diagnosis ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $rx->rx_date?->format('d M Y') }}</td>
                    <td class="px-4 py-3">
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium bg-{{ $rx->status_color }}-100 text-{{ $rx->status_color }}-700">
                            {{ ucfirst($rx->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        @if($rx->rx_image)
                            <a href="{{ asset('uploads/prescriptions/' . $rx->rx_image) }}" target="_blank" class="text-blue-600 hover:text-blue-800 text-xs">
                                <i class="fas fa-image mr-1"></i> View
                            </a>
                        @else
                            <span class="text-gray-400 text-xs">-</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-4 py-12 text-center text-gray-400">
                        <i class="fas fa-file-prescription text-3xl mb-2"></i>
                        <p class="text-sm">No external prescriptions yet</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
