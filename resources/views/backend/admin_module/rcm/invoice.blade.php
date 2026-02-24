@extends('backend.layouts.modern')
@section('content')
<div class="space-y-6" x-data="{ showPayModal: false, showSettleModal: false, isCredit: false }">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between print:hidden">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Invoice #{{ $invoice->bill_number }}</h1>
            <p class="mt-1 text-sm text-slate-500">Generated on {{ date('M d, Y h:i A', strtotime($invoice->bill_date)) }}</p>
        </div>
        <div class="flex flex-wrap gap-3 mt-4 sm:mt-0">
            <a href="{{ url($url_prefix . '/rcm') }}" class="inline-flex items-center rounded-md bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 transition-all">
                <i class="fas fa-arrow-left mr-2"></i> Back
            </a>
            @if($invoice->payment_status === 'pending')
            <button @click="showPayModal = true" class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 transition-all">
                <i class="fas fa-cash-register mr-2"></i> Close Bill / Pay
            </button>
            @elseif($invoice->payment_status === 'credit')
            <button @click="showSettleModal = true" class="inline-flex items-center rounded-md bg-amber-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-amber-500 transition-all">
                <i class="fas fa-hand-holding-usd mr-2"></i> Settle Credit
            </button>
            @endif
            <a href="{{ url($url_prefix . '/rcm/receipt/' . $invoice->bill_number) }}" target="_blank" class="inline-flex items-center rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 transition-all">
                <i class="fas fa-receipt mr-2"></i> Print Receipt
            </a>
        </div>
    </div>

    @if(session('success_message'))
    <div class="rounded-md bg-green-50 p-4 border border-green-200">
        <div class="flex"><div class="shrink-0"><i class="fas fa-check-circle text-green-400"></i></div>
        <div class="ml-3"><p class="text-sm font-medium text-green-800">{{ session('success_message') }}</p></div></div>
    </div>
    @endif

    <!-- Invoice Card -->
    <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden print:shadow-none print:ring-0">
        <!-- Invoice Header -->
        <div class="px-8 py-6 border-b border-slate-200">
            <div class="flex justify-between items-start">
                <div>
                    <h2 class="text-xl font-bold text-slate-900">{{ $general_settings->site_name ?? 'HEKA Clinic' }}</h2>
                    <p class="text-sm text-slate-500 mt-1">{{ $general_settings->address ?? '' }}</p>
                    <p class="text-sm text-slate-500">{{ $general_settings->phone ?? '' }}</p>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-bold text-primary-600">INVOICE</div>
                    <p class="text-sm text-slate-500 mt-1 font-mono">{{ $invoice->bill_number }}</p>
                    <p class="text-sm text-slate-500">{{ date('M d, Y', strtotime($invoice->bill_date)) }}</p>
                    <div class="mt-2 flex justify-end gap-2">
                        @if(($invoice->bill_type ?? 'appointment') === 'direct')
                            <span class="inline-flex items-center rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold text-emerald-800 ring-1 ring-inset ring-emerald-600/20"><i class="fas fa-cash-register mr-1"></i>DIRECT SALE</span>
                        @endif
                        @if($invoice->payment_status === 'paid')
                            <span class="inline-flex items-center rounded-full bg-green-100 px-3 py-1 text-sm font-bold text-green-800"><i class="fas fa-check-circle mr-1"></i>PAID</span>
                        @elseif($invoice->payment_status === 'credit')
                            <span class="inline-flex items-center rounded-full bg-amber-100 px-3 py-1 text-sm font-bold text-amber-800"><i class="fas fa-clock mr-1"></i>CREDIT</span>
                        @elseif($invoice->payment_status === 'partial')
                            <span class="inline-flex items-center rounded-full bg-yellow-100 px-3 py-1 text-sm font-bold text-yellow-800">PARTIAL</span>
                        @else
                            <span class="inline-flex items-center rounded-full bg-red-100 px-3 py-1 text-sm font-bold text-red-800"><i class="fas fa-exclamation-circle mr-1"></i>PENDING</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Patient & Doctor Info -->
        <div class="px-8 py-4 bg-slate-50 border-b border-slate-200">
            <div class="grid grid-cols-2 gap-8">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400 mb-1">Bill To</p>
                    <p class="text-sm font-semibold text-slate-900">{{ $invoice->patient->name ?? '—' }}</p>
                    <p class="text-sm text-slate-500">Patient Code: {{ $invoice->patient->patient_code ?? '—' }}</p>
                </div>
                <div>
                    @if($invoice->doctor && $invoice->doctor_id > 0)
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400 mb-1">Attending Doctor</p>
                    <p class="text-sm font-semibold text-slate-900">{{ $invoice->doctor->name }}</p>
                    @elseif(($invoice->bill_type ?? 'appointment') === 'direct')
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400 mb-1">Type</p>
                    <p class="text-sm font-semibold text-slate-900">Direct Sale / Walk-in</p>
                    @else
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400 mb-1">Attending Doctor</p>
                    <p class="text-sm font-semibold text-slate-900">—</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Payment Info (shown when paid or credit) -->
        @if($invoice->payment_status === 'paid' || $invoice->payment_status === 'credit')
        <div class="px-8 py-4 border-b border-slate-200 {{ $invoice->payment_status === 'paid' ? 'bg-green-50' : 'bg-amber-50' }}">
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400 mb-1">Payment Mode</p>
                    <p class="text-sm font-semibold text-slate-900">{{ $invoice->payment_mode ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400 mb-1">Reference</p>
                    <p class="text-sm font-semibold text-slate-900">{{ $invoice->payment_reference ?: '—' }}</p>
                </div>
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400 mb-1">{{ $invoice->payment_status === 'paid' ? 'Paid At' : 'Credit Due' }}</p>
                    <p class="text-sm font-semibold text-slate-900">
                        @if($invoice->payment_status === 'paid' && $invoice->paid_at)
                            {{ date('M d, Y h:i A', strtotime($invoice->paid_at)) }}
                        @elseif($invoice->credit_due_date)
                            {{ date('M d, Y', strtotime($invoice->credit_due_date)) }}
                        @else
                            —
                        @endif
                    </p>
                </div>
                @if($invoice->is_credit && $invoice->credit_settled_at)
                <div>
                    <p class="text-xs font-medium uppercase tracking-wide text-slate-400 mb-1">Credit Settled</p>
                    <p class="text-sm font-semibold text-green-700">{{ date('M d, Y h:i A', strtotime($invoice->credit_settled_at)) }}</p>
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Line Items grouped by Category -->
        <div class="px-8 py-6">
            <table class="min-w-full divide-y divide-slate-200">
                <thead>
                    <tr>
                        <th class="py-3 pl-0 pr-3 text-left text-xs font-medium uppercase tracking-wide text-slate-500 w-10">#</th>
                        <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-500">Service / Item</th>
                        <th class="px-3 py-3 text-left text-xs font-medium uppercase tracking-wide text-slate-500 w-28">Category</th>
                        <th class="px-3 py-3 text-center text-xs font-medium uppercase tracking-wide text-slate-500 w-16">Qty</th>
                        <th class="px-3 py-3 text-right text-xs font-medium uppercase tracking-wide text-slate-500 w-28">Unit Price</th>
                        <th class="px-3 py-3 text-right text-xs font-medium uppercase tracking-wide text-slate-500 w-28">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @php $rowNum = 0; @endphp
                    @foreach($items as $category => $categoryItems)
                        @foreach($categoryItems as $item)
                        @php $rowNum++; @endphp
                        <tr class="hover:bg-slate-50">
                            <td class="py-3 pl-0 pr-3 text-sm text-slate-500">{{ $rowNum }}</td>
                            <td class="px-3 py-3 text-sm font-medium text-slate-900">{{ $item->item_description }}</td>
                            <td class="px-3 py-3">
                                @php
                                $catColors = [
                                    'consultation' => 'bg-blue-50 text-blue-700 ring-blue-600/20',
                                    'pharmacy' => 'bg-emerald-50 text-emerald-700 ring-emerald-600/20',
                                    'pathology' => 'bg-purple-50 text-purple-700 ring-purple-600/20',
                                    'radiology' => 'bg-orange-50 text-orange-700 ring-orange-600/20',
                                    'procedure' => 'bg-pink-50 text-pink-700 ring-pink-600/20',
                                    'consumable' => 'bg-rose-50 text-rose-700 ring-rose-600/20',
                                    'other' => 'bg-slate-100 text-slate-700 ring-slate-600/20',
                                ];
                                $color = $catColors[$category] ?? $catColors['other'];
                                @endphp
                                <span class="inline-flex items-center rounded-full px-2 py-0.5 text-xs font-medium ring-1 ring-inset {{ $color }}">
                                    {{ $category_labels[$category] ?? ucfirst($category) }}
                                </span>
                            </td>
                            <td class="px-3 py-3 text-sm text-center text-slate-600">{{ $item->quantity }}</td>
                            <td class="px-3 py-3 text-sm text-right text-slate-600">{{ number_format($item->unit_price, 2) }}</td>
                            <td class="px-3 py-3 text-sm text-right font-semibold text-slate-900">{{ number_format($item->line_total, 2) }}</td>
                        </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Financial Summary -->
        <div class="px-8 py-6 border-t border-slate-200 bg-slate-50">
            <div class="flex justify-end">
                <div class="w-72 space-y-3">
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-600">Subtotal</span>
                        <span class="font-medium text-slate-900">{{ number_format($invoice->subtotal, 2) }}</span>
                    </div>
                    @if($invoice->discount_pct > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-600">Discount ({{ $invoice->discount_pct }}%)</span>
                        <span class="font-medium text-red-600">-{{ number_format($invoice->discount_amount, 2) }}</span>
                    </div>
                    @endif
                    @if($invoice->tax_pct > 0)
                    <div class="flex justify-between text-sm">
                        <span class="text-slate-600">Tax ({{ $invoice->tax_pct }}%)</span>
                        <span class="font-medium text-slate-600">+{{ number_format($invoice->tax_amount, 2) }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between border-t border-slate-300 pt-3">
                        <span class="text-base font-bold text-slate-900">Net Amount</span>
                        <span class="text-xl font-bold text-emerald-700">{{ number_format($invoice->net_amount, 2) }} K</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Notes -->
        @if($invoice->notes)
        <div class="px-8 py-4 border-t border-slate-200">
            <p class="text-xs font-medium uppercase tracking-wide text-slate-400 mb-1">Notes</p>
            <p class="text-sm text-slate-600">{{ $invoice->notes }}</p>
        </div>
        @endif

        <!-- Footer -->
        <div class="px-8 py-4 border-t border-slate-200 text-center">
            <p class="text-xs text-slate-400">Thank you for your visit. This is a computer-generated invoice.</p>
        </div>
    </div>

    <!-- ===================== PAYMENT MODAL ===================== -->
    <div x-show="showPayModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 print:hidden"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showPayModal = false"></div>

        <!-- Modal -->
        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-0 overflow-hidden"
             x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-emerald-600 to-emerald-500 px-6 py-5 text-white">
                <h3 class="text-lg font-bold"><i class="fas fa-cash-register mr-2"></i>Close Bill — Record Payment</h3>
                <p class="text-sm text-emerald-100 mt-1">Invoice #{{ $invoice->bill_number }} — {{ number_format($invoice->net_amount, 0) }} K</p>
            </div>

            <form action="{{ url($url_prefix . '/rcm/invoice/' . $invoice->id . '/pay') }}" method="POST" class="p-6 space-y-5">
                @csrf

                <!-- Payment Mode -->
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Payment Mode <span class="text-red-500">*</span></label>
                    <div class="grid grid-cols-2 gap-2">
                        @php
                        $paymentModes = [
                            ['value' => 'Cash', 'icon' => 'fas fa-money-bill-wave', 'color' => 'emerald'],
                            ['value' => 'KBZPay', 'icon' => 'fas fa-mobile-alt', 'color' => 'blue'],
                            ['value' => 'WavePay', 'icon' => 'fas fa-mobile-alt', 'color' => 'yellow'],
                            ['value' => 'CBPay', 'icon' => 'fas fa-mobile-alt', 'color' => 'indigo'],
                            ['value' => 'AYA Pay', 'icon' => 'fas fa-mobile-alt', 'color' => 'red'],
                            ['value' => 'Card', 'icon' => 'fas fa-credit-card', 'color' => 'purple'],
                            ['value' => 'Bank Transfer', 'icon' => 'fas fa-university', 'color' => 'slate'],
                        ];
                        @endphp
                        @foreach($paymentModes as $mode)
                        <label class="relative cursor-pointer">
                            <input type="radio" name="payment_mode" value="{{ $mode['value'] }}" class="peer sr-only" {{ $loop->first ? 'checked' : '' }} @click="isCredit = false">
                            <div class="flex items-center gap-2 rounded-lg border-2 border-slate-200 p-3 text-sm font-medium text-slate-700 peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:text-emerald-700 hover:bg-slate-50 transition-all">
                                <i class="{{ $mode['icon'] }} text-base"></i>
                                <span>{{ $mode['value'] }}</span>
                            </div>
                        </label>
                        @endforeach
                        <!-- Credit option -->
                        <label class="relative cursor-pointer">
                            <input type="radio" name="payment_mode" value="Credit" class="peer sr-only" @click="isCredit = true">
                            <div class="flex items-center gap-2 rounded-lg border-2 border-slate-200 p-3 text-sm font-medium text-slate-700 peer-checked:border-amber-500 peer-checked:bg-amber-50 peer-checked:text-amber-700 hover:bg-slate-50 transition-all">
                                <i class="fas fa-file-invoice text-base"></i>
                                <span>Credit</span>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Payment Reference -->
                <div x-show="!isCredit" x-transition>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Payment Reference <span class="text-slate-400 text-xs">(optional)</span></label>
                    <input type="text" name="payment_reference" placeholder="Transaction ID, receipt no., etc."
                           class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-emerald-500 focus:ring-emerald-500/20 focus:ring-4 transition-all">
                </div>

                <!-- Credit fields -->
                <div x-show="isCredit" x-transition class="space-y-3">
                    <input type="hidden" name="is_credit" x-bind:value="isCredit ? 1 : 0">
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-1">Credit Due Date <span class="text-red-500">*</span></label>
                        <input type="date" name="credit_due_date"
                               class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-amber-500 focus:ring-amber-500/20 focus:ring-4 transition-all">
                    </div>
                    <div class="rounded-lg bg-amber-50 border border-amber-200 p-3">
                        <p class="text-xs text-amber-700"><i class="fas fa-info-circle mr-1"></i> Credit invoices will be tracked separately. Use "Settle Credit" to record payment later.</p>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showPayModal = false"
                            class="flex-1 rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-all">
                        Cancel
                    </button>
                    <button type="submit"
                            class="flex-1 rounded-lg px-4 py-2.5 text-sm font-bold text-white shadow-sm transition-all"
                            :class="isCredit ? 'bg-amber-600 hover:bg-amber-500' : 'bg-emerald-600 hover:bg-emerald-500'">
                        <i class="fas fa-check mr-1"></i>
                        <span x-text="isCredit ? 'Record as Credit' : 'Confirm Payment'"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===================== SETTLE CREDIT MODAL ===================== -->
    <div x-show="showSettleModal" x-cloak
         class="fixed inset-0 z-50 flex items-center justify-center p-4 print:hidden"
         x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
        <div class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm" @click="showSettleModal = false"></div>

        <div class="relative bg-white rounded-2xl shadow-2xl w-full max-w-md p-0 overflow-hidden">
            <div class="bg-gradient-to-r from-amber-600 to-amber-500 px-6 py-5 text-white">
                <h3 class="text-lg font-bold"><i class="fas fa-hand-holding-usd mr-2"></i>Settle Credit</h3>
                <p class="text-sm text-amber-100 mt-1">Invoice #{{ $invoice->bill_number }} — {{ number_format($invoice->net_amount, 0) }} K</p>
            </div>

            <form action="{{ url($url_prefix . '/rcm/invoice/' . $invoice->id . '/settle-credit') }}" method="POST" class="p-6 space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-2">Payment Mode <span class="text-red-500">*</span></label>
                    <select name="payment_mode" required
                            class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-amber-500 focus:ring-amber-500/20 focus:ring-4 transition-all">
                        <option value="Cash">💵 Cash</option>
                        <option value="KBZPay">📱 KBZPay</option>
                        <option value="WavePay">📱 WavePay</option>
                        <option value="CBPay">📱 CBPay</option>
                        <option value="AYA Pay">📱 AYA Pay</option>
                        <option value="Card">💳 Card (Visa/MC)</option>
                        <option value="Bank Transfer">🏦 Bank Transfer</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-slate-700 mb-1">Payment Reference <span class="text-slate-400 text-xs">(optional)</span></label>
                    <input type="text" name="payment_reference" placeholder="Transaction ID"
                           class="w-full rounded-lg border-slate-300 shadow-sm text-sm focus:border-amber-500 focus:ring-amber-500/20 focus:ring-4 transition-all">
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" @click="showSettleModal = false"
                            class="flex-1 rounded-lg border border-slate-300 px-4 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-all">
                        Cancel
                    </button>
                    <button type="submit"
                            class="flex-1 rounded-lg bg-emerald-600 px-4 py-2.5 text-sm font-bold text-white shadow-sm hover:bg-emerald-500 transition-all">
                        <i class="fas fa-check mr-1"></i> Settle & Mark Paid
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
@@media print {
    .print\:hidden { display: none !important; }
    .print\:shadow-none { box-shadow: none !important; }
    .print\:ring-0 { --tw-ring-shadow: none !important; }
}
[x-cloak] { display: none !important; }
</style>
@endsection
