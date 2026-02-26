<?php
namespace App\Http\Controllers\AdminModule;
use App\Http\Controllers\Controller;
use App\Models\SettingsSupplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;



class SettingSupplierController extends Controller
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

        $items = SettingsSupplier::where('delete_status', 0)->orderBy('id', 'desc')->get();        
        generate_log('Supplier accessed');
        return view('backend.admin_module.setting_supplier.index', compact('items'))->with($this->page_info);
    }

    public function show($id)
    {
        $item = SettingsSupplier::findorFail($id)->toArray();
        generate_log('Supplier details accessed', $id);
        return view('backend.admin_module.setting_supplier.show', compact('item'))->with($this->page_info);
    }

    public function create()
    {        
        return view('backend.admin_module.setting_supplier.create')->with($this->page_info);
    }


    public function store(Request $request)
    {
        $data = $request->all(); 
        // Map form field 'suppliers' to expected 'supplier_name'
        if (!isset($data['supplier_name']) && isset($data['suppliers'])) {
            $data['supplier_name'] = $data['suppliers'];
        }
        $data['supplier_code']=generate_supplier_code();
        if (!$this->exists($data['supplier_name'])) {
            $validator = SettingsSupplier::validate_add($data);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $new_record = SettingsSupplier::create($data);
        } else
            return Redirect::back()->withInput($request->input())->with('error_message', 'A supplier with same supplier name already exists. Please use a different one.');
        generate_log('Supplier created', $new_record->id);
        return redirect($this->url_prefix . '/setting_suppliers')->with('message', 'Supplier added.');
    }



    public function edit($id)
    {
        $item = SettingsSupplier::findorFail($id)->toArray();
        return view('backend.admin_module.setting_supplier.edit', compact('item'))->with($this->page_info);
    }


    public function update(Request $request)
    {
        $data = $request->all(); 
        // Map form field 'suppliers' to expected 'supplier_name'
        if (!isset($data['supplier_name']) && isset($data['suppliers'])) {
            $data['supplier_name'] = $data['suppliers'];
        }
        $id = $data['id'];
        if (!$this->exists($data['supplier_name'], $id)) {
            $validator = SettingsSupplier::validate_update($data, $id);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $record = SettingsSupplier::findorfail($id);
            $record->update($data);
        } else
            return Redirect::back()->withInput($request->input())->with('error_message', 'A supplier with same supplier name already exists. Please use a different one.');
        generate_log('Supplier updated', $id);
        return redirect($this->url_prefix . '/setting_suppliers')->with('message', 'Supplier updated.');
    }


    public function destroy($id)
    {
        /*$items = inventery::where('role_id', $id)->where('delete_status', 0)->count();
        if ($items > 0)
            return redirect($this->url_prefix . '/setting_suppliers')->with('warning_message', 'There are certain supplier associated to this role. You can remove this role only once all the associated supplier are removed or their role is changed to a new one.');*/
        SettingsSupplier::where('id', $id)->update(['delete_status' => 1]);
        generate_log('Supplier deleted', $id);
        return redirect($this->url_prefix . '/setting_suppliers')->with('message', 'Supplier deleted.');
    }



    public function destroy_multiple($ids)
    {
        if (!empty($ids)) {
            $ids_array = explode(',', $ids);
            foreach ($ids_array as $id) {
                if ($id > 0) {
                     /*$items = Staff::where('role_id', $id)->where('delete_status', 0)->count();
                    if ($items > 0)
                        return redirect($this->url_prefix . '/setting_suppliers')->with('warning_message', 'There are certain staffs associated to this role. You can remove this role only once all the associated staffs are removed or their role is changed to a new one.');*/
                    SettingsSupplier::where('id', $id)->update(['delete_status' => 1]);
                }
            }
            generate_log('Supplier deleted multiple', null, 'Deleted record ids: ' . $ids);
            return redirect($this->url_prefix . '/setting_suppliers')->with('message', 'Supplier deleted.');
        } else
            return redirect($this->url_prefix . '/setting_suppliers')->with('error_message', 'Please select at least one role.');
    }


    public function activate($id)
    {
        SettingsSupplier::where('id', $id)->update(['status' => 1]);
        generate_log('Supplier activated', $id);
        return redirect($this->url_prefix . '/setting_suppliers')->with('message', 'Supplier activated.');
    }


    public function deactivate($id)
    {
        SettingsSupplier::where('id', $id)->update(['status' => 0]);
        generate_log('Supplier deactivated', $id);
        return redirect($this->url_prefix . '/setting_suppliers')->with('message', 'Supplier deactivated.');
    }


    /* Custom methods */
    public function exists($supplier_name, $id = null)
    {
        if ($id == null)
            $items = SettingsSupplier::all()->where('supplier_name', $supplier_name);
        else
            $items = SettingsSupplier::all()->where('supplier_name', $supplier_name)->where('id', '!=', $id);
        return ($items->count() > 0) ? true : false;
    }

     /********************* Staff email duplicate check *******************************/
    public function ajax_duplicate_name($name) {   
        $item = SettingsSupplier::all()->where('supplier_name',$name)->where('delete_status', 0);
        $item=collect($item)->sortBy('id')->toArray();        
        if(!empty($item)){
            return 1;
        }else{
            return 0;
        }
    }
    /********************* Staff email  duplicate check End*******************************/ 


}
