<?php

namespace App\Http\Controllers\AdminModule;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\PatientDiagnosisService;
use App\Models\Appointment;
use App\Models\InventoryItemMaster;
use App\Models\AppointmentBasicsDetail;
use App\Models\PatientDiagnosis;
use App\Models\RcmInvoice;
use App\Models\PatientDocument;
use App\Models\PatientMedicalTest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EMRController extends Controller
{
    protected $patientDiagnosisService;

    public function __construct(PatientDiagnosisService $patientDiagnosisService)
    {
        $this->patientDiagnosisService = $patientDiagnosisService;
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Doctor Workbench";
        $this->page_heading = "Doctor Workbench";
        $this->heading_icon = "fa-user-md";
        $this->page_info = [
            'url_prefix' => $this->url_prefix,
            'page_title' => $this->page_title,
            'page_heading' => $this->page_heading,
            'heading_icon' => $this->heading_icon
        ];
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        // Date filter — default to today
        $filterDate = $request->get('date', date('Y-m-d'));

        // Search
        $search = $request->get('search', '');

        $query = Appointment::with('patient', 'staff_doctor')
            ->where('status', 1)
            ->where('delete_status', 0);

        // When searching, skip date filter to search ALL patients across all dates
        // When not searching, default to today's appointments
        if (empty($search)) {
            $query->whereDate('appointment_date', $filterDate);
        }

        // Doctor restriction: if user has staff_id (is a doctor), only show their patients
        // Super admin (staff_id = null or 0) sees all
        if ($user->staff_id && $user->staff_id > 0) {
            $query->where('doctor_staff_id', $user->staff_id);
        }

        // Patient search
        if (!empty($search)) {
            $query->whereHas('patient', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('patient_code', 'like', '%' . $search . '%')
                  ->orWhere('phone', 'like', '%' . $search . '%');
            });
        }

        $items = $query->orderBy('appointment_date', 'desc')->get();

        // Stats
        $pendingCount = $items->where('diagnosis_status', 0)->count();
        $diagnosedCount = $items->where('diagnosis_status', 1)->count();

        return view('backend.admin_module.emr.index', compact(
            'items', 'filterDate', 'search', 'pendingCount', 'diagnosedCount'
        ))->with($this->page_info);
    }

    public function show($id)
    {
        $data = $this->patientDiagnosisService->getCreateData($id);

        $patientName = $data['patient_details'][0]->patient->name ?? 'Patient';
        $this->page_info['page_heading'] = "Workbench: " . $patientName;

        // Determine edit restrictions
        $appointment = $data['patient_details'][0];
        $isDiagnosed = ($appointment->diagnosis_status == 1);

        // Check if billed (has RCM invoice)
        $isBilled = false;
        if ($isDiagnosed) {
            $isBilled = RcmInvoice::where('appointment_id', $id)
                ->where('payment_status', '!=', 'pending')
                ->exists();
        }

        // Check previous diagnosis data for read-only display
        $existingDiagnosis = null;
        $existingPrescriptions = [];
        $existingTests = [];
        if ($isDiagnosed) {
            $existingDiagnosis = PatientDiagnosis::where('appointment_id', $id)->first();
            if ($existingDiagnosis) {
                $existingPrescriptions = DB::table('hospital_patient_prescriptions')
                    ->where('diagnosis_id', $existingDiagnosis->id)
                    ->where('delete_status', 0)
                    ->get();
                $existingTests = DB::table('hospital_patient_medical_tests')
                    ->where('diagnosis_id', $existingDiagnosis->id)
                    ->where('delete_status', 0)
                    ->get();
            }
        }

        $data['isDiagnosed'] = $isDiagnosed;
        $data['isBilled'] = $isBilled;
        $data['existingDiagnosis'] = $existingDiagnosis;
        $data['existingPrescriptions'] = $existingPrescriptions;
        $data['existingTests'] = $existingTests;

        // Fetch patient documents for the Documents tab
        $patientId = $appointment->patient_id ?? null;
        $data['patientDocuments'] = PatientDocument::where('patient_id', $patientId)
            ->where('delete_status', 0)
            ->orderBy('created_at', 'desc')
            ->get();
        $data['documentCategories'] = PatientDocument::$categories;

        return view('backend.admin_module.emr.show', $data)->with($this->page_info);
    }

    public function store(Request $request)
    {
        try {
            $patient_diagnosis = $this->patientDiagnosisService->createDiagnosis($request->all());
            return response()->json([
                'status' => "success",
                'message' => 'Medical notes and orders saved successfully',
                'diagnosis_id' => $patient_diagnosis->id
            ]);
        } catch (\Exception $e) {
            return response()->json(['validation' => [$e->getMessage()]]);
        }
    }

    /**
     * Save as Draft — persists SOAP notes and vitals without finalizing diagnosis.
     */
    public function saveDraft(Request $request)
    {
        try {
            $appointmentId = $request->input('id');

            // Update or create AppointmentBasicsDetail
            $basicData = [
                'appointment_id' => $appointmentId,
                'symptom' => $request->input('symptom', ''),
                'description' => $request->input('description', ''),
                'note' => $request->input('note', ''),
                'systolic_bp' => $request->input('systolic_bp'),
                'diastolic_bp' => $request->input('diastolic_bp'),
                'pulse' => $request->input('pulse'),
                'temperature' => $request->input('temperature'),
                'spo2' => $request->input('spo2'),
                'height' => $request->input('height'),
                'weight' => $request->input('weight'),
            ];

            AppointmentBasicsDetail::updateOrCreate(
                ['appointment_id' => $appointmentId],
                $basicData
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Draft saved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json(['validation' => [$e->getMessage()]]);
        }
    }

    /**
     * AJAX: Search drugs from inventory master by keyword.
     */
    public function ajaxSearchDrugs(Request $request)
    {
        $q = $request->get('q', '');
        if (strlen($q) < 1) {
            return response()->json([]);
        }

        $drugs = InventoryItemMaster::where('delete_status', 0)
            ->where(function ($query) use ($q) {
                $query->where('item_name', 'like', $q . '%')
                      ->orWhere('pharmacy_generic', 'like', $q . '%')
                      ->orWhere('item_name', 'like', '%' . $q . '%');
            })
            ->limit(15)
            ->get(['id', 'item_name', 'pharmacy_generic', 'pharmacy_dosage', 'route', 'inventory_unit']);

        return response()->json($drugs);
    }

    /**
     * AJAX: Search pathology/radiology tests by keyword.
     */
    public function ajaxSearchTests(Request $request)
    {
        $q = $request->get('q', '');
        if (strlen($q) < 1) {
            return response()->json([]);
        }

        // Search pathology tests
        $pathology = DB::table('hospital_settings_pathology')
            ->where('delete_status', 0)
            ->where(function ($query) use ($q) {
                $query->where('test', 'like', $q . '%')
                      ->orWhere('test', 'like', '%' . $q . '%');
            })
            ->limit(10)
            ->get(['id', DB::raw("test as test_name"), DB::raw("'pathology' as test_type")]);

        // Search radiology tests
        $radiology = DB::table('hospital_settings_radiology')
            ->where('delete_status', 0)
            ->where(function ($query) use ($q) {
                $query->where('test', 'like', $q . '%')
                      ->orWhere('test', 'like', '%' . $q . '%');
            })
            ->limit(10)
            ->get(['id', DB::raw("test as test_name"), DB::raw("'radiology' as test_type")]);

        return response()->json($pathology->merge($radiology));
    }

    /**
     * Upload a clinical document (image/PDF/camera capture).
     */
    public function uploadDocument(Request $request)
    {
        try {
            $request->validate([
                'file' => 'required|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:10240',
                'patient_id' => 'required|integer',
                'category' => 'required|string',
            ]);

            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $storedName = uniqid('doc_') . '_' . time() . '.' . $ext;

            // Store in public/uploads/patient_documents/
            $file->move(public_path('uploads/patient_documents'), $storedName);

            $fileType = in_array(strtolower($ext), ['jpg','jpeg','png','gif','webp']) ? 'image' : 'pdf';

            $doc = PatientDocument::create([
                'patient_id'    => $request->input('patient_id'),
                'appointment_id'=> $request->input('appointment_id'),
                'diagnosis_id'  => $request->input('diagnosis_id'),
                'uploaded_by'   => Auth::id(),
                'file_name'     => $storedName,
                'original_name' => $file->getClientOriginalName(),
                'file_type'     => $fileType,
                'category'      => $request->input('category', 'other'),
                'notes'         => $request->input('notes', ''),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Document uploaded successfully',
                'document' => $doc
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 422);
        }
    }

    /**
     * Soft-delete a patient document.
     */
    public function deleteDocument(Request $request)
    {
        $doc = PatientDocument::findOrFail($request->input('document_id'));
        $doc->update(['delete_status' => 1]);
        return response()->json(['status' => 'success', 'message' => 'Document removed']);
    }

    /**
     * Save lab/radiology test result.
     */
    public function saveTestResult(Request $request)
    {
        try {
            $test = PatientMedicalTest::findOrFail($request->input('test_id'));
            $test->update([
                'result_value'      => $request->input('result_value'),
                'result_unit'       => $request->input('result_unit'),
                'reference_range'   => $request->input('reference_range'),
                'interpretation'    => $request->input('interpretation'),
                'result_notes'      => $request->input('result_notes'),
                'result_date'       => $request->input('result_date', now()->toDateString()),
                'result_entered_by' => Auth::id(),
                'result_entered_at' => now(),
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Test result saved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 422);
        }
    }
}
