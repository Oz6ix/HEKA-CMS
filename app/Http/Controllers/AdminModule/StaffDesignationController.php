<?php
namespace App\Http\Controllers\AdminModule;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StaffDesignation;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;



class StaffDesignationController extends Controller  
{
    public function __construct()
    {
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Staff Designation";
        $this->page_heading = "Staff Designation";
        $this->heading_icon = "fa-cogs";
        $this->page_info = ['url_prefix' => $this->url_prefix, 'page_title' => $this->page_title, 'page_heading' => $this->page_heading, 'heading_icon' => $this->heading_icon];
    }

    public function index()
    {

        $items = StaffDesignation::where('delete_status', 0)->orderBy('designation', 'asc')->get();        
        generate_log('Staff designation accessed');
        return view('backend.admin_module.staff_designation.index', compact('items'))->with($this->page_info);
    }

    public function show($id)
    {
        $item = StaffDesignation::findorFail($id)->toArray();
        generate_log('Staff designation details accessed', $id);
        return view('backend.admin_module.staff_designation.show', compact('item'))->with($this->page_info);
    }

    public function create()
    {        
        return view('backend.admin_module.staff_designation.create')->with($this->page_info);
    }


    public function store(Request $request)
    {
        $data = $request->all();        
        if (!$this->exists($data['designation'])) {
            $validator = StaffDesignation::validate_add($data);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $new_record = StaffDesignation::create($data);
        } else
            return Redirect::back()->withInput($request->input())->with('error_message', 'A designation with same designation name already exists. Please use a different one.');
        generate_log('Staff designation created', $new_record->id);
        return redirect($this->url_prefix . '/staff_designations')->with('message', 'Designation added.');
    }



    public function edit($id)
    {
        $item = StaffDesignation::findorFail($id)->toArray();
        return view('backend.admin_module.staff_designation.edit', compact('item'))->with($this->page_info);
    }


    public function update(Request $request)
    {
        $data = $request->all(); 
        $id = $data['id'];
        if (!$this->exists($data['designation'], $id)) {
            $validator = StaffDesignation::validate_update($data, $id);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $record = StaffDesignation::findorfail($id);
            $record->update($data);
        } else
            return Redirect::back()->withInput($request->input())->with('error_message', 'A designation with same designation name already exists. Please use a different one.');
        generate_log('Staff designation updated', $id);
        return redirect($this->url_prefix . '/staff_designations')->with('message', 'Staff designation updated.');
    }


    public function destroy($id)
    {
        $items = Staff::where('designation_id', $id)->where('delete_status', 0)->count();
        if ($items > 0)
            return redirect($this->url_prefix . '/staff_designations')->with('warning_message', 'There are certain staffs associated to this designation. You can remove this designation only once all the associated staffs are removed or their designation is changed to a new one.');
        StaffDesignation::where('id', $id)->update(['delete_status' => 1]);
        generate_log('Staff designation deleted', $id);
        return redirect($this->url_prefix . '/staff_designations')->with('message', 'Staff designation deleted.');
    }



    public function destroy_multiple($ids)
    {
        if (!empty($ids)) {
            $ids_array = explode(',', $ids);
            foreach ($ids_array as $id) {
                if ($id > 0) {
                     $items = Staff::where('designation_id', $id)->where('delete_status', 0)->count();
                    if ($items > 0)
                        return redirect($this->url_prefix . '/staff_designations')->with('warning_message', 'There are certain staffs associated to this designation. You can remove this designation only once all the associated staffs are removed or their designation is changed to a new one.');
                    StaffDesignation::where('id', $id)->update(['delete_status' => 1]);
                }
            }
            generate_log('Staff designation deleted multiple', null, 'Deleted record ids: ' . $ids);
            return redirect($this->url_prefix . '/staff_designations')->with('message', 'Staff designation deleted.');
        } else
            return redirect($this->url_prefix . '/staff_designations')->with('error_message', 'Please select at least one designation.');
    }


    public function activate($id)
    {
        StaffDesignation::where('id', $id)->update(['status' => 1]);
        generate_log('Staff designation activated', $id);
        return redirect($this->url_prefix . '/staff_designations')->with('message', 'Staff designation activated.');
    }


    public function deactivate($id)
    {
        StaffDesignation::where('id', $id)->update(['status' => 0]);
        generate_log('Staff designation deactivated', $id);
        return redirect($this->url_prefix . '/staff_designations')->with('message', 'Staff designation deactivated.');
    }


    /* Custom methods */
    public function exists($designation, $id = null)
    {
        if ($id == null)
            $items = StaffDesignation::all()->where('designation', $designation)->where('delete_status', 0);
        else
            $items = StaffDesignation::all()->where('designation', $designation)->where('id', '!=', $id)->where('delete_status', 0);
        return ($items->count() > 0) ? true : false;
    }


}
