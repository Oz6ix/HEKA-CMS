<?php
// Checks the privilege of the admin user to access a section on the admin panel
function check_privilege($section)
{
    $auth_group_id= Auth::guard('web')->user()->group_id; 
    //$auth_group_id = \Session::get('authorization');    
    $auth = \App\Models\UserGroup::findOrFail($auth_group_id);
    return ($auth->$section == 1) ? true : false;
}
// Helper function to check the authenticity of api service called
function authenticate_api($get_all_headers)
{
    //Key: YmFzZTY0OndHdGh2emZBekhQWnN0KzJJVVZ5akxobWVqSkJJOGxHMU03ZGJPN09UNDA9
    //Secret: U2VSdkljRVNoT3BTTWFSdDIwMTl3SGlUZUxhTm1BckthUHBDdVN0T21Fcg==
    $keys_arr = !empty($get_all_headers['Auth']) ? explode(":", $get_all_headers['Auth']) : null;
    if ($keys_arr == null)
        $keys_arr = !empty($get_all_headers['Authorization']) ? explode(":", $get_all_headers['Authorization']) : null;
    $api_key = ($keys_arr != null) ? $keys_arr[0] : null;
    $api_secret = ($keys_arr != null) ? $keys_arr[1] : null;;
    $app_key = base64_encode(\Config::get('app.app_key'));
    $app_secret = base64_encode(\Config::get('app.app_secret'));
    if ($api_key == $app_key && $api_secret == $app_secret)
        return true;
    else
        return false;
}
// Helper function to check the authenticity of service app api called
function authenticate_api_service_app($get_all_headers)
{
    //Key: U2VSdkljRVNoT3BTTWFSdDIwMTl3SGlUZUxhTm1BckthUHBDdVN0T21Fcg==
    //Secret: TWFSdFdoSXRFMjAxOVNlUnZJY0VhUFA=
    $keys_arr = !empty($get_all_headers['Auth']) ? explode(":", $get_all_headers['Auth']) : null;
    if ($keys_arr == null)
        $keys_arr = !empty($get_all_headers['Authorization']) ? explode(":", $get_all_headers['Authorization']) : null;
    $api_key = ($keys_arr != null) ? $keys_arr[0] : null;
    $api_secret = ($keys_arr != null) ? $keys_arr[1] : null;;
    $app_key = base64_encode(\Config::get('app.service_app_key'));
    $app_secret = base64_encode(\Config::get('app.service_app_secret'));
    if ($api_key == $app_key && $api_secret == $app_secret)
        return true;
    else
        return false;
}
?>