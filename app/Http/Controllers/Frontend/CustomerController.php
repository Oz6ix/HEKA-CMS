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



class CustomerController extends Controller
{
    public function __construct()
    {
        
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_info = ['url_prefix' => $this->url_prefix];
    }   


    public function login()
    {
        $Authuser = Auth::guard('blogger')->user(); 
        //dd($Authuser->id);      
        if(empty($Authuser->id)){ 
            //$lasturlpath=url()->previous();           
           // session()->put('lasturlpath', $lasturlpath);
            $this->page_info = ['page_title' => 'Login'];      
            return view('frontend.customers.login')->with($this->page_info);
        }else{
            //Session::flash('alert-warning', 'Please login.');
            return redirect()->route('index');
        }
    }



   /* public function authenticate(Request $request)
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
                return redirect()->route('index');                         
            }    
        } else {
            Session::flash('alert-danger', 'Email or password is incorrect!'); 
           // return redirect('/customer/login'); 
            return redirect()->route('patient_login');
        }
    }*/

    public function authenticate(Request $request)
    {
        $data = $request->all(); 
        $patient_code = $data['patient_code'];
        $phone = $data['phone'];
        $password = $data['password']; 

        $throttleKey = \Illuminate\Support\Str::lower($patient_code) . '|' . $request->ip();

        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($throttleKey, 5)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($throttleKey);
            Session::flash('alert-danger', 'Too many login attempts. Please try again in '.$seconds.' seconds.');
            return redirect()->route('patient_login');
        }

        $checkuserdetail = Patient::where('patient_code', $patient_code)->where('phone', $phone)->where('delete_status', 0)->get();
        $checkuserdetail=collect($checkuserdetail)->toArray(); 
        if (Auth::guard('blogger')->attempt(['patient_code' => $patient_code,'phone' => $phone, 'password' => $password , 'delete_status' => 0])) { 
           
            \Illuminate\Support\Facades\RateLimiter::clear($throttleKey);
            $request->session()->regenerate(); // session fixation protection

            $userdetail = Patient::where('patient_code', $patient_code)->where('delete_status', 0)->get();
            $user=collect($userdetail)->toArray();   
            //dd($userdetail);
            if ($user[0]['delete_status'] == 1){ 
                Auth::guard('blogger')->logout();
                Session::flash('alert-danger', 'Unauthorized user! Please contact administrator.'); 
                return redirect()->route('patient_login'); 
            }           

            if ($user[0]['status'] == 0){ 
                Auth::guard('blogger')->logout();
                Session::flash('alert-danger', 'Access denied! Please contact administrator.'); 
                return redirect()->route('patient_login');
            }else{ 
                $customername=strtoupper($user[0]['name']);
                Session::flash('alert-success', 'WELCOME'.' '.$customername); 
                return redirect()->route('index');                         
            }    
        } else {
            \Illuminate\Support\Facades\RateLimiter::hit($throttleKey);
            Session::flash('alert-danger', 'Incorrect data.Please check and try again!'); 
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
        
        $Authuser = Auth::guard('blogger')->user();              
        if(empty($Authuser->id)){ 
            $this->page_info = ['page_title' => 'Sign Up'];
            
            $items = Patient::orderBy('id', 'desc')->first();
            $item_general = SettingsSiteGeneral::findOrFail(1)->toArray(); 
            $hospital_code=$item_general['hospital_code'];
            if(empty($items)){
                $patient_code=$hospital_code.'00001';
            }else{
            $patient_code=$hospital_code.(str_pad(substr($items['patient_code'],4) + 1, 5, 0, STR_PAD_LEFT));
            }
            
            
            return view('frontend.customers.register',compact('patient_code'))->with($this->page_info);
         }else{
            //Session::flash('alert-warning', 'Please login.');
            return redirect()->route('index');
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
            /*$patient_code_pattern='10000';
            $patient_count = Patient::orderBy('id', 'desc')->limit(1)->get();
            $patient_count = collect($patient_count)->toArray(); 
            if(!empty($patient_count)){
                $patient_count = collect($patient_count)->first(); 
                $patient_code =$patient_count['id'] + $patient_code_pattern+1; 
            }else{
                $patient_code = $patient_code_pattern+1;
            }   */
            $items = Patient::orderBy('id', 'desc')->first();
            $item_general = SettingsSiteGeneral::findOrFail(1)->toArray(); 
            $hospital_code=$item_general['hospital_code'];
            if(empty($items)){
                $patient_code=$hospital_code.'00001';
            }else{
            $patient_code=$hospital_code.(str_pad(substr($items['patient_code'],4) + 1, 5, 0, STR_PAD_LEFT));
            }


         $data['patient_code'] =$patient_code;
         $data['add_status'] =1;
         $data['patient_folder_name']=strval($patient_code).explode(' ',$request->name)[0];                
        
         // Ensure directory exists using Storage facade
        if(!\Storage::disk('uploads')->exists('patient/'.$data['patient_folder_name'])){
            \Storage::disk('uploads')->makeDirectory('patient/'.$data['patient_folder_name']);
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


    public function patient_forgotpassword()
    {
        $this->page_info = ['page_title' => 'Forgot Password'];
        
        return view('frontend.customers.forgotpassword')->with($this->page_info);

    }



   public function patient_send_email_link(Request $request)
    {
        $data = $request->all();
        $email = $data['email'];
        //$count = Patient::where('email', $email)->where('delete_status', 0)->count();
        $user = Patient::where('email', $email)->where('delete_status', 0)->get(); 
        $user = collect($user)->toArray();  
        //dd($user);
        if(!empty($user)){
            $user_name = $user[0]['name'];
            $contents = 'Hi ' . $user_name . ',<br><br>';
            $contents .= 'A link to reset the password has been generated. Please click ';
            $contents .= "<a href='" . url('/') . "/customer/reset/password/view/" . encrypt($email) . "' target='_blank' style='color:#fd8827;font-family:Tahoma, Geneva, sans-serif; line-height:22px; font-size:15px; font-weight:bold;'>here</a> to reset your password.";
            $subject = "Reset Password";
            $display_name = \Config::get('app.display_name');
            $from = \Config::get('app.from_email');
            $to = $email;
            send_single_email($contents, $subject, $display_name, $to, $from, $user_name);
            Patient::where('email', $email)->where('delete_status', 0)->update(['reset_pwd_status' => 1]);           
            Session::flash('alert-success', 'Reset password link sent to your registered email.');
            return redirect()->route('patient_login');                      
        } else {   
            Session::flash('alert-danger', 'Please enter a valid registered email address.');
            return redirect()->route('patient_forgotpassword');
        }
    }


    public function patient_view_reset_password($email)
    {
        $this->page_info = ['page_title' => 'Reset Password'];        
        $email = decrypt($email);
        $user = Patient::where('email', $email)->where('delete_status', 0)->first(); 
        if ($user != null && $user->reset_pwd_status == 1) { 
            return view('frontend.customers.reset_password', compact('email'))->with($this->page_info);
        } else if ($user != null && $user->reset_pwd_status == 2) { 
            Session::flash('alert-danger', 'Reset password link has expired.');
            return redirect()->route('patient_login');         
        } else {
           Session::flash('alert-danger', 'Reset password link is not valid.');
           return redirect()->route('patient_login');             
        }
    }

    public function patient_save_reset_password(Request $request)
    {
        $data = $request->all();
        $email = $data['email'];     
        $count = Patient::where('email', $email)->where('delete_status', 0)->where('reset_pwd_status', 1)->count();
        if ($count == 1) {
            $new = $data['password'];
            $confirm = $data['confirm_password'];
            if ($new == $confirm) {
                $hashed_password = \Hash::make($new);
                Patient::where('email', $email)->where('delete_status', 0)->update(['password' => $hashed_password, 'reset_pwd_status' => 2]);   
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