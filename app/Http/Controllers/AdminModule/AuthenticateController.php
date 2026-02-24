<?php
namespace App\Http\Controllers\AdminModule;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
/**
 * Class AuthenticateController
 * @package App\Http\Controllers\AdminModule
 */

class AuthenticateController extends Controller
{
    public function __construct()
    {
        $this->url_prefix = \Config::get('app.app_route_prefix');
        $this->page_info = ['url_prefix' => $this->url_prefix];
    }

    /**
     * Login as an administrator.
     * @return \Illuminate\View\Factory|\Illuminate\View\View
     * @return \Illuminate\Http\Response
     */

    public function login()
    {
        return view('backend.auth.login')->with($this->page_info);
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($this->throttleKey($request), 5)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($this->throttleKey($request));
            return back()->with('error_message', 'Too many login attempts. Please try again in '.$seconds.' seconds.');
        }

        // Using default 'web' guard as we migrated away from 'admin' custom guard in User model
        // If strict 'admin' guard is needed, config/auth.php needs updating.
        if (Auth::attempt($credentials)) {
            \Illuminate\Support\Facades\RateLimiter::clear($this->throttleKey($request));
            $request->session()->regenerate();
            return redirect()->intended($this->url_prefix . '/dashboard');
        }

        \Illuminate\Support\Facades\RateLimiter::hit($this->throttleKey($request));

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     *
     * @return string
     */
    protected function throttleKey(Request $request)
    {
        return \Illuminate\Support\Str::lower($request->input('email')) . '|' . $request->ip();
    }
    /**
     * FOrgot password of the Admin users.
     * @return \Illuminate\View\Factory|\Illuminate\View\View
     * @return \Illuminate\Http\Response
     */

    public function forgot_password($current_section = null)
    {
        return view('backend.auth.login')->with('current_section', 'forgot_password')->with($this->page_info);
    }
   
    /**
     * Logout an Admin user.
     * @return \Illuminate\View\Factory|\Illuminate\View\View
     * @return \Illuminate\Http\Response
     */
    public function logout()
    {
        \Session::flush();
        Auth::logout();
        Session::flash('alert-success', 'You have been successfully logged out!');  
        return redirect($this->url_prefix.'/login')->with($this->page_info);
    }
    /**
     * Display set password of the Admin users.
     * @return \Illuminate\View\Factory|\Illuminate\View\View
     * @return \Illuminate\Http\Response
     */

    public function view_set_password($email)
    {
        return view('backend.auth.set_password', compact('email'))->with($this->page_info);
    }
	/**
     * Store a new password created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */

    public function save_set_password(Request $request)
    {
        $data = $request->all();
        $email = $data['email'];
        $email = decrypt($email);
        $user = User::where('email', $email)->where('delete_status', 0)->get();
        if ($user->count() > 0 && $user[0]->reset_pwd_status == 1) {
            $new = $data['new_password'];
            $confirm = $data['confirm_password'];
            if ($new == $confirm) {
                $hashed_password = \Hash::make($new);
                User::where('email', $email)->where('delete_status', 0)->update(['password' => $hashed_password, 'reset_pwd_status' => 2]);
                generate_log('Admin password set', null, 'A new staff admin created password for the first time.#Email: ' . $email);
                return redirect($this->url_prefix.'/login')->with('message', 'Password set successfully.')->with($this->page_info);
            } else {
                return redirect()->back()->with('error_message', 'New & Confirm Password does not match.')->with($this->page_info);
            }
        } else {
            return redirect($this->url_prefix.'/login')->with('error_message', 'Reset password link has expired.')->with($this->page_info);
        }
    }


	/**
     * Send a reset request email.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */

     public function send_email_link(Request $request)
    {
        $data = $request->all();        
        $email = $data['email'];

        $throttleKey = 'forgot-password|' . $request->ip();
        if (\Illuminate\Support\Facades\RateLimiter::tooManyAttempts($throttleKey, 3)) {
            $seconds = \Illuminate\Support\Facades\RateLimiter::availableIn($throttleKey);
            return redirect()->back()->with('error_message', 'Too many password reset requests. Please try again in '.$seconds.' seconds.')->with($this->page_info);
        }
        \Illuminate\Support\Facades\RateLimiter::hit($throttleKey);
       
        $user = User::where('email', $email)->where('delete_status', 0)->get();
        $user = collect($user)->toArray(); 
        //dd($user); 
        if(!empty($user)){
            $user = collect($user)->first();
            $user_name = $user['name'];
            $contents = 'Hi ' . $user_name . ',<br><br>';
            $contents .= 'A link to reset the password has been generated. Please click ';
            $contents .= "<a href='" . url('/') . $this->url_prefix . "/reset/password/view/" . encrypt($email) . "' target='_blank' style='color:#fd8827;font-family:Tahoma, Geneva, sans-serif; line-height:22px; font-size:15px; font-weight:bold;'>here</a> to reset your password.";
            $subject = "Reset Password";
            $display_name = \Config::get('app.display_name');
            $from = \Config::get('app.from_email');
            $to = $email;
            send_single_email($contents, $subject, $display_name, $to, $from, $user_name);
            User::where('email', $email)->where('delete_status', 0)->update(['reset_pwd_status' => 1]);
            generate_log('Admin reset password requested', null, 'An admin has requested to reset the password. A password reset link has been sent to the email id.#Email: ' . $email . '#Id: ' . $user['id']);
            return redirect($this->url_prefix.'/login')->with('message', 'Reset password link sent to your registered email.')->with($this->page_info);
        } else {
            /*return redirect($this->url_prefix . '/forgot_password')*/
            return redirect()->back()->with('error_message', 'Please enter a valid registered email address.')->with($this->page_info);
        }
    }
	/**
     * Store a new password created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function view_reset_password($email)
    {
        $email = decrypt($email);
        $user = User::where('email', $email)->where('delete_status', 0)->first();
        if ($user != null && $user->reset_pwd_status == 1) { 
            return view('backend.auth.reset_password', compact('email'))->with($this->page_info);
        } else if ($user != null && $user->reset_pwd_status == 2) {
            return redirect($this->url_prefix.'/login')->with('error_message', 'Reset password link has expired.')->with($this->page_info);
        } else {
            return redirect($this->url_prefix.'/login')->with('error_message', 'Reset password link is not valid.')->with($this->page_info);
        }
    }
	/**
     * Store a reset password created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function save_reset_password(Request $request)
    {
        $data = $request->all();
        $email = $data['email'];
        $count = User::where('email', $email)->where('delete_status', 0)->where('reset_pwd_status', 1)->get();
        $count = collect($count)->toArray();
        if (!empty($count)) {            
            $new = $data['new_password'];
            $confirm = $data['confirm_password'];
            if ($new == $confirm) {
                $hashed_password = \Hash::make($new);
                User::where('email', $email)->where('delete_status', 0)->update(['password' => $hashed_password, 'reset_pwd_status' => 2]);
                generate_log('Admin password reset', null, 'An admin has reset the password.#Email: ' . $email);
                return redirect($this->url_prefix.'/login')->with('message', 'Password reset successfully.')->with($this->page_info);
            } else {
                return redirect()->back()->with('error_message', 'New & Confirm Password does not match.')->with($this->page_info);
            }
        } else {
            return redirect($this->url_prefix.'/login')->with('error_message', 'Reset password link has expired.')->with($this->page_info);
        }
    }



    
}
