<?php
/**
 * Created By Anu Abraham
 * Created at : 23-07-2021
 * Modified At :00-00-2021
 * 
 */
namespace App\Http\Controllers\AdminModule;

use Illuminate\Support\Facades\Redirect;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\PatientDiagnosisService;
use App\Models\PatientDiagnosis;
use App\Models\PatientBriefNote;

/**
 * Class PatientDiagnosisController
 * @package App\Http\Controllers\AdminModule
 */
class PatientDiagnosisController extends Controller
{
    protected $patientDiagnosisService;

    /**
     * PatientDiagnosisController constructor.
     * 
     */
    public function __construct(PatientDiagnosisService $patientDiagnosisService)
    {
        $this->patientDiagnosisService = $patientDiagnosisService;
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Diagnosis";
        $this->page_heading = "Diagnosis";
        $this->heading_icon = "fa-user-friends";
        $this->page_info = ['url_prefix' => $this->url_prefix, 'page_title' => $this->page_title, 'page_heading' => $this->page_heading, 'heading_icon' => $this->heading_icon];
    }

    /**
     * This method is used for list an diagnosis
     * @return \Illuminate\Http\RedirectResponse
     */

    public function index()              
    {
    }
    
    /**
     * This method is used for list an diagnosis
     * @return \Illuminate\Http\RedirectResponse
     */
    public function list($id)              
    {
        $aid=$id;
        $items = $this->patientDiagnosisService->getDiagnosisByAppointmentId($id);
        $patient_details = \App\Models\Appointment::with('patient')->with('staff_doctor')->where('id', $id)->where('delete_status', 0)->get();

        generate_log('Diagnosis list accessed');
        return view('backend.admin_module.diagnosis.index', compact('items','patient_details','aid'))->with($this->page_info);
    }

    /**
     * This method is used for history an diagnosis
     * @return \Illuminate\Http\RedirectResponse
     */
    public function history($id)               
    {
        $aid=$id;
        $items = $this->patientDiagnosisService->getHistory($id);
        $patient_details = \App\Models\Appointment::with('patient')->with('staff_doctor')->where('patient_id', $id)->where('delete_status', 0)->get();
        
        generate_log('Diagnosis history accessed');
        return view('backend.admin_module.diagnosis.history', compact('items','patient_details','aid'))->with($this->page_info);
    }
    
    public function history_view($id)
    {
        $data = $this->patientDiagnosisService->getHistoryViewData($id);
        return view('backend.admin_module.diagnosis.history_view', $data)->with($this->page_info);
    }

    /**
     * This method is used for display an diagnosis details
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function show($id)
    {
        $data = $this->patientDiagnosisService->getShowData($id);
        return view('backend.admin_module.diagnosis.show', $data)->with($this->page_info);
    }

    /**
     * This method is used for create an diagnosis
     * @return \Illuminate\Http\RedirectResponse
     */
    public function create($id)
    {
        $data = $this->patientDiagnosisService->getCreateData($id);
        return view('backend.admin_module.diagnosis.create', $data)->with($this->page_info);
    }
    
    /**
     * This method is used for store an diagnosis details in diagnosis 
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        date_default_timezone_set('asia/yangon');
        $data = $request->except('_token','dummy');

        $validator = PatientDiagnosis::validate_add($data);
         if ($validator->fails()) {
            return response()->json(['validation'=>$validator->errors()->all()]);
        } 
        else{
            try {
                $patient_diagnosis = $this->patientDiagnosisService->createDiagnosis($request->all());
                return response()->json(['status'=>"success",'message'=>'Diagnosis details added','diagnosis_id'=>$patient_diagnosis->id]);
            } catch (\Exception $e) {
                return response()->json(['validation'=>[$e->getMessage()]]);
            }
        }
    }

    /**
     * This method is used for updating an diagnosis details
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update_brief_note(Request $request)
    {
        $data = $request->except('_token','dummy');
        $validator = PatientBriefNote::validate_add($data);
         if ($validator->fails()) {
            return response()->json(['validation'=>$validator->errors()->all()]);
        } 
        else{
            $this->patientDiagnosisService->updateBriefNote($request->all());
            return response()->json(['status'=>"success",'message'=>'Breif notes added']);
        }
    }

    /**
     * This method is used for edit an diagnosis
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit($id)
    {
        $data = $this->patientDiagnosisService->getEditData($id);
        return view('backend.admin_module.diagnosis.edit', $data)->with($this->page_info);
    }

    /**
     * This method is used for updating an diagnosis details
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function update(Request $request)
    {
        $data = $request->except('_token','dummy');
        $validator = PatientDiagnosis::validate_add($data);
    
        if ($validator->fails()) {
            return response()->json(['validation'=>$validator->errors()->all()]);
        } 
        else{
             try {
                $this->patientDiagnosisService->updateDiagnosis($request->diagnosis_id, $request->all());
                return response()->json(['status'=>"success",'message'=>'Diagnosis details updated','diagnosis_id'=>$request->diagnosis_id]);
            } catch (\Exception $e) {
                return response()->json(['validation'=>[$e->getMessage()]]);
            }
        }
    }
    
    /**
     * This method is used for deleting an diagnosis
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $this->patientDiagnosisService->deleteDiagnosis($id);
        generate_log('Diagnosis deleted', $id);
        return Redirect::back()->with('message', 'Diagnosis deleted.');
    }
    
    /**
     * This method is used for deleting set of  an diagnosis
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy_multiple($ids)
    {
        if (!empty($ids)) {
            $this->patientDiagnosisService->deleteMultipleDiagnoses($ids);
            generate_log('Patient deleted multiple', null, 'Deleted record ids: ' . $ids);
            return Redirect::back()->with('message', 'Selected Diagnosis deleted.');
        } else
            return Redirect::back()->with('error_message', 'Please select at least one diagnosis.');
    }

    /* Custom methods */
    public function ajax_medicine_list($text)
    {
        $output = $this->patientDiagnosisService->ajaxGetMedicineList($text);
        return response()->json(['status'=>"success",'message'=>'Diagnosis details updated','items'=>$output]);
    } 
    public function ajax_get_medicine_id($text)
    {
        $id = $this->patientDiagnosisService->ajaxGetMedicineId($text);
        return response()->json(['medicine_id'=>$id]);
    } 

    public function ajax_get_pathology_test_id($text)
    {
        $id = $this->patientDiagnosisService->ajaxGetPathologyTestId($text);
        return response()->json(['test_id'=>$id]);
    } 
    public function ajax_get_radiology_test_id($text)
    {
        $id = $this->patientDiagnosisService->ajaxGetRadiologyTestId($text);
        return response()->json(['test_id'=>$id]);
    } 

    public function ajax_get_consumable_id($text)
    {
        $id = $this->patientDiagnosisService->ajaxGetConsumableId($text);
        return response()->json(['consumable_id'=>$id]);
    } 

    public function change_status($status,$id = null)
    {
    } 
}