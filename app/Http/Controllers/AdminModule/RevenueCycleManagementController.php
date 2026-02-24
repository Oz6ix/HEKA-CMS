<?php

namespace App\Http\Controllers\AdminModule;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Staff;
use App\Models\RcmInvoice;
use App\Models\RcmBillItem;
use App\Models\PatientBill;
use App\Models\PatientPrescription;
use App\Models\PatientMedicalTest;
use App\Models\MedicalConsumableUsed;
use App\Models\HospitalCharge;
use App\Models\InventoryStock;
use App\Models\SettingsSiteGeneral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RevenueCycleManagementController extends Controller
{
    public function __construct()
    {
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Revenue Cycle Management";
        $this->page_heading = "Revenue Cycle Management";
        $this->heading_icon = "fa-file-invoice-dollar";
        $this->page_info = [
            'url_prefix' => $this->url_prefix,
            'page_title' => $this->page_title,
            'page_heading' => $this->page_heading,
            'heading_icon' => $this->heading_icon
        ];
    }

    /**
     * List all invoices from the unified rcm_invoices table.
     */
    public function index()
    {
        $items = RcmInvoice::with('patient', 'doctor')
                    ->where('status', 1)
                    ->where('delete_status', 0)
                    ->orderBy('id', 'desc')
                    ->get();

        return view('backend.admin_module.rcm.index', compact('items'))->with($this->page_info);
    }

    /**
     * Show form to create a new unified invoice.
     * Lists diagnosed appointments that haven't been fully billed yet.
     */
    public function create()
    {
        $items = Appointment::with('patient', 'staff_doctor')
                    ->where('status', 1)
                    ->where('delete_status', 0)
                    ->where('diagnosis_status', 1)
                    ->orderBy('appointment_date', 'desc')
                    ->get();

        // Load available hospital charges for manual add
        $hospital_charges = HospitalCharge::with('hospital_charge_category')
                    ->where('status', 1)
                    ->where('delete_status', 0)
                    ->get();

        // For direct billing: patients and doctors
        $patients = Patient::where('delete_status', 0)->orderBy('name')->get();
        $doctors = Staff::where('delete_status', 0)->orderBy('name')->get();

        return view('backend.admin_module.rcm.create', compact('items', 'hospital_charges', 'patients', 'doctors'))->with($this->page_info);
    }

    /**
     * AJAX: Fetch all billable items for an appointment, grouped by service type.
     * This consolidates pharmacy, pathology, radiology, consumables, and consultation charges.
     */
    public function ajax_fetch_billable_items($appointment_id)
    {
        $appointment = Appointment::with('patient', 'staff_doctor')->findOrFail($appointment_id);

        // 1. Pharmacy Items (from prescriptions)
        $pharmacy_items = PatientPrescription::with('pharmacy_data')
                            ->where('appointment_id', $appointment_id)
                            ->where('delete_status', 0)
                            ->where('status', 1)
                            ->where('drug_id', '<>', 0)
                            ->get();

        foreach ($pharmacy_items as $item) {
            $stock = InventoryStock::where('inventory_master_id', $item->drug_id)->first();
            $item->price = $stock ? $stock->selling_price : 0;
        }

        // 2. Pathology Items
        $pathology_items = PatientMedicalTest::with('pathology_data')
                            ->where('appointment_id', $appointment_id)
                            ->where('delete_status', 0)
                            ->where('status', 1)
                            ->where(function($q) {
                                $q->where('pathology_test_id', '<>', 0)
                                  ->orWhereNotNull('pathology_test_id');
                            })
                            ->get();

        // 3. Radiology Items
        $radiology_items = PatientMedicalTest::with('radiology_data')
                            ->where('appointment_id', $appointment_id)
                            ->where('delete_status', 0)
                            ->where('status', 1)
                            ->where(function($q) {
                                $q->where('radiology_test_id', '<>', 0)
                                  ->orWhereNotNull('radiology_test_id');
                            })
                            ->get();

        // 4. Consumable Items
        $consumable_items = collect(); // Empty for now — will populate if consumables exist
        try {
            $consumable_items = MedicalConsumableUsed::with('medical_consumable.inventorymaster')
                            ->where('appointment_id', $appointment_id)
                            ->where('delete_status', 0)
                            ->where('status', 1)
                            ->get();
            foreach ($consumable_items as $item) {
                $item->price = $item->medical_consumable ? $item->medical_consumable->purchase_price : 0;
            }
        } catch (\Exception $e) {
            // Table may not exist yet
        }

        // 5. Consultation/Other Charges (from PatientBill where bill_type = 1)
        $other_charges = PatientBill::with('treatment')
                            ->where('appointment_id', $appointment_id)
                            ->where('bill_type', 1)
                            ->where('delete_status', 0)
                            ->where('status', 1)
                            ->get();

        return response()->json([
            'status' => 'success',
            'appointment' => $appointment,
            'pharmacy_items' => $pharmacy_items,
            'pathology_items' => $pathology_items,
            'radiology_items' => $radiology_items,
            'consumable_items' => $consumable_items,
            'other_charges' => $other_charges
        ]);
    }

    /**
     * Store a unified invoice.
     * All selected items are saved to rcm_bill_items with a single rcm_invoices header.
     */
    public function store(Request $request)
    {
        $bill_type = $request->bill_type ?? 'appointment';
        $appointment_id = $request->appointment_id ?: 0;  // 0 for direct billing (NOT NULL constraint)
        $bill_date = date('Y-m-d H:i:s');

        if ($bill_type === 'direct') {
            $bill_number = 'DS-' . date('YmdHis') . '-' . rand(100, 999);
        } else {
            $bill_number = 'INV-' . date('Ymd') . '-' . $appointment_id;
        }

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $line_items = [];

            // Common fields
            $common = [
                'bill_number' => $bill_number,
                'appointment_id' => $appointment_id,
                'patient_id' => $request->patient_id,
                'doctor_id' => $request->doctor_id ?: 0,
                'bill_date' => $bill_date,
                'status' => 1,
                'delete_status' => 0,
            ];

            // 1. Pharmacy Items
            if (!empty($request->pharmacy_items)) {
                foreach ($request->pharmacy_items as $item) {
                    if (!isset($item['selected']) || $item['selected'] != 1) continue;

                    $qty = (int)($item['quantity'] ?? 1);
                    $price = (float)($item['price'] ?? 0);
                    $lineTotal = $qty * $price;
                    $subtotal += $lineTotal;

                    // Decrement stock
                    $prescription = PatientPrescription::find($item['id']);
                    if ($prescription) {
                        $stock = InventoryStock::where('inventory_master_id', $prescription->drug_id)->first();
                        if ($stock && $qty <= $stock->quantity) {
                            InventoryStock::where('inventory_master_id', $prescription->drug_id)->decrement('quantity', $qty);
                        }
                    }

                    RcmBillItem::create(array_merge($common, [
                        'service_category' => RcmBillItem::CATEGORY_PHARMACY,
                        'item_description' => $item['name'] ?? 'Medication',
                        'reference_id' => $item['id'],
                        'reference_type' => 'App\\Models\\PatientPrescription',
                        'quantity' => $qty,
                        'unit_price' => $price,
                        'line_total' => $lineTotal,
                    ]));
                }
            }

            // 2. Pathology Items
            if (!empty($request->pathology_items)) {
                foreach ($request->pathology_items as $item) {
                    if (!isset($item['selected']) || $item['selected'] != 1) continue;

                    $qty = 1;
                    $price = (float)($item['price'] ?? 0);
                    $lineTotal = $qty * $price;
                    $subtotal += $lineTotal;

                    RcmBillItem::create(array_merge($common, [
                        'service_category' => RcmBillItem::CATEGORY_PATHOLOGY,
                        'item_description' => $item['name'] ?? 'Lab Test',
                        'reference_id' => $item['id'],
                        'reference_type' => 'App\\Models\\PatientMedicalTest',
                        'diagnosis_id' => $item['diagnosis_id'] ?? null,
                        'quantity' => $qty,
                        'unit_price' => $price,
                        'line_total' => $lineTotal,
                    ]));
                }
            }

            // 3. Radiology Items
            if (!empty($request->radiology_items)) {
                foreach ($request->radiology_items as $item) {
                    if (!isset($item['selected']) || $item['selected'] != 1) continue;

                    $qty = 1;
                    $price = (float)($item['price'] ?? 0);
                    $lineTotal = $qty * $price;
                    $subtotal += $lineTotal;

                    RcmBillItem::create(array_merge($common, [
                        'service_category' => RcmBillItem::CATEGORY_RADIOLOGY,
                        'item_description' => $item['name'] ?? 'Imaging Test',
                        'reference_id' => $item['id'],
                        'reference_type' => 'App\\Models\\PatientMedicalTest',
                        'diagnosis_id' => $item['diagnosis_id'] ?? null,
                        'quantity' => $qty,
                        'unit_price' => $price,
                        'line_total' => $lineTotal,
                    ]));
                }
            }

            // 4. Consumable Items
            if (!empty($request->consumable_items)) {
                foreach ($request->consumable_items as $item) {
                    if (!isset($item['selected']) || $item['selected'] != 1) continue;

                    $qty = (int)($item['quantity'] ?? 1);
                    $price = (float)($item['price'] ?? 0);
                    $lineTotal = $qty * $price;
                    $subtotal += $lineTotal;

                    RcmBillItem::create(array_merge($common, [
                        'service_category' => RcmBillItem::CATEGORY_CONSUMABLE,
                        'item_description' => $item['name'] ?? 'Consumable',
                        'reference_id' => $item['id'],
                        'reference_type' => 'App\\Models\\MedicalConsumableUsed',
                        'diagnosis_id' => $item['diagnosis_id'] ?? null,
                        'quantity' => $qty,
                        'unit_price' => $price,
                        'line_total' => $lineTotal,
                    ]));
                }
            }

            // 5. Other/Consultation Charges
            if (!empty($request->other_items)) {
                foreach ($request->other_items as $item) {
                    if (!isset($item['selected']) || $item['selected'] != 1) continue;

                    $qty = 1;
                    $price = (float)($item['price'] ?? 0);
                    $lineTotal = $qty * $price;
                    $subtotal += $lineTotal;

                    RcmBillItem::create(array_merge($common, [
                        'service_category' => RcmBillItem::CATEGORY_CONSULTATION,
                        'item_description' => $item['name'] ?? 'Consultation Fee',
                        'reference_id' => $item['id'] ?? null,
                        'reference_type' => 'App\\Models\\HospitalCharge',
                        'quantity' => $qty,
                        'unit_price' => $price,
                        'line_total' => $lineTotal,
                    ]));
                }
            }

            // 6. Manually added service items
            if (!empty($request->manual_items)) {
                foreach ($request->manual_items as $item) {
                    if (empty($item['description']) || empty($item['price'])) continue;

                    $qty = (int)($item['quantity'] ?? 1);
                    $price = (float)$item['price'];
                    $lineTotal = $qty * $price;
                    $subtotal += $lineTotal;

                    RcmBillItem::create(array_merge($common, [
                        'service_category' => $item['category'] ?? RcmBillItem::CATEGORY_OTHER,
                        'item_description' => $item['description'],
                        'quantity' => $qty,
                        'unit_price' => $price,
                        'line_total' => $lineTotal,
                    ]));
                }
            }

            // Calculate financials
            $discountPct = (float)($request->discount_pct ?? 0);
            $discountAmt = $subtotal * $discountPct / 100;
            $taxPct = (float)($request->tax_pct ?? 0);
            $taxAmt = $subtotal * $taxPct / 100;
            $netAmount = $subtotal - $discountAmt + $taxAmt;

            // Create invoice header
            RcmInvoice::create([
                'bill_number' => $bill_number,
                'bill_type' => $bill_type,
                'appointment_id' => $appointment_id,
                'patient_id' => $request->patient_id,
                'doctor_id' => $request->doctor_id ?: 0,
                'subtotal' => $subtotal,
                'discount_pct' => $discountPct,
                'discount_amount' => $discountAmt,
                'tax_pct' => $taxPct,
                'tax_amount' => $taxAmt,
                'net_amount' => $netAmount,
                'notes' => $request->notes,
                'payment_status' => 'pending',
                'bill_date' => $bill_date,
                'created_by' => Auth::id(),
            ]);

            // Update appointment billing status (only for appointment-based invoices)
            if ($appointment_id > 0) {
                Appointment::where('id', $appointment_id)->update(['status' => 2]);
            }

            DB::commit();
            return redirect($this->url_prefix . '/rcm')->with('success_message', 'Invoice ' . $bill_number . ' generated successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->withInput()->with('error_message', 'Error generating invoice: ' . $e->getMessage());
        }
    }

    /**
     * Display a single invoice with all line items grouped by service category.
     */
    public function show($bill_number)
    {
        $invoice = RcmInvoice::with('patient', 'doctor')->where('bill_number', $bill_number)->firstOrFail();

        $items = RcmBillItem::where('bill_number', $bill_number)
                    ->where('status', 1)
                    ->where('delete_status', 0)
                    ->orderBy('service_category')
                    ->get()
                    ->groupBy('service_category');

        $general_settings = SettingsSiteGeneral::find(1);
        $category_labels = RcmBillItem::categoryLabels();

        return view('backend.admin_module.rcm.invoice', compact(
            'invoice', 'items', 'general_settings', 'category_labels'
        ))->with($this->page_info);
    }

    /**
     * AJAX: Search patients by name or code.
     */
    public function ajax_search_patients(Request $request)
    {
        $q = $request->get('q', '');
        $patients = Patient::where('delete_status', 0)
            ->where(function ($query) use ($q) {
                $query->where('name', 'like', '%' . $q . '%')
                      ->orWhere('patient_code', 'like', '%' . $q . '%');
            })
            ->limit(20)
            ->get(['id', 'name', 'patient_code', 'phone', 'email']);

        return response()->json($patients);
    }

    /**
     * Mark an invoice as paid (or credit).
     * Accepts payment_mode, payment_reference, is_credit, credit_due_date.
     */
    public function markAsPaid(Request $request, $id)
    {
        $invoice = RcmInvoice::findOrFail($id);

        $isCredit = $request->is_credit ? true : false;

        $invoice->update([
            'payment_mode' => $request->payment_mode,
            'payment_reference' => $request->payment_reference,
            'is_credit' => $isCredit,
            'credit_due_date' => $isCredit ? $request->credit_due_date : null,
            'payment_status' => $isCredit ? 'credit' : 'paid',
            'paid_at' => $isCredit ? null : now(),
            'paid_by' => Auth::id(),
        ]);

        $statusLabel = $isCredit ? 'recorded as credit' : 'marked as paid';
        return redirect()->back()->with('success_message', "Invoice #{$invoice->bill_number} {$statusLabel} via {$request->payment_mode}.");
    }

    /**
     * Settle a credit invoice — record actual payment.
     */
    public function settleCredit(Request $request, $id)
    {
        $invoice = RcmInvoice::findOrFail($id);

        $invoice->update([
            'payment_mode' => $request->payment_mode,
            'payment_reference' => $request->payment_reference,
            'payment_status' => 'paid',
            'paid_at' => now(),
            'paid_by' => Auth::id(),
            'credit_settled_at' => now(),
        ]);

        return redirect()->back()->with('success_message', "Credit invoice #{$invoice->bill_number} settled via {$request->payment_mode}.");
    }

    /**
     * Thermal receipt view (80mm format).
     */
    public function receipt($bill_number)
    {
        $invoice = RcmInvoice::where('bill_number', $bill_number)->firstOrFail();
        $items = RcmBillItem::where('bill_number', $bill_number)
            ->orderBy('service_category')
            ->get()
            ->groupBy('service_category');

        $general_settings = SettingsSiteGeneral::first();

        $category_labels = [
            'consultation' => 'Consultation',
            'pharmacy' => 'Pharmacy',
            'pathology' => 'Lab Test',
            'radiology' => 'Radiology',
            'consumable' => 'Consumable',
            'manual' => 'Other',
            'other' => 'Other',
        ];

        return view('backend.admin_module.rcm.receipt', compact(
            'invoice', 'items', 'general_settings', 'category_labels'
        ))->with($this->page_info);
    }
}
