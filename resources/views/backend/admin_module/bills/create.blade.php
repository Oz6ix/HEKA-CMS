@extends('backend.layouts.modern')
@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Generate Pharmacy Bill</h1>
            <p class="mt-1 text-sm text-slate-500">Select a diagnosed case to generate a pharmacy bill.</p>
        </div>
        <div>
            <a href="{{ url($url_prefix . '/bills') }}" class="inline-flex items-center rounded-md bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 transition-all">
                <i class="fas fa-arrow-left mr-2"></i> Back to Bills
            </a>
        </div>
    </div>

    <!-- Flash Messages / Validation Errors -->
    @if($errors->any())
    <div class="rounded-md bg-red-50 p-4 border border-red-200">
        <div class="flex"><div class="shrink-0"><i class="fas fa-exclamation-circle text-red-400"></i></div>
        <div class="ml-3"><h3 class="text-sm font-medium text-red-800">Validation Error</h3>
        <ul class="mt-2 list-disc list-inside text-sm text-red-700">
            @foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach
        </ul></div></div>
    </div>
    @endif

    <form method="POST" action="{{ route('pharmacy_bill_add') }}" id="bill_form" class="space-y-6">
        @csrf

        <!-- Case Selection Card -->
        <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h2 class="text-base font-semibold text-slate-900"><i class="fas fa-search mr-2 text-primary-500"></i>Select Case</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Case Number <span class="text-red-500">*</span></label>
                        <select name="appointment_id" id="appointment_id" class="block w-full rounded-md border-0 py-2.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                            <option value="">— Select a Case —</option>
                            @foreach($items as $data)
                                <option value="{{ $data['id'] }}">{{ $data['case_number'] }} — {{ $data['patient']['name'] }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Patient</label>
                        <input type="text" class="block w-full rounded-md border-0 py-2.5 bg-slate-50 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 sm:text-sm sm:leading-6" readonly id="patient_name" name="patient_name" value="">
                        <input type="hidden" name="patient_id" id="patient_id">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Bill Number</label>
                        <input type="text" class="block w-full rounded-md border-0 py-2.5 bg-slate-50 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 sm:text-sm sm:leading-6" readonly id="bill_number" name="bill_number" value="">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Appointment Date</label>
                        <input type="text" class="block w-full rounded-md border-0 py-2.5 bg-slate-50 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 sm:text-sm sm:leading-6" readonly id="appointment_date" name="appointment_date" value="">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Doctor</label>
                        <input type="text" class="block w-full rounded-md border-0 py-2.5 bg-slate-50 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 sm:text-sm sm:leading-6" readonly id="doctor" name="doctor" value="">
                        <input type="hidden" name="doctor_id" id="doctor_id">
                    </div>
                </div>
            </div>
        </div>

        <!-- Prescription Items Card -->
        <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h2 class="text-base font-semibold text-slate-900"><i class="fas fa-pills mr-2 text-emerald-500"></i>Pharmacy Prescriptions</h2>
            </div>
            <div class="p-6">
                <div id="prescription_container">
                    <div class="text-center py-8 text-slate-400" id="prescription_placeholder">
                        <i class="fas fa-clipboard-list fa-2x mb-3"></i>
                        <p class="text-sm">Select a case above to load prescriptions</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Out-of-Pharmacy Items Card -->
        <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden" id="out_of_pharmacy_card" style="display:none;">
            <div class="px-6 py-4 border-b border-slate-200 bg-amber-50">
                <h2 class="text-base font-semibold text-slate-900"><i class="fas fa-exclamation-triangle mr-2 text-amber-500"></i>Medicines Out of Pharmacy</h2>
            </div>
            <div class="p-6">
                <div id="prescription_out_container"></div>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                <h2 class="text-base font-semibold text-slate-900"><i class="fas fa-calculator mr-2 text-indigo-500"></i>Bill Summary</h2>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Notes -->
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Notes</label>
                        <textarea name="notes" id="notes" rows="4" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6" placeholder="Optional billing notes..."></textarea>
                    </div>
                    <!-- Amounts -->
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <span class="text-sm text-slate-600">Subtotal</span>
                            <input type="text" readonly id="total" name="total" value="0" class="w-32 text-right rounded-md border-0 py-1.5 bg-slate-50 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 sm:text-sm font-semibold">
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-sm text-slate-600">Discount (%)</span>
                            <div class="flex items-center gap-2">
                                <input type="text" id="discount" name="discount" value="0" class="w-20 text-right rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" pattern="[0-9]+([.,][0-9]+)?">
                                <span class="text-slate-400">=</span>
                                <input type="text" readonly id="discount_price" name="discount_price" value="0" class="w-28 text-right rounded-md border-0 py-1.5 bg-slate-50 text-red-600 shadow-sm ring-1 ring-inset ring-slate-300 sm:text-sm font-medium">
                            </div>
                        </div>
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-sm text-slate-600">Tax (%)</span>
                            <div class="flex items-center gap-2">
                                <input type="text" id="tax" name="tax" value="0" class="w-20 text-right rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" pattern="[0-9]+([.,][0-9]+)?">
                                <span class="text-slate-400">=</span>
                                <input type="text" readonly id="tax_price" name="tax_price" value="0" class="w-28 text-right rounded-md border-0 py-1.5 bg-slate-50 text-slate-600 shadow-sm ring-1 ring-inset ring-slate-300 sm:text-sm font-medium">
                            </div>
                        </div>
                        <div class="border-t border-slate-200 pt-4 flex items-center justify-between">
                            <span class="text-base font-semibold text-slate-900">Net Amount</span>
                            <input type="text" readonly id="net_amount" name="net_amount" value="0" class="w-32 text-right rounded-md border-0 py-2 bg-emerald-50 text-emerald-700 shadow-sm ring-1 ring-inset ring-emerald-300 text-base font-bold">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Bar -->
        <div class="flex items-center justify-end gap-x-4">
            <a href="{{ url($url_prefix . '/bills') }}" class="text-sm font-semibold leading-6 text-slate-900">Cancel</a>
            <button type="submit" id="add_button" disabled class="rounded-md bg-primary-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                <i class="fas fa-check mr-2"></i> Save Bill
            </button>
        </div>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var caseSelect = document.getElementById('appointment_id');
    
    caseSelect.addEventListener('change', function() {
        var appointmentId = this.value;
        if (!appointmentId) return;
        
        fetch("{{ route('ajax_casenumber') }}/" + appointmentId, {
            headers: { 'Accept': 'application/json' }
        })
        .then(function(r) { return r.json(); })
        .then(function(result) {
            if (result.status === 'success') {
                document.getElementById('patient_name').value = result.patient_name;
                document.getElementById('patient_id').value = result.patient_id;
                document.getElementById('bill_number').value = result.billnumber;
                document.getElementById('appointment_date').value = result.appointment_date;
                document.getElementById('doctor').value = result.doctor_name;
                document.getElementById('doctor_id').value = result.doctor_id;
                
                renderPrescriptions(result.item_prescription);
                renderOutOfPharmacy(result.item_prescription_out);
                recalcTotals();
            }
        })
        .catch(function(e) {
            console.error('Error loading case:', e);
            alert('Failed to load case data');
        });
    });
    
    function renderPrescriptions(items) {
        var container = document.getElementById('prescription_container');
        if (!items || items.length === 0) {
            container.innerHTML = '<p class="text-center py-6 text-slate-400 text-sm">No prescriptions found for this case.</p>';
            document.getElementById('add_button').disabled = true;
            return;
        }
        
        document.getElementById('add_button').disabled = false;
        var html = '<div class="overflow-x-auto"><table class="min-w-full divide-y divide-slate-200"><thead class="bg-slate-50"><tr>';
        html += '<th class="py-3 pl-4 pr-3 text-left text-xs font-medium uppercase text-slate-500 sm:pl-6">Drug Name</th>';
        html += '<th class="px-3 py-3 text-left text-xs font-medium uppercase text-slate-500">Quantity</th>';
        html += '<th class="px-3 py-3 text-right text-xs font-medium uppercase text-slate-500">Price (K)</th>';
        html += '</tr></thead><tbody class="divide-y divide-slate-200 bg-white">';
        
        var total = 0;
        items.forEach(function(item, i) {
            var drugName = '', qty = 0, price = 0;
            var diagnosisId = '', prescriptionId = '';
            
            if (item.bill_medicine === undefined || item.bill_medicine === null) {
                drugName = item.drug_name || item.pharmacy_name || '—';
                qty = item.quantity || 0;
                price = (item.pharmacy_data && item.pharmacy_data.price) ? item.pharmacy_data.price : 0;
                diagnosisId = item.diagnosis_id || '';
                prescriptionId = item.id || '';
            } else {
                drugName = item.bill_medicine.drug_name || '—';
                qty = item.bill_medicine.quantity || 0;
                price = item.medicine_price || 0;
                diagnosisId = (item.bill_test && item.bill_test.diagnosis_id) ? item.bill_test.diagnosis_id : '';
                prescriptionId = item.bill_medicine.id || '';
            }
            
            total += parseFloat(price) * parseInt(qty);
            
            html += '<tr class="hover:bg-slate-50">';
            html += '<td class="py-3 pl-4 pr-3 text-sm text-slate-900 font-medium sm:pl-6">' + drugName;
            html += '<input type="hidden" name="prescription[' + i + '][drug_name]" value="' + drugName + '">';
            html += '<input type="hidden" name="prescription[' + i + '][diagnosis_id]" value="' + diagnosisId + '">';
            html += '<input type="hidden" name="prescription[' + i + '][prescription_id]" value="' + prescriptionId + '">';
            html += '</td>';
            html += '<td class="px-3 py-3 text-sm text-slate-600">';
            html += '<input type="text" name="prescription[' + i + '][quantity]" value="' + qty + '" class="prescription-qty w-20 rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm text-center" data-index="' + i + '">';
            html += '</td>';
            html += '<td class="px-3 py-3 text-sm text-right font-medium text-slate-900">';
            html += '<input type="text" name="prescription[' + i + '][medicine_price]" value="' + price + '" class="prescription-price w-28 rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm text-right" data-index="' + i + '">';
            html += '</td></tr>';
        });
        
        html += '</tbody></table></div>';
        container.innerHTML = html;
        
        document.getElementById('total').value = total;
        document.getElementById('net_amount').value = total;
        
        // Bind quantity/price change events
        container.querySelectorAll('.prescription-qty, .prescription-price').forEach(function(input) {
            input.addEventListener('keyup', recalcTotals);
        });
    }
    
    function renderOutOfPharmacy(items) {
        var container = document.getElementById('prescription_out_container');
        var card = document.getElementById('out_of_pharmacy_card');
        
        if (!items || items.length === 0) {
            card.style.display = 'none';
            container.innerHTML = '';
            return;
        }
        
        card.style.display = 'block';
        var html = '<div class="overflow-x-auto"><table class="min-w-full divide-y divide-slate-200"><thead class="bg-slate-50"><tr>';
        html += '<th class="py-3 pl-4 pr-3 text-left text-xs font-medium uppercase text-slate-500 sm:pl-6">Drug Name</th>';
        html += '<th class="px-3 py-3 text-left text-xs font-medium uppercase text-slate-500">Quantity</th>';
        html += '<th class="px-3 py-3 text-left text-xs font-medium uppercase text-slate-500">Unit</th>';
        html += '<th class="px-3 py-3 text-left text-xs font-medium uppercase text-slate-500">Frequency</th>';
        html += '<th class="px-3 py-3 text-left text-xs font-medium uppercase text-slate-500">Days</th>';
        html += '</tr></thead><tbody class="divide-y divide-slate-200 bg-white">';
        
        items.forEach(function(item) {
            html += '<tr class="hover:bg-slate-50">';
            html += '<td class="py-3 pl-4 pr-3 text-sm text-slate-900 sm:pl-6">' + (item.drug_name || '—') + '</td>';
            html += '<td class="px-3 py-3 text-sm text-slate-600">' + (item.quantity || 0) + '</td>';
            html += '<td class="px-3 py-3 text-sm text-slate-600">' + (item.unit ? item.unit.unit : '—') + '</td>';
            html += '<td class="px-3 py-3 text-sm text-slate-600">' + (item.frequency ? item.frequency.frequency : '—') + '</td>';
            html += '<td class="px-3 py-3 text-sm text-slate-600">' + (item.no_of_days || '—') + '</td>';
            html += '</tr>';
        });
        
        html += '</tbody></table></div>';
        container.innerHTML = html;
    }
    
    function recalcTotals() {
        var total = 0;
        var qtyInputs = document.querySelectorAll('.prescription-qty');
        var priceInputs = document.querySelectorAll('.prescription-price');
        
        for (var i = 0; i < qtyInputs.length; i++) {
            var qty = parseFloat(qtyInputs[i].value) || 0;
            var price = parseFloat(priceInputs[i].value) || 0;
            total += qty * price;
        }
        
        document.getElementById('total').value = total;
        
        var discountPct = parseFloat(document.getElementById('discount').value) || 0;
        var discountAmt = total * discountPct / 100;
        document.getElementById('discount_price').value = discountAmt.toFixed(2);
        
        var taxPct = parseFloat(document.getElementById('tax').value) || 0;
        var taxAmt = total * taxPct / 100;
        document.getElementById('tax_price').value = taxAmt.toFixed(2);
        
        var net = total + taxAmt - discountAmt;
        document.getElementById('net_amount').value = net.toFixed(2);
    }
    
    document.getElementById('discount').addEventListener('keyup', recalcTotals);
    document.getElementById('tax').addEventListener('keyup', recalcTotals);
});
</script>
@endsection