<?php
function qq_check_token($sdk, $params){
    $method = 'get';
    $script_name = '/auth/qq_check_token';
    return $sdk->api_ysdk($script_name, $params, $method);
}
function wx_check_token($sdk, $params){
    $method = 'get';
    $script_name = '/auth/wx_check_token';
    return $sdk->api_ysdk($script_name, $params, $method);
}
?>