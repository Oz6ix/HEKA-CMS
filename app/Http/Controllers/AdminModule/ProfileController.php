<?php
namespace App\Http\Controllers\AdminModule;
use App\Http\Controllers\Controller;
use App\Models\User;
use Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Admin Profile";
        $this->page_heading = "Profile";
        $this->heading_icon = "fa-user-cog";
        $this->page_info = ['url_prefix' => $this->url_prefix, 'page_title' => $this->page_title, 'page_heading' => $this->page_heading, 'heading_icon' => $this->heading_icon];
    }

    public function edit()
    {
        $id = Auth::guard('web')->user()->id;
        $item = User::where('id', $id)->first();
        generate_log('Admin profile section accessed', $id);
        return view('backend.admin_module.profile.edit', compact('item'))->with($this->page_info);
    }

    public function update(Request $request)
    {
        $data = $request->all();
        $id = Auth::guard('web')->user()->id;
        if (!$this->exists($data['email'], $id)) {
            $validator = User::profile_update($data, $id);
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->with($this->page_info);
            }
            $profile = User::findOrFail($id);
            $profile->update($data);           
        } else
            return Redirect::back()->withInput($request->input())->with('error_message', 'An admin user with same email address already exists. Please use a different one.');
        generate_log('Admin profile details updated', $id);
        return redirect($this->url_prefix . '/profile')->with('message', 'Profile updated successfully.');
    }


    public function update_password(Request $request)
    {
        $current = $request['current_password'];
        $new = $request['new_password'];
        $confirm = $request['confirm_password'];
        $id = Auth::guard('web')->user()->id;
        if (empty($current))
            return redirect($this->url_prefix . '/profile')->with('error_message', 'The current password you have entered is wrong. Please re-try.');
        if (empty($new) || empty($confirm))
            return redirect($this->url_prefix . '/profile')->with('error_message', 'Please enter a valid new and confirm password.');
        $current_password = User::where('id', $id)->first()->password;
        if (!Hash::check($current, $current_password))
            return redirect($this->url_prefix . '/profile')->with('error_message', 'The current password you have entered is wrong. Please re-try.');
        if (trim($new) != trim($confirm))
            return redirect($this->url_prefix . '/profile')->with('error_message', 'New password and confirm password does not match.');
        $hash_pass = Hash::make($confirm);
        User::where('id', $id)->update(['password' => $hash_pass]);
        generate_log('Admin login password updated', $id);
        return redirect($this->url_prefix . '/profile')->with('message', 'Password updated successfully.');
    }


    /* Custom methods */
    public function exists($email, $id = null)
    {
        if ($id == null)
            $items = User::all()->where('email', $email)->where('delete_status', 0);
        else
            $items = User::all()->where('email', $email)->where('delete_status', 0)->where('id', '!=', $id);
        return ($items->count() > 0) ? true : false;
    }


}
