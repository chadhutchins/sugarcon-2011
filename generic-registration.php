<?php

/******************************************************************************
Copyright (C) 2011 by Chad Hutchins

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
******************************************************************************/

// sugarcrm username and password that will be used to
// connect to sugarcrm via the web services
$username = "user";
$password = "pass";

// url to sugarcrm web services 
$web_services_url = "http://your-crm-system.com/service/v2/soap.php?wsdl";

// the url you would like the user to be redirected to
// after completing the form
$redirect_url = "http://your-website.com/thank-you-page";

// the name of the module you'll be adding the form data to
$module_name = "custom_module_name";

// the form fields you want to attempt to gather data from
// within the form and pass to sugar. for example, if you 
// have a field on the module named 'email' and the input name of
// the html element is named 'emailaddress' your array would look
// like the following:
// array(
//     "email" => "emailaddress"
// )
$available_fields = array(
    "field_1_name",
    "field_2_name",
    "field_3_name",
    "field_4_name_c",
    "field_5_name_c"
);

// Insert LifeLine record
define('sugarEntry',true);
require_once('nusoap.php');

// instantiate SOAP client
$client = new soapclient($web_services_url,true);

$auth_array = array(
    'user_auth' => array(
        'user_name' => $username,
        'password' => md5($password),
    )
);

// Login to SugarCRM and retrieve session id
$login_results = $client->call('login',$auth_array);
$session_id = $login_results['id'];

// loop through each available field and get value from form data
$name_value_list = array();
foreach($available_fields as $sugarfield => $formfield)
{
    $name_value_list []= array(
        "name" => $sugarfield,
        "value" => $_REQUEST[$formfield]
    );
}

// Setup array of data to send to SugarCRM
$set_entry_params = array(
    'session' => $session_id,
    'module_name' => $module_name,
    'name_value_list' => $name_value_list
);

// add form information to SugarCRM
$result = $client->call('set_entry',$set_entry_params);

// Redirect
header('Location: '.$redirect_url);

?>