@extends('backend.layouts.modern')
@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="sm:flex sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-slate-900">Generate Invoice</h1>
            <p class="mt-1 text-sm text-slate-500">Create an invoice from an appointment or as a direct sale.</p>
        </div>
        <a href="{{ url($url_prefix . '/rcm') }}" class="inline-flex items-center rounded-md bg-slate-100 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-200 transition-all">
            <i class="fas fa-arrow-left mr-2"></i> Back to Invoices
        </a>
    </div>

    @if(session('error_message'))
    <div class="rounded-md bg-red-50 p-4 border border-red-200">
        <div class="flex"><div class="shrink-0"><i class="fas fa-exclamation-circle text-red-400"></i></div>
        <div class="ml-3"><p class="text-sm font-medium text-red-800">{{ session('error_message') }}</p></div></div>
    </div>
    @endif

    <!-- Billing Mode Toggle -->
    <div x-data="{ mode: 'appointment' }" class="space-y-6">
        <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
            <div class="p-1.5 flex gap-1 bg-slate-100 rounded-xl">
                <button type="button"
                    @click="mode = 'appointment'"
                    :class="mode === 'appointment' ? 'bg-white text-primary-700 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                    class="flex-1 flex items-center justify-center gap-2 py-3 px-4 text-sm font-semibold rounded-lg transition-all duration-200">
                    <i class="fas fa-calendar-check"></i>
                    From Appointment
                </button>
                <button type="button"
                    @click="mode = 'direct'"
                    :class="mode === 'direct' ? 'bg-white text-emerald-700 shadow-sm' : 'text-slate-600 hover:text-slate-900'"
                    class="flex-1 flex items-center justify-center gap-2 py-3 px-4 text-sm font-semibold rounded-lg transition-all duration-200">
                    <i class="fas fa-cash-register"></i>
                    Direct Billing
                </button>
            </div>
        </div>

        <!-- ========================================= -->
        <!-- MODE 1: FROM APPOINTMENT (existing flow)  -->
        <!-- ========================================= -->
        <form method="POST" action="{{ route('rcm.store') }}" id="rcm_form_appointment" x-show="mode === 'appointment'" x-cloak>
            @csrf
            <input type="hidden" name="bill_type" value="appointment">
            <div class="space-y-6">
                <!-- Case Selection -->
                <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                        <h2 class="text-base font-semibold text-slate-900"><i class="fas fa-search mr-2 text-primary-500"></i>Select Case</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                            <div class="lg:col-span-2">
                                <label class="block text-sm font-medium text-slate-700 mb-1">Case Number <span class="text-red-500">*</span></label>
                                <div class="relative" x-data="searchDropdown('appointment')" @click.away="open = false">
                                    <div class="relative">
                                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                                        <input type="text" x-model="search" @focus="open = true" @input="open = true"
                                            placeholder="Search case or patient name..."
                                            class="block w-full rounded-md border-0 py-2.5 pl-9 pr-8 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm">
                                        <button type="button" x-show="selectedValue" @click="clear()" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    </div>
                                    <input type="hidden" name="appointment_id" id="appointment_id" x-model="selectedValue">
                                    <div x-show="open && filteredItems().length > 0" x-transition
                                        class="absolute z-50 mt-1 w-full max-h-60 overflow-auto rounded-lg bg-white shadow-lg ring-1 ring-slate-900/10">
                                        <template x-for="item in filteredItems()" :key="item.value">
                                            <button type="button" @click="select(item)"
                                                class="w-full text-left px-4 py-2.5 text-sm hover:bg-primary-50 transition-colors border-b border-slate-50 last:border-0"
                                                :class="selectedValue == item.value ? 'bg-primary-50 text-primary-700 font-medium' : 'text-slate-700'">
                                                <span x-html="highlight(item.label)"></span>
                                            </button>
                                        </template>
                                    </div>
                                    <div x-show="open && search.length > 0 && filteredItems().length === 0" x-transition
                                        class="absolute z-50 mt-1 w-full rounded-lg bg-white shadow-lg ring-1 ring-slate-900/10 p-4 text-center">
                                        <p class="text-sm text-slate-400"><i class="fas fa-search mr-1"></i>No cases found</p>
                                    </div>
                                    <div x-show="open && search.length === 0 && items.length === 0" x-transition
                                        class="absolute z-50 mt-1 w-full rounded-lg bg-white shadow-lg ring-1 ring-slate-900/10 p-4 text-center">
                                        <p class="text-sm text-slate-400"><i class="fas fa-info-circle mr-1"></i>No diagnosed cases available for billing</p>
                                    </div>
                                </div>
                                <script>
                                    var appointmentOptions = [
                                        @foreach($items as $data)
                                        { value: '{{ $data->id }}', label: '{{ $data->case_number }} — {{ addslashes($data->patient->name ?? '') }}', patientName: '{{ addslashes($data->patient->name ?? '') }}', patientId: '{{ $data->patient->id ?? '' }}', doctorName: '{{ addslashes($data->staff_doctor->name ?? '') }}', doctorId: '{{ $data->staff_doctor->id ?? '' }}', apptDate: '{{ $data->appointment_date }}' },
                                        @endforeach
                                    ];
                                </script>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Patient</label>
                                <input type="text" id="patient_name" readonly class="block w-full rounded-md border-0 py-2.5 bg-slate-50 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 sm:text-sm">
                                <input type="hidden" name="patient_id" id="patient_id">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Doctor</label>
                                <input type="text" id="doctor_name" readonly class="block w-full rounded-md border-0 py-2.5 bg-slate-50 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 sm:text-sm">
                                <input type="hidden" name="doctor_id" id="doctor_id">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Appointment Date</label>
                                <input type="text" id="appt_date" readonly class="block w-full rounded-md border-0 py-2.5 bg-slate-50 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 sm:text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Loading indicator -->
                <div id="loading_indicator" class="hidden text-center py-8">
                    <i class="fas fa-spinner fa-spin text-3xl text-primary-500"></i>
                    <p class="mt-2 text-sm text-slate-500">Loading billable items...</p>
                </div>

                <!-- Billable Items (populated by AJAX) -->
                <div id="billable_items_container" class="space-y-6 hidden">
                    <!-- Consultation -->
                    <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden" id="section_consultation">
                        <div class="px-6 py-4 border-b border-slate-200 bg-blue-50">
                            <h2 class="text-base font-semibold text-slate-900"><i class="fas fa-stethoscope mr-2 text-blue-500"></i>Consultation & Other Charges</h2>
                        </div>
                        <div class="p-6" id="consultation_items"><p class="text-sm text-slate-400 text-center py-4">No consultation charges found.</p></div>
                    </div>
                    <!-- Pharmacy -->
                    <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden" id="section_pharmacy">
                        <div class="px-6 py-4 border-b border-slate-200 bg-emerald-50">
                            <h2 class="text-base font-semibold text-slate-900"><i class="fas fa-pills mr-2 text-emerald-500"></i>Pharmacy</h2>
                        </div>
                        <div class="p-6" id="pharmacy_items"><p class="text-sm text-slate-400 text-center py-4">No pharmacy items found.</p></div>
                    </div>
                    <!-- Lab -->
                    <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden" id="section_pathology">
                        <div class="px-6 py-4 border-b border-slate-200 bg-purple-50">
                            <h2 class="text-base font-semibold text-slate-900"><i class="fas fa-flask mr-2 text-purple-500"></i>Laboratory (Pathology)</h2>
                        </div>
                        <div class="p-6" id="pathology_items"><p class="text-sm text-slate-400 text-center py-4">No lab tests found.</p></div>
                    </div>
                    <!-- Radiology -->
                    <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden" id="section_radiology">
                        <div class="px-6 py-4 border-b border-slate-200 bg-orange-50">
                            <h2 class="text-base font-semibold text-slate-900"><i class="fas fa-x-ray mr-2 text-orange-500"></i>Radiology / Imaging</h2>
                        </div>
                        <div class="p-6" id="radiology_items"><p class="text-sm text-slate-400 text-center py-4">No radiology tests found.</p></div>
                    </div>
                    <!-- Consumables -->
                    <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden" id="section_consumable" style="display:none;">
                        <div class="px-6 py-4 border-b border-slate-200 bg-rose-50">
                            <h2 class="text-base font-semibold text-slate-900"><i class="fas fa-syringe mr-2 text-rose-500"></i>Consumables</h2>
                        </div>
                        <div class="p-6" id="consumable_items"><p class="text-sm text-slate-400 text-center py-4">No consumables found.</p></div>
                    </div>

                    <!-- Manual Items -->
                    <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                            <h2 class="text-base font-semibold text-slate-900"><i class="fas fa-plus-circle mr-2 text-slate-500"></i>Additional Service Items</h2>
                            <button type="button" id="add_manual_item" class="text-sm text-primary-600 hover:text-primary-800 font-medium"><i class="fas fa-plus mr-1"></i>Add Item</button>
                        </div>
                        <div class="p-6" id="manual_items_container">
                            <p class="text-sm text-slate-400 text-center py-2" id="manual_placeholder">Click "Add Item" to add custom service charges.</p>
                        </div>
                    </div>

                    <!-- Summary -->
                    <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                        <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                            <h2 class="text-base font-semibold text-slate-900"><i class="fas fa-calculator mr-2 text-indigo-500"></i>Invoice Summary</h2>
                        </div>
                        <div class="p-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 mb-1">Notes</label>
                                    <textarea name="notes" rows="4" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" placeholder="Optional billing notes..."></textarea>
                                </div>
                                <div class="space-y-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-sm text-slate-600">Subtotal</span>
                                        <span id="subtotal_display" class="text-lg font-semibold text-slate-900">0</span>
                                    </div>
                                    <div class="flex items-center justify-between gap-3">
                                        <span class="text-sm text-slate-600">Discount (%)</span>
                                        <div class="flex items-center gap-2">
                                            <input type="number" id="discount_pct" name="discount_pct" value="0" min="0" max="100" step="0.01" class="w-20 text-right rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm">
                                            <span class="text-slate-400">=</span>
                                            <span id="discount_display" class="w-24 text-right text-red-600 font-medium">0</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-between gap-3">
                                        <span class="text-sm text-slate-600">Tax (%)</span>
                                        <div class="flex items-center gap-2">
                                            <input type="number" id="tax_pct" name="tax_pct" value="0" min="0" max="100" step="0.01" class="w-20 text-right rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm">
                                            <span class="text-slate-400">=</span>
                                            <span id="tax_display" class="w-24 text-right text-slate-600 font-medium">0</span>
                                        </div>
                                    </div>
                                    <div class="border-t border-slate-200 pt-4 flex items-center justify-between">
                                        <span class="text-lg font-bold text-slate-900">Net Amount</span>
                                        <span id="net_amount_display" class="text-2xl font-bold text-emerald-700">0</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="flex items-center justify-end gap-x-4">
                        <a href="{{ url($url_prefix . '/rcm') }}" class="text-sm font-semibold leading-6 text-slate-900">Cancel</a>
                        <button type="submit" id="appointment_submit_btn" class="rounded-md bg-primary-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 transition-all">
                            <i class="fas fa-file-invoice mr-2"></i> Generate Invoice
                        </button>
                    </div>
                </div>
            </div>
        </form>

        <!-- ================================ -->
        <!-- MODE 2: DIRECT BILLING (new)     -->
        <!-- ================================ -->
        <form method="POST" action="{{ route('rcm.store') }}" id="rcm_form_direct" x-show="mode === 'direct'" x-cloak>
            @csrf
            <input type="hidden" name="bill_type" value="direct">
            <div class="space-y-6">
                <!-- Patient & Info Selection -->
                <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl">
                    <div class="px-6 py-4 border-b border-slate-200 bg-emerald-50">
                        <h2 class="text-base font-semibold text-slate-900"><i class="fas fa-user mr-2 text-emerald-500"></i>Customer / Patient Details</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Patient <span class="text-red-500">*</span></label>
                                <div class="relative" x-data="searchDropdown('directPatient')" @click.away="open = false">
                                    <div class="relative">
                                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                                        <input type="text" x-model="search" @focus="open = true" @input="open = true"
                                            placeholder="Search patient name or code..."
                                            class="block w-full rounded-md border-0 py-2.5 pl-9 pr-8 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm">
                                        <button type="button" x-show="selectedValue" @click="clear()" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    </div>
                                    <input type="hidden" name="patient_id" id="direct_patient_id" x-model="selectedValue">
                                    <div x-show="open && filteredItems().length > 0" x-transition
                                        class="absolute z-50 mt-1 w-full max-h-60 overflow-auto rounded-lg bg-white shadow-lg ring-1 ring-slate-900/10">
                                        <template x-for="item in filteredItems()" :key="item.value">
                                            <button type="button" @click="select(item)"
                                                class="w-full text-left px-4 py-2.5 text-sm hover:bg-primary-50 transition-colors border-b border-slate-50 last:border-0"
                                                :class="selectedValue == item.value ? 'bg-primary-50 text-primary-700 font-medium' : 'text-slate-700'">
                                                <span x-html="highlight(item.label)"></span>
                                            </button>
                                        </template>
                                    </div>
                                    <div x-show="open && search.length > 0 && filteredItems().length === 0" x-transition
                                        class="absolute z-50 mt-1 w-full rounded-lg bg-white shadow-lg ring-1 ring-slate-900/10 p-4 text-center">
                                        <p class="text-sm text-slate-400"><i class="fas fa-search mr-1"></i>No patients found</p>
                                    </div>
                                </div>
                                <script>
                                    var directPatientOptions = [
                                        @foreach($patients as $p)
                                        { value: '{{ $p->id }}', label: '{{ addslashes($p->name) }} ({{ $p->patient_code }})' },
                                        @endforeach
                                    ];
                                </script>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Doctor <span class="text-slate-400 font-normal">(optional)</span></label>
                                <div class="relative" x-data="searchDropdown('directDoctor')" @click.away="open = false">
                                    <div class="relative">
                                        <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs pointer-events-none"></i>
                                        <input type="text" x-model="search" @focus="open = true" @input="open = true"
                                            placeholder="Search doctor..."
                                            class="block w-full rounded-md border-0 py-2.5 pl-9 pr-8 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm">
                                        <button type="button" x-show="selectedValue" @click="clear()" class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 hover:text-slate-600">
                                            <i class="fas fa-times text-xs"></i>
                                        </button>
                                    </div>
                                    <input type="hidden" name="doctor_id" id="direct_doctor_id" x-model="selectedValue">
                                    <div x-show="open && filteredItems().length > 0" x-transition
                                        class="absolute z-50 mt-1 w-full max-h-60 overflow-auto rounded-lg bg-white shadow-lg ring-1 ring-slate-900/10">
                                        <template x-for="item in filteredItems()" :key="item.value">
                                            <button type="button" @click="select(item)"
                                                class="w-full text-left px-4 py-2.5 text-sm hover:bg-primary-50 transition-colors border-b border-slate-50 last:border-0"
                                                :class="selectedValue == item.value ? 'bg-primary-50 text-primary-700 font-medium' : 'text-slate-700'">
                                                <span x-html="highlight(item.label)"></span>
                                            </button>
                                        </template>
                                    </div>
                                    <div x-show="open && search.length > 0 && filteredItems().length === 0" x-transition
                                        class="absolute z-50 mt-1 w-full rounded-lg bg-white shadow-lg ring-1 ring-slate-900/10 p-4 text-center">
                                        <p class="text-sm text-slate-400"><i class="fas fa-search mr-1"></i>No doctors found</p>
                                    </div>
                                </div>
                                <script>
                                    var directDoctorOptions = [
                                        @foreach($doctors as $d)
                                        { value: '{{ $d->id }}', label: '{{ addslashes($d->name) }}' },
                                        @endforeach
                                    ];
                                </script>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Bill Date</label>
                                <input type="text" value="{{ date('M d, Y') }}" readonly class="block w-full rounded-md border-0 py-2.5 bg-slate-50 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 sm:text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Direct Bill Items -->
                <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50 flex items-center justify-between">
                        <h2 class="text-base font-semibold text-slate-900"><i class="fas fa-receipt mr-2 text-primary-500"></i>Bill Items</h2>
                        <button type="button" id="add_direct_item" class="inline-flex items-center rounded-md bg-primary-50 px-3 py-1.5 text-sm font-semibold text-primary-700 hover:bg-primary-100 transition-all ring-1 ring-inset ring-primary-200">
                            <i class="fas fa-plus mr-1.5"></i>Add Item
                        </button>
                    </div>
                    <div class="p-6" id="direct_items_container">
                        <!-- Table header -->
                        <div class="grid grid-cols-12 gap-3 items-center mb-3 pb-2 border-b border-slate-200">
                            <div class="col-span-3"><span class="text-xs font-medium uppercase text-slate-500">Category</span></div>
                            <div class="col-span-4"><span class="text-xs font-medium uppercase text-slate-500">Description</span></div>
                            <div class="col-span-1 text-center"><span class="text-xs font-medium uppercase text-slate-500">Qty</span></div>
                            <div class="col-span-2 text-right"><span class="text-xs font-medium uppercase text-slate-500">Unit Price</span></div>
                            <div class="col-span-1 text-right"><span class="text-xs font-medium uppercase text-slate-500">Total</span></div>
                            <div class="col-span-1"></div>
                        </div>
                        <div id="direct_items_list">
                            <p class="text-sm text-slate-400 text-center py-6" id="direct_placeholder">
                                <i class="fas fa-inbox text-slate-300 text-2xl mb-2 block"></i>
                                Click "Add Item" to start adding bill items.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Quick Add from Hospital Charges -->
                @if($hospital_charges->count() > 0)
                <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 bg-amber-50">
                        <h2 class="text-base font-semibold text-slate-900"><i class="fas fa-bolt mr-2 text-amber-500"></i>Quick Add — Hospital Charges</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">
                            @foreach($hospital_charges as $hc)
                            <button type="button"
                                class="quick-add-charge text-left p-3 rounded-lg border border-slate-200 hover:border-primary-300 hover:bg-primary-50 transition-all group"
                                data-name="{{ $hc->title }}"
                                data-price="{{ $hc->standard_charge }}"
                                data-category="{{ $hc->hospital_charge_category->title ?? 'other' }}">
                                <p class="text-sm font-medium text-slate-900 group-hover:text-primary-700 truncate">{{ $hc->title }}</p>
                                <p class="text-xs text-slate-500 mt-0.5">
                                    <span class="font-semibold text-emerald-600">{{ number_format($hc->standard_charge, 2) }}</span>
                                    @if($hc->hospital_charge_category)
                                    <span class="text-slate-400 ml-1">· {{ $hc->hospital_charge_category->title }}</span>
                                    @endif
                                </p>
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>
                @endif

                <!-- Summary -->
                <div class="bg-white shadow-sm ring-1 ring-slate-900/5 rounded-xl overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                        <h2 class="text-base font-semibold text-slate-900"><i class="fas fa-calculator mr-2 text-indigo-500"></i>Invoice Summary</h2>
                    </div>
                    <div class="p-6">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                            <div>
                                <label class="block text-sm font-medium text-slate-700 mb-1">Notes</label>
                                <textarea name="notes" rows="4" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 placeholder:text-slate-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm" placeholder="Optional billing notes..."></textarea>
                            </div>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <span class="text-sm text-slate-600">Subtotal</span>
                                    <span id="d_subtotal" class="text-lg font-semibold text-slate-900">0.00</span>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-sm text-slate-600">Discount (%)</span>
                                    <div class="flex items-center gap-2">
                                        <input type="number" id="d_discount_pct" name="discount_pct" value="0" min="0" max="100" step="0.01" class="w-20 text-right rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm">
                                        <span class="text-slate-400">=</span>
                                        <span id="d_discount_amt" class="w-24 text-right text-red-600 font-medium">0.00</span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-between gap-3">
                                    <span class="text-sm text-slate-600">Tax (%)</span>
                                    <div class="flex items-center gap-2">
                                        <input type="number" id="d_tax_pct" name="tax_pct" value="0" min="0" max="100" step="0.01" class="w-20 text-right rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm">
                                        <span class="text-slate-400">=</span>
                                        <span id="d_tax_amt" class="w-24 text-right text-slate-600 font-medium">0.00</span>
                                    </div>
                                </div>
                                <div class="border-t border-slate-200 pt-4 flex items-center justify-between">
                                    <span class="text-lg font-bold text-slate-900">Net Amount</span>
                                    <span id="d_net_amount" class="text-2xl font-bold text-emerald-700">0.00</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit -->
                <div class="flex items-center justify-end gap-x-4">
                    <a href="{{ url($url_prefix . '/rcm') }}" class="text-sm font-semibold leading-6 text-slate-900">Cancel</a>
                    <button type="submit" id="direct_submit_btn" class="rounded-md bg-emerald-600 px-6 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 transition-all">
                        <i class="fas fa-file-invoice-dollar mr-2"></i> Generate Direct Invoice
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // =============================================
    // Reusable searchable dropdown Alpine component
    // =============================================
    function searchDropdown(optionsKey) {
        return {
            search: '',
            open: false,
            selectedValue: '',
            selectedLabel: '',
            items: window[optionsKey + 'Options'] || [],
            init() {
                // Show selected label when not focused
                this.$watch('open', (val) => {
                    if (!val && this.selectedLabel) {
                        this.search = this.selectedLabel;
                    }
                });
            },
            filteredItems() {
                // When re-opening with selected label showing, show all items
                if (!this.search || this.search === this.selectedLabel) return this.items;
                var q = this.search.toLowerCase();
                return this.items.filter(function(item) {
                    return item.label.toLowerCase().indexOf(q) >= 0;
                });
            },
            highlight(text) {
                if (!this.search || this.search === this.selectedLabel) return text;
                var escaped = this.search.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
                return text.replace(new RegExp('(' + escaped + ')', 'gi'), '<mark class="bg-yellow-200 rounded px-0.5">$1</mark>');
            },
            select(item) {
                this.selectedValue = item.value;
                this.selectedLabel = item.label;
                this.search = item.label;
                this.open = false;
                // Dispatch a custom event so vanilla JS can react
                this.$nextTick(() => {
                    document.getElementById(this.getHiddenId()).dispatchEvent(new Event('change'));
                    // For appointment: also fill patient/doctor/date
                    if (optionsKey === 'appointment' && item.patientName !== undefined) {
                        document.getElementById('patient_name').value = item.patientName;
                        document.getElementById('patient_id').value = item.patientId;
                        document.getElementById('doctor_name').value = item.doctorName;
                        document.getElementById('doctor_id').value = item.doctorId;
                        document.getElementById('appt_date').value = item.apptDate;
                    }
                });
            },
            clear() {
                this.selectedValue = '';
                this.selectedLabel = '';
                this.search = '';
                document.getElementById(this.getHiddenId()).dispatchEvent(new Event('change'));
            },
            getHiddenId() {
                if (optionsKey === 'appointment') return 'appointment_id';
                if (optionsKey === 'directPatient') return 'direct_patient_id';
                if (optionsKey === 'directDoctor') return 'direct_doctor_id';
                return '';
            }
        };
    }
</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ======================
    // APPOINTMENT MODE LOGIC
    // ======================
    var caseHidden = document.getElementById('appointment_id');
    var container = document.getElementById('billable_items_container');
    var loading = document.getElementById('loading_indicator');
    var manualIdx = 0;

    caseHidden.addEventListener('change', function() {
        var id = this.value;
        if (!id) return;
        container.classList.add('hidden');
        loading.classList.remove('hidden');

        fetch("{{ url($url_prefix . '/rcm/ajax_fetch_items') }}/" + id, { headers: { 'Accept': 'application/json' } })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            loading.classList.add('hidden');
            container.classList.remove('hidden');
            if (data.status !== 'success') return;

            // Patient/doctor/date are now set by Alpine select handler


            renderCategoryItems('consultation_items', 'other_items', data.other_charges, function(item) {
                return { id: item.id, name: item.treatment ? item.treatment.title : 'Consultation Fee', price: item.hospital_charge_price, qty: 1, diagnosis_id: item.diagnosis_id };
            });
            renderCategoryItems('pharmacy_items', 'pharmacy_items', data.pharmacy_items, function(item) {
                return { id: item.id, name: item.pharmacy_name || item.drug_name || 'Medication', price: item.price || 0, qty: item.quantity || 1 };
            });
            renderCategoryItems('pathology_items', 'pathology_items', data.pathology_items, function(item) {
                var testName = item.pathology_data ? item.pathology_data.test_name : (item.test_name || 'Lab Test');
                return { id: item.id, name: testName, price: item.pathology_data ? (item.pathology_data.standard_charge || 0) : 0, qty: 1, test_id: item.pathology_test_id, diagnosis_id: item.diagnosis_id };
            });
            renderCategoryItems('radiology_items', 'radiology_items', data.radiology_items, function(item) {
                var testName = item.radiology_data ? item.radiology_data.test_name : (item.test_name || 'Imaging');
                return { id: item.id, name: testName, price: item.radiology_data ? (item.radiology_data.standard_charge || 0) : 0, qty: 1, test_id: item.radiology_test_id, diagnosis_id: item.diagnosis_id };
            });

            if (data.consumable_items && data.consumable_items.length > 0) {
                document.getElementById('section_consumable').style.display = 'block';
                renderCategoryItems('consumable_items', 'consumable_items', data.consumable_items, function(item) {
                    return { id: item.id, name: item.medical_consumable ? item.medical_consumable.inventorymaster ? item.medical_consumable.inventorymaster.name : 'Consumable' : 'Consumable', price: item.price || 0, qty: item.quantity || 1, item_id: item.medical_consumable_id, diagnosis_id: item.diagnosis_id };
                });
            }

            recalcTotals();
        })
        .catch(function(e) { loading.classList.add('hidden'); console.error(e); alert('Failed to load items.'); });
    });

    function renderCategoryItems(containerId, inputPrefix, items, mapper) {
        var el = document.getElementById(containerId);
        if (!items || items.length === 0) {
            el.innerHTML = '<p class="text-sm text-slate-400 text-center py-4">None found for this case.</p>';
            return;
        }
        var html = '<table class="min-w-full divide-y divide-slate-200"><thead class="bg-slate-50"><tr>';
        html += '<th class="py-2 pl-4 pr-3 text-left text-xs font-medium uppercase text-slate-500 w-10"><input type="checkbox" class="select-all-category rounded" data-category="' + inputPrefix + '" checked></th>';
        html += '<th class="px-3 py-2 text-left text-xs font-medium uppercase text-slate-500">Item</th>';
        html += '<th class="px-3 py-2 text-center text-xs font-medium uppercase text-slate-500 w-24">Qty</th>';
        html += '<th class="px-3 py-2 text-right text-xs font-medium uppercase text-slate-500 w-32">Unit Price</th>';
        html += '<th class="px-3 py-2 text-right text-xs font-medium uppercase text-slate-500 w-32">Total</th>';
        html += '</tr></thead><tbody class="divide-y divide-slate-200">';

        items.forEach(function(rawItem, i) {
            var item = mapper(rawItem);
            var lineTotal = (item.qty * item.price).toFixed(2);
            html += '<tr class="hover:bg-slate-50">';
            html += '<td class="py-2 pl-4 pr-3"><input type="checkbox" class="item-checkbox rounded" name="' + inputPrefix + '[' + i + '][selected]" value="1" data-price="' + item.price + '" data-qty="' + item.qty + '" checked></td>';
            html += '<td class="px-3 py-2 text-sm text-slate-900 font-medium">' + item.name + '<input type="hidden" name="' + inputPrefix + '[' + i + '][id]" value="' + item.id + '"><input type="hidden" name="' + inputPrefix + '[' + i + '][name]" value="' + item.name + '">';
            if (item.test_id !== undefined) html += '<input type="hidden" name="' + inputPrefix + '[' + i + '][test_id]" value="' + item.test_id + '">';
            if (item.diagnosis_id !== undefined) html += '<input type="hidden" name="' + inputPrefix + '[' + i + '][diagnosis_id]" value="' + item.diagnosis_id + '">';
            if (item.item_id !== undefined) html += '<input type="hidden" name="' + inputPrefix + '[' + i + '][item_id]" value="' + item.item_id + '">';
            html += '</td>';
            html += '<td class="px-3 py-2 text-center"><input type="number" name="' + inputPrefix + '[' + i + '][quantity]" value="' + item.qty + '" min="1" class="item-qty w-16 text-center rounded-md border-0 py-1 shadow-sm ring-1 ring-inset ring-slate-300 sm:text-sm" data-row="' + i + '"></td>';
            html += '<td class="px-3 py-2 text-right"><input type="number" name="' + inputPrefix + '[' + i + '][price]" value="' + item.price + '" min="0" step="0.01" class="item-price w-28 text-right rounded-md border-0 py-1 shadow-sm ring-1 ring-inset ring-slate-300 sm:text-sm" data-row="' + i + '"></td>';
            html += '<td class="px-3 py-2 text-right text-sm font-semibold text-slate-900 line-total">' + lineTotal + '</td>';
            html += '</tr>';
        });

        html += '</tbody></table>';
        el.innerHTML = html;
    }

    // Manual items (appointment mode)
    document.getElementById('add_manual_item').addEventListener('click', function() {
        document.getElementById('manual_placeholder').style.display = 'none';
        var c = document.getElementById('manual_items_container');
        var idx = manualIdx++;
        var row = document.createElement('div');
        row.className = 'grid grid-cols-12 gap-3 items-center mb-3';
        row.innerHTML = '<div class="col-span-4"><select name="manual_items[' + idx + '][category]" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 sm:text-sm"><option value="consultation">Consultation Fee</option><option value="procedure">Procedure</option><option value="other">Other</option></select></div>' +
            '<div class="col-span-3"><input type="text" name="manual_items[' + idx + '][description]" placeholder="Description" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 sm:text-sm"></div>' +
            '<div class="col-span-1"><input type="number" name="manual_items[' + idx + '][quantity]" value="1" min="1" class="manual-qty block w-full text-center rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-slate-300 sm:text-sm"></div>' +
            '<div class="col-span-2"><input type="number" name="manual_items[' + idx + '][price]" value="0" min="0" step="0.01" class="manual-price block w-full text-right rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-slate-300 sm:text-sm"></div>' +
            '<div class="col-span-2 text-right"><button type="button" class="text-red-500 hover:text-red-700 remove-manual-item"><i class="fas fa-trash"></i></button></div>';
        c.appendChild(row);
        row.querySelector('.remove-manual-item').addEventListener('click', function() { row.remove(); recalcTotals(); });
        row.querySelector('.manual-qty').addEventListener('input', recalcTotals);
        row.querySelector('.manual-price').addEventListener('input', recalcTotals);
    });

    // Recalculate totals (appointment mode)
    function recalcTotals() {
        var subtotal = 0;
        document.querySelectorAll('#rcm_form_appointment .item-checkbox').forEach(function(cb) {
            if (cb.checked) {
                var row = cb.closest('tr');
                var qty = parseFloat(row.querySelector('.item-qty').value) || 0;
                var price = parseFloat(row.querySelector('.item-price').value) || 0;
                var lt = qty * price;
                row.querySelector('.line-total').textContent = lt.toFixed(2);
                subtotal += lt;
            }
        });
        document.querySelectorAll('#rcm_form_appointment .manual-qty').forEach(function(qtyEl) {
            var row = qtyEl.closest('.grid');
            var priceEl = row.querySelector('.manual-price');
            subtotal += (parseFloat(qtyEl.value) || 0) * (parseFloat(priceEl.value) || 0);
        });
        document.getElementById('subtotal_display').textContent = subtotal.toFixed(2);
        var discPct = parseFloat(document.getElementById('discount_pct').value) || 0;
        var discAmt = subtotal * discPct / 100;
        document.getElementById('discount_display').textContent = '-' + discAmt.toFixed(2);
        var taxPct = parseFloat(document.getElementById('tax_pct').value) || 0;
        var taxAmt = subtotal * taxPct / 100;
        document.getElementById('tax_display').textContent = '+' + taxAmt.toFixed(2);
        document.getElementById('net_amount_display').textContent = (subtotal - discAmt + taxAmt).toFixed(2);
    }

    document.addEventListener('change', function(e) {
        if (e.target.classList.contains('item-checkbox') || e.target.classList.contains('select-all-category')) {
            if (e.target.classList.contains('select-all-category')) {
                var checked = e.target.checked;
                e.target.closest('table').querySelectorAll('.item-checkbox').forEach(function(cb) { cb.checked = checked; });
            }
            recalcTotals();
        }
    });
    document.addEventListener('input', function(e) {
        if (e.target.classList.contains('item-qty') || e.target.classList.contains('item-price')) recalcTotals();
    });
    document.getElementById('discount_pct').addEventListener('input', recalcTotals);
    document.getElementById('tax_pct').addEventListener('input', recalcTotals);

    // ======================
    // DIRECT BILLING LOGIC
    // ======================
    var directIdx = 0;

    function addDirectItem(category, desc, price, qty) {
        document.getElementById('direct_placeholder').style.display = 'none';
        var list = document.getElementById('direct_items_list');
        var idx = directIdx++;
        var row = document.createElement('div');
        row.className = 'grid grid-cols-12 gap-3 items-center mb-2 py-2 border-b border-slate-100';
        row.innerHTML =
            '<div class="col-span-3"><select name="manual_items[' + idx + '][category]" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 sm:text-sm">' +
                '<option value="consultation"' + (category === 'consultation' ? ' selected' : '') + '>Consultation</option>' +
                '<option value="pharmacy"' + (category === 'pharmacy' ? ' selected' : '') + '>Pharmacy</option>' +
                '<option value="pathology"' + (category === 'pathology' ? ' selected' : '') + '>Laboratory</option>' +
                '<option value="radiology"' + (category === 'radiology' ? ' selected' : '') + '>Radiology</option>' +
                '<option value="procedure"' + (category === 'procedure' ? ' selected' : '') + '>Procedure</option>' +
                '<option value="consumable"' + (category === 'consumable' ? ' selected' : '') + '>Consumable</option>' +
                '<option value="other"' + (category === 'other' ? ' selected' : '') + '>Other</option>' +
            '</select></div>' +
            '<div class="col-span-4"><input type="text" name="manual_items[' + idx + '][description]" value="' + (desc || '') + '" placeholder="Item description" class="block w-full rounded-md border-0 py-1.5 text-slate-900 shadow-sm ring-1 ring-inset ring-slate-300 sm:text-sm"></div>' +
            '<div class="col-span-1"><input type="number" name="manual_items[' + idx + '][quantity]" value="' + (qty || 1) + '" min="1" class="d-qty block w-full text-center rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-slate-300 sm:text-sm"></div>' +
            '<div class="col-span-2"><input type="number" name="manual_items[' + idx + '][price]" value="' + (price || 0) + '" min="0" step="0.01" class="d-price block w-full text-right rounded-md border-0 py-1.5 shadow-sm ring-1 ring-inset ring-slate-300 sm:text-sm"></div>' +
            '<div class="col-span-1 text-right"><span class="d-line-total text-sm font-semibold text-slate-900">' + ((qty || 1) * (price || 0)).toFixed(2) + '</span></div>' +
            '<div class="col-span-1 text-center"><button type="button" class="text-red-400 hover:text-red-600 remove-d-item"><i class="fas fa-times-circle"></i></button></div>';
        list.appendChild(row);

        row.querySelector('.remove-d-item').addEventListener('click', function() { row.remove(); directRecalc(); });
        row.querySelector('.d-qty').addEventListener('input', function() { updateDirectRowTotal(row); directRecalc(); });
        row.querySelector('.d-price').addEventListener('input', function() { updateDirectRowTotal(row); directRecalc(); });
        directRecalc();
    }

    function updateDirectRowTotal(row) {
        var q = parseFloat(row.querySelector('.d-qty').value) || 0;
        var p = parseFloat(row.querySelector('.d-price').value) || 0;
        row.querySelector('.d-line-total').textContent = (q * p).toFixed(2);
    }

    function directRecalc() {
        var subtotal = 0;
        document.querySelectorAll('#direct_items_list .d-qty').forEach(function(qEl) {
            var row = qEl.closest('.grid');
            var p = parseFloat(row.querySelector('.d-price').value) || 0;
            subtotal += (parseFloat(qEl.value) || 0) * p;
        });
        document.getElementById('d_subtotal').textContent = subtotal.toFixed(2);
        var disc = subtotal * (parseFloat(document.getElementById('d_discount_pct').value) || 0) / 100;
        document.getElementById('d_discount_amt').textContent = '-' + disc.toFixed(2);
        var tax = subtotal * (parseFloat(document.getElementById('d_tax_pct').value) || 0) / 100;
        document.getElementById('d_tax_amt').textContent = '+' + tax.toFixed(2);
        document.getElementById('d_net_amount').textContent = (subtotal - disc + tax).toFixed(2);
    }

    document.getElementById('add_direct_item').addEventListener('click', function() {
        addDirectItem('other', '', 0, 1);
    });

    document.getElementById('d_discount_pct').addEventListener('input', directRecalc);
    document.getElementById('d_tax_pct').addEventListener('input', directRecalc);

    // Quick add hospital charges
    document.querySelectorAll('.quick-add-charge').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var name = this.getAttribute('data-name');
            var price = parseFloat(this.getAttribute('data-price')) || 0;
            var cat = this.getAttribute('data-category').toLowerCase();
            // Map category name to value
            if (cat.indexOf('consult') >= 0) cat = 'consultation';
            else if (cat.indexOf('procedure') >= 0 || cat.indexOf('surg') >= 0) cat = 'procedure';
            else if (cat.indexOf('lab') >= 0 || cat.indexOf('path') >= 0) cat = 'pathology';
            else if (cat.indexOf('radio') >= 0 || cat.indexOf('imag') >= 0) cat = 'radiology';
            else if (cat.indexOf('pharm') >= 0) cat = 'pharmacy';
            else cat = 'other';
            addDirectItem(cat, name, price, 1);

            // Visual feedback
            this.classList.add('ring-2', 'ring-primary-400');
            var self = this;
            setTimeout(function() { self.classList.remove('ring-2', 'ring-primary-400'); }, 500);
        });
    });

    // Validate appointment billing form before submit
    document.getElementById('rcm_form_appointment').addEventListener('submit', function(e) {
        var appointmentId = document.getElementById('appointment_id').value;
        if (!appointmentId) {
            e.preventDefault();
            alert('Please select a diagnosed case first.');
            return false;
        }
        var patientId = document.getElementById('patient_id').value;
        if (!patientId) {
            e.preventDefault();
            alert('Please select a valid case with a patient.');
            return false;
        }
    });

    // Validate direct billing form before submit
    document.getElementById('rcm_form_direct').addEventListener('submit', function(e) {
        var patientId = document.getElementById('direct_patient_id').value;
        if (!patientId) {
            e.preventDefault();
            alert('Please select a patient.');
            return false;
        }
        var items = document.querySelectorAll('#direct_items_list .d-qty');
        if (items.length === 0) {
            e.preventDefault();
            alert('Please add at least one bill item.');
            return false;
        }
    });
});
</script>
@endsection
