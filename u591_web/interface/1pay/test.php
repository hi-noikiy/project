<?php
include_once 'config.php';
$access_key = 'uzc5r6mjo1myb3skppam';
$pin = '0779613481244';
$serial = '78431540329';
$transRef = '17121212333641187649';
$type = 'viettel';
$secret = '4axe31lz16g22u5fhpg3gi8eurcb4rie';
$data_ep = "access_key=" . $access_key . "&pin=" . $pin . "&serial=" . $serial . "&transId=&transRef=" . $transRef . "&type=" . $type;
$signature_ep = hash_hmac("sha256", $data_ep, $secret);
$data_ep.= "&signature=" . $signature_ep;
 $url = 'https://api.1pay.vn/card-charging/v5/query';
    $query_api_ep = execPostRequest($url, $data_ep);
    print_r($query_api_ep);die;
