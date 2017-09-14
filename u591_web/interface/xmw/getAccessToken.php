<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2016/12/6
 * Time: 下午3:56
 */
require_once 'common.php';
require_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","xmw_token_all_log_","post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
$gameId = intval($_REQUEST['game_id']);
$appId = $key_arr[$gameId]['appkey'];
$appSecret = $key_arr[$gameId]['appsecret'];
if(!$gameId || !$appId || !$appSecret){
    write_log(ROOT_PATH."log","xmw_token_error_log_","parameter error!, post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
    exit("fail");
}

try {
    $oauth2 = new XMWOAuth2($appId, $appSecret);
    $grantType = isset($_REQUEST['grant_type']) ? $_REQUEST['grant_type'] : 'authorization_code';

    if($grantType === 'authorization_code')
    {
        $data = $oauth2->getAccessTokenByCode($_REQUEST['token']);
    }
    elseif($grantType === 'refresh_token')
    {
        $data = $oauth2->getAccessTokenByRefreshToken($_REQUEST['token']);
    }
    else
    {
        $XMWException = new XMWException($XMWException::CODE_PARAM_ERROR);
        write_log(ROOT_PATH."log","xmw_token_error_log_","parameter error!, xmwError=$XMWException, post=$post, get=$get, ".date("Y-m-d H:i:s")."\r\n");
        exit("fail");
    }

    if (!isset($data['access_token'])){
        write_log(ROOT_PATH."log","xmw_token_error_log_","sign error!, ".json_encode($data). ",  ".date("Y-m-d H:i:s")."\r\n");
        exit("fail");
    }
    $jsonData = json_encode($data);
    write_log(ROOT_PATH."log","xmw_token_result_log_","data=$jsonData, ".date("Y-m-d H:i:s")."\r\n");


    $jsonData = json_encode($data);
    if(isset($data['access_token'])){
        $accessToken = $data['access_token'];
        exit($accessToken);
    } else {
        exit('fail');
    }

}
catch(XMWException $exception)
{
    $errorMsg = $exception->getMessage();
    write_log(ROOT_PATH."log","xmw_token_error_log_","sign error!,error_description=$errorMsg, ".date("Y-m-d H:i:s")."\r\n");
    exit('fail');
}