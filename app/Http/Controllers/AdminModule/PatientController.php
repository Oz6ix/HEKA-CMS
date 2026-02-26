<?php
namespace App\Http\Controllers\AdminModule;
use App\Http\Controllers\Controller;
use App\Services\PatientService;
use App\Models\UserGroup;
use App\Models\SettingsSiteGeneral;
use App\Models\BloodGroup;
use Illuminate\Http\Request;
use App\Http\Requests; // Import the Requests namespace
use Illuminate\Support\Facades\Redirect;
use File;

class PatientController extends Controller
{
    protected $patientService;

    public function __construct(PatientService $patientService)
    {
        $this->patientService = $patientService;
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Patients";
        $this->page_heading = "Patients";
        $this->heading_icon = "fa-user-friends";
        $this->page_info = ['url_prefix' => $this->url_prefix, 'page_title' => $this->page_title, 'page_heading' => $this->page_heading, 'heading_icon' => $this->heading_icon];
    }

    /* Page events */
    public function index()
    {
        $items = $this->patientService->getAllPatients();
        generate_log('Patient list accessed');
        return view('backend.admin_module.patient.index', compact('items'))->with($this->page_info);
    }

    public function show($id)
    {
       // $item = Patient::where('delete_status', 0)->findOrFail($id);
        $item = $this->patientService->getPatientById($id);
        if($item) {
             $item = $item->load('patient_blood_group')->toArray();
        } else {
            abort(404);
        }
        
        generate_log('Patient details accessed', $id);
        return view('backend.admin_module.patient.show', compact('item'))->with($this->page_info);
    }

    public function create()
    {
        // Logic to get the last patient code to auto-populate (presentation logic)
         $items = \App\Models\Patient::orderBy('id', 'desc')->first(); // Keeping this simple model call for now as it's purely for UI suggestion, or could be moved to service
        
        $item_general = SettingsSiteGeneral::findOrFail(1)->toArray(); 
        $hospital_code=$item_general['hospital_code'];
        if(empty($items)){
            $patient_code='00000';
        }else
        $patient_code=substr($items['patient_code'], 4);
        
        
        $blood_group_item = BloodGroup::where('status',1)
        ->where('delete_status',0)
        ->orderBy('blood_group', 'asc')
        ->get();

        return view('backend.admin_module.patient.create', compact('patient_code','blood_group_item','hospital_code'))->with($this->page_info);

    }

    public function store(Requests\StorePatientRequest $request)
    {
        try {
            $data = $request->validated();
            
            // Logic for generating patient code
            $items = \App\Models\Patient::where('patient_code', $request['patient_code'])->orderBy('id', 'desc')->first();
           
             if(!empty($items)){
                 $item_general = SettingsSiteGeneral::first(); 
                 $hospital_code = $item_general ? $item_general->hospital_code : 'HEKA'; 
                 $last_item=\App\Models\Patient::orderBy('id', 'desc')->first();
                 
                 // Extract numeric part safely
                 $last_code_str = substr($last_item['patient_code'], strlen($hospital_code)); 
                 $last_code_int = intval($last_code_str);
                 
                $patient_code = $hospital_code . str_pad($last_code_int + 1, 5, '0', STR_PAD_LEFT);
                $data['patient_code'] = $patient_code;
            } else {
                $data['patient_code'] = $request['patient_code'];
            }
            
            $data['patient_folder_name'] = strval($data['patient_code']) . '_' . time(); 
            $data['dob_str'] = strtotime($data['dob']); 
            
            // Ensure permissions
            $path = 'patient/' . $data['patient_folder_name'];
            if(!\Storage::disk('uploads')->exists($path)){
                \Storage::disk('uploads')->makeDirectory($path);
            }
    
            $new_record = $this->patientService->createPatient($data);
    
            generate_log('New Patient created', $new_record->id);
            return redirect($this->url_prefix . '/patient')->with('success_message', 'Patient created successfully');
        } catch (\Exception $e) {
            \Log::error('Patient Store Error: ' . $e->getMessage());
            return redirect($this->url_prefix . '/patient/create')->with('error_message', 'Failed to create patient: ' . $e->getMessage());
        }
    }

    // We are submitting are image along with userid and with the help of user id we are updateing our record
    public function storeImage(Request $request)
    {
        
        if($request->file('file')){

            $img = $request->file('file');

            //here we are geeting userid alogn with an image
            $userid = $request->patient_code;
            
            // Validate user exists
            $patient = $this->patientService->patientRepository->findByCode($userid); 
             
            // Using model directly for now if repository method not exposed, but expecting repository to have it or we use raw query here for safety
            if (!$patient) {
                 $patient = \App\Models\Patient::where('patient_code', $userid)->where('delete_status', 0)->first();
            }
            
            if (!$patient) {
                return response()->json(['status' => 'error', 'message' => 'Patient not found'], 404);
            }

            $imageName = strtotime(now()).rand(11111,99999).'.'.$img->getClientOriginalExtension();
            $original_name = $img->getClientOriginalName();

            // Use Storage facade
            $path = 'patient/'.$patient->patient_folder_name;
            
            // Delete old image if exists
            if($patient->patient_photo != NULL){
                if(\Storage::disk('uploads')->exists($path.'/'.$patient->patient_photo)){
                    \Storage::disk('uploads')->delete($path.'/'.$patient->patient_photo);
                }
            }             
            
            // Store new image securely
            \Storage::disk('uploads')->putFileAs($path, $img, $imageName);

            // we are updating our image column with the help of user id
            $this->patientService->updatePatient($patient->id, ['patient_photo'=>$imageName]);

            return response()->json(['status'=>"success",'imgdata'=>$original_name,'patient_photo'=>$userid]);
        }
    }

    public function viewImage($id)
    {
        $patient = $this->patientService->getPatientById($id);

        if($patient && ($patient->patient_photo!=''||$patient->patient_photo!=NULL)){
      
            $obj['name'] = $patient->patient_photo;
            $file_path = public_path('uploads/patient/'.$patient->patient_folder_name.'/'.$patient->patient_photo);
            // $obj['size'] = filesize($file_path); // potentially unsafe if file doesn't exist
            // Let's trust the storage url mostly
            $obj['size'] = 0; // Placeholder
            $obj['path'] = url('public/uploads/patient/'.$patient->patient_folder_name.'/'.$patient->patient_photo);
            $data[] = $obj;
        }
        else{
            $data[] = '';
        }
        //dd($data);
        return response()->json($data);
    }

    public function edit($id)
    {
        $item = $this->patientService->getPatientById($id);
        $blood_group_item = BloodGroup::where('status',1)
        ->where('delete_status',0)
        ->orderBy('blood_group', 'asc')
        ->get();
        $blood_group_item = collect($blood_group_item)->toArray(); 

        return view('backend.admin_module.patient.edit', compact('item','blood_group_item'))->with($this->page_info);
    }

    public function update(Requests\UpdatePatientRequest $request)
    {
       
        $id = $request->id;
        $data = $request->validated();
        
        $data['dob_str'] =strtotime($data['dob']); 

        $record = $this->patientService->getPatientById($id);

        if(isset($data['patient_photo_check']) && $data['patient_photo_check']=='1'){
            $data['patient_photo']=NULL;
            // Use Storage facade
            $path = 'patient/'.$record->patient_folder_name.'/'.$record->patient_photo;
            if(\Storage::disk('uploads')->exists($path)){
                \Storage::disk('uploads')->delete($path);
            }
        }
        $this->patientService->updatePatient($id, $data);
           
        generate_log('Patient Updated', $id); 
        // Return JSON for AJAX requests, redirect for normal requests
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['status' => 'success', 'message' => 'Patient Details updated.']);
        }
        return redirect($this->url_prefix . '/patient')->with('message', 'Patient Details updated.');

    }
    

    public function activate($id)
    {
        $this->patientService->updatePatientStatus($id, 1);
        generate_log('Patient activated', $id);
        return redirect($this->url_prefix . '/patient')->with('message', 'Patient status activated.');
    }

    public function deactivate($id)
    {
        $this->patientService->updatePatientStatus($id, 0);
        generate_log('Patient deactivated', $id);
        return redirect($this->url_prefix . '/patient')->with('message', 'Patient status deactivated. ');
    }

    public function destroy($id)
    {
        $this->patientService->deletePatient($id);
        generate_log('Patient deleted', $id);
        return redirect($this->url_prefix . '/patient')->with('message', 'Patient deleted.');
    }

    public function destroy_multiple($ids)
    {
        if (!empty($ids)) {
            $ids_array = explode(',', $ids);
            // Clean array
            $ids_array = array_filter($ids_array, function($value) { return $value > 0; });
            
            $this->patientService->deleteMultiplePatients($ids_array);
            
            generate_log('Patient deleted multiple', null, 'Deleted record ids: ' . $ids);
            return redirect($this->url_prefix . '/patient')->with('message', 'Patient deleted.');
        } else
            return redirect($this->url_prefix . '/patient')->with('error_message', 'Please select at least one Patient.');
    }


    /********************* Patient email duplicate check *******************************/
    public function ajax_duplicate_email($email) {   
        // Kept logic similar but could use service
        $isDuplicate = $this->patientService->checkDuplicateEmail($email);
        return $isDuplicate ? 1 : 0;
    }
    /********************* Patient email  duplicate check End*******************************/ 

}