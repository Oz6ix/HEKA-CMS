<?php
namespace App\Http\Controllers\AdminModule;

use App\Models\MedicalCertificate;
use App\Models\Patient;
use App\Models\Staff;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MedicalCertificateController extends Controller
{
    public function __construct()
    {
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Medical Certificates";
        $this->page_heading = "Medical Certificates";
        $this->heading_icon = "fa-file-medical";
        $this->page_info = [
            'url_prefix' => $this->url_prefix,
            'page_title' => $this->page_title,
            'page_heading' => $this->page_heading,
            'heading_icon' => $this->heading_icon
        ];
    }

    public function index()
    {
        $certificates = MedicalCertificate::where('delete_status', 0)
            ->with('patient', 'doctor')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('backend.admin_module.medical_certificates.index', compact('certificates'))
            ->with($this->page_info);
    }

    public function create()
    {
        $patients = Patient::where('delete_status', 0)->orderBy('name')->get();
        $doctors = Staff::where('delete_status', 0)->where('status', 1)->orderBy('name')->get();

        return view('backend.admin_module.medical_certificates.create', compact('patients', 'doctors'))
            ->with($this->page_info);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|integer',
            'doctor_id' => 'required|integer',
            'type' => 'required|string|in:fitness,sick_leave,medical,custom',
            'purpose' => 'nullable|string|max:500',
            'issue_date' => 'required|date',
            'valid_from' => 'nullable|date',
            'valid_to' => 'nullable|date',
            'findings' => 'nullable|string|max:2000',
            'recommendations' => 'nullable|string|max:2000',
            'restrictions' => 'nullable|string|max:2000',
            'is_fit' => 'nullable|boolean',
        ]);

        $data['certificate_no'] = 'MC-' . date('Ymd') . '-' . str_pad(
            MedicalCertificate::whereDate('created_at', today())->count() + 1, 3, '0', STR_PAD_LEFT
        );
        $data['created_by'] = auth()->id();
        $data['is_fit'] = $request->has('is_fit');

        $cert = MedicalCertificate::create($data);

        generate_log('Medical certificate created', $cert->id);
        return redirect($this->url_prefix . '/medical_certificates/print/' . $cert->id)
            ->with('message', 'Certificate created.');
    }

    public function print($id)
    {
        $certificate = MedicalCertificate::with('patient', 'doctor')->findOrFail($id);
        $settings = \App\Models\SettingsSiteGeneral::first();

        return view('backend.admin_module.medical_certificates.print', compact('certificate', 'settings'))
            ->with($this->page_info);
    }

    public function destroy($id)
    {
        MedicalCertificate::where('id', $id)->update(['delete_status' => 1]);
        generate_log('Medical certificate deleted', $id);
        return redirect($this->url_prefix . '/medical_certificates')->with('message', 'Certificate deleted.');
    }
}
