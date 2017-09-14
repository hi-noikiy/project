<?php
//define('ROOT_PATH', str_replace('analysis/header.php', '', str_replace('\\', '/', __FILE__)));
//define('A_ROOT', ROOT_PATH.'analysis/');
set_time_limit(6000);
//ini_set ('memory_limit', '1024M');
error_reporting(0);
include 'config/config.php';
include 'inc/files.inc.php';
//区服列表
include "inc/servers.php";
//渠道列表
include "inc/fenbao.php";

$servers_grp = include 'inc/server_group.inc.php';
$servers_list = include 'inc/server_list.inc.php';

//查询条件

$bt = !empty($_GET['bt']) ? $_GET['bt'] : date('Y-m-d', strtotime('-7 days'));
$et = !empty($_GET['et']) ? $_GET['et'] : date('Y-m-d', strtotime('-1 days'));
if (date('d')=='01' && empty($_GET['et'])) {
	$et = date('Y-m-d');
}
//$sid        = intval($_GET['server_id']);
//$serverids  = isset($_GET['serverids']) ? $_GET['serverids'] : array();
//if (!empty($_GET['server_gid']) && $_GET['server_gid']>0) {
//    $s_gid = intval($_GET['server_gid']);
//    $serverids = $sid>0 ? array($sid) : array_keys($servers_list[$s_gid]);
//}

$min_sid   = intval($_GET['min_sid']);
$max_sid   = intval($_GET['max_sid']);

$serverids  = array();
if ($min_sid!=$max_sid) {
	if ($_GET['test']) {
		echo '<pre>';
		var_dump($max_sid);
		var_dump($min_sid);
		echo '</pre>';
	}
    if ($min_sid > $max_sid ) {
        $tmp = $max_sid;
        $max_sid = $min_sid;
        $min_sid = $tmp;
    }
    for ($min_sid; $min_sid<=$max_sid;$min_sid++) {
        $serverids[] = $min_sid;
    }
}
elseif($min_sid==$max_sid && $min_sid>0) {
	$serverids[] = $min_sid;
}


if ($_GET['test']) {
    echo '<pre>';
    var_dump($serverids);
    echo '</pre>';
}
if ($_SESSION['channel']) {
    $fenbaoids      = array($_SESSION['channel']);
    $noFenbaoFilter = true;
}
else {
    $fenbaoids = isset($_GET['fenbaoids']) ? $_GET['fenbaoids'] : array();
}

if (isset($_GET['game_id'])) {
    if (isset($_COOKIE['game_id'])) {
        setcookie('game_id','',10000);
    }
    setcookie('game_id', intval($_GET['game_id']), $_SERVER['REQUEST_TIME']+strtotime('+1 weeks'));
}

$game_id = isset($_COOKIE['game_id'])? $_COOKIE['game_id'] :5;
//分页
$currentPage = $_REQUEST["currentPage"]?$_REQUEST["currentPage"]:1;
$pageSize    = $_REQUEST["pageSize"]?$_REQUEST["pageSize"]:40;
$offset      = ($currentPage-1)*$pageSize;
//数据库连接
$db_sum  = db('analysis');
if (isset($initDbSource)) {
    $db_source = db('gamedata');
}
//登陆判断
if (System::UserLoginCheck()===false) {
    header('location:login.php');
    exit;
}
//print_r($_SESSION);
//页面权限判断
$filename = $_SERVER['PHP_SELF'];
$filename = substr($filename, strrpos ($filename,'/')+1);
$fileLev  = System::UserRightsChk($db_sum, $filename);
$urightsId = $_SESSION['urights']!=='all' ? explode(',', $_SESSION['urights']) : 'all';
$white_list = array('s_player_through.php','s_word_drop.php');
if ($_SESSION['uid']!=1) {
    if ($filename !='index.php' && $fileLev===false && !in_array($filename, $white_list)) {
//        header("Content-type: text/html; charset=utf-8");
//        exit('您没有权限访问此页面！');
    }
}


$pageHeader = $files_no_grp[$fileLev['id']]['title_'.$_COOKIE['lang']];

?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>海牛游戏——运营管理后台</title>
    <link href="public/css/bootstrap.min.css" rel="stylesheet">
    <link href="public/css/sb-admin.css" rel="stylesheet">
    <link href="public/css/page.css" rel="stylesheet" type="text/css">
    <link href="public/css/plugins/dataTables/dataTables.bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="public/css/assets/jquery.multiselect.css" />
    <link rel="stylesheet" type="text/css" href="public/css/assets/style.css" />
    <link rel="stylesheet" type="text/css" href="public/css/assets/prettify.css" />
    <link rel="stylesheet" type="text/css" href="public/css/ui-lightness/jquery-ui-1.10.4.min.css" />
    <link href="http://cdn.staticfile.org/font-awesome/4.1.0/css/font-awesome.min.css" rel="stylesheet">
    <script src="public/js/jquery-1.10.2.js"></script>
</head>
<body>
    <div id="wrapper">
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <a class="navbar-brand" href="index.php"><?php echo $lang['h_webtitle']?></a>
            </div>
            <ul class="nav navbar-top-links navbar-right">
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-language fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-lang">
                        <li><a href="lang_setting.php?lang=zh_CN"><i class="fa fa-fw"></i>简体中文</a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="lang_setting.php?lang=en_US"><i class="fa fa-fw"></i>English</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i>  <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="#"><i class="fa fa-gear fa-fw"></i><?=$lang['h_setting']?></a>
                        </li>
                        <li class="divider"></li>
                        <li><a href="logout.php"><i class="fa fa-sign-out fa-fw"></i><?=$lang['h_logout']?></a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->
            <div class="navbar-default navbar-static-side" role="navigation">
                <div class="sidebar-collapse">
                    <ul class="nav" id="side-menu">
                        <li class="sidebar-search">
                            <form method="get" name="changegame" id="changegame">
                                <select class="form-control" name="game_id"  onchange="document.changegame.submit()">
                                    <option value="5">三国将魂录</option>
<!--                                    <option value="2" --><?php //if($_COOKIE['game_id']==2) echo 'selected' ?><!-->llll</option>-->
                                </select>
                            </form>
                            <!-- /input-group -->
                        </li>
<!--                        <li>-->
<!--                            <a href="index.php"><i class="fa fa-dashboard fa-fw"></i> Dashboard</a>-->
<!--                        </li>-->
                        <!-- <li <?=$fileLev['gid']==1?'class="active"':''?>>
                            <a href="#"><i class="fa fa-bar-chart-o fa-fw"></i><?=$lang['n_datacount']?><span class="fa arrow"></span></a>
                             <ul class="nav nav-second-level active">
                                 <?php foreach($files[1] as $file):?>
                                     <?if($urightsId!=='all' && !in_array($file['id'], $urightsId) && !in_array($filename, $white_list)) continue;?>
                                     <li> <a href="<?=$file['path']?>"><?=$file['title_'.$_COOKIE['lang']]?></a> </li>
                                 <?php endforeach;?>
                            </ul>
                        </li>
                        <li <?=$fileLev['gid']==7?'class="active"':''?>>
                            <a href="#"><i class="fa fa-money fa-fw"></i><?=$lang['n_pay']?><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <?php foreach($files[7] as $file):?>
                                    <?if($urightsId!=='all' && !in_array($file['id'], $urightsId) && !in_array($filename, $white_list)) continue;?>
                                    <li> <a href="<?=$file['path']?>"><?=$file['title_'.$_COOKIE['lang']]?></a> </li>
                                <?php endforeach;?>
                            </ul>
                        </li>
                        <li <?=$fileLev['gid']==2?'class="active"':''?>>
                            <a href="#"><i class="fa fa-apple fa-fw"></i><?=$lang['n_apple']?><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <?php foreach($files[2] as $file):?>
                                    <?if($urightsId!=='all' && !in_array($file['id'], $urightsId) && !in_array($filename, $white_list)) continue;?>
                                    <li> <a href="<?=$file['path']?>"><?=$file['title_'.$_COOKIE['lang']]?></a> </li>
                                <?php endforeach;?>
                            </ul>
                        </li> -->

                        <li <?=$fileLev['gid']==5?'class="active"':''?> data-fileid="<?=$fileLev['gid']?>" >
                            <a href="#"><i class="fa fa-files-o fa-fw"></i><?=$lang['n_pannel'];?><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <?php foreach($files[5] as $file):?>
                                    <?if($urightsId!=='all' && !in_array($file['id'], $urightsId) && !in_array($filename, $white_list)) continue;?>
                                    <li> <a href="<?=$file['path']?>"><?=$file['title_'.$_COOKIE['lang']]?></a> </li>
                                <?php endforeach;?>
                            </ul>
                        </li>
                        <!-- <li <?=$fileLev['gid']==8?'class="active"':''?> data-fileid="<?=$fileLev['gid']?>" >
                            <a href="#"><i class="fa fa-files-o fa-fw"></i><?=$lang['n_analysis'];?><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <?php foreach($files[8] as $file):?>
                                    <?if($urightsId!=='all' && !in_array($file['id'], $urightsId) && !in_array($filename, $white_list)) continue;?>
                                    <li> <a href="<?=$file['path']?>"><?=$file['title_'.$_COOKIE['lang']]?></a> </li>
                                <?php endforeach;?>
                            </ul>
                        </li>
						<li <?=$fileLev['gid']==9?'class="active"':''?> data-fileid="<?=$fileLev['gid']?>" >
                            <a href="#"><i class="fa fa-files-o fa-fw"></i><?=$lang['n_develope_statistical'];?><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <?php foreach($files[9] as $file):?>
                                    <?if($urightsId!=='all' && !in_array($file['id'], $urightsId) && !in_array($filename, $white_list)) continue;?>
                                    <li> <a href="<?=$file['path']?>"><?=$file['title_'.$_COOKIE['lang']]?></a> </li>
                                <?php endforeach;?>
                            </ul>
                        </li>
						
						<li <?=$fileLev['gid']==10?'class="active"':''?> data-fileid="<?=$fileLev['gid']?>" >
                            <a href="#"><i class="fa fa-files-o fa-fw"></i><?=$lang['p_analysis'];?><span class="fa arrow"></span></a>
                            <ul class="nav nav-second-level">
                                <?php foreach($files[10] as $file):?>
                                    <?if($urightsId!=='all' && !in_array($file['id'], $urightsId) && !in_array($filename, $white_list)) continue;?>
                                    <li> <a href="<?=$file['path']?>"><?=$file['title_'.$_COOKIE['lang']]?></a> </li>
                                <?php endforeach;?>
                            </ul>
                        </li> -->
						
                    </ul>
                    <!-- /#side-menu -->
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>
        <div id="nav_toggle" title="显示/隐藏" class="show-hide"><a href="javascript:;" class="close-status">&nbsp;</a></div>
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1><?php echo $pageHeader;?></h1>
                </div>
                <!-- /.col-lg-12 -->
            </div>

