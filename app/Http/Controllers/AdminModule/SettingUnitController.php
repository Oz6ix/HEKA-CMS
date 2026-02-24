<?php
namespace App\Http\Controllers\AdminModule;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Units;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;



class SettingUnitController extends Controller
{
    public function __construct()
    {
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Settings";
        $this->page_heading = "Settings";
        $this->heading_icon = "fa-cogs";
        $this->page_info = ['url_prefix' => $this->url_prefix, 'page_title' => $this->page_title, 'page_heading' => $this->page_heading, 'heading_icon' => $this->heading_icon];
    }

    


    public function index()
    {

        $items = Units::where('delete_status', 0)->orderBy('unit', 'desc')->get();        
        generate_log('Units accessed');
        return view('backend.admin_module.setting_unit.index', compact('items'))->with($this->page_info);
    }

    public function show($id)
    {
        $item = Units::findorFail($id)->toArray();
        generate_log('Unit details accessed', $id);
        return view('backend.admin_module.setting_unit.show', compact('item'))->with($this->page_info);
    }

    public function create()
    {        
        return view('backend.admin_module.setting_unit.create')->with($this->page_info);
    }


    public function store(Request $request)
    {
        $data = $request->all();        
        if (!$this->exists($data['unit'])) {
            $validator = Units::validate_add($data);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $new_record = Units::create($data);
        } else
            return Redirect::back()->withInput($request->input())->with('error_message', 'A unit with same unit name already exists. Please use a different one.');
        generate_log('Unit created', $new_record->id);
        return redirect($this->url_prefix . '/setting_units')->with('message', 'Unit added.');
    }



    public function edit($id)
    {
        $item = Units::findorFail($id)->toArray();
        return view('backend.admin_module.setting_unit.edit', compact('item'))->with($this->page_info);
    }


    public function update(Request $request)
    {
        $data = $request->all(); 
        $id = $data['id'];
        if (!$this->exists($data['unit'], $id)) {
            $validator = Units::validate_update($data, $id);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $record = Units::findorfail($id);
            $record->update($data);
        } else
            return Redirect::back()->withInput($request->input())->with('error_message', 'A unit with same unit name already exists. Please use a different one.');
        generate_log('Unit updated', $id);
        return redirect($this->url_prefix . '/setting_units')->with('message', 'Unit updated.');
    }


    public function destroy($id)
    {
        //$items = Staff::where('role_id', $id)->where('delete_status', 0)->count();
        /*if ($items > 0)
            return redirect($this->url_prefix . '/setting_units')->with('warning_message', 'There are certain staffs associated to this role. You can remove this role only once all the associated staffs are removed or their role is changed to a new one.');*/
        Units::where('id', $id)->update(['delete_status' => 1]);
        generate_log('Unit deleted', $id);
        return redirect($this->url_prefix . '/setting_units')->with('message', 'Unit deleted.');
    }



    public function destroy_multiple($ids)
    {
        if (!empty($ids)) {
            $ids_array = explode(',', $ids);
            foreach ($ids_array as $id) {
                if ($id > 0) {
                    /* $items = Staff::where('role_id', $id)->where('delete_status', 0)->count();
                    if ($items > 0)
                        return redirect($this->url_prefix . '/setting_units')->with('warning_message', 'There are certain staffs associated to this role. You can remove this role only once all the associated staffs are removed or their role is changed to a new one.');*/
                    Units::where('id', $id)->update(['delete_status' => 1]);
                }
            }
            generate_log('Unit deleted multiple', null, 'Deleted record ids: ' . $ids);
            return redirect($this->url_prefix . '/setting_units')->with('message', 'Unit deleted.');
        } else
            return redirect($this->url_prefix . '/setting_units')->with('error_message', 'Please select at least one role.');
    }


    public function activate($id)
    {
        Units::where('id', $id)->update(['status' => 1]);
        generate_log('Units activated', $id);
        return redirect($this->url_prefix . '/setting_units')->with('message', 'Unit activated.');
    }


    public function deactivate($id)
    {
        Units::where('id', $id)->update(['status' => 0]);
        generate_log('Unit deactivated', $id);
        return redirect($this->url_prefix . '/setting_units')->with('message', 'Unit deactivated.');
    }


    /* Custom methods */
    public function exists($name, $id = null)
    {
        if ($id == null)
            $items = Units::all()->where('unit', $name);
        else
            $items = Units::all()->where('unit', $name)->where('id', '!=', $id);
        return ($items->count() > 0) ? true : false;
    }


      public function ajax_duplicate_name($name) {   
        $items = Units::all()->where('unit',$name)->where('delete_status', 0);
        $items=collect($items)->sortBy('id')->toArray();        
        if(!empty($items)){
            return 1;
        }else{
            return 0;
        }
    }


}
