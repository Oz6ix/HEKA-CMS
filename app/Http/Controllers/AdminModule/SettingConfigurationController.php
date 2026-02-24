<?php
namespace App\Http\Controllers\AdminModule;
use App\Http\Controllers\Controller;
use App\Models\SettingsConfiguration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SettingConfigurationController extends Controller
{
    public function __construct()
    {
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Settings";
        $this->page_heading = "Settings";
        $this->heading_icon = "fa-edit";         
        $this->page_info = [
            'url_prefix' => $this->url_prefix,
            'page_title' => $this->page_title,
            'page_heading' => $this->page_heading,
            'heading_icon' => $this->heading_icon, 
        ];
    }
    /* Page events */
    public function edit()
    {
        $item = SettingsConfiguration::findOrFail(1)->toArray();   
        //dd($item);   
        return view('backend.admin_module.setting_configuration.edit', compact('item'))->with($this->page_info);
    }



    public function update(Request $request)
    {
        $data = $request->all(); 
        $id = $data['id']; 
        $data['enable_pharmacy_status'] = $request->has('enable_pharmacy_status');
        $data['enable_pathology_status'] = $request->has('enable_pathology_status');
        $data['enable_radiology_status'] = $request->has('enable_radiology_status');
        $data['enable_inventory_status'] = $request->has('enable_inventory_status'); 
        $validator = SettingsConfiguration::validate_update($data, $id);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
        }
        $record = SettingsConfiguration::findorfail($id);
        $record->update($data);        
        generate_log('Settings configuration updated', $id);
        return redirect($this->url_prefix . '/configuration')->with('message', 'Configuration updated.')->with($this->page_info);
    }
  

}
