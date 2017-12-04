<?php
require_once dirname(dirname(__FILE__)) . '/util/HttpUtils.php';
require_once dirname(dirname(__FILE__)) . '/util/Logger.php';
require_once dirname(dirname(__FILE__)) . '/model/UserInfo.php';
require_once dirname(dirname(__FILE__)) . '/model/PayCallback.php';
class SDKServices {

    public static function verifySession(UserInfo $userinfo, $config=array()) {
    	$url = $config['baseUrl'];
    	$gameId = $config['gameId'];
    	$apikey = $config['apikey'];
        $urldata = json_encode(array("gameId" => $gameId, "uid" => $userinfo->userId));
        //-------签名--------------
        $sign = base64_encode(md5($urldata . $apikey, true));
        //------封装http headers-----------
        $headers = array(
            "Content-type:application/json",
            "sid:{$userinfo->sid}",
            "sign:{$sign}"
        );
        //Logger::info('sign=' . $sign);
        $info = HttpUtils::http_post_data($url, $urldata, $headers);
        if ($info['code'] <> 200) {
            return '请求出错';
        } else {
            return $info['msg'];
        }
    }

    public static function verifyNotify($ttsign, $urldata, $chargekey) {
        //-------签名--------------
        $sign = base64_encode(md5($urldata . $chargekey, true));

        //Logger::info('ttsign=' . $ttsign);
        //Logger::info('mysign=' . $sign);

        //-------验证签名是否一致----------
        if ($sign == $ttsign) {
            
            /* ---------解析服务器请求,自定义操作----------
              $backinfo = new PayCallback();
              $backinfo = json_decode($urldata);
              echo $backinfo->cpOrderId . "<br/>";
              echo $backinfo->exInfo . "<br/>";
              echo $backinfo->gameId . "<br/>";
              echo $backinfo->payDate . "<br/>";
              echo $backinfo->payFee . "<br/>";
              -------------------------------------------------- */
            //-------封装响应信息--------
            $msg ['head'] = array("result" => "0", "message" => '验签成功');
        } else {
            //-------封装响应信息--------
            $msg ['head'] = array("result" => "-1", "message" => '验签失败');
        }
        return json_encode($msg);
    }

}
