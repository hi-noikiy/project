<?php
define("GOOGLE_API_KEY", "AAAAqUl2rcU:APA91bHSqLSSPAeqXOVer1v3-HEAz9EDioUmj-rm-yvgu2Z9bXz9lTkQLUh0rDupSFRgx3QxIgyl5aD68EYQxaEm6Jdp6xr6SDXMlnv5ATX4_9RxLmEGzPS0CWUkVmurXUaZePHvg4X-");
define("GOOGLE_GCM_URL", "https://fcm.googleapis.com/fcm/send");
function send_gcm_notify($reg_id,$message) {
 
    $fields = array(
        'registration_ids'  => array( $reg_id,),
        'data'              => array( "message" => $message ),
    );
 
    $headers = array(
        'Authorization: key=' . GOOGLE_API_KEY,
        'Content-Type: application/json'
    );
 
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, GOOGLE_GCM_URL);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
 
    $result = curl_exec($ch);
    if ($result === FALSE) {
        die('Problem occurred: ' . curl_error($ch));
    }
 
    curl_close($ch);
    echo $result;
 }
$reg_id = "cwv8zzU2k4s:APA91bFrb7mW4kx0ps4jPS7UTknjbn7wq4Yi-IAuqiFj3Wi0Dmu131lCklVAFKudO5tV5WNs5PCQTI4vouxf-YgVP7l_hibWKrn8xaYWYcMEUnagt-KKPXgFIswUtPnxPnFAkvr82chv";
$msg = "成功的推送了";
 
send_gcm_notify($reg_id, $msg);