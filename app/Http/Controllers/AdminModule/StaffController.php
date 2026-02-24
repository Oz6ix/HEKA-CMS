<?php
namespace App\Http\Controllers\AdminModule;
use App\Http\Controllers\Controller;
use App\Services\StaffService;
use App\Models\StaffDepartment;
use App\Models\StaffDesignation;
use App\Models\StaffRole;
use App\Models\StaffSpecialist;
use App\Models\Staff;
use App\Models\BloodGroup;
use App\Models\UserGroup;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use File;

class StaffController extends Controller
{
    protected $staffService;

    public function __construct(StaffService $staffService)
    {
        $this->staffService = $staffService;
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Staff Manage";
        $this->page_heading = "Staff Manage";
        $this->heading_icon = "fa-cogs";
        $this->directory_staff = "staff";
        $this->page_info = ['url_prefix' => $this->url_prefix, 'page_title' => $this->page_title, 'page_heading' => $this->page_heading, 'heading_icon' => $this->heading_icon,'directory_staff' => $this->directory_staff];
    }

    public function index()
    {
        $items = $this->staffService->getAllStaff();        
        generate_log('Staff accessed');
        return view('backend.admin_module.staff.index', compact('items'))->with($this->page_info);
    }

    public function show($id)
    {
        $item = $this->staffService->getStaffById($id);
        if($item) {
             $item = $item->toArray();
        } else {
            abort(404);
        }                          
        generate_log('Staff details accessed', $id);
        return view('backend.admin_module.staff.show', compact('item'))->with($this->page_info);
    }

    public function create()
    {       
        $staff_department_item = StaffDepartment::where('status',1)->where('delete_status',0)->orderBy('department', 'asc')->get()->toArray();
        $staff_designation_item = StaffDesignation::where('status',1)->where('delete_status',0)->orderBy('designation', 'asc')->get()->toArray();
        $staff_role_item = StaffRole::where('status',1)->where('delete_status',0)->orderBy('role', 'asc')->get()->toArray();
        $staff_specialist_item = StaffSpecialist::where('status',1)->where('delete_status',0)->orderBy('specialist', 'asc')->get()->toArray();
        $blood_group_item = BloodGroup::where('status',1)->where('delete_status',0)->orderBy('blood_group', 'asc')->get()->toArray();
        $groups = UserGroup::where('status', 1)->where('delete_status',0)->where('id', '!=', 1)->orderBy('title', 'asc')->get()->toArray();
        
        $staff_code_pattern='100000';
        $staff_count = \App\Models\Staff::orderBy('id', 'desc')->limit(1)->first(); // Use model directly for simple query or add to service
        
        if(!empty($staff_count)){
            $staff_code = $staff_count->id + $staff_code_pattern+1; 
        }else{
            $staff_code = $staff_code_pattern+1;
        }       
        return view('backend.admin_module.staff.create',compact('staff_department_item','staff_designation_item','staff_role_item','staff_specialist_item','blood_group_item','staff_code','groups'))->with($this->page_info);
    }

    public function store(StoreStaffRequest $request)
    {
        $data = $request->validated();
        
        if ($this->staffService->checkDuplicateEmail($data['email'])) {
             return Redirect::back()->withInput($request->input())->with('error_message', 'A staff with same staff email address already exists. Please use a different one.');
        }

        if ($this->staffService->checkExists($data['staff_code'])) {
             return Redirect::back()->withInput($request->input())->with('error_message', 'A staff with same staff code already exists. Please use a different one.');
        }

        $data['permission_admin_access'] = $request->has('permission_admin_access');
        
        // Handle File Uploads (Moved to Controller or Service? Typically Service handles logic, but Request handles file retrieval)
        // For simplicity in refactor, we can prepare the data here.
        // Ideally, service should handle the actual storage logic.
        // I will keep specific file validation request logic here for now to avoid breaking complex validation flow, 
        // but storage calls should ideally move or use the helper wrapper.
        
        $directory = $this->directory_staff. '/' . trim($data['staff_code']);           
        make_directory($directory);
        
        if ($request->hasFile('document')) {
            $file = $request->file('document');
            if (verify_file_mime_type($file, 'special') && validate_file_size($file, '10485760 ')) {
                $data['document'] = upload_file($file, $directory);
                $data['document_file_type'] = pathinfo($data['document'], PATHINFO_EXTENSION);
            }
        }
        if ($request->hasFile('resume')) {
             $file = $request->file('resume');
             if (verify_file_mime_type($file, 'special') && validate_file_size($file, '10485760 ')) {
                 $data['resume'] = upload_file($file, $directory);
                 $data['resume_file_type'] = pathinfo($data['resume'], PATHINFO_EXTENSION);
             }
        }
        if ($request->hasFile('staff_image')) {
             $file = $request->file('staff_image');
             if (verify_file_mime_type($file, 'image')) {
                 $data['staff_image'] = upload_file($file, $directory);
                 $data['image_file_type'] = pathinfo($data['staff_image'], PATHINFO_EXTENSION);
                 $data['profile_photo_path'] = $data['staff_image'];
             }
        }

        $new_record = $this->staffService->createStaff($data);
        
        // Email sending logic can be moved to service or event, but keeping here or in service is fine.
        // Service handles user creation now.
        
        generate_log('Staff department created', $new_record->id);        
        return redirect($this->url_prefix . '/staffs')->with('message', 'Staff added.');
    }

    public function edit($id)
    {
        $staff_department_item = StaffDepartment::where('status',1)->where('delete_status',0)->orderBy('department', 'asc')->get()->toArray();
        $staff_designation_item = StaffDesignation::where('status',1)->where('delete_status',0)->orderBy('designation', 'asc')->get()->toArray();
        $staff_role_item =  StaffRole::where('status',1)->where('delete_status',0)->orderBy('role', 'asc')->get()->toArray();
        $staff_specialist_item = StaffSpecialist::where('status',1)->where('delete_status',0)->orderBy('specialist', 'asc')->get()->toArray();
        $blood_group_item = BloodGroup::where('status',1)->where('delete_status',0)->orderBy('blood_group', 'asc')->get()->toArray();
        $groups = UserGroup::where('status', 1)->where('delete_status',0)->where('id', '!=', 1)->orderBy('title', 'asc')->get()->toArray();
        
        $item = $this->staffService->getStaffById($id);
        if ($item) $item = $item->toArray();

        return view('backend.admin_module.staff.edit', compact('staff_department_item','staff_designation_item','staff_role_item','staff_specialist_item','blood_group_item','item','groups'))->with($this->page_info);
    }

    public function update(UpdateStaffRequest $request)
    {    
        $data = $request->validated();              
        $id = $data['id'];
        
        if ($this->staffService->checkExists($data['staff_code'], $id)) {
            return Redirect::back()->withInput($request->input())->with('error_message', 'A staff with same staff code already exists. Please use a different one.');
        }

        $data['permission_admin_access'] = $request->has('permission_admin_access') ? 1 : 0;
        
        $directory = $this->directory_staff. '/' . trim($data['staff_code']); 
        
        // Handle Files
        // Logic similar to create, updating data array
         if ($request->hasFile('document')) {
            $file = $request->file('document');
            if (verify_file_mime_type($file, 'special') && validate_file_size($file, '10485760 ')) {
                $data['document'] = upload_file($file, $directory);
                $data['document_file_type'] = pathinfo($data['document'], PATHINFO_EXTENSION);
                // Service handles specific document table update if needed, or we pass it
            }
        }
        if ($request->hasFile('resume')) {
             $file = $request->file('resume');
             if (verify_file_mime_type($file, 'special') && validate_file_size($file, '10485760 ')) {
                 $data['resume'] = upload_file($file, $directory);
                 $data['resume_file_type'] = pathinfo($data['resume'], PATHINFO_EXTENSION);
             }
        }
        if ($request->hasFile('staff_image')) {
             $file = $request->file('staff_image');
             if (verify_file_mime_type($file, 'image')) {
                 $data['staff_image'] = upload_file($file, $directory);
                 $data['image_file_type'] = pathinfo($data['staff_image'], PATHINFO_EXTENSION);
                 $data['profile_photo_path'] = $data['staff_image'];
             }
        }

        $this->staffService->updateStaff($id, $data);
        
        generate_log('Staff updated', $id);
        return redirect($this->url_prefix . '/staffs')->with('message', 'Staff updated.');
    }

    public function destroy($id)
    {
        $this->staffService->deleteStaff($id);
        generate_log('Staff deleted', $id);
        return redirect($this->url_prefix . '/staffs')->with('message', 'Staff deleted.');
    }

    public function destroy_multiple($ids)
    {
        if (!empty($ids)) {
            $ids_array = explode(',', $ids);
            $ids_array = array_filter($ids_array, function($value) { return $value > 0; });
            $this->staffService->deleteMultipleStaff($ids_array);
            generate_log('Staff deleted multiple', null, 'Deleted record ids: ' . $ids);
            return redirect($this->url_prefix . '/staffs')->with('message', 'Staff deleted.');
        } else
            return redirect($this->url_prefix . '/staffs')->with('error_message', 'Please select at least one staff.');
    }

    public function activate($id)
    {
        $this->staffService->updateStaffStatus($id, 1);
        generate_log('Staff activated', $id);
        return redirect($this->url_prefix . '/staffs')->with('message', 'Staff activated.');
    }

    public function deactivate($id)
    {
        $this->staffService->updateStaffStatus($id, 0);
        generate_log('Staff deactivated', $id);
        return redirect($this->url_prefix . '/staffs')->with('message', 'Staff deactivated.');
    }

    public function ajax_duplicate_email($email) {   
        $isDuplicate = $this->staffService->checkDuplicateEmail($email);
        return $isDuplicate ? 1 : 0;
    }
}
