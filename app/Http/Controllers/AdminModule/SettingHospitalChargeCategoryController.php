<?php
namespace App\Http\Controllers\AdminModule;
use App\Http\Controllers\Controller;
use App\Models\HospitalChargeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;



class SettingHospitalChargeCategoryController extends Controller
{
    public function __construct()
    {
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Hospital Charge Category";
        $this->page_heading = "Hospital Charge Category";
        $this->heading_icon = "fa-cogs";
        $this->page_info = ['url_prefix' => $this->url_prefix, 'page_title' => $this->page_title, 'page_heading' => $this->page_heading, 'heading_icon' => $this->heading_icon];

    }
    


    public function index()
    {
        $items = HospitalChargeCategory::where('delete_status', 0)->with('subcategory')->orderBy('id', 'desc')->get(); 
        generate_log('Hospital charge category accessed');
        return view('backend.admin_module.hospital_charge_category.index', compact('items'))->with($this->page_info);
    }

    public function show($id)
    {
        $item = HospitalChargeCategory::findorFail($id)->toArray();
        generate_log('Hospital charge category details accessed', $id);
        return view('backend.admin_module.hospital_charge_category.show', compact('item'))->with($this->page_info);
    }

    public function create()
    {      
        $parent_category = HospitalChargeCategory::where('parent_id',0)
                                                ->where('delete_status', 0)
                                                ->orderBy('name', 'asc')
                                                ->get();
        $parent_category=collect($parent_category)->toArray();  
        return view('backend.admin_module.hospital_charge_category.create',compact('parent_category'))->with($this->page_info);
    }


    public function store(Request $request)
    {
        $data = $request->all(); 
        
        if (!$this->exists($data['name'])) {
            $validator = HospitalChargeCategory::validate_add($data);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $new_record = HospitalChargeCategory::create($data);
        } else
            return Redirect::back()->withInput($request->input())->with('error_message', 'A hospital charge category with same category name already exists. Please use a different one.');
        generate_log('Hospital charge category created', $new_record->id);
        return redirect($this->url_prefix . '/hospital_charge_categorys')->with('message', 'Hospital charge category added.');
    }



    public function edit($id)
    {
        $item = HospitalChargeCategory::with('subcategory')->findorFail($id)->toArray();
        $parent_category = HospitalChargeCategory::where('parent_id',0)
                                                ->where('delete_status', 0)
                                                ->where('id', '!=', $id)
                                                ->orderBy('name', 'asc')
                                                ->get();      
        return view('backend.admin_module.hospital_charge_category.edit', compact('item','parent_category'))->with($this->page_info);
    }


    public function update(Request $request)
    {
        $data = $request->all(); 
        $id = $data['id'];
        if (!$this->exists($data['name'], $id)) {
            $validator = HospitalChargeCategory::validate_update($data, $id);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $record = HospitalChargeCategory::findorfail($id);
            $record->update($data);
        } else
            return Redirect::back()->withInput($request->input())->with('error_message', 'A hospital charge category with same category name already exists. Please use a different one.');
        generate_log('Hospital charge category updated', $id);
        return redirect($this->url_prefix . '/hospital_charge_categorys')->with('message', 'Hospital charge category updated.');
    }


    public function destroy($id)
    {
        /*$items = inventery::where('role_id', $id)->where('delete_status', 0)->count();
        if ($items > 0)
            return redirect($this->url_prefix . '/hospital_charge_categorys')->with('warning_message', 'There are certain supplier associated to this role. You can remove this role only once all the associated supplier are removed or their role is changed to a new one.');*/
        HospitalChargeCategory::where('id', $id)->update(['delete_status' => 1]);
        generate_log('Inventory Category deleted', $id);
        return redirect($this->url_prefix . '/hospital_charge_categorys')->with('message', 'Hospital charge category deleted.');
    }



    public function destroy_multiple($ids)
    {
        if (!empty($ids)) {
            $ids_array = explode(',', $ids);
            foreach ($ids_array as $id) {
                if ($id > 0) {
                     /*$items = Staff::where('role_id', $id)->where('delete_status', 0)->count();
                    if ($items > 0)
                        return redirect($this->url_prefix . '/hospital_charge_categorys')->with('warning_message', 'There are certain staffs associated to this role. You can remove this role only once all the associated staffs are removed or their role is changed to a new one.');*/
                    HospitalChargeCategory::where('id', $id)->update(['delete_status' => 1]);
                }
            }
            generate_log('Hospital charge category deleted multiple', null, 'Deleted record ids: ' . $ids);
            return redirect($this->url_prefix . '/hospital_charge_categorys')->with('message', 'Hospital charge category deleted.');
        } else
            return redirect($this->url_prefix . '/hospital_charge_categorys')->with('error_message', 'Please select at least one category.');
    }


    public function activate($id)
    {
        HospitalChargeCategory::where('id', $id)->update(['status' => 1]);
        generate_log('Hospital charge category activated', $id);
        return redirect($this->url_prefix . '/hospital_charge_categorys')->with('message', 'Hospital charge category activated.');
    }


    public function deactivate($id)
    {
        HospitalChargeCategory::where('id', $id)->update(['status' => 0]);
        generate_log('Hospital charge category deactivated', $id);
        return redirect($this->url_prefix . '/hospital_charge_categorys')->with('message', 'Hospital charge category deactivated.');
    }


    /* Custom methods */
    public function exists($name, $id = null)
    {
        if ($id == null)
            $items = HospitalChargeCategory::all()->where('name', $name)->where('delete_status', 0);
        else
            $items = HospitalChargeCategory::all()->where('name', $name)->where('id', '!=', $id);
        return ($items->count() > 0) ? true : false;
    }

     /********************* Staff email duplicate check *******************************/
    public function ajax_duplicate_name($name) {   
        $item = HospitalChargeCategory::all()->where('name',$name)->where('delete_status', 0);
        $item=collect($item)->sortBy('id')->toArray();        
        if(!empty($item)){
            return 1;
        }else{
            return 0;
        }
    }
    /********************* Staff email  duplicate check End*******************************/ 


}
