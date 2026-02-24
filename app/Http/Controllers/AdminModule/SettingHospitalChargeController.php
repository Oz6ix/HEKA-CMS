<?php
namespace App\Http\Controllers\AdminModule;
use App\Http\Controllers\Controller;
use App\Models\HospitalChargeCategory;
use App\Models\HospitalCharge;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;




class SettingHospitalChargeController extends Controller
{
    public function __construct()
    {
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Hospital Charge";
        $this->page_heading = "Hospital Charge";
        $this->heading_icon = "fa-cogs";
        $this->page_info = ['url_prefix' => $this->url_prefix, 'page_title' => $this->page_title, 'page_heading' => $this->page_heading, 'heading_icon' => $this->heading_icon];
    }
    


    public function index()
    {
        $items = HospitalCharge::where('delete_status', 0)->with('hospital_charge_category')->orderBy('id', 'desc')->get();
    
        generate_log('Hospital charge accessed');
        return view('backend.admin_module.hospital_charge.index', compact('items'))->with($this->page_info);
    }

    public function show($id)
    {
        $item = HospitalCharge::findorFail($id)->toArray();
        generate_log('Hospital charge details accessed', $id);
        return view('backend.admin_module.hospital_charge.show', compact('item'))->with($this->page_info);
    }

    public function create()
    {      
        $hospital_charge_category = HospitalChargeCategory::where('delete_status', 0)
                                                ->orderBy('name', 'asc')
                                                ->get()
                                                ->toArray();

        // Generate next charge code using existing helper
        $code = generate_hospital_charge_code();

        return view('backend.admin_module.hospital_charge.create', compact('hospital_charge_category', 'code'))->with($this->page_info);
    }



    public function store(Request $request)
    {
        $data = $request->all();
        $data['code']=generate_hospital_charge_code();         
        $validator = HospitalCharge::validate_add($data);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
        }
        $new_record = HospitalCharge::create($data);
       
        generate_log('Hospital charge created', $new_record->id);
        return redirect($this->url_prefix . '/hospital_charges')->with('message', 'Hospital charge added.');
    }



    public function edit($id)
    {
        $item = HospitalCharge::with('hospital_charge_category')->findorFail($id)->toArray();
        $hospital_charge_category = HospitalChargeCategory::where('delete_status', 0)
                                                ->orderBy('name', 'asc')
                                                ->get()
                                                ->toArray();
        return view('backend.admin_module.hospital_charge.edit', compact('item','hospital_charge_category'))->with($this->page_info);
    }


    public function update(Request $request)
    {
        $data = $request->all(); 
        $id = $data['id'];        
        $validator = HospitalCharge::validate_update($data, $id);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
        }
        $record = HospitalCharge::findorfail($id);
        $record->update($data);        
        generate_log('Hospital charge updated', $id);
        return redirect($this->url_prefix . '/hospital_charges')->with('message', 'Hospital charge updated.');
    }


    public function destroy($id)
    {        
        HospitalCharge::where('id', $id)->update(['delete_status' => 1]);
        generate_log('Hospital charge deleted', $id);
        return redirect($this->url_prefix . '/hospital_charges')->with('message', 'Hospital charge deleted.');
    }



    public function destroy_multiple($ids)
    {
        if (!empty($ids)) {
            $ids_array = explode(',', $ids);
            foreach ($ids_array as $id) {
                if ($id > 0) {                     
                    HospitalCharge::where('id', $id)->update(['delete_status' => 1]);
                }
            }
            generate_log('Hospital charge deleted multiple', null, 'Deleted record ids: ' . $ids);
            return redirect($this->url_prefix . '/hospital_charges')->with('message', 'Hospital charge deleted.');
        } else
            return redirect($this->url_prefix . '/hospital_charges')->with('error_message', 'Please select at least one inventory item master.');
    }


    public function activate($id)
    {
        HospitalCharge::where('id', $id)->update(['status' => 1]);
        generate_log('Hospital charge activated', $id);
        return redirect($this->url_prefix . '/hospital_charges')->with('message', 'Hospital charge activated.');
    }


    public function deactivate($id)
    {
        HospitalCharge::where('id', $id)->update(['status' => 0]);
        generate_log('Hospital charge deactivated', $id);
        return redirect($this->url_prefix . '/hospital_charges')->with('message', 'Hospital charge deactivated.');
    }


    /* Custom methods */
    public function exists($name, $id = null)
    {
        if ($id == null)
            $items = HospitalCharge::all()->where('title', $name);
        else
            $items = HospitalCharge::all()->where('title', $name)->where('id', '!=', $id);
        return ($items->count() > 0) ? true : false;
    }

     /*********************  name duplicate check *******************************/
    public function ajax_duplicate_name($name) {   
        $item = HospitalCharge::all()->where('title',$name)->where('delete_status', 0);
        $item=collect($item)->sortBy('id')->toArray();        
        if(!empty($item)){
            return 1;
        }else{
            return 0;
        }
    }
    /*********************  name  duplicate check End*******************************/ 


}
