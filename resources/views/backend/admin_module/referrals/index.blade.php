@extends('backend.layouts.modern')

@section('title', 'Referrals')

@section('content')
<div class="max-w-7xl mx-auto">
    @include('backend.layouts.includes.notification_alerts')

    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-800"><i class="fas fa-share-from-square text-blue-500 mr-2"></i> Referrals</h1>
            <p class="text-slate-500 mt-1">Track incoming and outgoing patient referrals.</p>
        </div>
        <a href="{{ route('referral.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700">
            <i class="fas fa-plus mr-2"></i> New Referral
        </a>
    </div>

    <!-- Summary -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-arrow-right-to-bracket text-blue-600"></i>
            </div>
            <div>
                <p class="text-xl font-bold text-blue-700">{{ $referrals->where('referral_type', 'incoming')->count() }}</p>
                <p class="text-sm text-blue-600">Incoming</p>
            </div>
        </div>
        <div class="bg-purple-50 border border-purple-200 rounded-xl p-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-arrow-right-from-bracket text-purple-600"></i>
            </div>
            <div>
                <p class="text-xl font-bold text-purple-700">{{ $referrals->where('referral_type', 'outgoing')->count() }}</p>
                <p class="text-sm text-purple-600">Outgoing</p>
            </div>
        </div>
        <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 flex items-center gap-3">
            <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                <i class="fas fa-clock text-amber-600"></i>
            </div>
            <div>
                <p class="text-xl font-bold text-amber-700">{{ $referrals->where('status', 'pending')->count() }}</p>
                <p class="text-sm text-amber-600">Pending</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                <tr>
                    <th class="px-4 py-3">Type</th>
                    <th class="px-4 py-3">Patient</th>
                    <th class="px-4 py-3">From / To</th>
                    <th class="px-4 py-3">Specialty</th>
                    <th class="px-4 py-3">Reason</th>
                    <th class="px-4 py-3">Date</th>
                    <th class="px-4 py-3">Status</th>
                    <th class="px-4 py-3">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($referrals as $ref)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-xs font-medium {{ $ref->referral_type === 'incoming' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }}">
                            <i class="fas {{ $ref->type_icon }}"></i> {{ $ref->type_label }}
                        </span>
                    </td>
                    <td class="px-4 py-3 font-medium text-gray-900">{{ $ref->patient->first_name ?? '' }} {{ $ref->patient->last_name ?? '' }}</td>
                    <td class="px-4 py-3 text-gray-600">
                        @if($ref->referral_type === 'incoming')
                            <span class="text-xs text-gray-400">From:</span> {{ $ref->referred_by ?? '-' }}
                        @else
                            <span class="text-xs text-gray-400">To:</span> {{ $ref->referred_to ?? '-' }}
                        @endif
                    </td>
                    <td class="px-4 py-3 text-gray-600">{{ $ref->specialty ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-500 max-w-xs truncate">{{ $ref->reason ?? '-' }}</td>
                    <td class="px-4 py-3 text-gray-600">{{ $ref->referral_date?->format('d M Y') }}</td>
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('referral.status', $ref->id) }}" class="inline">
                            @csrf
                            <select name="status" onchange="this.form.submit()" class="text-xs rounded px-2 py-1 border-0 bg-{{ $ref->status_color }}-100 text-{{ $ref->status_color }}-700 font-medium cursor-pointer">
                                <option value="pending" {{ $ref->status === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="completed" {{ $ref->status === 'completed' ? 'selected' : '' }}>Completed</option>
                                <option value="cancelled" {{ $ref->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                        </form>
                    </td>
                    <td class="px-4 py-3">
                        <a href="{{ route('referral.delete', $ref->id) }}" onclick="return confirm('Delete referral?')" class="text-red-500 hover:text-red-700 text-xs">
                            <i class="fas fa-trash-can"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" class="px-4 py-12 text-center text-gray-400">
                        <i class="fas fa-share-from-square text-3xl mb-2"></i>
                        <p class="text-sm">No referrals yet</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
