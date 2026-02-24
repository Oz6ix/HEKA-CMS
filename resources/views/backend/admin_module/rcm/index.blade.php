@extends('backend.layouts.modern')
@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Revenue Cycle Management</h1>
            <p class="mt-1 text-sm text-slate-500">Unified billing — all service items consolidated in one invoice.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ url($url_prefix . '/rcm/create') }}" class="inline-flex items-center rounded-md bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 transition-all">
                <i class="fas fa-file-invoice mr-2"></i> Generate Invoice
            </a>
        </div>
    </div>

    @if(session('success_message'))
    <div class="rounded-md bg-green-50 p-4 border border-green-200">
        <div class="flex"><div class="shrink-0"><i class="fas fa-check-circle text-green-400"></i></div>
        <div class="ml-3"><p class="text-sm font-medium text-green-800">{{ session('success_message') }}</p></div></div>
    </div>
    @endif

    <!-- Stats -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-5">
        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="truncate text-sm font-medium text-slate-500">Total Invoices</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-slate-900">{{ $items->count() }}</dd>
        </div>
        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="truncate text-sm font-medium text-slate-500">Total Revenue</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-emerald-600">{{ number_format($items->sum('net_amount'), 0) }} K</dd>
        </div>
        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="truncate text-sm font-medium text-slate-500">Pending</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-red-600">{{ $items->where('payment_status', 'pending')->count() }}</dd>
        </div>
        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="truncate text-sm font-medium text-slate-500">Credit</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-amber-600">{{ $items->where('payment_status', 'credit')->count() }}</dd>
        </div>
        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="truncate text-sm font-medium text-slate-500">Paid</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-emerald-600">{{ $items->where('payment_status', 'paid')->count() }}</dd>
        </div>
    </div>

    <!-- Invoice Table -->
    <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th class="py-3.5 pl-4 pr-3 text-left text-xs font-medium uppercase tracking-wide text-slate-500 sm:pl-6">#</th>
                        <th class="px-3 py-3.5 text-left text-xs font-medium uppercase tracking-wide text-slate-500">Invoice No</th>
                        <th class="px-3 py-3.5 text-left text-xs font-medium uppercase tracking-wide text-slate-500">Date</th>
                        <th class="px-3 py-3.5 text-left text-xs font-medium uppercase tracking-wide text-slate-500">Patient</th>
                        <th class="px-3 py-3.5 text-left text-xs font-medium uppercase tracking-wide text-slate-500">Doctor</th>
                        <th class="px-3 py-3.5 text-right text-xs font-medium uppercase tracking-wide text-slate-500">Net Amount</th>
                        <th class="px-3 py-3.5 text-center text-xs font-medium uppercase tracking-wide text-slate-500">Payment</th>
                        <th class="px-3 py-3.5 text-center text-xs font-medium uppercase tracking-wide text-slate-500">Status</th>
                        <th class="relative py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($items as $index => $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-slate-900 sm:pl-6">{{ $index + 1 }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                            <span class="inline-flex items-center rounded-md bg-indigo-50 px-2 py-1 text-xs font-medium text-indigo-700 ring-1 ring-inset ring-indigo-700/10">
                                {{ $item->bill_number }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">{{ date('M d, Y', strtotime($item->bill_date)) }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-900 font-medium">{{ $item->patient->name ?? '—' }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">{{ $item->doctor->name ?? '—' }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-right font-semibold text-slate-900">{{ number_format($item->net_amount, 0) }} K</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-center">
                            @if($item->payment_mode)
                                <span class="text-xs text-slate-500">{{ $item->payment_mode }}</span>
                            @else
                                <span class="text-xs text-slate-300">—</span>
                            @endif
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-center">
                            @if($item->payment_status === 'paid')
                                <span class="inline-flex items-center rounded-full bg-green-50 px-2 py-1 text-xs font-medium text-green-700 ring-1 ring-inset ring-green-600/20"><i class="fas fa-check-circle mr-1"></i>Paid</span>
                            @elseif($item->payment_status === 'credit')
                                <span class="inline-flex items-center rounded-full bg-amber-50 px-2 py-1 text-xs font-medium text-amber-800 ring-1 ring-inset ring-amber-600/20"><i class="fas fa-clock mr-1"></i>Credit</span>
                            @else
                                <span class="inline-flex items-center rounded-full bg-red-50 px-2 py-1 text-xs font-medium text-red-700 ring-1 ring-inset ring-red-600/20">Pending</span>
                            @endif
                        </td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                            <div class="flex items-center justify-end gap-2">
                                <a href="{{ url($url_prefix . '/rcm/invoice/' . $item->bill_number) }}" class="text-primary-600 hover:text-primary-900" title="View Invoice">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ url($url_prefix . '/rcm/receipt/' . $item->bill_number) }}" target="_blank" class="text-slate-500 hover:text-slate-900" title="Print Receipt">
                                    <i class="fas fa-receipt"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-3 py-12 text-center">
                            <div class="text-slate-400">
                                <i class="fas fa-file-invoice-dollar fa-3x mb-4"></i>
                                <p class="text-sm font-medium text-slate-500">No invoices generated yet</p>
                                <p class="mt-1 text-sm text-slate-400">Generate an invoice from a diagnosed appointment.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
