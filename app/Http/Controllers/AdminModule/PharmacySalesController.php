<?php
namespace App\Http\Controllers\AdminModule;

use App\Models\Pharmacy;
use App\Models\PharmacySale;
use App\Models\DispensedItem;
use App\Models\ExternalPrescription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PharmacySalesController extends Controller
{
    public function __construct()
    {
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Pharmacy Sales";
        $this->page_heading = "Pharmacy Sales";
        $this->heading_icon = "fa-cash-register";
        $this->page_info = [
            'url_prefix' => $this->url_prefix,
            'page_title' => $this->page_title,
            'page_heading' => $this->page_heading,
            'heading_icon' => $this->heading_icon
        ];
    }

    /**
     * Quick Billing / POS Screen
     */
    public function index()
    {
        $drugs = Pharmacy::where('delete_status', 0)
            ->where('status', 1)
            ->orderBy('title', 'asc')
            ->get();

        $recent_sales = PharmacySale::where('delete_status', 0)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('backend.admin_module.pharmacy_sales.index', compact('drugs', 'recent_sales'))
            ->with($this->page_info);
    }

    /**
     * Store a quick sale (POS)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'nullable|string|max:20',
            'payment_method' => 'required|string|in:cash,card,upi',
            'discount' => 'nullable|numeric|min:0',
            'items' => 'required|array|min:1',
            'items.*.pharmacy_id' => 'required|integer',
            'items.*.drug_name' => 'required|string',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.unit_price' => 'required|numeric|min:0',
        ]);

        $subtotal = 0;
        foreach ($data['items'] as $item) {
            $subtotal += $item['quantity'] * $item['unit_price'];
        }

        $discount = $data['discount'] ?? 0;
        $total = max(0, $subtotal - $discount);

        // Create sale record
        $sale = PharmacySale::create([
            'invoice_no' => 'INV-' . date('Ymd') . '-' . str_pad(PharmacySale::whereDate('created_at', today())->count() + 1, 3, '0', STR_PAD_LEFT),
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'] ?? null,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
            'payment_method' => $data['payment_method'],
            'created_by' => auth()->id(),
        ]);

        // Create dispensed items & deduct stock
        foreach ($data['items'] as $item) {
            DispensedItem::create([
                'pharmacy_id' => $item['pharmacy_id'],
                'drug_name' => $item['drug_name'],
                'quantity_prescribed' => $item['quantity'],
                'quantity_dispensed' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['quantity'] * $item['unit_price'],
                'dispensed_by' => auth()->id(),
                'dispensed_at' => now(),
            ]);

            // Deduct from pharmacy stock
            $drug = Pharmacy::find($item['pharmacy_id']);
            if ($drug) {
                $drug->decrement('quantity', $item['quantity']);
            }
        }

        generate_log('Pharmacy sale created', $sale->id);
        return redirect($this->url_prefix . '/pharmacy_sales/invoice/' . $sale->id)
            ->with('message', 'Sale completed! Invoice: ' . $sale->invoice_no);
    }

    /**
     * External Prescription Entry
     */
    public function external_create()
    {
        $drugs = Pharmacy::where('delete_status', 0)
            ->where('status', 1)
            ->orderBy('title', 'asc')
            ->get();

        return view('backend.admin_module.pharmacy_sales.external', compact('drugs'))
            ->with($this->page_info);
    }

    /**
     * Store External Prescription
     */
    public function external_store(Request $request)
    {
        $data = $request->validate([
            'patient_name' => 'required|string|max:255',
            'patient_phone' => 'nullable|string|max:20',
            'doctor_name' => 'nullable|string|max:255',
            'doctor_license_no' => 'nullable|string|max:100',
            'diagnosis' => 'nullable|string|max:2000',
            'instructions' => 'nullable|string|max:2000',
            'rx_image' => 'nullable|image|mimes:jpeg,png,gif,webp|max:5120',
            'rx_date' => 'required|date',
        ]);

        $data['rx_code'] = 'RX-' . date('Ymd') . '-' . str_pad(ExternalPrescription::whereDate('created_at', today())->count() + 1, 3, '0', STR_PAD_LEFT);
        $data['created_by'] = auth()->id();

        // Handle Rx image upload
        if ($request->hasFile('rx_image')) {
            $file = $request->file('rx_image');
            $imageName = 'rx_' . time() . '_' . rand(1000, 9999) . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/prescriptions'), $imageName);
            $data['rx_image'] = $imageName;
        }

        $rx = ExternalPrescription::create($data);

        generate_log('External prescription created', $rx->id);
        return redirect($this->url_prefix . '/pharmacy_sales')
            ->with('message', 'External prescription ' . $rx->rx_code . ' saved.');
    }

    /**
     * List external prescriptions
     */
    public function external_index()
    {
        $prescriptions = ExternalPrescription::where('delete_status', 0)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('backend.admin_module.pharmacy_sales.external_list', compact('prescriptions'))
            ->with($this->page_info);
    }

    /**
     * Invoice view
     */
    public function invoice($id)
    {
        $sale = PharmacySale::findOrFail($id);
        $items = DispensedItem::where('dispensed_at', '>=', $sale->created_at->subMinute())
            ->where('dispensed_at', '<=', $sale->created_at->addMinute())
            ->where('dispensed_by', $sale->created_by)
            ->get();

        return view('backend.admin_module.pharmacy_sales.invoice', compact('sale', 'items'))
            ->with($this->page_info);
    }

    /**
     * AJAX: Search drugs by name or barcode
     */
    public function search_drug(Request $request)
    {
        $query = $request->get('q', '');
        $drugs = Pharmacy::where('delete_status', 0)
            ->where('status', 1)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                  ->orWhere('generic_name', 'like', "%{$query}%")
                  ->orWhere('brand_name', 'like', "%{$query}%")
                  ->orWhere('barcode', $query);
            })
            ->limit(15)
            ->get(['id', 'title', 'generic_name', 'brand_name', 'strength', 'form', 'quantity', 'price', 'mrp', 'barcode', 'medicine_type', 'schedule']);

        return response()->json($drugs);
    }
}
