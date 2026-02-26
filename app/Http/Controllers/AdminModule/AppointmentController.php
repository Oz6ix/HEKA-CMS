<?php
/**
 * Created By Anu Abraham
 * Created at : 21-07-2021
 * Modified At :00-00-2021
 * 
 */
namespace App\Http\Controllers\AdminModule;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AppointmentService;
use App\Models\Appointment;

/**
 * Class AppointmentController
 * @package App\Http\Controllers\AdminModule
 */
class AppointmentController extends Controller
{
    protected $appointmentService;

    /**
     * AppointmentController constructor.
     * 
     */
    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Appointment";
        $this->page_heading = "Appointment";
        $this->heading_icon = "fa-user-friends";
        $this->page_info = ['url_prefix' => $this->url_prefix, 'page_title' => $this->page_title, 'page_heading' => $this->page_heading, 'heading_icon' => $this->heading_icon];
    }

        /**
     * This method is used for list an appointments
     * @return \Illuminate\View\Factory|\Illuminate\View\View
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $items = $this->appointmentService->getAllAppointments();
        
        $dependencies = $this->appointmentService->getDependencyData();
        $doctor_item = $dependencies['doctors'];
        $patient_item = $dependencies['patients'];
        
        // Needed for dropdowns even on index
        $appointment_item = $items; 

        generate_log('Appointment list accessed');
        return view('backend.admin_module.appointment.index', compact('appointment_item','items','patient_item','doctor_item'))->with($this->page_info);
    }

    public function calendarEvents(Request $request)
    {
        $items = $this->appointmentService->getAllAppointments();

        $events = $items->map(function ($item) {
            $statusColors = [
                1 => '#22c55e', // Open - green
                2 => '#ef4444', // Cancelled - red
                0 => '#6b7280', // Closed - gray
            ];
            return [
                'id' => $item->id,
                'title' => ($item->patient->name ?? 'Unknown') . ' — ' . ($item->staff_doctor->name ?? 'N/A'),
                'start' => $item->appointment_date,
                'backgroundColor' => $statusColors[$item->status] ?? '#3b82f6',
                'borderColor' => $statusColors[$item->status] ?? '#3b82f6',
                'extendedProps' => [
                    'patient' => $item->patient->name ?? 'Unknown',
                    'doctor' => $item->staff_doctor->name ?? 'N/A',
                    'case_number' => $item->case_number,
                    'status' => $item->status,
                    'view_url' => url($this->url_prefix . '/appointment/view/' . $item->id),
                ],
            ];
        });

        return response()->json($events);
    }
    
    /**
     * This method is used for display an appointment details
     * @param  \Illuminate\Http\Request  $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */

    public function appointment_search(Request $request)
    {
        $selected_doctor_id=$request['doctor_id'];
        $selected_patient_id=$request['patient_id'];
        $selected_case_number=$request['case_number'];
        $selected_appointment_date=$request['appointment_date'];

        $dependencies = $this->appointmentService->getDependencyData();
        $doctor_item = $dependencies['doctors'];
        $patient_item = $dependencies['patients'];
        
        // Usually index view expects all appointments for the dropdown as well
        $appointment_item = $this->appointmentService->getAllAppointments();
        
        $items = $this->appointmentService->searchAppointments($request->all());

        generate_log('Appointment search accessed');

        return view('backend.admin_module.appointment.index',compact('appointment_item','items','doctor_item','selected_appointment_date','selected_case_number','selected_doctor_id','selected_patient_id','patient_item'))->with($this->page_info);
    }  
    /**
     * This method is used for display an appointment details
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */

    public function show($id)
    {
        $item = $this->appointmentService->getAppointmentById($id)->toArray();
        $item_basic = $this->appointmentService->getBasicDetails($id);
        
        generate_log('Appointment details accessed', $id);
        return view('backend.admin_module.appointment.show', compact('item','item_basic'))->with($this->page_info);
    }
    /**
     * This method is used for create an appointment
     * @return \Illuminate\View\Factory|\Illuminate\View\View
     * @return \Illuminate\Http\Response
     */
    public function create($id=0)
    {
        $case_number = $this->appointmentService->getNextCaseNumber();
        
        $dependencies = $this->appointmentService->getDependencyData();
        $patient_item = $dependencies['patients'];
        $symptom_item = $dependencies['symptoms'];
        $casualty_item = $dependencies['casualties'];
        $tpa_item = $dependencies['tpas'];
        $doctor_item = $dependencies['doctors'];

        return view('backend.admin_module.appointment.create', compact('id','patient_item','case_number','symptom_item','casualty_item','tpa_item','doctor_item'))->with($this->page_info);
    }
    /**
     * This method is used for store an appointment details in Appointment and AppointmentBasicsDetail
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        $data = $request->all(); 
        
        // Ensure defaults for optional dropdowns to prevent internal server error
        $data['tpa_id'] = $request->input('tpa_id') ?? 0;
        $data['casualty_id'] = $request->input('casualty_id') ?? 0;
        $data['symptom_type_id'] = $request->input('symptom_type_id') ?? 0;
        $data['hospital_id'] = 1; // Default hospital ID

        $validator = Appointment::validate_add($data);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
        }
        
        $new_record = $this->appointmentService->createAppointment($data);

        // Handle patient report uploads
        $reportFiles = array_merge(
            $request->file('patient_reports', []),
            $request->file('patient_camera_reports', [])
        );
        if (!empty($reportFiles)) {
            $category = $request->input('report_category', 'external_lab');
            foreach ($reportFiles as $file) {
                if ($file && $file->isValid()) {
                    $ext = $file->getClientOriginalExtension();
                    $storedName = uniqid('doc_') . '_' . time() . '.' . $ext;
                    $file->move(public_path('uploads/patient_documents'), $storedName);
                    $fileType = in_array(strtolower($ext), ['jpg','jpeg','png','gif','webp']) ? 'image' : 'pdf';
                    \App\Models\PatientDocument::create([
                        'patient_id'     => $new_record->patient_id,
                        'appointment_id' => $new_record->id,
                        'uploaded_by'    => \Illuminate\Support\Facades\Auth::id(),
                        'file_name'      => $storedName,
                        'original_name'  => $file->getClientOriginalName(),
                        'file_type'      => $fileType,
                        'category'       => $category,
                    ]);
                }
            }
        }

        generate_log('Appointment created', $new_record->id);
        return redirect($this->url_prefix . '/appointment')->with('message', 'Appointment added.');

    }

    /**
     * This method is used for edit an appointment
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        $dependencies = $this->appointmentService->getDependencyData();
        $patient_item = $dependencies['patients'];
        $symptom_item = $dependencies['symptoms'];
        $casualty_item = $dependencies['casualties'];
        $tpa_item = $dependencies['tpas'];
        $doctor_item = $dependencies['doctors'];
        
        $item = $this->appointmentService->getAppointmentById($id)->toArray(); 
        $item_basic = $this->appointmentService->getBasicDetails($id)->toArray(); 

        // Fetch existing documents for this appointment
        $existingDocs = \App\Models\PatientDocument::where('appointment_id', $id)
            ->where('delete_status', 0)
            ->orderBy('created_at', 'desc')
            ->get();
                        
        return view('backend.admin_module.appointment.edit', compact('item','item_basic','patient_item','symptom_item','casualty_item','tpa_item','doctor_item','existingDocs'))->with($this->page_info);
    }
    /**
     * This method is used for updating an appointment details
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        $data = $request->all(); 

        // Ensure defaults for optional dropdowns to prevent NOT NULL constraint error
        $data['tpa_id'] = $request->input('tpa_id') ?? 0;
        $data['casualty_id'] = $request->input('casualty_id') ?? 0;
        $data['symptom_type_id'] = $request->input('symptom_type_id') ?? 0;

        $validator = Appointment::validate_update($data,$request->id);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
        }
        
        $this->appointmentService->updateAppointment($request->id, $data);

        // Handle new patient report uploads on update
        $reportFiles = array_merge(
            $request->file('patient_reports', []),
            $request->file('patient_camera_reports', [])
        );
        if (!empty($reportFiles)) {
            $category = $request->input('report_category', 'external_lab');
            foreach ($reportFiles as $file) {
                if ($file && $file->isValid()) {
                    $ext = $file->getClientOriginalExtension();
                    $storedName = uniqid('doc_') . '_' . time() . '.' . $ext;
                    $file->move(public_path('uploads/patient_documents'), $storedName);
                    $fileType = in_array(strtolower($ext), ['jpg','jpeg','png','gif','webp']) ? 'image' : 'pdf';
                    \App\Models\PatientDocument::create([
                        'patient_id'     => $data['patient_id'],
                        'appointment_id' => $request->id,
                        'uploaded_by'    => \Illuminate\Support\Facades\Auth::id(),
                        'file_name'      => $storedName,
                        'original_name'  => $file->getClientOriginalName(),
                        'file_type'      => $fileType,
                        'category'       => $category,
                    ]);
                }
            }
        }
        
        generate_log('Appointment Updated', $request->id);
        return redirect($this->url_prefix . '/appointment')->with('message', 'Appointment Updated.');
    }

    /**
     * This method is used for deleting an appointment
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */

    public function destroy($id)
    {
        $this->appointmentService->deleteAppointment($id);
        generate_log('Appointment deleted', $id);
        return redirect($this->url_prefix . '/appointment')->with('message', 'Appointment deleted.');
    }
    /**
     * This method is used for deleting set of  an appointments
     * @param int[] $ids An array of integer objects.
     * @return \Illuminate\Http\RedirectResponse
     */

    public function destroy_multiple($ids)
    {
        if (!empty($ids)) {
            $this->appointmentService->deleteMultipleAppointments($ids);
            
            generate_log('Appointment deleted multiple', null, 'Deleted record ids: ' . $ids);
            return redirect($this->url_prefix . '/appointment')->with('message', 'Appointment deleted.');
        } else
            return redirect($this->url_prefix . '/appointment')->with('error_message', 'Please select at least one Appointment.');
    }

    /* Custom methods */
	/**
     * Activate/Deactivate the specified resource in storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function change_status($status,$id = null)
    {
        $this->appointmentService->changeStatus($id, $status);
        generate_log('Status Changed', $id);
        return redirect($this->url_prefix . '/appointment')->with('message', 'Status changed.');
    } 

    public function getApptPrintData($id = null)
    {
        $data = $this->appointmentService->getPrintData($id);
        
        $hospital_info = $data['hospital_info'];
        $appt_data = $data['appt_data'];
        $patient_data = $data['patient_data'];
        $staff_data = $data['staff_data'];
        $prescription_data = $data['prescription_data'];
        $vital_data = $data['vital_data'];
        $cheif_com_data = $data['cheif_com_data'];
        $medical_test = $data['medical_test'];
        $age = $data['age'];
        $gender = $data['gender'];

        $clinicName = $hospital_info->hospital_name ?? 'Your Clinic Name';
        $clinicAddress = $hospital_info->hospital_address ?? '';
        $clinicPhone = $hospital_info->contact_phone ?? '';
        $patientName = $patient_data->name ?? 'N/A';
        $doctorName = $staff_data->name ?? 'N/A';
        $caseNo = $appt_data->case_number ?? '';
        $apptDate = $appt_data->appointment_date ?? '';

        $html = "
        <style>
            .print-page { font-family: 'Segoe UI', Arial, sans-serif; color: #1a1a1a; max-width: 800px; margin: 0 auto; }
            .print-header { display: flex; align-items: center; border-bottom: 3px solid #2563eb; padding-bottom: 16px; margin-bottom: 20px; }
            .print-logo { width: 70px; height: 70px; border-radius: 12px; background: linear-gradient(135deg, #2563eb, #1d4ed8); display: flex; align-items: center; justify-content: center; margin-right: 16px; flex-shrink: 0; }
            .print-logo span { color: white; font-size: 28px; font-weight: 700; }
            .clinic-name { font-size: 22px; font-weight: 700; color: #1e293b; margin: 0; }
            .clinic-detail { font-size: 12px; color: #64748b; margin: 2px 0; }
            .section-title { font-size: 13px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #2563eb; border-bottom: 1px solid #e2e8f0; padding-bottom: 6px; margin: 20px 0 10px 0; }
            .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 4px 24px; }
            .info-row { display: flex; padding: 4px 0; }
            .info-label { color: #64748b; font-size: 12px; min-width: 120px; }
            .info-value { font-size: 12px; font-weight: 600; color: #1e293b; }
            .vitals-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 8px; }
            .vital-box { border: 1px solid #e2e8f0; border-radius: 8px; padding: 8px 10px; text-align: center; }
            .vital-label { font-size: 9px; color: #94a3b8; text-transform: uppercase; letter-spacing: 0.5px; }
            .vital-val { font-size: 14px; font-weight: 700; color: #1e293b; margin-top: 2px; }
            .rx-table { width: 100%; border-collapse: collapse; font-size: 12px; }
            .rx-table th { background: #f1f5f9; text-align: left; padding: 8px 10px; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; color: #475569; border-bottom: 2px solid #e2e8f0; }
            .rx-table td { padding: 7px 10px; border-bottom: 1px solid #f1f5f9; }
            .footer-line { border-top: 2px solid #e2e8f0; margin-top: 30px; padding-top: 12px; display: flex; justify-content: space-between; font-size: 11px; color: #94a3b8; }
        </style>
        <div class='print-page'>
            <!-- Header -->
            <div class='print-header'>
                <div class='print-logo'><span>+</span></div>
                <div>
                    <p class='clinic-name'>$clinicName</p>
                    <p class='clinic-detail'>$clinicAddress</p>
                    <p class='clinic-detail'>Phone: $clinicPhone</p>
                </div>
            </div>

            <!-- Patient Info -->
            <div class='section-title'>Patient Information</div>
            <div class='info-grid'>
                <div class='info-row'><span class='info-label'>Patient Name</span><span class='info-value'>$patientName</span></div>
                <div class='info-row'><span class='info-label'>Case No</span><span class='info-value'>$caseNo</span></div>
                <div class='info-row'><span class='info-label'>Gender / Age</span><span class='info-value'>$gender ($age)</span></div>
                <div class='info-row'><span class='info-label'>Visit Date</span><span class='info-value'>$apptDate</span></div>
                <div class='info-row'><span class='info-label'>Consultant</span><span class='info-value'>Dr. $doctorName</span></div>
            </div>";

        // Vitals
        if (!empty($vital_data) && count($vital_data) > 0) {
            $v = $vital_data[0] ?? null;
            if ($v) {
                $html .= "
            <div class='section-title'>Vitals</div>
            <div class='vitals-grid'>
                <div class='vital-box'><div class='vital-label'>Height</div><div class='vital-val'>" . ($v->height ?? '—') . "</div></div>
                <div class='vital-box'><div class='vital-label'>Weight</div><div class='vital-val'>" . ($v->weight ?? '—') . "</div></div>
                <div class='vital-box'><div class='vital-label'>BP</div><div class='vital-val'>" . ($v->systolic_bp ?? '—') . "/" . ($v->diastolic_bp ?? '—') . "</div></div>
                <div class='vital-box'><div class='vital-label'>Pulse</div><div class='vital-val'>" . ($v->pulse ?? '—') . "</div></div>
                <div class='vital-box'><div class='vital-label'>Temp</div><div class='vital-val'>" . ($v->temperature ?? '—') . "</div></div>
                <div class='vital-box'><div class='vital-label'>SPO2</div><div class='vital-val'>" . ($v->spo2 ?? '—') . "%</div></div>
                <div class='vital-box'><div class='vital-label'>Resp</div><div class='vital-val'>" . ($v->respiration ?? '—') . "</div></div>
                <div class='vital-box'><div class='vital-label'>RBS</div><div class='vital-val'>" . ($v->rbs ?? '—') . "</div></div>
            </div>";
            }
        }

        // Chief Complaints
        if (!empty($cheif_com_data) && count($cheif_com_data) > 0) {
            $html .= "<div class='section-title'>Chief Complaints</div><ul style='margin:0;padding-left:18px;font-size:12px;'>";
            foreach ($cheif_com_data as $cheif) {
                $style = ($cheif->cheif_complaint_status == 1) ? "color:#dc2626;font-weight:600;" : "";
                $html .= "<li style='$style margin-bottom:3px;'>" . e($cheif->cheif_complaint) . "</li>";
            }
            $html .= "</ul>";
        }

        // Prescriptions
        if (!empty($prescription_data) && count($prescription_data) > 0) {
            $html .= "
            <div class='section-title'>Prescription (Rx)</div>
            <table class='rx-table'>
                <thead><tr><th>#</th><th>Drug Name</th><th>Qty</th><th>Unit</th><th>Frequency</th><th>Days</th></tr></thead>
                <tbody>";
            $rx = 1;
            foreach ($prescription_data as $p) {
                $unit = '';
                $freq = '';
                if (!empty($p->unit)) { $u = json_decode($p->unit); $unit = $u->unit ?? ''; }
                if (!empty($p->frequency)) { $f = json_decode($p->frequency); $freq = $f->frequency ?? ''; }
                $html .= "<tr><td>$rx</td><td><strong>" . e($p->drug_name) . "</strong></td><td>" . e($p->quantity) . "</td><td>$unit</td><td>$freq</td><td>" . e($p->no_of_days) . "</td></tr>";
                $rx++;
            }
            $html .= "</tbody></table>";
        }

        // Medical Tests
        if (!empty($medical_test) && count($medical_test) > 0) {
            $html .= "<div class='section-title'>Medical Tests Ordered</div><ul style='margin:0;padding-left:18px;font-size:12px;'>";
            foreach ($medical_test as $mt) {
                $html .= "<li style='margin-bottom:3px;'>" . e($mt->test_name) . "</li>";
            }
            $html .= "</ul>";
        }

        // Footer
        $html .= "
            <div class='footer-line'>
                <span>Printed on: " . date('d M Y, h:i A') . "</span>
                <span>Consultant: Dr. $doctorName</span>
            </div>
            <div style='text-align:center;margin-top:40px;'>
                <div style='display:inline-block;border-top:1px solid #94a3b8;padding-top:4px;min-width:200px;'>
                    <span style='font-size:11px;color:#64748b;'>Doctor's Signature</span>
                </div>
            </div>
        </div>";

        return $html;
    }
}