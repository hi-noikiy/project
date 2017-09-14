<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-6-13
 * Time: 下午6:15
 */
define('ROOT_PATH', str_replace('analysis/ajax/call.php', '', str_replace('\\', '/', __FILE__)));
define('A_ROOT', ROOT_PATH.'analysis/');
set_time_limit(0);
include A_ROOT.'config/config.php';
include A_ROOT.'inc/func.inc.php';

$action = $_REQUEST['action'];
//发布公告
$actionObject = $_REQUEST['actionObject'];
if ($actionObject=="Notice") {
    if ($action=='Show') {
        //GETJSON
    }
    else if ($action=='List') {
        //GETJSON
    }
    else {
        $salt   = 'u591212';
        $action = $_REQUEST['action'];
        $token  = md5(md5($salt.$action));
        $data   = $_POST;
        $data['token'] = $token;
        $ret = request_post('http://14.17.105.217/interface/notice_212/callback.php', $data);
    }
    echo $ret;
    exit;
}
elseif ($actionObject=='PlayerInfo') {
    $db_source = db('gamedata');
    $serverid = intval($_GET['serverid']);
    $accountid = intval($_GET['accountid']);
    $sql = "SELECT `name` FROM player WHERE accountid=? AND serverid=? LIMIT 1";
    $stmt = $db_source->prepare($sql);
    //print_r(array($accountid, $serverid));
    $stmt->execute(array($accountid, $serverid));
    $name = $stmt->fetchColumn();
//    print_r($name);
    if(!$name) {
        $ret = array('status'=>'fail', 'msg'=>'Player Not Exist');
    }
    else {
        $ret = array('status'=>'ok','name'=>$name);
    }
    echo json_encode($ret);
    $db_source = null;
    exit;
}

$db = db('analysis');
$sysObj = new System($db);
if(!$action) {
    exit;
}

switch ($action) {
    case 'GetGrpRights':
        $grpid = intval($_GET['grpid']);
        $arr = $sysObj->GroupFileList($grpid);
        $ret = $sysObj->FilesFormat($arr);
        echo $ret;
        exit;
        break;
    case 'UserAdd':
        $user = $_POST['user'];
        $urights = implode(',', $user['urights']);
        $ret  = $sysObj->UserAdd($user['ugrp'], $user['account'],
            $user['uname'], $urights, $user['upwd'], $user['repwd']);
        break;
    case 'UserUpdate':
        $user = $_POST['user'];
        if (isset($user['urights'])) {
            $user['urights'] = implode(',', $_POST['user']['urights']);
        }
        $ret = $sysObj->UserUpdate($user['id'], $user);
        break;
    case 'GroupAdd':
        $grp = $_POST['grp'];
        $rights = $_POST['user']['urights'];
        $ret = $sysObj->GroupAdd($grp['gname'], $grp['gtype'], $rights);
        break;
    case 'GroupUpdate':
        $grp = $_POST['grp'];
        //print_r($_POST);exit;
        $rights = $_POST['user']['urights'];
        $ret = $sysObj->GroupUpdate($grp['id'], $grp['gname'], $grp['gtype'], $rights);
        break;
    case 'FileAdd':
        $file = $_POST['file'];
        $ret = $sysObj->FileAdd($file['fpath'], $file['ftitle_zh'], $file['ftitle_en'], $file['gid']);
        break;
    case 'FileUpdate':
        $file = $_POST['file'];
        $ret = $sysObj->FileUpdate($file['id'], $file['fpath'], $file['ftitle_zh'], $file['ftitle_en'], $file['gid']);
        break;
    case 'UserPasswordReset':
        $pwd = $_POST['user'];
        $ret = $sysObj->UserPasswordReset($_SESSION['uid'], $pwd['oldpwd'], $pwd['upwd'], $pwd['repwd']);
        break;
    case 'FileSort':
        if($sysObj->FileSort(intval($_POST['fid']), intval($_POST['fsort']))!==false)
        {
            echo 'ok';
        }
        else {
            echo 'fail';
        }
        break;
    case 'ServerAdd':
        $ret = $sysObj->AddServer();
        break;
    case 'ServerGroupAdd':
        $ret = $sysObj->AddServerGroup();
        break;
    case 'ServerList':
        $sid = intval($_GET['sid']);
        $servers_list = include '../inc/server_list.inc.php';
        $ret = $servers_list[$sid];
//        echo json_encode($ret);
//        exit;
        break;
    case 'AccountLimit':
        $db_game    = db('gamedata');
        $serverId   = intval($_POST['serverid']);
        $accountId  = trim($_POST['accountid']);
        $flag       = intval($_POST['flag']);
        $sqlChk     = "SELECT flag FROM account_limit WHERE accountid=$accountId AND serverid=$serverId LIMIT 1";
        $stmt       = $db_game->prepare($sqlChk);
//        echo $sqlChk;
        $stmt->execute();
        $eflag = $stmt->fetchColumn(0);
        if ( $eflag===false ) {
            $sql     = <<<SQL
INSERT INTO account_limit (accountid, serverid, flag,created_time,operator)
VALUES($accountId,$serverId, $flag, now(), '{$_SESSION ['uname']}')
SQL;
        } else {
            $sql     = <<<SQL
UPDATE account_limit SET flag=$flag,updated_time=now(),
operator='{$_SESSION ['uname']}'
WHERE accountid=$accountId AND serverid=$serverId LIMIT 1
SQL;
        }
        try {

            $sql2   = "UPDATE u_player_through_time_bg SET flag={$flag} WHERE accountid=$accountId AND serverid=$serverId";
//            echo $sql;
//            echo $sql2;
//            exit;
            $msg = '{"status":"ok"}';
            if ($db_game->exec($sql2)===false) {
                $msg = '{"status":"fail", "msg":"UPDATE u_player_through_time_bg Fail"}';
            }
            if ($db_game->exec($sql)===false) {
                $msg = '{"status":"fail","msg":"UPDATE account_limit Fail"}';
            }
            echo $msg;

        } catch (PDOException $e) {
            echo '{"status":"fail","msg":"'.$e->getMessage().'"}';
        }
        exit;
        break;
    default:break;
}
echo json_encode($ret);