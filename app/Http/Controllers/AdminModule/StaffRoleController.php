<?php
namespace App\Http\Controllers\AdminModule;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StaffRole;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;



class StaffRoleController extends Controller
{
    public function __construct()
    {
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Staff Role";
        $this->page_heading = "Staff Role";
        $this->heading_icon = "fa-cogs";
        $this->page_info = ['url_prefix' => $this->url_prefix, 'page_title' => $this->page_title, 'page_heading' => $this->page_heading, 'heading_icon' => $this->heading_icon];
    }

    


    public function index()
    {

        $items = StaffRole::where('delete_status', 0)->orderBy('role', 'asc')->get();        
        generate_log('Staff role accessed');
        return view('backend.admin_module.staff_role.index', compact('items'))->with($this->page_info);
    }

    public function show($id)
    {
        $item = StaffRole::findorFail($id)->toArray();
        generate_log('Staff role details accessed', $id);
        return view('backend.admin_module.staff_role.show', compact('item'))->with($this->page_info);
    }

    public function create()
    {        
        return view('backend.admin_module.staff_role.create')->with($this->page_info);
    }


    public function store(Request $request)
    {
        $data = $request->all();        
        if (!$this->exists($data['role'])) {
            $validator = StaffRole::validate_add($data);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $new_record = StaffRole::create($data);
        } else
            return Redirect::back()->withInput($request->input())->with('error_message', 'A role with same role name already exists. Please use a different one.');
        generate_log('Staff role created', $new_record->id);
        return redirect($this->url_prefix . '/staff_roles')->with('message', 'Role added.');
    }



    public function edit($id)
    {
        $item = StaffRole::findorFail($id)->toArray();
        return view('backend.admin_module.staff_role.edit', compact('item'))->with($this->page_info);
    }


    public function update(Request $request)
    {
        $data = $request->all(); 
        $id = $data['id'];
        if (!$this->exists($data['role'], $id)) {
            $validator = StaffRole::validate_update($data, $id);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $record = StaffRole::findorfail($id);
            $record->update($data);
        } else
            return Redirect::back()->withInput($request->input())->with('error_message', 'A role with same role name already exists. Please use a different one.');
        generate_log('Staff role updated', $id);
        return redirect($this->url_prefix . '/staff_roles')->with('message', 'Staff role updated.');
    }


    public function destroy($id)
    {
        $items = Staff::where('role_id', $id)->where('delete_status', 0)->count();
        if ($items > 0)
            return redirect($this->url_prefix . '/staff_roles')->with('warning_message', 'There are certain staffs associated to this role. You can remove this role only once all the associated staffs are removed or their role is changed to a new one.');
        StaffRole::where('id', $id)->update(['delete_status' => 1]);
        generate_log('Staff role deleted', $id);
        return redirect($this->url_prefix . '/staff_roles')->with('message', 'Staff role deleted.');
    }



    public function destroy_multiple($ids)
    {
        if (!empty($ids)) {
            $ids_array = explode(',', $ids);
            foreach ($ids_array as $id) {
                if ($id > 0) {
                     $items = Staff::where('role_id', $id)->where('delete_status', 0)->count();
                    if ($items > 0)
                        return redirect($this->url_prefix . '/staff_roles')->with('warning_message', 'There are certain staffs associated to this role. You can remove this role only once all the associated staffs are removed or their role is changed to a new one.');
                    StaffRole::where('id', $id)->update(['delete_status' => 1]);
                }
            }
            generate_log('Staff role deleted multiple', null, 'Deleted record ids: ' . $ids);
            return redirect($this->url_prefix . '/staff_roles')->with('message', 'Staff role deleted.');
        } else
            return redirect($this->url_prefix . '/staff_roles')->with('error_message', 'Please select at least one role.');
    }


    public function activate($id)
    {
        StaffRole::where('id', $id)->update(['status' => 1]);
        generate_log('Staff role activated', $id);
        return redirect($this->url_prefix . '/staff_roles')->with('message', 'Staff role activated.');
    }


    public function deactivate($id)
    {
        StaffRole::where('id', $id)->update(['status' => 0]);
        generate_log('Staff role deactivated', $id);
        return redirect($this->url_prefix . '/staff_roles')->with('message', 'Staff role deactivated.');
    }


    /* Custom methods */
    public function exists($role, $id = null)
    {
        if ($id == null)
            $items = StaffRole::all()->where('role', $role)->where('delete_status', 0);
        else
            $items = StaffRole::all()->where('role', $role)->where('id', '!=', $id)->where('delete_status', 0);
        return ($items->count() > 0) ? true : false;
    }


}
