@extends('backend.layouts.modern')

@section('title', 'New Referral')

@section('content')
<div class="max-w-4xl mx-auto">
    @include('backend.layouts.includes.notification_alerts')

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800"><i class="fas fa-share-from-square text-blue-500 mr-2"></i> New Referral</h1>
            <p class="text-slate-500 mt-1">Record an incoming or outgoing referral.</p>
        </div>
        <a href="{{ route('referrals.index') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
            <i class="fas fa-arrow-left mr-2"></i> Back
        </a>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <form action="{{ route('referral.store') }}" method="POST" class="p-6 space-y-8">
            @csrf

            <div x-data="{ type: '{{ old('referral_type', 'incoming') }}' }">
                <!-- Referral Type Toggle -->
                <div class="flex gap-4 mb-6">
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="referral_type" value="incoming" x-model="type" class="hidden peer">
                        <div class="p-4 rounded-xl border-2 text-center transition-all peer-checked:border-blue-500 peer-checked:bg-blue-50 border-gray-200 hover:border-gray-300">
                            <i class="fas fa-arrow-right-to-bracket text-2xl text-blue-500 mb-2"></i>
                            <p class="font-semibold text-gray-800">Incoming Referral</p>
                            <p class="text-xs text-gray-500">Patient referred TO us</p>
                        </div>
                    </label>
                    <label class="flex-1 cursor-pointer">
                        <input type="radio" name="referral_type" value="outgoing" x-model="type" class="hidden peer">
                        <div class="p-4 rounded-xl border-2 text-center transition-all peer-checked:border-purple-500 peer-checked:bg-purple-50 border-gray-200 hover:border-gray-300">
                            <i class="fas fa-arrow-right-from-bracket text-2xl text-purple-500 mb-2"></i>
                            <p class="font-semibold text-gray-800">Outgoing Referral</p>
                            <p class="text-xs text-gray-500">Patient referred FROM us</p>
                        </div>
                    </label>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Patient <span class="text-red-500">*</span></label>
                        <select name="patient_id" required class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5">
                            <option value="">Select Patient</option>
                            @foreach($patients as $p)
                                <option value="{{ $p->id }}" {{ old('patient_id') == $p->id ? 'selected' : '' }}>{{ $p->first_name }} {{ $p->last_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Referral Date <span class="text-red-500">*</span></label>
                        <input type="date" name="referral_date" value="{{ old('referral_date', date('Y-m-d')) }}" required class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5">
                    </div>

                    <div x-show="type === 'incoming'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Referred By (Doctor/Clinic)</label>
                        <input type="text" name="referred_by" value="{{ old('referred_by') }}" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" placeholder="Name of referring doctor/clinic">
                    </div>

                    <div x-show="type === 'outgoing'">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Referred To (Doctor/Clinic)</label>
                        <input type="text" name="referred_to" value="{{ old('referred_to') }}" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" placeholder="Name of destination doctor/clinic">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Specialty</label>
                        <select name="specialty" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5">
                            <option value="">Select Specialty</option>
                            <option value="Cardiology">Cardiology</option>
                            <option value="Dermatology">Dermatology</option>
                            <option value="ENT">ENT</option>
                            <option value="Gastroenterology">Gastroenterology</option>
                            <option value="General Surgery">General Surgery</option>
                            <option value="Nephrology">Nephrology</option>
                            <option value="Neurology">Neurology</option>
                            <option value="Ophthalmology">Ophthalmology</option>
                            <option value="Orthopedics">Orthopedics</option>
                            <option value="Pediatrics">Pediatrics</option>
                            <option value="Psychiatry">Psychiatry</option>
                            <option value="Pulmonology">Pulmonology</option>
                            <option value="Radiology">Radiology</option>
                            <option value="Urology">Urology</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Reason for Referral</label>
                        <textarea name="reason" rows="3" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" placeholder="Clinical reason...">{{ old('reason') }}</textarea>
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
                        <textarea name="notes" rows="2" class="bg-gray-50 border border-gray-300 text-sm rounded-lg w-full p-2.5" placeholder="Additional notes...">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <a href="{{ route('referrals.index') }}" class="px-5 py-2.5 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</a>
                <button type="submit" class="px-5 py-2.5 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700">
                    <i class="fas fa-save mr-2"></i> Save Referral
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
