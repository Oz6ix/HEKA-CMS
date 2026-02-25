<?php
namespace App\Http\Controllers\AdminModule;
use App\Http\Controllers\Controller;
use App\Models\SettingSiteLogo;
use App\Models\SettingsSiteGeneral;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
class SettingGeneralController extends Controller
{
    public function __construct()
    {
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Settings";
        $this->page_heading = "Settings";
        $this->heading_icon = "fa-edit";
        $this->directory_logos = "logos";
       // $this->directory_sliders = "sliders";
       // $this->directory_videos = "videos";
        $this->directory_homepage_elements = "homepage_elements";
        $this->directory_web_banner_elements = "banner";
        $this->directory_themes = "themes";
        $this->logo_mobile_width = \Config::get('app.logo_mobile_width');
        $this->logo_mobile_height = \Config::get('app.logo_mobile_height');
        $this->logo_mobile_2x_width = \Config::get('app.logo_mobile_2x_width');
        $this->logo_mobile_2x_height = \Config::get('app.logo_mobile_2x_height');
        $this->logo_desktop_width = \Config::get('app.logo_desktop_width');
        $this->logo_desktop_height = \Config::get('app.logo_desktop_height');        
        $this->page_info = [
            'url_prefix' => $this->url_prefix,
            'page_title' => $this->page_title,
            'page_heading' => $this->page_heading,
            'heading_icon' => $this->heading_icon,
            'directory_logos' => $this->directory_logos,          
            'directory_homepage_elements' => $this->directory_homepage_elements,
            'directory_web_banner_elements' => $this->directory_web_banner_elements,
            'directory_themes' => $this->directory_themes,
            'logo_mobile_width' => $this->logo_mobile_width,
            'logo_mobile_height' => $this->logo_mobile_height,
            'logo_mobile_2x_width' => $this->logo_mobile_2x_width,
            'logo_mobile_2x_height' => $this->logo_mobile_2x_height,
            'logo_desktop_width' => $this->logo_desktop_width,
            'logo_desktop_height' => $this->logo_desktop_height,
           
        ];
    }
    /* Page events */
    public function edit($current_section = '')
    {
        // Use firstOrCreate so Site Settings works on fresh databases without seeded data
        $item_site = SettingSiteLogo::firstOrCreate(
            ['id' => 1],
            ['favicon' => '', 'logo_desktop' => '']
        )->toArray(); 
        $item_general = SettingsSiteGeneral::firstOrCreate(
            ['id' => 1],
            ['site_title' => 'HEKA', 'site_title_footer' => 'HEKA Clinic Management', 'address' => '', 'phone' => '', 'email' => '']
        )->toArray(); 
        return view('backend.admin_module.setting_general.edit', compact('item_site','item_general'))->with('current_section', $current_section)->with($this->page_info);
    }
    public function update_site(Request $request)
    {
        $data = $request->all();
        $id = $data['id'];
        // mobile logo file upload
        //dd($data);
        if ($request->hasFile('favicon')) {
            $file = $request->file('favicon');
            if (verify_file_mime_type($file, 'image')) {                
                /*if (validate_image_dimension($file, $this->logo_mobile_width, $this->logo_mobile_height)) {*/
                    /*$validator = SettingSiteLogo::validate_image($data);
                    if ($validator->fails()) {
                        return redirect($this->url_prefix . '/general_settings/update_logo')->withErrors($validator)->with($this->page_info);
                    }*/
                    $data['favicon'] = upload_file($file, $this->directory_logos, true, 'favicon');
                /*} else
                    return redirect($this->url_prefix . '/general_settings/update_logo')->with('error_message', 'Please upload image with valid file dimension.')->with($this->page_info);*/
            } else
                return redirect($this->url_prefix . '/general_settings/update_logo')->with('error_message', 'Please upload a valid image file.')->with($this->page_info);
        }
       
         // desktop logo file upload
        if ($request->hasFile('logo_desktop')) {
            $file = $request->file('logo_desktop');
            if (verify_file_mime_type($file, 'image')) {
               /* if (validate_image_dimension($file, $this->logo_desktop_width, $this->logo_desktop_height)) {*/
                    /*$validator = SettingSiteLogo::validate_image($data);
                    if ($validator->fails()) {
                        return redirect($this->url_prefix . '/general_settings/update_logo')->withErrors($validator)->with($this->page_info);
                    }*/
                    $data['logo_desktop'] = upload_file($file, $this->directory_logos, true, 'logo_desktop');
                /*} else
                    return redirect($this->url_prefix . '/general_settings/update_logo')->with('error_message', 'Please upload image with valid file dimension.')->with($this->page_info);*/
            } else
                return redirect($this->url_prefix . '/general_settings/update_logo')->with('error_message', 'Please upload a valid image file.')->with($this->page_info);
        }

        if ($request->hasFile('favicon') || $request->hasFile('logo_desktop')) {
            $record = SettingSiteLogo::findorfail($id);
            $record->update($data);
            generate_log('Logo image uploaded', $id);
            return redirect($this->url_prefix . '/general_settings/update_logo')->with('message', 'Logo image uploaded.');
        } else
            return redirect($this->url_prefix . '/general_settings/update_logo')->with('error_message', 'Please select a logo image file to upload.')->with($this->page_info);
    }     

    public function update_general_info(Request $request)
    {
        $data = $request->all(); 
        $id = $data['id'];        
            $validator = SettingsSiteGeneral::validate_update($data, $id);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $record = SettingsSiteGeneral::findorfail($id);
            $record->update($data);        
        generate_log('Settings site general updated', $id);
        return redirect($this->url_prefix . '/general_settings/update_general_info')->with('message', 'General site info updated.')->with($this->page_info);
    }
  

}
