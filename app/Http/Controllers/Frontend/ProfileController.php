<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use App\Models\Patient;
use App\Models\UserGroup;
use App\Models\BloodGroup;
use App\Models\SettingsSiteGeneral;
use Auth;
use Session;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Redirect;



class ProfileController extends Controller
{
    public function __construct()
    {
        
        $this->url_prefix = \Config::get('app.app_route_customer_prefix');
        $this->page_info = ['url_prefix' => $this->url_prefix];
    }   

    public function index()
    {
        $item=Auth::guard('blogger')->user();
        $blood_group_item = BloodGroup::where('status',1)
        ->where('delete_status',0)
        ->orderBy('blood_group', 'asc')
        ->get();
        $blood_group_item = collect($blood_group_item)->toArray(); 
        generate_log('Patient details Viewed'); 
        return view('frontend.patient_profile.index',compact('item','blood_group_item'))->with($this->page_info);

    }

    public function patient_profile_update(Request $request)
    {
        $item=Auth::guard('blogger')->user();
        $id = $item->id;
        $data = $request->all();
        $exixting_data=Patient::where('id',$id)->select('id','email','delete_status')->get()->toArray(); 
        $data['dob_str'] =strtotime($data['dob']); 
        if($exixting_data[0]['email'] == $data['email'] ){ //echo "4444444";exit;
            $email_duplicate=[];  
        }else{ //echo "string";exit;
            $email_duplicate=Patient::where('email',$data['email'])->where('delete_status',0)->select('email','delete_status')->get()->toArray(); 
        } 
        if (empty($email_duplicate)) {
            $validator = Patient::validate_update($data, $id);
            if ($validator->fails()) {
                return Redirect::back()->withErrors($validator)->with($this->page_info);
            }
            $record = Patient::findOrFail($id);
            /* if($data['patient_photo_check']=='1'){
                $data['patient_photo']=NULL;
                if(File::exists(public_path('uploads/patient/'.$record['patient_folder_name'].'/'.$record['patient_photo']))){
                    File::delete(public_path('uploads/patient/'.$record['patient_folder_name'].'/'.$record['patient_photo']));
                }
            } */


            if($request->file('file')){
 
                $img = $request->file('file');
    
                //here we are geeting userid alogn with an image
                $userid = $request->patient_code;
                $patient_folder_name = $request->patient_folder_name;
    
                $imageName = strtotime(now()).rand(11111,99999).'.'.$img->getClientOriginalExtension();
                $original_name = $img->getClientOriginalName();
                
                $patient_item = Patient::where('id',$id)->where('delete_status',0)->first();
    
                $path = 'patient/'.$patient_item->patient_folder_name;

                // Use Storage facade
                if($patient_item->patient_photo != NULL){
                    if(\Storage::disk('uploads')->exists($path.'/'.$patient_item->patient_photo)){
                        \Storage::disk('uploads')->delete($path.'/'.$patient_item->patient_photo);
                    }
                }             
                
                \Storage::disk('uploads')->putFileAs($path, $img, $imageName);
                $data['patient_photo']=$imageName;
    
            }
            $record->update($data);
        } 
        else{
            return redirect($this->url_prefix . '/patient_profile')->with('message','A patient with same email address already exists. Please use a different one.');
            //return Redirect::back()->withInput($request->input())->with('error_message', 'A patient with same email address already exists. Please use a different one.');
        }
         /* return redirect($this->url_prefix . '/patient')->with('message', 'Patient Details updated.'); */

        $blood_group_item = BloodGroup::where('status',1)
                                        ->where('delete_status',0)
                                        ->orderBy('blood_group', 'asc')
                                        ->get();
        $blood_group_item = collect($blood_group_item)->toArray(); 
        generate_log('Patient details updated', $id); 
        return redirect($this->url_prefix . '/patient_profile')->with('message','Profile update');
    }

    public function update_patient_password(Request $request)
    {
        //dd($request);
        $current = $request['current_password'];
        $new = $request['new_password'];
        $confirm = $request['confirm_password'];
        $id = Auth::guard('blogger')->user()->id;
        if (empty($current))
            return redirect($this->url_prefix . '/patient_profile')->with('error_message', 'The current password you have entered is wrong. Please re-try.');
        if (empty($new) || empty($confirm))
            return redirect($this->url_prefix . '/patient_profile')->with('error_message', 'Please enter a valid new and confirm password.');
        $current_password = Patient::where('id', $id)->first()->password;
        if (!Hash::check($current, $current_password))
            return redirect($this->url_prefix . '/patient_profile')->with('error_message', 'The current password you have entered is wrong. Please re-try.');
        if (trim($new) != trim($confirm))
            return redirect($this->url_prefix . '/patient_profile')->with('error_message', 'New password and confirm password does not match.');
        $hash_pass = Hash::make($confirm);
        Patient::where('id', $id)->update(['password' => $hash_pass]);
        generate_log('Patient login password updated', $id);
        return redirect($this->url_prefix . '/patient_profile')->with('message', 'Password updated successfully.');
    }























    



    public function login()
    {

        $Authuser = Auth::guard('blogger')->user();
        //$Authuser= Auth::user();        
        //dd($user);
        /*if(empty($Authuser)){ */
            //$lasturlpath=url()->previous();           
           // session()->put('lasturlpath', $lasturlpath);
            $this->page_info = ['page_title' => 'Login'];      
            return view('frontend.customers.login')->with($this->page_info);
       /* }else{
            //Session::flash('alert-warning', 'Please login.');
              return redirect()->route('patient_login');
        }*/
    }



    public function authenticate(Request $request)
    {
        $data = $request->all();   
        $email = $data['email'];
        $password = $data['password']; 
        $checkuserdetail = Patient::where('email', $email)->where('delete_status', 0)->get();
        $checkuserdetail=collect($checkuserdetail)->toArray(); 
        if (Auth::guard('blogger')->attempt(['email' => $email, 'password' => $password , 'delete_status' => 0])) { 
           
            $userdetail = Patient::where('email', $email)->where('delete_status', 0)->get();
            $user=collect($userdetail)->toArray();   
            //dd($userdetail);
            if ($user[0]['delete_status'] == 1){ 
                Session::flash('alert-danger', 'Unauthorized user! Please contact administrator.'); 
                return redirect()->route('patient_login'); 
            }           

            if ($user[0]['status'] == 0){ 
                Session::flash('alert-danger', 'Access denied! Please contact administrator.'); 
                return redirect()->route('patient_login');
            }else{ 
                $customername=strtoupper($user[0]['name']);
                Session::flash('alert-success', 'WELCOME'.' '.$customername); 
                return redirect()->route('patient_appointment');                         
            }    
        } else {
            Session::flash('alert-danger', 'Email or password is incorrect!'); 
           // return redirect('/customer/login'); 
            return redirect()->route('patient_login');
        }
    }



   public function customer_logout()
    {  
        Auth::logout();
        Session::flush();
        Session::flash('alert-success', 'You have been successfully logged out!'); 
        return redirect()->route('patient_login'); 
    }



    public function register()
    {
        
        $Authuser= Auth::user();
        if(empty($Authuser)){
            $this->page_info = ['page_title' => 'Sign Up'];
            return view('frontend.customers.register')->with($this->page_info);
         }else{
            //Session::flash('alert-warning', 'Please login.');
              return redirect()->route('patient_login');
        }

    }



    public function register_create(Request $request)
    {        
        $data = $request->all();        
        $first_name = isset($data['name']) ? $data['name'] : '';       
        $email = isset($data['email']) ? $data['email'] : '';
        $password = isset($data['password']) ? $data['password'] : '';      
        $phone = isset($data['phone']) ? $data['phone'] : '';        
        $data['password'] = Hash::make($password);     
        $email_duplicate = Patient::where('email', $email)->where('delete_status', 0)->get();
        $email_duplicate=collect($email_duplicate)->toArray();
        if(!empty($email_duplicate)){
            Session::flash('alert-danger', 'Email address already exists.');
            return back();
        }
        if (empty($email_duplicate)) {               
            $items = Patient::orderBy('id', 'desc')->get()->toArray();       
            $patient_code_pattern='10000';
            $patient_count = Patient::orderBy('id', 'desc')->limit(1)->get();
            $patient_count = collect($patient_count)->toArray(); 
            if(!empty($patient_count)){
                $patient_count = collect($patient_count)->first(); 
                $patient_code =$patient_count['id'] + $patient_code_pattern+1; 
            }else{
                $patient_code = $patient_code_pattern+1;
            }   

         $data['patient_code'] =$patient_code;
         $data['add_status'] =1;
         $data['patient_folder_name']=strval($patient_code).explode(' ',$request->name)[0];                
        if(!is_dir(public_path() . '/uploads/patient/'.$data['patient_folder_name'])){
            mkdir(public_path() . '/uploads/patient/'.$data['patient_folder_name'], 0777, true);
        } 

            $new_record = Patient::create($data);
            //Email send to the patient email 
            $from = \Config::get('app.from_email');
            //$from = 'chackochan16@gmail.com';
            // send enquiry notification email to admin                      
            $contents = '<br><br>Welcome to Clinic Management System . Thank you for registering with us.';  
            $contents .= '<br>You have registered successfully'; 
            $subject = 'User Registration';
            $display_name = \Config::get('app.display_name');
            //$to ='jacob2790.wst@gmail.com';
            $to = $new_record['email'];
            //send_single_email($contents, $subject, $display_name, $to, $from);
            //admin mail notification 
            //$from = \Config::get('app.from_email');
            $from = $new_record['email'];
            $customer_name = $new_record['name'];
            $customer_email = $new_record['email'];
            // send enquiry notification email to admin                      
            $contents = '<br><br>';  
            $contents .= '<br><br><b>User Details:</b>'; 
            $contents .= '<br>' . 'Name: ' . $customer_name; 
            $contents .= '<br>' . 'Email: ' . $customer_email; 
            $subject = 'New User Registration - '. $customer_name;
            $display_name = \Config::get('app.display_name');
            $contact_info =SettingsSiteGeneral::findOrFail(1)->toArray();
            //dd($contact_info);
            //$to ='jacob2790.wst@gmail.com';
            $to = $contact_info['contact_email'];
            //send_single_email($contents, $subject, $display_name, $to, $from);  
        Session::flash('alert-success', 'You have been completed the registration successfully.'); 
        return redirect()->route('patient_login');
        }           
    }      



   public function profile(Request $request){
        $Authuser= Auth::user();
        if(!empty($Authuser)){   
            $userprofile = CustomerProfile::where('customer_id',Auth::user()->id)->with('customer')->get(); 
            $userprofile=collect($userprofile)->toArray();
            if ($request->isMethod('post')){
                $post_data=$request->all();
                $post_data['agree_personal_knowledge']=1;
                $post_data['agree_privacy_statement']=1;
                $post_data['agree_affirm_statement_true']=1;
                $Select_Address= $post_data['preferred']['0'];
                if($Select_Address==1){
                    $post_data['preferred_address'] = 1;
                }else{
                     $post_data['preferred_address'] = 2;
                }


                $Country_Code = Country_Code::where('id',$post_data['phone_code'])->get();
                $Country_Code=collect($Country_Code)->toArray();
                    if(!empty($Country_Code)){
                    $Country_Code=collect($Country_Code)->first();
                    $p_code = '+'.$Country_Code['phonecode'];
                    $c_code = $Country_Code['id'];

                        $customer_record_item=Customer::where('delete_status',0)
                        ->where('id',$post_data['customer_id'])
                        ->get();
                        $customer_record_item  =collect($customer_record_item)->toArray(); 
                        if(!empty($customer_record_item)){
                        foreach ($customer_record_item as $key => $value1) {
                        $obj =Customer::findOrFail($value1['id']);                         
                        $obj->country_id =$c_code;   
                        $obj->phone_code =$p_code;               
                        $obj->save();              
                        }
                        }
                    }else{
                    $p_code = '+'.'63';
                    $c_code = '169';
                    } 

                $CourseUrl=$post_data['courseurl'];
                $input = $post_data['dob']; 
                $date = strtotime($input); 
                //$post_data['dob'] = date('Y-m-d h:i:s', $date); 
                $post_data['dob'] = $post_data['dob']; 
                    unset($post_data['_token']); 
                    unset($post_data['courseurl']); 
                    unset($post_data['preferred']);
                    unset($post_data['phone_code']);
                    if(empty($userprofile)){
                        $validator = CustomerProfile::validate_add($post_data);
                        if ($validator->fails()) {  
                            Session::flash('alert-danger', 'Invalid or incorrect parameters!'); 
                            return back();
                        }
                        $status = CustomerProfile::create($post_data);
                        $status=1;                      

                    }else{   
                        $Profile=CustomerProfile::where('customer_id',Auth::user()->id)->get();
                        $Profile=collect($Profile)->toArray(); 
                        if(!empty($Profile)){
                            $Profile=collect($Profile)->first(); 
                            $validator = CustomerProfile::validate_update($post_data, $Profile['id']);
                            if ($validator->fails()) {
                             Session::flash('alert-danger', 'Invalid or incorrect parameters!'); 
                             return back();
                            }
                        } 
                        
                        $status=CustomerProfile::where('customer_id',Auth::user()->id)->update($post_data);
                    }                   
                    if($status==1){  
                      if(!empty($CourseUrl)){// echo "string";exit;
                        Session::flash('alert-success', 'Profile updated successfully.');
                        return redirect('/order/summary/'.$CourseUrl);
                      }else{
                        Session::flash('alert-success', 'Profile updated successfully.');
                        return back();
                      }   
                    }else{
                        Session::flash('alert-danger', 'The Profile could not be saved. Please, try again.');
                        return back();
                    }
            }else{  
                $IBPChapter_list=IBPChapter::where('status','1')->orderBy('name', 'asc')->get()->toArray();
                $LawSchool_list=LawSchool::where('status','1')->orderBy('name', 'asc')->get()->toArray();
                $Country_Code = Country_Code::get();
                $Country_Code=collect($Country_Code)->toArray(); 

                $this->page_info = ['page_title' => 'Profile Update'];
                $banner_image_details=fetch_header_banner();
                $banner_image=$banner_image_details['banner_name_default'];
                return view('frontend.customers.profile',compact('banner_image','banner_image_details','userprofile','enccourseId','LawSchool_list','IBPChapter_list','Country_Code'))->with($this->page_info);
            }            
        }else{
            Session::flash('alert-warning', 'Please login.');
             return redirect()->route('patient_login');

        }
   }

   public function password(Request $request){ 
    $Authuser= Auth::user();
        if(!empty($Authuser)){  
                $this->page_info = ['page_title' => 'Update Password'];
                $banner_image_details=fetch_header_banner();
                $banner_image=$banner_image_details['banner_name_default'];
            if ($request->isMethod('post')) {
                $post_data=$request->all();
                $data = $request->all();                   
                $user = Customer::find(auth()->user()->id);
                if(!Hash::check($data['oldpassword'], $user->password)){             
                Session::flash('alert-danger', 'The old password does not match.');
                return back();           
                }else{               
                        $password=Hash::make($post_data['newpassword']);
                        $status=Customer::where('id',$user->id)->update(['password'=>$password]);              
                    if($status==1){
                        Session::flash('alert-success', 'Password has been updated successfully.');
                        return back();
                    }else{
                        Session::flash('alert-danger', 'Password could not be updated. Please try again.');
                        return back();
                    }
                }               
            }else{
                return view('frontend.customers.password',compact('banner_image','banner_image_details'))->with($this->page_info);
            }
        }else{
            Session::flash('alert-warning', 'Please login.');
              return redirect()->route('patient_login');
        }
   }


    public function forgot_password()
    {
        $this->page_info = ['page_title' => 'Forgot Password'];
        $banner_image_details=fetch_header_banner();
        $banner_image=$banner_image_details['banner_name_default'];
        return view('frontend.customers.forgot',compact('banner_image','banner_image_details'))->with($this->page_info);

    }



   public function send_email_link(Request $request)
    {
        $data = $request->all();
        $email = $data['email'];
        //$count = Customer::where('email', $email)->where('delete_status', 0)->count();
        $user = Customer::where('email', $email)->where('delete_status', 0)->get(); 
        $user = collect($user)->toArray();  
        //dd($user);
        if(!empty($user)){
            $user_name = $user[0]['first_name'].' '.$user[0]['last_name'];
            $contents = 'Hi ' . $user_name . ',<br><br>';
            $contents .= 'A link to reset the password has been generated. Please click ';
            $contents .= "<a href='" . url('/') . "/user/reset/password/view/" . encrypt($email) . "' target='_blank' style='color:#fd8827;font-family:Tahoma, Geneva, sans-serif; line-height:22px; font-size:15px; font-weight:bold;'>here</a> to reset your password.";
            $subject = "Reset Password";
            $display_name = \Config::get('app.display_name');
            $from = \Config::get('app.from_email');
            $to = $email;
            send_single_email($contents, $subject, $display_name, $to, $from, $user_name);
            Customer::where('email', $email)->where('delete_status', 0)->update(['reset_pwd_status' => 1]);           
            Session::flash('alert-success', 'Reset password link sent to your registered email.');
               return redirect()->route('patient_login');                      
        } else {   
            Session::flash('alert-danger', 'Please enter a valid registered email address.');
              return redirect()->route('patient_login');
        }
    }


    public function view_reset_password($email)
    {
        $this->page_info = ['page_title' => 'Reset Password'];
        $banner_image_details=fetch_header_banner();
        $banner_image=$banner_image_details['banner_name_default'];
        $email = decrypt($email);
        $user = Customer::where('email', $email)->where('delete_status', 0)->first(); 
        if ($user != null && $user->reset_pwd_status == 1) { 
            return view('frontend.customers.reset_password', compact('email','banner_image','banner_image_details'))->with($this->page_info);
        } else if ($user != null && $user->reset_pwd_status == 2) { 
            Session::flash('alert-danger', 'Reset password link has expired.');
               return redirect()->route('patient_login');          
        } else {
           Session::flash('alert-danger', 'Reset password link is not valid.');
               return redirect()->route('patient_login');             
        }
    }

    public function save_reset_password(Request $request)
    {
        $data = $request->all();
        $email = $data['email'];     
        $count = Customer::where('email', $email)->where('delete_status', 0)->where('reset_pwd_status', 1)->count();
        if ($count == 1) {
            $new = $data['newpassword'];
            $confirm = $data['confirmpwd'];
            if ($new == $confirm) {
                $hashed_password = \Hash::make($new);
                Customer::where('email', $email)->where('delete_status', 0)->update(['password' => $hashed_password, 'reset_pwd_status' => 2]);   
                Session::flash('alert-success', 'Password reset successfully.');
                  return redirect()->route('patient_login');                
            } else {
                Session::flash('alert-warning', 'New & Confirm Password does not match.');
                return redirect()->back();
            }
        } else {
            Session::flash('alert-warning', 'Reset password link has expired.');
              return redirect()->route('patient_login');             
        }
    }

    /********************* customer email duplicate check *******************************/
    public function ajax_duplicate_email($email) {   
        $CustomerTable = Customer::all()->where('email',$email)->where('status',1)->where('delete_status', 0);
        $CustomerTable=collect($CustomerTable)->sortBy('id')->toArray();        
        if(!empty($CustomerTable)){
            return 1;
        }else{
            return 0;
        }
    }
    /********************* customer email  duplicate check End*******************************/


    public function user_order_list_old()
    {

            $Authuser= Auth::user();
        if(!empty($Authuser)){ 
            $this->page_info = ['page_title' => 'User Order List'];
            $banner_image_details=fetch_header_banner();
            $banner_image=$banner_image_details['banner_name_default'];
            $user_order_info = CustomerOrder::where('customer_id',Auth::user()->id)->where('status',1)->where('payment_status','1')->with('ordercourse')->with('customer')->with('orderpackage')->orderBy('id','desc')->get();
             $user_order_info = collect($user_order_info)->toArray(); 
            return view('frontend.customers.user_order_list',compact('banner_image','banner_image_details','user_order_info'))->with($this->page_info);
        }else{
            Session::flash('alert-warning', 'Please login.');
            return redirect()->route('login');
        }

    }  




    public function appointment()
    {

        $Authuser = Auth::guard('blogger')->user();         
        if(!empty($Authuser)){
            $this->page_info = ['page_title' => 'Patient Appointment'];
            return view('frontend.customers.appointment')->with($this->page_info);
         }else{
            Session::flash('alert-warning', 'Please login.');
            return redirect()->route('patient_login');
        }

    }


    public function appointment_create()
    {

        $Authuser = Auth::guard('blogger')->user();         
        if(!empty($Authuser)){
            $this->page_info = ['page_title' => 'Patient Appointment'];
            return view('frontend.customers.appointment')->with($this->page_info);
         }else{
            Session::flash('alert-warning', 'Please login.');
            return redirect()->route('patient_login');
        }

    }


  
}