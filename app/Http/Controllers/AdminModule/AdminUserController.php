<?php
namespace App\Http\Controllers\AdminModule;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
/**
 * Class AdminUserController
 * @package App\Http\Controllers\AdminModule
 */

class AdminUserController extends Controller
{ 
    /**
     * AdminUserController constructor.
     * @param page_title 
     * @param page_heading 
     * @param heading_icon 
     */

    public function __construct()
    {
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_title = "Admin Users";
        $this->page_heading = "Admin Users";
        $this->heading_icon = "fa-user-friends";
        $this->page_info = ['url_prefix' => $this->url_prefix, 'page_title' => $this->page_title, 'page_heading' => $this->page_heading, 'heading_icon' => $this->heading_icon];
    }
    /**
     * List the resources.
     * @return \Illuminate\View\Factory|\Illuminate\View\View
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $items = User::with('user_group')->where('group_id', '>', 1)->where('delete_status', 0)->orderBy('id', 'desc')->get();
        generate_log('Users list accessed');
        return view('backend.admin_module.admin_users.index', compact('items'))->with($this->page_info);
    }
    /**
     * Display the specified resource.
     * @return \Illuminate\View\Factory|\Illuminate\View\View
     * @param $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $item = User::with('user_group')->where('delete_status', 0)->findOrFail($id);
        generate_log('Admin user details accessed', $id);
        return view('backend.admin_module.admin_users.show', compact('item'))->with($this->page_info);
    }
	/**
     * Show the form for creating a new resource.
     * @return \Illuminate\View\Factory|\Illuminate\View\View
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = UserGroup::where('status', 1)->where('delete_status',0)->where('id', '!=', 1)->orderBy('title', 'asc')->get();
        return view('backend.admin_module.admin_users.create', compact('groups'))->with($this->page_info);
    }
	/**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */

    public function store(Request $request)
    {
        $data = $request->all();
        $user_name = $data['name'];
        $email_duplicate=User::where('email',$data['email'])->where('delete_status',0)->select('email','delete_status')->get()->toArray();   
        if (empty($email_duplicate)) {
            $validator = User::validate_add($data);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $data['reset_pwd_status'] = 1;
            $data['status'] = 1;
            $new_record = User::create($data);
            //send password set link email to the admin user
            $contents = '<br><br>Hi ' . $user_name . ',<br><br>';
            $contents .= "You are successfully registered as an administrator on " . \Config::get('app.display_name') . ".";
            $contents .= " Please click <a href='" . url('/') . $this->url_prefix . "/set/password/view/" . encrypt($data['email']) . "' target='_blank' style='color:#2d3691;font-family:Tahoma, Geneva, sans-serif; line-height:22px; font-size:15px; font-weight:bold;'>here</a> to set a new password for your account.";
            $subject = "Admin User Registration";
            $display_name = \Config::get('app.display_name');
            $from = \Config::get('app.from_email');
            $to = $data['email'];
            send_single_email($contents, $subject, $display_name, $to, $from, $user_name);
        } else
            return Redirect::back()->withInput($request->input())->with('error_message', 'An admin user with same email address already exists. Please use a different one.');
        generate_log('Admin user created', $new_record->id);
        return redirect($this->url_prefix . '/admin_users')->with('message', 'Admin user added. An email to set the login password has been sent to the user.');
    }
	/**
     * Show the form for editing the specified resource.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $groups = UserGroup::where('id', '!=', 1)->where('delete_status',0)->orderBy('title', 'asc')->get();
        $item = User::findOrFail($id)->toArray();
        return view('backend.admin_module.admin_users.edit', compact('item', 'groups'))->with($this->page_info);
    }
	/**
     * Update the specified resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */

    public function update(Request $request)
    {
        $data = $request->all();
        $id = $data['id'];
        $exixting_data=User::where('id',$id)->select('id','email','delete_status')->get()->toArray(); 

        if($exixting_data[0]['email'] == $data['email'] ){ //echo "4444444";exit;
            $email_duplicate=[];  
        }else{ //echo "string";exit;
            $email_duplicate=User::where('email',$data['email'])->where('delete_status',0)->select('email','delete_status')->get()->toArray(); 
        } 

        if (empty($email_duplicate)) {
            $validator = User::validate_update($data, $id);
            if ($validator->fails()) {
                return Redirect::back()->withInput($request->input())->withErrors($validator)->with($this->page_info);
            }
            $record = User::findOrFail($id);
            $record->update($data);
        } else
            return Redirect::back()->withInput($request->input())->with('error_message', 'An admin user with same email address already exists. Please use a different one.');
        generate_log('Admin user updated', $id);
        return redirect($this->url_prefix . '/admin_users')->with('message', 'Admin user updated.');
    }
	/**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::where('id', $id)->update(['delete_status' => 1]);
        generate_log('Admin user deleted', $id);
        return redirect($this->url_prefix . '/admin_users')->with('message', 'Admin user deleted.');
    }
	/**
     * Remove the specified resources from storage.
     * @param int[] $ids An array of integer objects.
     * @return \Illuminate\Http\Response
     */
    public function destroy_multiple($ids)
    {
        if (!empty($ids)) {
            $ids_array = explode(',', $ids);
            foreach ($ids_array as $id) {
                if ($id > 0) {
                    User::where('id', $id)->update(['delete_status' => 1]);
                }
            }
            generate_log('Admin user deleted multiple', null, 'Deleted record ids: ' . $ids);
            return redirect($this->url_prefix . '/admin_users')->with('message', 'Admin user deleted.');
        } else
            return redirect($this->url_prefix . '/admin_users')->with('error_message', 'Please select at least one user.');
    }
	/**
     * Activate the specified resource in storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function activate($id)
    {
        User::where('id', $id)->update(['status' => 1]);
        generate_log('Admin user activated', $id);
        return redirect($this->url_prefix . '/admin_users')->with('message', 'Admin user status activated.');
    }
	/**
     * Deactivate the specified resource in storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function deactivate($id)
    {
        User::where('id', $id)->update(['status' => 0]);
        generate_log('Admin user deactivated', $id);
        return redirect($this->url_prefix . '/admin_users')->with('message', 'Admin user status deactivated. ');
    }

    /* Custom methods */
	/**
     * Check if Exist the specified resource in the storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function exists($email, $id = null)
    {
        if ($id == null)
            $items = User::all()->where('email', $email)->where('delete_status', 0);
        else
            $items = User::all()->where('email', $email)->where('delete_status', 0)->where('id', '!=', $id);
        return ($items->count() > 0) ? true : false;
    }

    /********************* Staff email duplicate check *******************************/
    /**
     *Check if the specified resource in the storage.
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function ajax_duplicate_email($email) {   
        $UserTable = User::all()->where('email',$email)->where('status',1)->where('delete_status', 0);
        $UserTable=collect($UserTable)->sortBy('id')->toArray();        
        if(!empty($UserTable)){
            return 1;
        }else{
            return 0;
        }
    }
    /********************* Staff email  duplicate check End*******************************/ 



}