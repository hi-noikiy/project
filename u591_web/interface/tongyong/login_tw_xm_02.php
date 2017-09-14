<?php
/**
 * ==============================================
 * Copyright (c) 2015 All rights reserved.
 * ----------------------------------------------
 * 通用登陆接口
 * ==============================================
 * @date: 2016-7-28
 * @author: Administrator
 * @return:
 */
include_once 'config.php';
$post = serialize($_POST);
$get = serialize($_GET);
write_log(ROOT_PATH."log","tongyong_info_log_","post=$post,get=$get, ".date("Y-m-d H:i:s")."\r\n");

$sdkType = $_REQUEST['sdktype'];
$token = $_REQUEST['token'];
$p = $_REQUEST['p'];
$gameId = intval($_REQUEST['game_id']);

switch ($sdkType){
    case 7: //当乐
        $url = 'http://127.0.0.1/interface/dangle/login.php';
        $data['token'] = $p;
        $data['mid'] = $token;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 32: //联想
        $url = 'http://127.0.0.1/interface/lenovo/login.php';
        $data['token'] = $p;
        $data['realm'] = $token;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 35: //4399
        $url = 'http://127.0.0.1/interface/4399/login.php';
        $data['uid'] = $token;
        $data['state'] = $p;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 97: //ios pp助手越狱
        $url = 'http://127.0.0.1/interface/pp/login.php';
        $data['sid'] = $p;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 100: //facebook
        $url = 'http://127.0.0.1/interface/facebook/login.php';
        $data['token'] =$p;
        $data['type'] = $token;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 102: //google
        $url = 'http://127.0.0.1/interface/google/login.php';
        $data['access_token'] =$token;
        $data['type'] = $p;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 132: //爱普
        $url = 'http://127.0.0.1/interface/union/login.php';
        $data['token'] = $p;
        $data['userID'] = $token;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 133: //TT语音
        $url = 'http://127.0.0.1/interface/TTyuyin/login.php';
        $data['sid'] = $p;
        $data['uid'] = $token;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 134: //ios 兔兔
        $url = 'http://127.0.0.1/interface/tutu/login.php';
        $data['openid'] = $p;
        $data['openkey'] = $token;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 135: //ios、android 小七
        $url = 'http://127.0.0.1/interface/xiao7/login.php';
        $data['tokenkey'] = $token;
        $data['type'] = $p;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 136: //虫虫
        $url = 'http://127.0.0.1/interface/chongchong/login.php';
        $data['token'] = $p;
        $data['uid'] = $token;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 137: //汉风
        $url = 'http://127.0.0.1/interface/hanfeng/login.php';
        $data['sid'] = $p;
        $data['p'] = $token;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 138: //乐游
        $url = 'http://127.0.0.1/interface/leyou/login.php';
        $data['username'] = $token;
        $data['memkey'] = $p;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 139: //同游游
        $url = 'http://127.0.0.1/interface/tongyouyou/login.php';
        $data['sign'] = $p;
        $data['p'] = $token;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 140: //拇指玩
        $url = 'http://127.0.0.1/interface/muzhiwan/login.php';
        $data['token'] = $token;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 141: //07073
        $url = 'http://127.0.0.1/interface/07073/login.php';
        $data['token'] = $p;
        $data['p'] = $token;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 142: //ios 乐8 越狱
        $url = 'http://127.0.0.1/interface/le8/login.php';
        $data['token'] =$p;
        $data['uid'] =  $token;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 143: //ios itools 越狱
        $url = 'http://127.0.0.1/interface/itools/login.php';
        $data['notify_data'] =$p;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 144: //ios 爱思 越狱
        $url = 'http://127.0.0.1/interface/i4/login.php';
        $data['token'] =$p;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 145: //夜神
        $url = 'http://127.0.0.1/interface/yeshen/login.php';
        $data['accessToken'] =$p;
        $data['uid'] = $token;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 146: //ios熊猫玩
        $url = 'http://127.0.0.1/interface/xmw/login.php';
        $data['access_token'] =$token;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 147: //猎宝
        $url = 'http://127.0.0.1/interface/liebao/login.php';
        $data['username'] = $token;
        $data['logintime'] = $p;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 148: //海马
        $url = 'http://127.0.0.1/interface/haima/login.php';
        $data['t'] = $p;
        $data['uid'] = $token;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 149: //龙虾
        $url = 'http://127.0.0.1/interface/longxia/login.php';
        $data['token'] =$p;
        $data['user_id'] = $token;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 150: //ios 爱应用
        $url = 'http://127.0.0.1/interface/iapp/login.php';
        $data['t'] =$p;
        $data['uid'] = $token;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 151: //ios play800
    case 152: //ios2
    case 153: //ios3
    case 154: //ios4
    case 157: //ios5
    case 158: //ios6
    case 159: //ios7
    case 164: //ios8
    case 165: //ios9
    case 166: //ios10
    case 167: //ios11
    case 168: //ios12
    case 169: //ios13
    case 170: //ios14
    case 171: //ios15
    case 172: //ios16
    case 173: //ios17
    case 174: //ios18
    case 175: //ios19
    case 176: //ios20
    case 177: //ios21
    case 178: //ios22
    case 179: //ios23
    case 180: //ios24
    case 181: //ios25
    case 182: //ios26
    case 183: //ios27
    case 184: //ios28
    case 187: //ios29
    case 188: //ios30
    case 189: //ios31
    case 190:
    case 191:
    case 192:
    case 193:
    case 194:
    case 195:
    case 196:
    case 197:
    case 198:
    case 199:
    case 200:
    case 201:
    case 202:
    case 203:
    case 204:
    case 205:
    case 206:
    case 207:
    case 208:
    case 209:
    case 210:
        $url = 'http://127.0.0.1/interface/play800/login.php';
        $data['sessionid'] =$p;
        $data['uid'] = $token;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 155: //点游
        $url = 'http://127.0.0.1/interface/dianyou/login.php';
        $data['userCertificate'] =$token;
        $data['userid'] = $p;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 156: //奥创
        $url = 'http://127.0.0.1/interface/aochuang/login.php';
        $data['sessionId'] = $token;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 160: //港台
    case 162:
        $url = 'http://127.0.0.1/interface/gangtai/login.php';
        $data['token'] = $token;
        $data['userId'] = $p;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 161: //快发
        $url = 'http://127.0.0.1/interface/kuaifa/login.php';
        $data['token'] = $token;
        $data['openid'] = $p;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 163: //新马魔方
        $url = 'http://127.0.0.1/interface/mofang/login.php';
        $data['usign'] = $token;
        $data['uid'] = $p;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 185: //顺玩
        $url = 'http://127.0.0.1/interface/shunwan/login.php';
        $data['token'] = $token;
        $data['p'] = $p;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 186: //新马爱洛克
        $url = 'http://127.0.0.1/interface/ailuoke/login.php';
        $data['token'] = $p;
        $data['p'] = $token;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 211:
        $url = 'http://127.0.0.1/interface/play800_android/login.php';
        $data['data'] =$p;
        $data['uid'] = $token;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 212: //星趣
        $url = 'http://127.0.0.1/interface/xingqu/login.php';
        $data['access_token'] =$p;
        $data['cch_id'] = $token;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 213: //阿里uc
        $url = 'http://127.0.0.1/interface/uc_new/login.php';
        $data['sid'] =$p;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 218: //阿里豌豆荚
        $url = 'http://127.0.0.1/interface/ali_wdj/login.php';
        $data['sid'] =$p;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 214: //爱乐
        $url = 'http://127.0.0.1/interface/aile/login.php';
        $data['user_token'] =$p;
        $data['mem_id'] = $token;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 215: //爱贝云
        $url = 'http://127.0.0.1/interface/iapppay/login.php';
        $data['logintoken'] =$p;
        $data['Sign'] = $token;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 217: //16yo
        $url = 'http://127.0.0.1/interface/16yo/login.php';
        $data['user_token'] = $p;
        $data['mem_id'] = $token;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
    case 700:
        $url = 'http://127.0.0.1/interface/guanwang/login.php';
        $data['p'] = $p;
        $data['game_id'] = $gameId;
        $rs = https_post($url, $data);
        echo $rs;
        break;
}