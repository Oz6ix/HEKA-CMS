<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=80mm, initial-scale=1.0">
    <title>Receipt — {{ $invoice->bill_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Courier New', 'Lucida Console', monospace;
            font-size: 11px;
            line-height: 1.4;
            width: 80mm;
            margin: 0 auto;
            padding: 3mm;
            color: #000;
            background: #fff;
        }
        .center { text-align: center; }
        .right { text-align: right; }
        .bold { font-weight: bold; }
        .divider {
            border-top: 1px dashed #333;
            margin: 4px 0;
        }
        .double-divider {
            border-top: 2px double #333;
            margin: 6px 0;
        }
        .clinic-name {
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .clinic-info {
            font-size: 9px;
            color: #333;
        }
        .receipt-title {
            font-size: 14px;
            font-weight: bold;
            letter-spacing: 2px;
            margin: 4px 0;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
        }
        .info-label {
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        table th {
            font-size: 10px;
            text-transform: uppercase;
            border-bottom: 1px solid #333;
            padding: 2px 0;
        }
        table td {
            font-size: 10px;
            padding: 2px 0;
            vertical-align: top;
        }
        .item-name {
            max-width: 35mm;
            word-wrap: break-word;
        }
        .total-section .row {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            padding: 1px 0;
        }
        .grand-total {
            font-size: 14px;
            font-weight: bold;
        }
        .payment-info {
            font-size: 10px;
            margin: 4px 0;
        }
        .footer {
            font-size: 9px;
            color: #666;
            margin-top: 8px;
        }
        .status-badge {
            display: inline-block;
            font-size: 11px;
            font-weight: bold;
            padding: 1px 6px;
            border: 1px solid #333;
            margin: 2px 0;
        }
        .status-paid { border-color: #059669; color: #059669; }
        .status-credit { border-color: #d97706; color: #d97706; }
        .status-pending { border-color: #dc2626; color: #dc2626; }

        @@media print {
            body { width: 80mm; margin: 0; padding: 2mm; }
            @@page {
                size: 80mm auto;
                margin: 0;
            }
            .no-print { display: none !important; }
        }
        @@media screen {
            body {
                border: 1px solid #ddd;
                margin: 20px auto;
                box-shadow: 0 2px 8px rgba(0,0,0,0.15);
                min-height: 200mm;
            }
            .print-btn-container {
                width: 80mm;
                margin: 10px auto;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <!-- Print Button (screen only) -->
    <div class="print-btn-container no-print" style="margin-bottom: 10px;">
        <button onclick="window.print()" style="padding: 8px 24px; background: #059669; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: bold;">
            🖨️ Print Receipt
        </button>
        <button onclick="window.close()" style="padding: 8px 16px; background: #64748b; color: white; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; margin-left: 4px;">
            Close
        </button>
    </div>

    <!-- ===== RECEIPT START ===== -->

    <!-- Clinic Header -->
    <div class="center">
        <div class="clinic-name">{{ $general_settings->site_name ?? 'HEKA CLINIC' }}</div>
        @if($general_settings->address ?? false)
        <div class="clinic-info">{{ $general_settings->address }}</div>
        @endif
        @if($general_settings->phone ?? false)
        <div class="clinic-info">Tel: {{ $general_settings->phone }}</div>
        @endif
    </div>

    <div class="divider"></div>

    <!-- Invoice Info -->
    <div class="center">
        <div class="receipt-title">RECEIPT</div>
    </div>

    <div class="info-row">
        <span class="info-label">Invoice:</span>
        <span class="bold">{{ $invoice->bill_number }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Date:</span>
        <span>{{ date('d/m/Y H:i', strtotime($invoice->bill_date)) }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Patient:</span>
        <span>{{ $invoice->patient->name ?? '—' }}</span>
    </div>
    <div class="info-row">
        <span class="info-label">Code:</span>
        <span>{{ $invoice->patient->patient_code ?? '—' }}</span>
    </div>
    @if($invoice->doctor && $invoice->doctor_id > 0)
    <div class="info-row">
        <span class="info-label">Doctor:</span>
        <span>{{ $invoice->doctor->name }}</span>
    </div>
    @endif

    <div class="divider"></div>

    <!-- Line Items -->
    <table>
        <thead>
            <tr>
                <th style="text-align: left;">Item</th>
                <th style="text-align: center; width: 12mm;">Qty</th>
                <th style="text-align: right; width: 18mm;">Amt</th>
            </tr>
        </thead>
        <tbody>
            @php $rowNum = 0; @endphp
            @foreach($items as $category => $categoryItems)
                @foreach($categoryItems as $item)
                @php $rowNum++; @endphp
                <tr>
                    <td class="item-name">{{ $item->item_description }}</td>
                    <td style="text-align: center;">{{ $item->quantity }}</td>
                    <td style="text-align: right;">{{ number_format($item->line_total, 0) }}</td>
                </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    <div class="divider"></div>

    <!-- Totals -->
    <div class="total-section">
        <div class="row">
            <span>Subtotal</span>
            <span>{{ number_format($invoice->subtotal, 0) }}</span>
        </div>
        @if($invoice->discount_pct > 0)
        <div class="row">
            <span>Discount ({{ $invoice->discount_pct }}%)</span>
            <span>-{{ number_format($invoice->discount_amount, 0) }}</span>
        </div>
        @endif
        @if($invoice->tax_pct > 0)
        <div class="row">
            <span>Tax ({{ $invoice->tax_pct }}%)</span>
            <span>+{{ number_format($invoice->tax_amount, 0) }}</span>
        </div>
        @endif
    </div>

    <div class="double-divider"></div>

    <div class="total-section">
        <div class="row grand-total">
            <span>TOTAL</span>
            <span>{{ number_format($invoice->net_amount, 0) }} K</span>
        </div>
    </div>

    <div class="double-divider"></div>

    <!-- Payment Info -->
    <div class="payment-info">
        <div class="center">
            @if($invoice->payment_status === 'paid')
                <span class="status-badge status-paid">✓ PAID</span>
            @elseif($invoice->payment_status === 'credit')
                <span class="status-badge status-credit">⏳ CREDIT</span>
            @else
                <span class="status-badge status-pending">⊘ UNPAID</span>
            @endif
        </div>
        @if($invoice->payment_mode)
        <div class="info-row" style="margin-top: 3px;">
            <span class="info-label">Payment:</span>
            <span>{{ $invoice->payment_mode }}</span>
        </div>
        @endif
        @if($invoice->payment_reference)
        <div class="info-row">
            <span class="info-label">Ref:</span>
            <span>{{ $invoice->payment_reference }}</span>
        </div>
        @endif
        @if($invoice->paid_at)
        <div class="info-row">
            <span class="info-label">Paid at:</span>
            <span>{{ date('d/m/Y H:i', strtotime($invoice->paid_at)) }}</span>
        </div>
        @endif
        @if($invoice->is_credit && $invoice->credit_due_date)
        <div class="info-row">
            <span class="info-label">Due:</span>
            <span>{{ date('d/m/Y', strtotime($invoice->credit_due_date)) }}</span>
        </div>
        @endif
    </div>

    <div class="divider"></div>

    <!-- Footer -->
    <div class="footer center">
        <p>Thank you for your visit!</p>
        <p>{{ $general_settings->site_name ?? 'HEKA Clinic' }}</p>
        <p style="margin-top: 4px; font-size: 8px;">Printed: {{ date('d/m/Y H:i') }}</p>
    </div>

    <script>
        // Auto-print on load if accessed directly
        // window.onload = function() { window.print(); }
    </script>
</body>
</html>
