<?php
namespace App\Http\Controllers\AdminModule;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Tpa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;



class TpaController extends Controller
{
    public function __construct()
    {
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Tpa Manage";
        $this->page_heading = "Tpa Manage";
        $this->heading_icon = "fa-cogs";
        $this->page_info = ['url_prefix' => $this->url_prefix, 'page_title' => $this->page_title, 'page_heading' => $this->page_heading, 'heading_icon' => $this->heading_icon];
    }

    /* Page events */
/*     public function appointment_manage()
    {        
       
        generate_log('Appointment manage list accessed');
        return view('backend.admin_module.appointment_manage.appointment_manage')->with($this->page_info);
    }
 */

    public function index()
    {

        $items = Tpa::where('delete_status', 0)->orderBy('tpa', 'asc')->get();        
        generate_log('Tpa accessed');
        return view('backend.admin_module.tpa.index', compact('items'))->with($this->page_info);
    }

    public function show($id)
    {
        $item = Tpa::findorFail($id)->toArray();
        generate_log('Tpa details accessed', $id);
        return view('backend.admin_module.tpa.show', compact('item'))->with($this->page_info);
    }

    public function create()
    {        
        return view('backend.admin_module.tpa.create')->with($this->page_info);
    }


    public function store(Request $request)
    {
        $data = $request->all();               
        $item = Tpa::where('tpa',$data['tpa'])->where('delete_status',0)->count();
//dd($item);
        if ($item==0) {
            $validator = Tpa::validate_add($data);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $new_record = Tpa::create($data);
        } else
            return Redirect::back()->withInput($request->input())->with('error_message', 'A Tpa with same Tpa name already exists. Please use a different one.');
        generate_log('Tpa created', $new_record->id);
        return redirect($this->url_prefix . '/tpa')->with('message', 'Tpa added.');
    }

    public function edit($id)
    {
        $item = Tpa::findorFail($id)->toArray();
        return view('backend.admin_module.tpa.edit', compact('item'))->with($this->page_info);
    }


    public function update(Request $request)
    {
        $data = $request->all(); 
        $id = $data['id'];
        $item = Tpa::where('tpa',$data['tpa'])->where('delete_status',0)->where('id', '!=', $id)->count();
        if ($item==0) {
            $validator = Tpa::validate_update($data, $id);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $record = Tpa::findorfail($id);
            $record->update($data);
        } else
            return Redirect:: back()->withInput($request->input())->with('error_message', 'A casualty with same casualty name already exists. Please use a different one.');
        generate_log('Tpa updated', $id);
        return redirect($this->url_prefix . '/tpa')->with('message', 'Tpa updated.');
    }


    public function destroy($id)
    {
        $items = Appointment::where('tpa_id', $id)->where('delete_status', 0)->count();
        if ($items > 0)
            return redirect($this->url_prefix . '/tpa')->with('warning_message', 'There are certain Appointments associated to this casualty. You can remove this casualty only once all the associated staffs are removed or their casualty is changed to a new one.');
            Tpa::where('id', $id)->update(['delete_status' => 1]);
        generate_log('Tpa deleted', $id);
        return redirect($this->url_prefix . '/tpa')->with('message', 'Tpa deleted.');
    }



    public function destroy_multiple($ids)
    {
        if (!empty($ids)) {
            $ids_array = explode(',', $ids);
            foreach ($ids_array as $id) {
                if ($id > 0) {
                     $items = Appointment::where('tpa_id', $id)->where('delete_status', 0)->count();
                    if ($items > 0)
                        return redirect($this->url_prefix . '/tpa')->with('warning_message', 'There are certain appointments associated to this casualty. You can remove this casualty only once all the associated appointments are removed or their casualty is changed to a new one.');
                        Tpa::where('id', $id)->update(['delete_status' => 1]);
                }
            }
            generate_log('Tpa deleted multiple', null, 'Deleted record ids: ' . $ids);
            return redirect($this->url_prefix . '/tpa')->with('message', 'Tpa deleted.');
        } else
            return redirect($this->url_prefix . '/tpa')->with('error_message', 'Please select at least one casualty.');
    }


    public function activate($id)
    {
        Tpa::where('id', $id)->update(['status' => 1]);
        generate_log('Tpa activated', $id);
        return redirect($this->url_prefix . '/tpa')->with('message', 'Tpa activated.');
    }


    public function deactivate($id)
    {
        Tpa::where('id', $id)->update(['status' => 0]);
        generate_log('Tpa deactivated', $id);
        return redirect($this->url_prefix . '/tpa')->with('message', 'Tpa deactivated.');
    }


    /* Custom methods */
    public function exists($tpa, $id = null)
    {
        if ($id == null)
            $items = Tpa::all()->where('tpa', $tpa);
        else
            $items = Tpa::all()->where('tpa', $tpa)->where('id', '!=', $id);
        return ($items->count() > 0) ? true : false;
    }


}
