<?php
namespace App\Http\Controllers\AdminModule;
use App\Http\Controllers\Controller;
use App\Models\SettingsNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class SettingNotificationController extends Controller
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
        $item = SettingsNotification::findOrFail(1)->toArray();   
        //dd($item);   
        return view('backend.admin_module.setting_notification.edit', compact('item'))->with($this->page_info);
    }



    public function update(Request $request)
    {
        $data = $request->all(); 
        $id = $data['id'];         
        $validator = SettingsNotification::validate_update($data, $id);
        if ($validator->fails()) {
            return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
        }
        $record = SettingsNotification::findorfail($id);
        $record->update($data);        
        generate_log('Settings notification updated', $id);
        return redirect($this->url_prefix . '/setting_notification')->with('message', 'Notification updated.')->with($this->page_info);
    }
  

}
