<?php
// ****************************************************************************************************
// SFMC API Looper - Looping PHP API Trigger Script
// Purpose: To allow copy and paste of Postman API calls and create loops for execution during demo
//
// Author: Martin Andrew <martin.andrew@salesforce.com>
// v1.0 - Initial release for REST API (can be adapted for SOAP as needed)
//
// TODO
// - add total execution timer and compare against auth "expiresIn" and re-auth if required
//
// NOTE: Use Generate Code link in Postman and select PHP cURL  
//
// ****************************************************************************************************

// *****************************
// ***** VARIABLES - START *****
// *****************************

// set variable for loop timing and optional iteration count

$loop_timer = 5;      // time in seconds to wait between loops
$max_loop_count = 1;  // max loop count, optional, 0 = unlimited
$exit = FALSE;        // initialise loop exit flag (used for counter)

// *****************************
// ****** VARIABLES - END ******
// *****************************

// convert seconds to microseconds
$loop_timer = $loop_timer * 1000000;

echo "SFMC API Looping Trigger Script\n";
echo " - Authenticating with API\n";

// ****************************************************************************************************
// INSERT AUTH CODE HERE - START
// ****************************************************************************************************

$curl = curl_init();
print_r($_REQUEST);

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://auth.exacttargetapis.com/v1/requestToken",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\n    \"clientId\":\"wu0k8ze473jk427e313v996p\",\n    \"clientSecret\":\"BNW0MWMwIHf9k2sVDB4XucZ1\"\n}",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: application/json",
    "postman-token: c689c91c-2a5c-a945-ca7e-392a3f2cc95e"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}

// ****************************************************************************************************
// INSERT AUTH CODE HERE - END
// ****************************************************************************************************

$data = json_decode($response , true);
$accessToken = $data['accessToken'];

if ($max_loop_count > 0) {
  $counter = 0;
  echo "\n - Looping " . $max_loop_count . " times with wait time of " . $loop_timer . " microseconds\n";
} else {
  echo "\n - Looping indefinitely with wait time of " . $loop_timer . " microseconds\n";
}

while ($exit != TRUE) {

// ****************************************************************************************************
// INSERT API CODE HERE - START
// ****************************************************************************************************

$message = ($_REQUEST['message'] ? $_REQUEST['message'] : "message");
$title = ($_REQUEST['title'] ? $_REQUEST['title'] : "title");
$subtitle = ($_REQUEST['subtitle'] ? $_REQUEST['subtitle'] : "subtitle");
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://www.exacttargetapis.com/push/v1/messageContact/MzoxMTQ6MA/send",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "{\n  \"SubscriberKeys\": [\n    \"lachlan.ross@salesforce.com\"\n  ],\n  \"Override\": true,\n  \"MessageText\": \"$message\",\n  \"title\": \"$title\",\n  \"subtitle\": \"$subtitle\",\n  \"Badge\": \"+1\"\n}",
  CURLOPT_HTTPHEADER => array(
    "authorization: Bearer $accessToken",
    "cache-control: no-cache",
    "content-type: application/json",
    "postman-token: 8f8033ef-c4d7-cfa2-e4c3-16dae3a9ab6b"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  echo $response;
}

// ****************************************************************************************************
// INSERT API CODE HERE - END
// ****************************************************************************************************

if ($max_loop_count > 0) {
  $counter++;
  if ($counter == $max_loop_count) {
    // loop count reached, exit
    echo " - Loop count (" . $max_loop_count . ") reached, exiting\n";
    exit();
  }
}

// sleep/wait for $loop_timer microseconds before continuing
echo " - Sleeping for " . $loop_timer . " microseconds\n";
usleep($loop_timer);

}

?>
