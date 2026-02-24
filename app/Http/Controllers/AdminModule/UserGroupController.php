<?php
namespace App\Http\Controllers\AdminModule;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
class UserGroupController extends Controller
{
    public function __construct()
    {
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "User Groups";
        $this->page_heading = "User Groups";
        $this->heading_icon = "fa-cogs";
        $this->page_info = ['url_prefix' => $this->url_prefix, 'page_title' => $this->page_title, 'page_heading' => $this->page_heading, 'heading_icon' => $this->heading_icon];
    }

    /* Page events */
    public function index()
    {
        $items = UserGroup::where('id', '!=', 1)->where('delete_status',0)->orderBy('id', 'desc')->get();
        generate_log('User groups list accessed');
        return view('backend.admin_module.user_groups.index', compact('items'))->with($this->page_info);
    }

    public function show($id)
    {
        $item = UserGroup::findorFail($id)->toArray();
        generate_log('User group details accessed', $id);
        return view('backend.admin_module.user_groups.show', compact('item'))->with($this->page_info);
    }

    public function create()
    {
        return view('backend.admin_module.user_groups.create')->with($this->page_info);
    }


    public function store(Request $request)
    {
        $data = $request->all();

        //dd($data);

        $data['admin_users'] = $request->has('admin_users');
        $data['staff'] = $request->has('staff');
        $data['patients'] = $request->has('patients');
        $data['appointments'] = $request->has('appointments');
        $data['bills'] = $request->has('bills');
        $data['inventory'] = $request->has('inventory');
        $data['appointment_report'] = $request->has('appointment_report');
        $data['revenue_report'] = $request->has('revenue_report');
        $data['general_settings'] = $request->has('general_settings');
        $data['user_groups'] = $request->has('user_groups');
        $data['notifications'] = $request->has('notifications');
        $data['hospital_charges'] = $request->has('hospital_charges');
        $data['pharmacy'] = $request->has('pharmacy');
        $data['phatology'] = $request->has('phatology');
        $data['radiology'] = $request->has('radiology');
        $data['suppliers'] = $request->has('suppliers');        
        $data['configuration'] = $request->has('configuration');       
        if (!$this->exists($data['title'])) {
            $validator = UserGroup::validate_add($data);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $new_record = UserGroup::create($data);
        } else
            return Redirect::back()->withInput($request->input())->with('error_message', 'A user group with same title already exists. Please use a different one.');
        generate_log('User group created', $new_record->id);
        return redirect($this->url_prefix . '/user_groups')->with('message', 'User group added.');
    }



    public function edit($id)
    {
        $item = UserGroup::findorFail($id)->toArray();
        return view('backend.admin_module.user_groups.edit', compact('item'))->with($this->page_info);
    }


    public function update(Request $request)
    {
        $data = $request->all();
        $id = $data['id'];
        $data['admin_users'] = $request->has('admin_users');
        $data['staff'] = $request->has('staff');
        $data['patients'] = $request->has('patients');
        $data['appointments'] = $request->has('appointments');
        $data['bills'] = $request->has('bills');
        $data['inventory'] = $request->has('inventory');
        $data['appointment_report'] = $request->has('appointment_report');
        $data['revenue_report'] = $request->has('revenue_report');
        $data['general_settings'] = $request->has('general_settings');
        $data['user_groups'] = $request->has('user_groups');
        $data['notifications'] = $request->has('notifications');
        $data['hospital_charges'] = $request->has('hospital_charges');
        $data['pharmacy'] = $request->has('pharmacy');
        $data['phatology'] = $request->has('phatology');
        $data['radiology'] = $request->has('radiology');
        $data['suppliers'] = $request->has('suppliers');        
        $data['configuration'] = $request->has('configuration'); 
       

        if (!$this->exists($data['title'], $id)) {
            $validator = UserGroup::validate_update($data, $id);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $record = UserGroup::findorfail($id);
            $record->update($data);
        } else
            return Redirect::back()->withInput($request->input())->with('error_message', 'A user group with same title already exists. Please use a different one.');
        generate_log('User group updated', $id);
        return redirect($this->url_prefix . '/user_groups')->with('message', 'User group updated.');
    }


    public function destroy($id)
    {
        $items = User::where('group_id', $id)->count();
        if ($items > 0)
            return redirect($this->url_prefix . '/user_groups')->with('warning_message', 'There are certain admin users associated to this user group. You can remove this group only once all the associated users are removed.');
        UserGroup::where('id', $id)->update(['delete_status' => 1]);
        generate_log('User group deleted', $id);
        return redirect($this->url_prefix . '/user_groups')->with('message', 'User group deleted.');
    }



    public function destroy_multiple($ids)
    {
        if (!empty($ids)) {
            $ids_array = explode(',', $ids);
            foreach ($ids_array as $id) {
                if ($id > 0) {
                    $items = User::where('group_id', $id)->count();
                    if ($items > 0)
                        return redirect($this->url_prefix . '/user_groups')->with('warning_message', 'There are certain admin users associated to this user group. You can remove this group only once all the associated users are removed.');
                    UserGroup::where('id', $id)->update(['delete_status' => 1]);
                }
            }
            generate_log('User group deleted multiple', null, 'Deleted record ids: ' . $ids);
            return redirect($this->url_prefix . '/user_groups')->with('message', 'User groups deleted.');
        } else
            return redirect($this->url_prefix . '/user_groups')->with('error_message', 'Please select at least one user group.');
    }


    public function activate($id)
    {
        UserGroup::where('id', $id)->update(['status' => 1]);
        generate_log('User group activated', $id);
        return redirect($this->url_prefix . '/user_groups')->with('message', 'User group activated.');
    }


    public function deactivate($id)
    {
        UserGroup::where('id', $id)->update(['status' => 0]);
        generate_log('User group deactivated', $id);
        return redirect($this->url_prefix . '/user_groups')->with('message', 'User group deactivated.');
    }


    /* Custom methods */
    public function exists($title, $id = null)
    {
        if ($id == null)
            $items = UserGroup::all()->where('title', $title);
        else
            $items = UserGroup::all()->where('title', $title)->where('id', '!=', $id);
        return ($items->count() > 0) ? true : false;
    }


}
