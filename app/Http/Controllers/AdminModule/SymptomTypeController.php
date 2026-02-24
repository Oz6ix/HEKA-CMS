<?php
namespace App\Http\Controllers\AdminModule;
use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\AppointmentBasicsDetail;
use App\Models\SymptomType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SymptomTypeController extends Controller
{
    public function __construct()
    {
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Appointment Manage";
        $this->page_heading = "Appointment Manage";
        $this->heading_icon = "fa-cogs";
        $this->page_info = ['url_prefix' => $this->url_prefix, 'page_title' => $this->page_title, 'page_heading' => $this->page_heading, 'heading_icon' => $this->heading_icon];
    }

    /* Page events */
    public function appointment_manage()
    {        
       
        generate_log('Appointment manage list accessed');
        return view('backend.admin_module.appointment_manage.appointment_manage')->with($this->page_info);
    }


    public function index()
    {

        $items = SymptomType::where('delete_status', 0)->orderBy('symptom', 'asc')->get();        
        generate_log('Symptom accessed');
        return view('backend.admin_module.symptom_type.index', compact('items'))->with($this->page_info);
    }

    public function show($id)
    {
        $item = SymptomType::findorFail($id)->toArray();
        generate_log('Symptom details accessed', $id);
        return view('backend.admin_module.symptom_type.show', compact('item'))->with($this->page_info);
    }

    public function create()
    {        
        return view('backend.admin_module.symptom_type.create')->with($this->page_info);
    }


    public function store(Request $request)
    {
        $data = $request->all();               
        $item = SymptomType::where('symptom',$data['symptom'])->where('delete_status',0)->count();
//dd($item);
        if ($item==0) {
            $validator = SymptomType::validate_add($data);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $new_record = SymptomType::create($data);
        } else
            return Redirect::back()->withInput($request->input())->with('error_message', 'A symptom with same symptom name already exists. Please use a different one.');
        generate_log('Symptom created', $new_record->id);
        return redirect($this->url_prefix . '/symptom_type')->with('message', 'symptom added.');
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
        $item = SymptomType::findorFail($id)->toArray();
        return view('backend.admin_module.symptom_type.edit', compact('item'))->with($this->page_info);
    }


    public function update(Request $request)
    {
        $data = $request->all(); 
        $id = $data['id'];
        $item = SymptomType::where('symptom',$data['symptom'])->where('delete_status',0)->where('id', '!=', $id)->count();
        if ($item==0) {
            $validator = SymptomType::validate_update($data, $id);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $record = SymptomType::findorfail($id);
            $record->update($data);
        } else
            return Redirect::back()->withInput($request->input())->with('error_message', 'A symptom with same symptom name already exists. Please use a different one.');
        generate_log('Symptom updated', $id);
        return redirect($this->url_prefix . '/symptom_type')->with('message', 'Symptom updated.');
    }


    public function destroy($id)
    {
        $items = AppointmentBasicsDetail::where('symptom_type_id', $id)->where('delete_status', 0)->count();
        if ($items > 0)
            return redirect($this->url_prefix . '/symptom_type')->with('warning_message', 'There are certain Appointments associated to this symptom. You can remove this symptom only once all the associated staffs are removed or their symptom is changed to a new one.');
            SymptomType::where('id', $id)->update(['delete_status' => 1]);
        generate_log('Symptom deleted', $id);
        return redirect($this->url_prefix . '/symptom_type')->with('message', 'Symptom deleted.');
    }



    public function destroy_multiple($ids)
    {
        if (!empty($ids)) {
            $ids_array = explode(',', $ids);
            foreach ($ids_array as $id) {
                if ($id > 0) {
                     $items = Appointment::where('symptom_type_id', $id)->where('delete_status', 0)->count();
                    if ($items > 0)
                        return redirect($this->url_prefix . '/symptom_type')->with('warning_message', 'There are certain appointments associated to this symptom. You can remove this symptom only once all the associated appointments are removed or their symptoms is changed to a new one.');
                        SymptomType::where('id', $id)->update(['delete_status' => 1]);
                }
            }
            generate_log('Symptoms deleted multiple', null, 'Deleted record ids: ' . $ids);
            return redirect($this->url_prefix . '/symptom_type')->with('message', 'Symptom deleted.');
        } else
            return redirect($this->url_prefix . '/symptom_type')->with('error_message', 'Please select at least one symptom.');
    }


    public function activate($id)
    {
        SymptomType::where('id', $id)->update(['status' => 1]);
        generate_log('Symptom activated', $id);
        return redirect($this->url_prefix . '/symptom_type')->with('message', 'Symptom activated.');
    }


    public function deactivate($id)
    {
        SymptomType::where('id', $id)->update(['status' => 0]);
        generate_log('Symptom deactivated', $id);
        return redirect($this->url_prefix . '/symptom_type')->with('message', 'Symptom deactivated.');
    }


    /* Custom methods */
    public function exists($symptom, $id = null)
    {
        if ($id == null)
            $items = SymptomType::all()->where('symptom', $symptom)->where('delete_status', 0);
        else
            $items = SymptomType::all()->where('symptom', $symptom)->where('id', '!=', $id)->where('delete_status', 0);
        return ($items->count() > 0) ? true : false;
    }


}
