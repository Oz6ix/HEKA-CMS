@extends('backend.layouts.modern')

@section('title', 'New Medical Certificate')

@section('content')
<div class="max-w-4xl mx-auto">
    @include('backend.layouts.includes.notification_alerts')

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800"><i class="fas fa-file-medical text-green-500 mr-2"></i> New Medical Certificate</h1>
            <p class="text-slate-500 mt-1">Generate a fitness, sick leave, or medical certificate.</p>
        </div>
        <a href="{{ route('medical_certificates.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('medical_certificate.store') }}" method="POST" class="p-6 space-y-8">
            @csrf

            <!-- Certificate Type Cards -->
            <div>
                <h3 class="text-base font-semibold text-slate-900 border-b border-slate-200 pb-2 mb-4">Certificate Type</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3" x-data="{ type: '{{ old('type', 'fitness') }}' }">
                    @foreach(['fitness' => ['Fitness', 'fa-heart-pulse', 'green'], 'sick_leave' => ['Sick Leave', 'fa-bed', 'amber'], 'medical' => ['Medical', 'fa-stethoscope', 'blue'], 'custom' => ['Custom', 'fa-file-pen', 'gray']] as $key => $meta)
                    <label class="cursor-pointer">
                        <input type="radio" name="type" value="{{ $key }}" x-model="type" class="hidden peer">
                        <div class="p-3 rounded-xl border-2 text-center transition-all peer-checked:border-{{ $meta[2] }}-500 peer-checked:bg-{{ $meta[2] }}-50 border-gray-200 hover:border-gray-300">
                            <i class="fas {{ $meta[1] }} text-xl text-{{ $meta[2] }}-500 mb-1"></i>
                            <p class="text-sm font-medium text-gray-700">{{ $meta[0] }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
            </div>

            <!-- Patient & Doctor -->
            <div>
                <h3 class="text-base font-semibold text-slate-900 border-b border-slate-200 pb-2 mb-4">Patient & Doctor</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Patient <span class="text-red-500">*</span></label>
                        <select name="patient_id" required class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5">
                            <option value="">Select Patient</option>
                            @foreach($patients as $p)
                                <option value="{{ $p->id }}">{{ $p->first_name }} {{ $p->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Doctor <span class="text-red-500">*</span></label>
                        <select name="doctor_id" required class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5">
                            <option value="">Select Doctor</option>
                            @foreach($doctors as $d)
                                <option value="{{ $d->id }}">Dr. {{ $d->first_name }} {{ $d->last_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Dates & Status -->
            <div>
                <h3 class="text-base font-semibold text-slate-900 border-b border-slate-200 pb-2 mb-4">Dates & Fitness Status</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Issue Date <span class="text-red-500">*</span></label>
                        <input type="date" name="issue_date" value="{{ old('issue_date', date('Y-m-d')) }}" required class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Valid From</label>
                        <input type="date" name="valid_from" value="{{ old('valid_from') }}" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Valid To</label>
                        <input type="date" name="valid_to" value="{{ old('valid_to') }}" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5">
                    </div>
                </div>
                <div class="mt-4 flex items-center gap-3">
                    <label class="relative inline-flex items-center cursor-pointer">
                        <input type="checkbox" name="is_fit" value="1" class="sr-only peer" checked>
                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-green-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-600"></div>
                        <span class="ml-3 text-sm font-medium text-gray-700">Patient is Fit</span>
                    </label>
                </div>
            </div>

            <!-- Clinical Details -->
            <div>
                <h3 class="text-base font-semibold text-slate-900 border-b border-slate-200 pb-2 mb-4">Clinical Details</h3>
                <div class="grid grid-cols-1 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Purpose</label>
                        <input type="text" name="purpose" value="{{ old('purpose') }}" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" placeholder="e.g. Employment, Travel, School admission">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Findings</label>
                        <textarea name="findings" rows="3" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" placeholder="Clinical findings...">{{ old('findings') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Recommendations</label>
                        <textarea name="recommendations" rows="2" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" placeholder="Doctor's recommendations...">{{ old('recommendations') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Restrictions</label>
                        <textarea name="restrictions" rows="2" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" placeholder="Any restrictions on activities...">{{ old('restrictions') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('medical_certificates.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-green-600 rounded-lg hover:bg-green-700">
                    <i class="fas fa-file-circle-check mr-2"></i> Generate Certificate
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
