@extends('backend.layouts.modern')
@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Pharmacy Bills</h1>
            <p class="mt-1 text-sm text-slate-500">Manage and generate pharmacy bills for diagnosed patients.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ url($url_prefix . '/bills/create') }}" class="inline-flex items-center rounded-md bg-primary-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition-all">
                <i class="fas fa-plus mr-2"></i> Generate Bill
            </a>
        </div>
    </div>

    <!-- Flash Messages -->
    @if(session('message'))
    <div class="rounded-md bg-green-50 p-4 border border-green-200">
        <div class="flex"><div class="shrink-0"><i class="fas fa-check-circle text-green-400"></i></div>
        <div class="ml-3"><p class="text-sm font-medium text-green-800">{{ session('message') }}</p></div></div>
    </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 gap-5 sm:grid-cols-3">
        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="truncate text-sm font-medium text-slate-500">Total Bills</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-slate-900">{{ count($items) }}</dd>
        </div>
        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="truncate text-sm font-medium text-slate-500">Total Revenue</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-emerald-600">{{ number_format($items->sum('net_amount'), 0) }} K</dd>
        </div>
        <div class="overflow-hidden rounded-lg bg-white px-4 py-5 shadow sm:p-6">
            <dt class="truncate text-sm font-medium text-slate-500">Latest Bill</dt>
            <dd class="mt-1 text-3xl font-semibold tracking-tight text-slate-900">{{ $items->count() > 0 ? $items->first()->bill_number : '—' }}</dd>
        </div>
    </div>

    <!-- Bills Table -->
    <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50">
                    <tr>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-xs font-medium uppercase tracking-wide text-slate-500 sm:pl-6">#</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-medium uppercase tracking-wide text-slate-500">Bill No</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-medium uppercase tracking-wide text-slate-500">Date</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-medium uppercase tracking-wide text-slate-500">Patient</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-xs font-medium uppercase tracking-wide text-slate-500">Doctor</th>
                        <th scope="col" class="px-3 py-3.5 text-right text-xs font-medium uppercase tracking-wide text-slate-500">Amount (K)</th>
                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6"><span class="sr-only">Actions</span></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 bg-white">
                    @forelse($items as $index => $item)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-slate-900 sm:pl-6">{{ $index + 1 }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm">
                            <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                                {{ $item->bill_number }}
                            </span>
                        </td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">{{ date('M d, Y', strtotime($item->bill_date)) }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-900 font-medium">{{ $item->patient->name ?? '—' }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-slate-500">{{ $item->staff_doctor->name ?? '—' }}</td>
                        <td class="whitespace-nowrap px-3 py-4 text-sm text-right font-semibold text-slate-900">{{ number_format($item->net_amount, 0) }}</td>
                        <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                            <button type="button" class="text-primary-600 hover:text-primary-900 view-bill-btn" data-bill="{{ $item->bill_number }}">
                                <i class="fas fa-eye mr-1"></i> View
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-3 py-12 text-center">
                            <div class="text-slate-400">
                                <i class="fas fa-file-invoice fa-3x mb-4"></i>
                                <p class="text-sm font-medium text-slate-500">No bills generated yet</p>
                                <p class="mt-1 text-sm text-slate-400">Generate a bill from a diagnosed appointment.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Bill Detail Modal -->
<div id="billModal" class="fixed inset-0 z-50 hidden" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-slate-500/75 transition-opacity" id="billModalBackdrop"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-3xl">
                <div class="bg-white px-4 pb-4 pt-5 sm:p-6 sm:pb-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-slate-900">Bill Details</h3>
                        <button type="button" class="text-slate-400 hover:text-slate-500" id="closeBillModal">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div id="billModalContent" class="min-h-[200px]">
                        <div class="flex items-center justify-center py-12">
                            <i class="fas fa-spinner fa-spin text-2xl text-slate-400"></i>
                        </div>
                    </div>
                </div>
                <div class="bg-slate-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                    <button type="button" onclick="window.print()" class="inline-flex w-full justify-center rounded-md bg-primary-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 sm:ml-3 sm:w-auto">
                        <i class="fas fa-print mr-2"></i> Print Invoice
                    </button>
                    <button type="button" id="closeBillModalBtn" class="mt-3 inline-flex w-full justify-center rounded-md bg-white px-3 py-2 text-sm font-semibold text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 hover:bg-slate-50 sm:mt-0 sm:w-auto">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var modal = document.getElementById('billModal');
    var content = document.getElementById('billModalContent');
    
    function openModal() { modal.classList.remove('hidden'); }
    function closeModal() { modal.classList.add('hidden'); }
    
    document.getElementById('closeBillModal').addEventListener('click', closeModal);
    document.getElementById('closeBillModalBtn').addEventListener('click', closeModal);
    document.getElementById('billModalBackdrop').addEventListener('click', closeModal);

    document.querySelectorAll('.view-bill-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var billNumber = this.getAttribute('data-bill');
            content.innerHTML = '<div class="flex items-center justify-center py-12"><i class="fas fa-spinner fa-spin text-2xl text-slate-400"></i></div>';
            openModal();
            
            fetch("{{ url($url_prefix . '/bill/ajax_fetch_bill_print_data') }}/" + billNumber, {
                headers: { 'Accept': 'text/html' }
            })
            .then(function(r) { return r.text(); })
            .then(function(html) { content.innerHTML = html; })
            .catch(function(e) { content.innerHTML = '<p class="text-red-500 text-center py-8">Failed to load bill details.</p>'; });
        });
    });
});
</script>
@endsection
