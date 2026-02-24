@extends('backend.layouts.modern')

@section('title', 'View Appointment')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h1 class="text-2xl font-bold text-slate-800">Appointment Details</h1>
        <div class="flex items-center gap-3">
            <a href="{{ url($url_prefix . '/appointment/edit/'.$item['id']) }}" class="flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-edit"></i>
                <span>Edit</span>
            </a>
            <a href="{{ url($url_prefix . '/appointment') }}" class="flex items-center gap-2 px-4 py-2 bg-white text-slate-600 border border-slate-300 rounded-lg hover:bg-slate-50 transition-colors">
                <i class="fas fa-arrow-left"></i>
                <span>Back to List</span>
            </a>
        </div>
    </div>

    @include('backend.layouts.includes.notification_alerts')

    <!-- Primary Info Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-lg font-semibold text-slate-800 mb-4 border-b border-slate-100 pb-2">Appointment Info</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-3">
            <div class="flex justify-between py-2 border-b border-slate-50">
                <span class="text-sm text-slate-500">Patient</span>
                <span class="text-sm font-semibold text-slate-800">{{ $item['patient']['name'] ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-slate-50">
                <span class="text-sm text-slate-500">Consultant Doctor</span>
                <span class="text-sm font-semibold text-slate-800">{{ $item['staff_doctor']['name'] ?? 'N/A' }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-slate-50">
                <span class="text-sm text-slate-500">Appointment Date</span>
                <span class="text-sm font-semibold text-slate-800">{{ $item['appointment_date'] }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-slate-50">
                <span class="text-sm text-slate-500">Case No.</span>
                <span class="text-sm font-semibold text-slate-800">{{ $item['case_number'] }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-slate-50">
                <span class="text-sm text-slate-500">Casualty</span>
                <span class="text-sm font-semibold text-slate-800">{{ $item['casualty']['casualty'] ?? '—' }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-slate-50">
                <span class="text-sm text-slate-500">TPA</span>
                <span class="text-sm font-semibold text-slate-800">{{ $item['tpa']['tpa'] ?? '—' }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-slate-50">
                <span class="text-sm text-slate-500">Reference</span>
                <span class="text-sm font-semibold text-slate-800">{{ $item['reference'] ?? '—' }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-slate-50">
                <span class="text-sm text-slate-500">Status</span>
                @if($item['status'] == 1)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Open</span>
                @elseif($item['status'] == 2)
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">Cancelled</span>
                @else
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">Closed</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Vitals Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-lg font-semibold text-slate-800 mb-4 border-b border-slate-100 pb-2">Vitals</h2>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            @php
                $vitals = [
                    ['label' => 'Height', 'value' => ($item_basic['height'] ?? '—') . ' ' . (isset($item_basic['height_unit']) && $item_basic['height_unit'] ? config('global.height')[$item_basic['height_unit']] ?? '' : ''), 'icon' => 'fa-ruler-vertical', 'color' => 'blue'],
                    ['label' => 'Weight', 'value' => ($item_basic['weight'] ?? '—') . ' ' . (isset($item_basic['weight_unit']) && $item_basic['weight_unit'] ? config('global.weight')[$item_basic['weight_unit']] ?? '' : ''), 'icon' => 'fa-weight', 'color' => 'purple'],
                    ['label' => 'Blood Pressure', 'value' => ($item_basic['systolic_bp'] ?? '—') . ' / ' . ($item_basic['diastolic_bp'] ?? '—') . ' mmHg', 'icon' => 'fa-heartbeat', 'color' => 'red'],
                    ['label' => 'Pulse', 'value' => ($item_basic['pulse'] ?? '—') . ' bpm', 'icon' => 'fa-wave-square', 'color' => 'amber'],
                    ['label' => 'Temperature', 'value' => ($item_basic['temperature'] ?? '—') . ' ' . (isset($item_basic['temperature_unit']) && $item_basic['temperature_unit'] ? config('global.temperature')[$item_basic['temperature_unit']] ?? '' : ''), 'icon' => 'fa-thermometer-half', 'color' => 'orange'],
                    ['label' => 'SPO2', 'value' => ($item_basic['spo2'] ?? '—') . ' %', 'icon' => 'fa-lungs', 'color' => 'green'],
                    ['label' => 'Respiration', 'value' => ($item_basic['respiration'] ?? '—') . ' /min', 'icon' => 'fa-wind', 'color' => 'teal'],
                    ['label' => 'RBS', 'value' => $item_basic['rbs'] ?? '—', 'icon' => 'fa-tint', 'color' => 'pink'],
                ];
            @endphp
            @foreach($vitals as $v)
            <div class="rounded-lg border border-slate-100 bg-slate-50/50 p-3">
                <div class="flex items-center gap-2 mb-1">
                    <i class="fas {{ $v['icon'] }} text-{{ $v['color'] }}-500 text-xs"></i>
                    <span class="text-xs text-slate-500 uppercase tracking-wide">{{ $v['label'] }}</span>
                </div>
                <div class="text-sm font-semibold text-slate-800">{{ $v['value'] }}</div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Symptoms & Notes Card -->
    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-6">
        <h2 class="text-lg font-semibold text-slate-800 mb-4 border-b border-slate-100 pb-2">Symptoms & Notes</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-3">
            <div class="flex justify-between py-2 border-b border-slate-50">
                <span class="text-sm text-slate-500">Symptom Type</span>
                <span class="text-sm font-semibold text-slate-800">{{ $item_basic['symptom_type']['symptom'] ?? '—' }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-slate-50">
                <span class="text-sm text-slate-500">Symptom</span>
                <span class="text-sm font-semibold text-slate-800">{{ $item_basic['symptom'] ?? '—' }}</span>
            </div>
            <div class="flex justify-between py-2 border-b border-slate-50">
                <span class="text-sm text-slate-500">Checkup At</span>
                <span class="text-sm font-semibold text-slate-800">{{ $item_basic['checkup_at'] ?? '—' }}</span>
            </div>
        </div>
        @if(!empty($item_basic['description']))
        <div class="mt-4">
            <span class="text-sm text-slate-500 block mb-1">Description</span>
            <p class="text-sm text-slate-700 bg-slate-50 rounded-lg p-3">{{ $item_basic['description'] }}</p>
        </div>
        @endif
        @if(!empty($item_basic['note']))
        <div class="mt-3">
            <span class="text-sm text-slate-500 block mb-1">Notes</span>
            <p class="text-sm text-slate-700 bg-slate-50 rounded-lg p-3">{{ $item_basic['note'] }}</p>
        </div>
        @endif
    </div>
</div>
@endsection
