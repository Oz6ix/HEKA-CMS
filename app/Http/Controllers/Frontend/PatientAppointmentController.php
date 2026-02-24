<?php
/**
 * Created By Anu Abraham
 * Created at : 25-11-2021
 * Modified At :00-00-2021
 * 
 */
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\AppointmentBasicsDetail;
use App\Models\PatientBriefNote;
use App\Models\Customer;
use App\Models\UserGroup;
use App\Models\BloodGroup;
use App\Models\SettingsSiteGeneral;
use Auth;
use Session;
use Hash;
use App\Models\Appointment;
use App\Models\Tpa;
use App\Models\Casualty;
use App\Models\Patient;
use App\Models\Staff;
use App\Models\PatientDiagnosis;
use App\Models\PatientPrescription;
use App\Models\Frequency;
use App\Models\MedicalConsumableUsed;
use App\Models\PatientMedicalTest;
use App\Models\PatientDiagnosisReport;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Redirect;


/**
 * Class AppointmentController
 * @package App\Http\Controllers\AdminModule
 */
class PatientAppointmentController extends Controller
{
    /**
     * AppointmentController constructor.
     * 
     */
    public function __construct()
    {
        $this->url_prefix = \Config::get('app.app_route_customer_prefix');
        $this->page_title = "Appointment";
        $this->page_heading = "Appointment";
        $this->heading_icon = "fa-user-friends";
        $this->page_info = ['url_prefix' => $this->url_prefix, 'page_title' => $this->page_title, 'page_heading' => $this->page_heading, 'heading_icon' => $this->heading_icon];
    }

        /**
     * This method is used for list an appointments
     * @return \Illuminate\Http\RedirectResponse
     */

    public function index()
    {

        $Authuser = Auth::guard('blogger')->user();    
        //dd($Authuser);     
        if(!empty($Authuser)){
            $items = Appointment::orderBy('id', 'desc')->first();
            if(empty($items)){
                $case_number='10000';
            }else
            $case_number=$items['case_number']+1;
            /* $patient_item = Patient::where('id',$Authuser->id)
                                            ->where('status',1)
                                            ->where('delete_status',0)
                                            ->orderBy('patient_code', 'asc')
                                            ->get(); */
    
            $tpa_item = Tpa::where('status',1)
                                            ->where('delete_status',0)
                                            ->orderBy('id', 'asc')
                                            ->get();
            $doctor_item = Staff::select('name','id')
                                            ->where('status',1)
                                            ->where('delete_status',0)
                                            ->whereIn('designation_id',array(1,2))
                                            ->orderBy('name', 'asc')
                                            ->get();
    


            $this->page_info = ['page_title' => 'Patient Appointment'];
            return view('frontend.appointment.appointment', compact('case_number','tpa_item','doctor_item'))->with($this->page_info);
         }else{
            Session::flash('alert-warning', 'Please login.');
            return redirect()->route('patient_login');
        }

    }
    
        /**
     * This method is used for store an appointment details in Appointment and AppointmentBasicsDetail
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function book_appointment_store(Request $request)
    {
        $Authuser = Auth::guard('blogger')->user();    
        if(!empty($Authuser)){
        $data = $request->all(); 
        $data['patient_id'] =$Authuser->id; 
        $data['appointment_date_str'] =strtotime($data['appointment_date']); 
       // $data['appointment_date_str'] = date('d-m-Y', strtotime($data['appointment_date']));          
            $validator = Appointment::validate_add($data);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            else 
                $new_record = Appointment::create($data);
                $data['appointment_id']=$new_record->id;
                $new_record_basic = AppointmentBasicsDetail::create($data);
                $new_record_brief_note = PatientBriefNote::create($data);

        generate_log('Appointment created', $new_record->id);
        return redirect($this->url_prefix . '/booked_appointment_list')->with('message', 'Appointment added.');
        }
    }


            /**
     * This method is used for list an appointments
     * @return \Illuminate\Http\RedirectResponse
     */

    public function booked_appointment_list()
    {
        //dd(Auth::guard('blogger')->user()->id);
        $items = Appointment::with('patient')
                            ->with('staff_doctor')
                            ->where('patient_id', Auth::guard('blogger')->user()->id)
                            ->where('delete_status', 0)
                            ->orderBy('id', 'desc')
                            ->get();
        //dd($items);
        generate_log('Appointment list accessed');
        return view('frontend.booking.index', compact('items'))->with($this->page_info);
    }

        /**
     * This method is used for display an appointment details
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */

    public function booked_appointment_show($id)
    {
        $item = Appointment::with('patient')
                            ->with('staff_doctor')
                            ->with('casualty')
                            ->with('tpa')
                            ->where('delete_status', 0)
                            ->findorFail($id)
                            ->toArray();
        $item_basic = AppointmentBasicsDetail::with('symptom_type')
                            ->where('delete_status', 0)
                            ->findorFail($id)
                            ->toArray();



        //dd($item_basic);
        generate_log('Appointment details accessed', $id);
        return view('frontend.booking.show', compact('item','item_basic'))->with($this->page_info);
    }
        /**
     * This method is used for display an appointment details
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */

    public function booked_appointment_cancel($id)
    {
        Appointment::where('id', $id)->update(['delete_status' => 1]);
        AppointmentBasicsDetail::where('appointment_id', $id)->update(['delete_status' => 1]);
        $items = Appointment::with('patient')
        ->with('staff_doctor')
        ->where('patient_id', Auth::guard('blogger')->user()->id)
        ->where('delete_status', 0)
        ->orderBy('id', 'desc')
        ->get();
        generate_log('Appointment Cancelled');
        return redirect($this->url_prefix . '/booked_appointment_list')->with('message', 'Appointment Cancelled.');
    }



        /**
     * This method is used for list an diagnosis
     * @return \Illuminate\Http\RedirectResponse
     */

    public function patient_diagnosis_list($id)              
    {
        $items = PatientDiagnosis::with('treatment')->where('appointment_id', $id)->where('delete_status', 0)->orderBy('id', 'desc')->get();
        $patient_details = Appointment::with('patient')->with('staff_doctor')->where('id', $id)->where('delete_status', 0)->get();

        //dd($items);
        generate_log('Customer Diagnosis list accessed');
        return view('frontend.diagnosis.index', compact('items','patient_details','id'))->with($this->page_info);
    }

        /**
     * This method is used for display an diagnosis details
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */

    public function patient_diagnosis_list_view($id)
    {
        $item_diagnosis = PatientDiagnosis::with('treatment')->find($id);
        $appointment_id=$item_diagnosis->appointment_id;
        $item_prescription = PatientPrescription::with('unit')->with('frequency')->where('diagnosis_id', $id)->where('delete_status', 0)->get(); 
        //dd($item_prescription);
        $item_medical_consumable = MedicalConsumableUsed::with('unit')->with('medical_consumable.inventorymaster')->where('diagnosis_id', $id)->where('delete_status', 0)->get(); 
        $item_medical_test = PatientMedicalTest::with('center')->where('diagnosis_id', $id)->where('delete_status', 0)->get(); 
        $items = PatientDiagnosis::where('appointment_id', $appointment_id)->where('delete_status', 0)->orderBy('id', 'desc')->get();
        $patient_details = Appointment::with('patient')->with('staff_doctor')->where('id', $appointment_id)->where('delete_status', 0)->get();
        $item_basic = AppointmentBasicsDetail::with('symptom_type')->where('appointment_id', $appointment_id)->where('delete_status', 0)->get(); 
        $item_brief_note = PatientBriefNote::where('appointment_id', $appointment_id)->where('delete_status', 0)->get(); 
       

         return view('frontend.diagnosis.show', compact('id','item_diagnosis','item_prescription','item_medical_consumable','item_medical_test','item_basic','item_brief_note','items','patient_details','appointment_id'))->with($this->page_info);
        
    }
        /**
     * This method is used for display an diagnosis details
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */

    public function list_reports($id)
    {
         $item_reports = PatientDiagnosisReport::where('diagnosis_id',$id)->where('delete_status', 0)->get(); 
         return response()->json($item_reports);
       
    }
        /**
     * This method is used for store an appointment details in Appointment and AppointmentBasicsDetail
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function upload_reports(Request $request)
    {
        $Authuser = Auth::guard('blogger')->user();  
        if(!empty($Authuser)){
        $data = $request->all(); 
            /* Removed files updation */
            $reports= PatientDiagnosisReport::where('diagnosis_id',$request->diagnosis_id)->where('delete_status', 0)->get(); 
            if(count($reports)>0){
                if(!empty($request->doc)){
                    PatientDiagnosisReport::where('diagnosis_id', $request->diagnosis_id)->whereNotIn('id', $request->doc)->update(['delete_status' => 1]);
                }
                else{
                    PatientDiagnosisReport::where('diagnosis_id', $request->diagnosis_id)->update(['delete_status' => 1]);
                }
            }
            /*! Removed files updation */
            if ($request->hasFile('report_file')){
                $directory = 'patient/'.$Authuser->patient_folder_name.'/reports';
                
                // Ensure directory exists using Storage facade
                if(!\Storage::disk('uploads')->exists($directory)){
                    \Storage::disk('uploads')->makeDirectory($directory);
                }
                   
                $file = $request->file('report_file');
                if (verify_file_mime_type($file, 'special')) {
                    if (validate_file_size($file, '10485760 ')) {
                        $imageName = strtotime(now()).rand(11111,99999).'.'.$file->getClientOriginalExtension();
                        
                        // Use Storage facade for file upload
                        \Storage::disk('uploads')->putFileAs($directory, $file, $imageName);
        
                        $data['report_name'] = $Authuser->patient_folder_name.'/reports/'.$imageName;
                       //$ext = pathinfo($data['document'], PATHINFO_EXTENSION);
                        //$data['document_file_type'] = $ext;PatientDiagnosisReport
                    } else
                        return redirect($this->url_prefix.'/patient_diagnosis_list/'.$request->appointment_id)->with('error_message', 'Please upload less than 10 mb in size for document.')->with($this->page_info);
                } else
                    return redirect($this->url_prefix .'/patient_diagnosis_list/'.$request->appointment_id)->with('error_message', 'Please upload a valid document file.')->with($this->page_info);
                
                $new_record = PatientDiagnosisReport::create($data);
            }

        generate_log('Diagnosis report created/Updated');
        return redirect($this->url_prefix . '/patient_diagnosis_list/'.$request->appointment_id)->with('message', 'Report submitted.');
        }
    }









    


    







}