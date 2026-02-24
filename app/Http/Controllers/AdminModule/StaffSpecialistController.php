<?php
namespace App\Http\Controllers\AdminModule;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\StaffSpecialist;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;



class StaffSpecialistController extends Controller
{
    public function __construct()
    {
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Staff Manage";
        $this->page_heading = "Staff Manage";
        $this->heading_icon = "fa-cogs";
        $this->page_info = ['url_prefix' => $this->url_prefix, 'page_title' => $this->page_title, 'page_heading' => $this->page_heading, 'heading_icon' => $this->heading_icon];
    }

    public function index()
    {

        $items = StaffSpecialist::where('delete_status', 0)->orderBy('specialist', 'asc')->get();        
        generate_log('Staff specialist accessed');
        return view('backend.admin_module.staff_specialist.index', compact('items'))->with($this->page_info);
    }

    public function show($id)
    {
        $item = StaffSpecialist::findorFail($id)->toArray();
        generate_log('Staff specialist details accessed', $id);
        return view('backend.admin_module.staff_specialist.show', compact('item'))->with($this->page_info);
    }

    public function create()
    {        
        return view('backend.admin_module.staff_specialist.create')->with($this->page_info);
    }


    public function store(Request $request)
    {
        $data = $request->all();        
        if (!$this->exists($data['specialist'])) {
            $validator = StaffSpecialist::validate_add($data);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $new_record = StaffSpecialist::create($data);
        } else
            return Redirect::back()->withInput($request->input())->with('error_message', 'A specialist with same specialist name already exists. Please use a different one.');
        generate_log('Staff specialist created', $new_record->id);
        return redirect($this->url_prefix . '/staff_specialists')->with('message', 'specialist added.');
    }



    public function edit($id)
    {
        $item = StaffSpecialist::findorFail($id)->toArray();
        return view('backend.admin_module.staff_specialist.edit', compact('item'))->with($this->page_info);
    }


    public function update(Request $request)
    {
        $data = $request->all(); 
        $id = $data['id'];
        if (!$this->exists($data['specialist'], $id)) {
            $validator = StaffSpecialist::validate_update($data, $id);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $record = StaffSpecialist::findorfail($id);
            $record->update($data);
        } else
            return Redirect::back()->withInput($request->input())->with('error_message', 'A specialist with same specialist name already exists. Please use a different one.');
        generate_log('Staff specialist updated', $id);
        return redirect($this->url_prefix . '/staff_specialists')->with('message', 'Staff specialist updated.');
    }


    public function destroy($id)
    {
        $items = Staff::where('specialist_id', $id)->where('delete_status', 0)->count();
        if ($items > 0)
            return redirect($this->url_prefix . '/staff_specialists')->with('warning_message', 'There are certain staffs associated to this specialist. You can remove this specialist only once all the associated staffs are removed or their specialist is changed to a new one.');
        StaffSpecialist::where('id', $id)->update(['delete_status' => 1]);
        generate_log('Staff specialist deleted', $id);
        return redirect($this->url_prefix . '/staff_specialists')->with('message', 'Staff specialist deleted.');
    }



    public function destroy_multiple($ids)
    {
        if (!empty($ids)) {
            $ids_array = explode(',', $ids);
            foreach ($ids_array as $id) {
                if ($id > 0) {
                     $items = Staff::where('specialist_id', $id)->where('delete_status', 0)->count();
                    if ($items > 0)
                        return redirect($this->url_prefix . '/staff_specialists')->with('warning_message', 'There are certain staffs associated to this specialist. You can remove this specialist only once all the associated staffs are removed or their specialist is changed to a new one.');
                    StaffSpecialist::where('id', $id)->update(['delete_status' => 1]);
                }
            }
            generate_log('Staff specialist deleted multiple', null, 'Deleted record ids: ' . $ids);
            return redirect($this->url_prefix . '/staff_specialists')->with('message', 'Staff specialist deleted.');
        } else
            return redirect($this->url_prefix . '/staff_specialists')->with('error_message', 'Please select at least one specialist.');
    }


    public function activate($id)
    {
        StaffSpecialist::where('id', $id)->update(['status' => 1]);
        generate_log('Staff specialist activated', $id);
        return redirect($this->url_prefix . '/staff_specialists')->with('message', 'Staff specialist activated.');
    }


    public function deactivate($id)
    {
        StaffSpecialist::where('id', $id)->update(['status' => 0]);
        generate_log('Staff specialist deactivated', $id);
        return redirect($this->url_prefix . '/staff_specialists')->with('message', 'Staff specialist deactivated.');
    }


    /* Custom methods */
    public function exists($specialist, $id = null)
    {
        if ($id == null)
            $items = StaffSpecialist::all()->where('specialist', $specialist)->where('delete_status', 0);
        else
            $items = StaffSpecialist::all()->where('specialist', $specialist)->where('id', '!=', $id)->where('delete_status', 0);
        return ($items->count() > 0) ? true : false;
    }


}
