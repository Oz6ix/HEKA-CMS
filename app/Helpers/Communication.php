<?php
use App\User;
function onesignal_push_notification_to_all($title, $message, $data, $image = '')
{
    \OneSignal::setParam('big_picture', $image)->sendNotificationToAll(
        $message,
        $url = null,
        $data = $data,
        $buttons = null,
        $schedule = null,
        $headings = $title,
        $subtitle = null
    );
}
function onesignal_push_notification_to_user($title, $message, $player_id, $data, $image = '')
{
    if ($player_id != null && sizeof($player_id) > 0) {
        // send push notification using OneSignal
        \OneSignal::setParam('big_picture', $image)->sendNotificationToUser(
            $message,
            $player_id,
            $url = null,
            $data = $data,
            $buttons = null,
            $schedule = null,
            $headings = $title,
            $subtitle = null
        );
    }
}
function onesignal_push_notification_to_staff($title, $message, $player_id, $data, $image = '')
{
    if ($player_id != null && sizeof($player_id) > 0) {
        $app_id = \Config::get('onesignal.staff_app_id');
        $rest_api_key = \Config::get('onesignal.staff_rest_api_key');
        $user_auth_key = \Config::get('onesignal.user_auth_key');
        $client = new \Berkayk\OneSignal\OneSignalClient($app_id, $rest_api_key, $user_auth_key);
        $client->sendNotificationToUser(
            $message,
            $player_id,
            $url = null,
            $data = $data,
            $buttons = null,
            $schedule = null,
            $headings = $title,
            $subtitle = null
        );
    }
}
// General helper function to send a single email
function send_single_email($content, $subject, $display_name = NULL, $to = NULL, $from = NULL, $user_name = NULL)
{
    if ($from === NULL) {
        $from = Config::get('app.from_email');
    }
    if ($to === NULL) {
        $to = Config::get('app.from_email');
    }
    if ($display_name === NULL) {
        $display_name = Config::get('app.display_name');
    }
    if ($user_name === NULL) {
        $user_name = Config::get('app.display_name');
    }
    $content = email_template($content, $subject, $user_name);
    $subject = Config::get('app.display_name') . " : " . $subject;
    // Send email using Sendgrid cURL method
    $url = 'https://api.sendgrid.com/';
    $api_key = Config::get('app.sendgrid_key');
    $params = array(
        'to' => $to,
        'subject' => $subject,
        'html' => $content,
        'from' => $from,
        'fromname' => $display_name
    );
    $request = $url . 'api/mail.send.json';
    // Generate curl request
    $session = curl_init($request);
    // Tell curl to use HTTP POST
    curl_setopt($session, CURLOPT_POST, true);
    // Tell curl that this is the body of the POST
    curl_setopt($session, CURLOPT_POSTFIELDS, $params);
    // Tell curl not to return headers, but do return the response
    curl_setopt($session, CURLOPT_HEADER, false);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($session, CURLOPT_HTTPHEADER, array(
        "authorization: Bearer " . $api_key
    ));
    // obtain response
    $response = curl_exec($session);
}
// General helper function to send a single email
function sendMultipleEmailBCC($content, $subject, $display_name = NULL, $to_bcc = NULL, $from = NULL)
{
    $user_info = null;
    if ($from === NULL || $to_bcc === NULL || $display_name === NULL)
        $user_info = User::where('status', 1)->where('group_id', 1)->get();
    $to = $user_info[0]->email;
    if ($from === NULL) {
        $from = $user_info[0]->email;
    }
    if ($to_bcc === NULL) {
        $to_bcc = array($user_info[0]->email);
    }
    if ($display_name === NULL) {
        $firstname = $user_info[0]->first_name;
        $lastname = $user_info[0]->last_name;
        $display_name = $firstname . ' ' . $lastname;
    }
    $subject = "{{ config('app.site_title') }} : " . $subject;
    $content = email_template($content, $subject);
    $to_bcc = implode(',', $to_bcc);
    /**
     * Send email using Sendgrid cURL method
     */
    $json_string = array(
        'to' => array(
            $to_bcc
        ),
        'category' => 'test_category'
    );
    $fromemail = "johndoetesting123@gmail.com";
    $url = 'https://api.sendgrid.com/';
    $user = 'libin1993swt';
    $pass = '';
    $params = array(
        'api_user' => $user,
        'api_key' => $pass,
        'x-smtpapi' => json_encode($json_string),
        'to' => $to,
        'subject' => $subject,
        'html' => $content,
        'from' => $fromemail,
        'fromname' => Config::get('app.display_name')
    );
    $request = $url . 'api/mail.send.json';
    // Generate curl request
    $session = curl_init($request);
    // Tell curl to use HTTP POST
    curl_setopt($session, CURLOPT_POST, true);
    // Tell curl that this is the body of the POST
    curl_setopt($session, CURLOPT_POSTFIELDS, $params);
    // Tell curl not to return headers, but do return the response
    curl_setopt($session, CURLOPT_HEADER, false);
    curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
    // obtain response
    $response = curl_exec($session);
}
// Template for emails
function email_template($content, $subject, $user_name = null)
{
    $current_date = date('M d, Y');
    $template = '<!DOCTYPE HTML>
	<html>
	<body>
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width:620px; border:1px solid #e8e8e8; background: #f5f5f5;">
    <tbody>
    <tr>
    <td align="center"  valign="top">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" >
    <tbody>
    <tr>
    <td style="padding-left:10px;padding-right:10px" align="center" valign="top">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody>
    <tr>
    <td style="padding: 40px 20px 20px;" align="center" valign="middle">
    <a href="#" style="text-decoration:none" target="_blank">
    <img alt="" border="0" src="' . \URL::asset('resources/files/uploads/email_template/hospital-logo-mail.png') . '" style="height:auto;display:block" width="100">
    </a>
    </td>
    </tr>
    <tr>
    <td style="padding-bottom: 5px; padding-left: 20px; padding-right: 20px;" align="center" valign="middle">
    <p style="color:#191919;font-family:Lora,Georgia,Times,serif;font-size:24px;font-weight:400;font-style:normal;letter-spacing:normal;line-height:32px;text-transform:none;text-align:center;padding:0;margin:0;">
    ' . $subject . '</p>
    </td>
    </tr>
    <tr>
    <td style="padding-bottom: 40px; padding-left: 20px; padding-right: 20px;" align="center" valign="middle">
    <p style="color:#999;font-family:\'Open Sans\',Helvetica,Arial,sans-serif;font-size:11px;font-weight:400;line-height:20px;font-style:normal;letter-spacing:normal;text-transform:none;text-align:center;margin:0;padding:0">
    ' . $current_date . '</p></td>
    </tr>
    </tbody>
    </table>
    </td>
    </tr>
    </tbody>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody>
    <tr>
    <td align="center" valign="top">
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody>
    <tr>
    <td style="padding-left:10px;padding-right:10px;padding-bottom:20px">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#fff">
    <tbody>';


    //<tr>
   // <td style="padding: 20px 20px 40px;" align="center" valign="top">
    //<img alt="" border="0" src="' . \URL::asset('resources/files/uploads/email_template/hospital-image-mail.png') . '" style="width:100%;max-width:560px;height:auto;display:block" width="560">
   // </td>
   // </tr>
    // <tr>
    // <td style="padding-bottom: 20px; padding-left: 40px; padding-right: 40px;" align="center" valign="top">
    // <h2 style="color:#191919;font-family:'Open Sans',Helvetica,Arial,sans-serif;font-size:20px;font-weight:700;font-style:normal;letter-spacing:normal;line-height:28px;text-transform:uppercase;text-align:center;padding:0;margin:0">
    // Mail Content Heading, if any
    // </h2>
    // </td>
    // </tr>
    $template .= '
    <tr>
    <td style="padding-bottom: 40px; padding-left: 40px; padding-right: 40px;" align="center" valign="top">
    <pre style="color:#777;font-family:\'Open Sans\',Helvetica,Arial,sans-serif;font-size:14px;font-weight:400;font-style:normal;letter-spacing:.5px;line-height:20px;text-transform:none;text-align:left;padding:0;margin:0">' . $content . '</pre>
    </td>
    </tr>
    </tbody>
    </table>
    </td>
    </tr>
    </tbody>
    </table>
    </td>
    </tr>
    </tbody>
    </table>
    <table border="0" cellpadding="0" cellspacing="0" width="100%">
    <tbody>
    <tr>
    <td style="padding-left:10px;padding-right:10px" align="center" valign="top">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" 
    <tbody>
   
   
    <tr>
    <td style="font-size:1px;line-height:1px" height="30">&nbsp;</td>
    </tr>
    </tbody>
    </table>
    </td>
    </tr>
    </tbody>
    </table>
    </td>
    </tr>
    </tbody>
    </table>
    </body>
    </html>';



    return $template;
}
// Method to send SMS message to phone
function send_sms($phone_number, $message)
{
    $sender_id = Config::get('app.sms_sender_id');
    $api_key = Config::get('app.sms_api_key');
    $url = "http://api.unifiedbuzz.com/sms/insent?mobile=" . $phone_number . "&format=json&text=" . $message . "&flash=0&type=1&sender=" . $sender_id;
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $url,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array('X - API - Key:' . $api_key)));
    // send the request & save response to $response
    $response = curl_exec($curl);
    // close request to clear up some resources
    curl_close($curl);
    // print response
    //dd($response);
    $apiarray = json_decode($response, true);
    $apiresult = $apiarray['data'];
    return $apiresult;
}
?>