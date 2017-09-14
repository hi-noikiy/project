<?php
/**
 * Created by PhpStorm.
 * User: luoxue
 * Date: 2017/2/21
 * Time: 下午2:04
 * @params 接收参数，重组生成字符串+key sign加密
 * @return 返回sign加密值
 */
include_once 'config.php';
include_once 'ucGameServer/service/BaseSDKService.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","uc_setSign_info_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

if(empty($_REQUEST)){
    echo json_encode(array('status'=>1, 'msg'=>'fail'));
    exit();
}
$baseInfo = BaseSDKService::getSignData($_REQUEST);
$appkey = CConfigHelper::getStrVal("sdkserver.game.apikey");
$sign = md5($baseInfo.$appkey);
echo json_encode(array('status'=>0, 'msg'=>'success','data'=>array('sign'=>$sign)));
exit();