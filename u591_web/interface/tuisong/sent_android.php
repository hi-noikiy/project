<?php
//define("GOOGLE_API_KEY", "AIzaSyA4nYPYOICuZVTikI2Y0YtgZ1GxJ_EQohk");
//define("GOOGLE_API_KEY", "AIzaSyA4nYPYOICuZVTikI2Y0YtgZ1GxJ_EQohk");
define("GOOGLE_API_KEY", "AIzaSyBEjQU3MH5jLro6AKSgrUcFGXrNw1LfRSs");
define("GOOGLE_GCM_URL", "https://fcm.googleapis.com/fcm/send");
 
//,'APA91bGedKKCAucpJqYz0ZeZ53zXhRZ1_IfbCu-2YeLxQc-JBJ_I40zBoUZM34Ab_wcHGXxhFIkcpAwyGi7r5SvwDlVHviXlC2UxZ9ueOlfgD5qVRmhXbQ_bu1v_pwYj6iggiAjpKE9DkEvODDF_64OEJBEI0IgIK5VuTRCRe3rqEzCV7JYc66A'
function send_gcm_notify($reg_id,$reg_id1,$reg_id2, $message) {
 
    $fields = array(
        'registration_ids'  => array( $reg_id,$reg_id1,$reg_id2,),
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
//APA91bHDUqEEbWHpxzuMMA_ZZXCyRa5YxTc2J1aMRkZfYYlb0Q9w_Dg3FFT0f46FldJBU1Zgafgrk7rPfLCVyfDcjOmj9x5_2KD6L6L1K4hSmVtE67FWJsABRwPqdCzCFIUGrYJG2V9pvnrIl3InHvQtT4jIwOnJMQ 
//APA91bHDUqEEbWHpxzuMMA_ZZXCyRa5YxTc2J1aMRkZfYYlb0Q9w_Dg3FFT0f46FldJBU1Zgafgrk7rPfLCVyfDcjOmj9x5_2KD6L6L1K4hSmVtE67FWJsABRwPqdCzCFIUGrYJG2V9pvnrIl3InHvQtT4jIwOnJMQ
//$reg_id = "APA91bGY18-zLrCj19RSm3W6AGBT-obK587s3KZhJUudIbtnReTZvTIKyXfOXBPmymVTf15mwP_LDd-nQTdeng1GDyUgvQa5COhtBsraukuEr8alaxIjTLPxI4DNKWfsUGMC9PMATwWvZEQHrdaTd7ichT_S4meQXA";
$reg_id = "APA91bHDUqEEbWHpxzuMMA_ZZXCyRa5YxTc2J1aMRkZfYYlb0Q9w_Dg3FFT0f46FldJBU1Zgafgrk7rPfLCVyfDcjOmj9x5_2KD6L6L1K4hSmVtE67FWJsABRwPqdCzCFIUGrYJG2V9pvnrIl3InHvQtT4jIwOnJMQ";
$reg_id1 ="APA91bGedKKCAucpJqYz0ZeZ53zXhRZ1_IfbCu-2YeLxQc-JBJ_I40zBoUZM34Ab_wcHGXxhFIkcpAwyGi7r5SvwDlVHviXlC2UxZ9ueOlfgD5qVRmhXbQ_bu1v_pwYj6iggiAjpKE9DkEvODDF_64OEJBEI0IgIK5VuTRCRe3rqEzCV7JYc66A"; 
$reg_id2 ="APA91bGve2pQqaOrpF7-RfI_XWC__hbuS4op_vP_S8i4oiU2XtI274QRh7Co6xV0CKdxHkQKcSIaPeqo-JLn_nQJTzAUnlpkWpfi9HSdHU0-lhQwNmhSKhFik3I-DUBVhgH-9kMo34Z7OqVI1oKwSU25b7iiePgoTCOx0L4WcTPVoYs6TcR3CP4";
 
//$msg = "Google Cloud Messaging working well";
$msg = "成功的推送了";
 
send_gcm_notify($reg_id,$reg_id1,$reg_id2, $msg);