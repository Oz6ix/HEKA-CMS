@extends('backend.layouts.modern')

@section('title', 'Invoice #' . $sale->invoice_no)

@section('content')
<div class="max-w-3xl mx-auto">
    @include('backend.layouts.includes.notification_alerts')

    <div class="flex items-center justify-between mb-6 print:hidden">
        <a href="{{ url($url_prefix . '/pharmacy_sales') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
            <i class="fas fa-arrow-left mr-2"></i> Back to Sales
        </a>
        <button onclick="window.print()" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700">
            <i class="fas fa-print mr-2"></i> Print Invoice
        </button>
    </div>

    <!-- Invoice Card -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden" id="invoice">
        <!-- Header -->
        <div class="px-8 py-6 bg-gradient-to-r from-primary-600 to-primary-800 text-white">
            <div class="flex justify-between items-start">
                <div>
                    <h1 class="text-2xl font-bold">PHARMACY INVOICE</h1>
                    <p class="text-primary-200 mt-1 text-sm">{{ $sale->invoice_no }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-primary-200">Date</p>
                    <p class="font-semibold">{{ $sale->created_at->format('d M Y, h:i A') }}</p>
                </div>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="px-8 py-5 border-b border-gray-100 bg-gray-50">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Bill To</p>
                    <p class="font-semibold text-gray-800">{{ $sale->customer_name }}</p>
                    @if($sale->customer_phone)
                        <p class="text-sm text-gray-500">{{ $sale->customer_phone }}</p>
                    @endif
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500 uppercase tracking-wide">Payment</p>
                    <p class="font-semibold text-gray-800">{{ ucfirst($sale->payment_method) }}</p>
                </div>
            </div>
        </div>

        <!-- Items Table -->
        <div class="px-8 py-5">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b-2 border-gray-200">
                        <th class="pb-3 text-left text-gray-600">#</th>
                        <th class="pb-3 text-left text-gray-600">Medicine</th>
                        <th class="pb-3 text-center text-gray-600">Qty</th>
                        <th class="pb-3 text-right text-gray-600">Unit Price</th>
                        <th class="pb-3 text-right text-gray-600">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($items as $index => $item)
                    <tr class="border-b border-gray-100">
                        <td class="py-3 text-gray-400">{{ $index + 1 }}</td>
                        <td class="py-3 font-medium text-gray-800">{{ $item->drug_name }}</td>
                        <td class="py-3 text-center">{{ $item->quantity_dispensed }}</td>
                        <td class="py-3 text-right text-gray-600">{{ number_format($item->unit_price) }} Ks</td>
                        <td class="py-3 text-right font-medium">{{ number_format($item->total_price) }} Ks</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="px-8 py-5 bg-gray-50 border-t border-gray-100">
            <div class="flex flex-col items-end space-y-2">
                <div class="flex justify-between w-48 text-sm">
                    <span class="text-gray-600">Subtotal</span>
                    <span>{{ number_format($sale->subtotal) }} Ks</span>
                </div>
                @if($sale->discount > 0)
                <div class="flex justify-between w-48 text-sm text-red-600">
                    <span>Discount</span>
                    <span>-{{ number_format($sale->discount) }} Ks</span>
                </div>
                @endif
                <div class="flex justify-between w-48 text-lg font-bold border-t pt-2">
                    <span>Total</span>
                    <span class="text-primary-600">{{ number_format($sale->total) }} Ks</span>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="px-8 py-4 text-center text-xs text-gray-400 border-t border-gray-100">
            Thank you for your purchase! • HEKA Clinic Management
        </div>
    </div>
</div>

<style>
    @media print {
        body * { visibility: hidden; }
        #invoice, #invoice * { visibility: visible; }
        #invoice { position: absolute; left: 0; top: 0; width: 100%; border-radius: 0; box-shadow: none; }
    }
</style>
@endsection
