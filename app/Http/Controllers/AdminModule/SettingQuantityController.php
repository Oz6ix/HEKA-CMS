<?php
namespace App\Http\Controllers\AdminModule;
use App\Http\Controllers\Controller;
use App\Models\Quantitys;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
class SettingQuantityController extends Controller
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

        $items = Quantitys::where('delete_status', 0)->orderBy('quantity', 'desc')->get();   
        //dd( $items);     
        generate_log('Quantitys accessed');
        return view('backend.admin_module.setting_quantity.index', compact('items'))->with($this->page_info);
    }

    public function show($id)
    {
        $item = Quantitys::findorFail($id)->toArray();
        generate_log('Quantity details accessed', $id);
        return view('backend.admin_module.setting_quantity.show', compact('item'))->with($this->page_info);
    }

    public function create()
    {        
        return view('backend.admin_module.setting_quantity.create')->with($this->page_info);
    }


    public function store(Request $request)
    {
        $data = $request->all();        
        if (!$this->exists($data['quantity'])) {
            $validator = Quantitys::validate_add($data);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $new_record = Quantitys::create($data);
        } else
            return Redirect::back()->withInput($request->input())->with('error_message', 'A quantity with same quantity name already exists. Please use a different one.');
        generate_log('Quantity created', $new_record->id);
        return redirect($this->url_prefix . '/setting_quantitys')->with('message', 'Quantity added.');
    }



    public function edit($id)
    {
        $item = Quantitys::findorFail($id)->toArray();
        return view('backend.admin_module.setting_quantity.edit', compact('item'))->with($this->page_info);
    }


    public function update(Request $request)
    {
        $data = $request->all(); 
        $id = $data['id'];
        if (!$this->exists($data['quantity'], $id)) {
            $validator = Quantitys::validate_update($data, $id);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $record = Quantitys::findorfail($id);
            $record->update($data);
        } else
            return Redirect::back()->withInput($request->input())->with('error_message', 'A quantity with same quantity name already exists. Please use a different one.');
        generate_log('Quantity updated', $id);
        return redirect($this->url_prefix . '/setting_quantitys')->with('message', 'Quantity updated.');
    }


    public function destroy($id)
    {
        //$items = Staff::where('role_id', $id)->where('delete_status', 0)->count();
        /*if ($items > 0)
            return redirect($this->url_prefix . '/setting_quantitys')->with('warning_message', 'There are certain staffs associated to this role. You can remove this role only once all the associated staffs are removed or their role is changed to a new one.');*/
        Quantitys::where('id', $id)->update(['delete_status' => 1]);
        generate_log('Quantity deleted', $id);
        return redirect($this->url_prefix . '/setting_quantitys')->with('message', 'Quantity deleted.');
    }



    public function destroy_multiple($ids)
    {
        if (!empty($ids)) {
            $ids_array = explode(',', $ids);
            foreach ($ids_array as $id) {
                if ($id > 0) {
                    /* $items = Staff::where('role_id', $id)->where('delete_status', 0)->count();
                    if ($items > 0)
                        return redirect($this->url_prefix . '/setting_quantitys')->with('warning_message', 'There are certain staffs associated to this role. You can remove this role only once all the associated staffs are removed or their role is changed to a new one.');*/
                    Quantitys::where('id', $id)->update(['delete_status' => 1]);
                }
            }
            generate_log('Quantity deleted multiple', null, 'Deleted record ids: ' . $ids);
            return redirect($this->url_prefix . '/setting_quantitys')->with('message', 'Quantity deleted.');
        } else
            return redirect($this->url_prefix . '/setting_quantitys')->with('error_message', 'Please select at least one role.');
    }


    public function activate($id)
    {
        Quantitys::where('id', $id)->update(['status' => 1]);
        generate_log('Quantitys activated', $id);
        return redirect($this->url_prefix . '/setting_quantitys')->with('message', 'Quantity activated.');
    }


    public function deactivate($id)
    {
        Quantitys::where('id', $id)->update(['status' => 0]);
        generate_log('Quantity deactivated', $id);
        return redirect($this->url_prefix . '/setting_quantitys')->with('message', 'Quantity deactivated.');
    }


    /* Custom methods */
    public function exists($name, $id = null)
    {
        if ($id == null)
            $items = Quantitys::all()->where('quantity', $name);
        else
            $items = Quantitys::all()->where('quantity', $name)->where('id', '!=', $id);
        return ($items->count() > 0) ? true : false;
    }


      public function ajax_duplicate_name($name) {   
        $items = Quantitys::all()->where('quantity',$name)->where('delete_status', 0);
        $items=collect($items)->sortBy('id')->toArray();        
        if(!empty($items)){
            return 1;
        }else{
            return 0;
        }
    }


}
