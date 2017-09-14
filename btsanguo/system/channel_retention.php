<?php
include("inc/config.php");
include("inc/CheckUser.php");
include("inc/function.php");
include("inc/page.php");
include("../inc/config.php");
include("../inc/function.php");
include("inc/game_config.php");
$gameList = $game_arr;
set_time_limit(600);

$time = $_REQUEST['time'];


if($time){

    $liucun['PP助手'] =  $liucun_pp = qudao_liucun("new_account_pp_log_","old_account_pp_log_",strtotime($time));
    $liucun['360'] =      $liucun_360 = qudao_liucun("new_account_360_log_","old_account_360_log_",strtotime($time));
    $liucun['37wan'] =      $liucun_zhen_37wan = qudao_liucun("new_account_zhen_37wan_log_","old_account_zhen_37wan_log_",strtotime($time));
    $liucun['37wan_ios'] =      $liucun_37wan_ios = qudao_liucun("new_account_ios_37wan_log_","old_account_37wanios_log_",strtotime($time));
    $liucun['17173'] =      $liucun_17173 = qudao_liucun("new_account_37wan_log_","old_account_37wan_log_",strtotime($time));
    $liucun['3g门户'] =      $liucun_3g2324 = qudao_liucun("new_account_3g_log_","old_account_3g2324_log_",strtotime($time));
    $liucun['5gwan'] =      $liucun_5gwan = qudao_liucun("new_account_5gwan_log_","old_account_5gwan_log_",strtotime($time));
    $liucun['5gwan渠道'] =      $liucun_5gwan_channel = qudao_liucun("new_account_5gwan_channel_log_","old_account_5gwan_channel_log_",strtotime($time));
    $liucun['8849'] =      $liucun_8849 = qudao_liucun("new_account_8849_log_","old_account_8849_log_",strtotime($time));//不准
    $liucun['8849渠道'] =     $liucun_8849_changel = qudao_liucun("new_account_8849_changel_log_","old_account_8849_changel_log_",strtotime($time));//不准
    $liucun['91'] =     $liucun_91 = qudao_liucun("new_account_91_log_","old_account_91_log_",strtotime($time));
    $liucun['安智'] =      $liucun_anzhi = qudao_liucun("new_account_anzhi_log_","old_account_anzhi_log_",strtotime($time));
    $liucun['应用汇'] =      $liucun_appchina = qudao_liucun("new_account_appchina_log_","old_account_appchina_log_",strtotime($time));
    $liucun['百度'] =      $liucun_baidu = qudao_liucun("new_account_baidu_log_","old_account_baidu_log_",strtotime($time));
    $liucun['禅游'] =     $liucun_chanyou = qudao_liucun("new_account_chanyou_log_","old_account_chanyou_log_",strtotime($time));//不准
    $liucun['虫虫'] =      $liucun_chongchong = qudao_liucun("new_account_chongchong_log_","old_account_chongchong_log_",strtotime($time));
    $liucun['谷果'] =      $liucun_cooguo = qudao_liucun("new_account_cooguo_log_","old_account_cooguo_log_",strtotime($time));
    $liucun['点金'] =      $liucun_dianjin = qudao_liucun("new_account_dianjin_log_","old_account_dianjin_log_",strtotime($time));
    $liucun['当乐'] =      $liucun_dl = qudao_liucun("new_account_dl3_log_","old_account_dl_log_",strtotime($time));
    $liucun['机锋'] =      $liucun_gfan = qudao_liucun("new_account_gfan_log_","old_account_gfan_log_",strtotime($time));
    $liucun['华为'] =     $liucun_huawei = qudao_liucun("new_account_huawei_log_","old_account_huawei_log_",strtotime($time));
    $liucun['itools'] =     $liucun_itools = qudao_liucun("new_account_itools_log_","old_account_itools_log_",strtotime($time));
    $liucun['快玩'] =     $liucun_kuaiwan = qudao_liucun("new_account_kuaiwan_log_","old_account_kuaiwan_log_",strtotime($time));
    $liucun['快用'] =     $liucun_kuaiyong = qudao_liucun("new_account_kaiyong_log_","old_account_kuaiyong_log_",strtotime($time));
    $liucun['酷动'] =      $liucun_kudong = qudao_liucun("new_account_kudong_log_","old_account_kudong_log_",strtotime($time));
    $liucun['联想'] =      $liucun_lenovo = qudao_liucun("new_account_lenovo_log_","old_account_lenovo_log_",strtotime($time));
    $liucun['oppo'] =      $liucun_oppo = qudao_liucun("new_account_oppo_log_","old_account_oppo_log_",strtotime($time));
    $liucun['pptv'] =      $liucun_pptv = qudao_liucun("new_account_pptv_log_","old_account_pptv_log_",strtotime($time));
    $liucun['sogou'] =      $liucun_sogou = qudao_liucun("new_account_sogou_log_","old_account_sogou_log_",strtotime($time));
    $liucun['uc'] =     $liucun_uc = qudao_liucun("new_account_uc_log_","old_account_uc_log_",strtotime($time));
    $liucun['悠悠村'] =     $liucun_uucun = qudao_liucun("new_account_uucun_37wan_log_","old_account_uucun_log_",strtotime($time));
    $liucun['有信'] =      $liucun_uxin = qudao_liucun("new_account_youxin_log_","old_account_uxin_log_",strtotime($time));
    $liucun['vivo'] =      $liucun_vivo = qudao_liucun("new_account_vivo_log_","old_account_vivo_log_",strtotime($time));
    $liucun['万普'] =     $liucun_wanpu = qudao_liucun("new_account_wanpu_log_","old_account_wanpu_log_",strtotime($time));
    $liucun['小米'] =      $liucun_xiaomi = qudao_liucun("new_account_xiaomi_log_","old_account_xiaomi_log_",strtotime($time));
    $liucun['迅雷'] =      $liucun_xunlei = qudao_liucun("new_account_xunlei_log_","old_account_xunlei_log_",strtotime($time));
    $liucun['丫丫玩'] =     $liucun_yayawan = qudao_liucun("new_account_yayawan_log_","old_account_yayawan_log_",strtotime($time));
    $liucun['丫丫玩2'] =     $liucun_yayawan_2 = qudao_liucun("new_account_yayawan_2_log_","old_account_yayawan_2_log_",strtotime($time));
    $liucun['云游'] =     $liucun_yunyou = qudao_liucun("new_account_yunyou_log_","old_account_yunyou_log_",strtotime($time));



}

function qudao_liucun($login_new_name,$login_old_name,$time){
    if(file_exists(ROOT_PATH."log/".date("ym", $time)."/$login_new_name".date("ymd",$time).".txt")&&file_exists(ROOT_PATH."log/".date("ym", $time+3600*24)."/$login_old_name".date("ymd",$time+3600*24).".txt"))
    {
        $handle = @fopen(ROOT_PATH."log/".date("ym", $time)."/$login_new_name".date("ymd",$time).".txt", "r");
        $title_pattern = "/, return= 1 (\d+)  /is";
        while (!@feof($handle)) {
            $buffer = @fgets($handle, 4096);
            $buffer_matches = array();
            preg_match($title_pattern,$buffer,$buffer_matches);
            if($buffer_matches[1]){
                $new_account[] =  $buffer_matches[1];
            }
        }
        fclose($handle);

        $handle = @fopen(ROOT_PATH."log/".date("ym", $time+3600*24)."/$login_old_name".date("ymd",$time+3600*24).".txt", "r");
        $title_pattern = "/, return= 0 (\d+)  /is";
        while (!@feof($handle)) {
            $buffer = @fgets($handle, 4096);
            $buffer_matches = array();
            preg_match($title_pattern,$buffer,$buffer_matches);
            if($buffer_matches[1]){
                $old_account[] =  $buffer_matches[1];
            }
        }
        fclose($handle);

        $liucun =  array_intersect($new_account, $old_account);
        return number_format(count($liucun)/count($new_account)*100,2)."(".count($liucun)."/".count($new_account).")" ;

    }else{
        return  ;
    }


}


?>
<html>
    <head>
        <title>list</title>
        <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
        <link href="CSS/Style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="JS/calendar.js"></script>
        <script language="javascript" src="JS/jquery-1.3.2.min.js"></script>
    </head>
    <body class="main">
        <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
            <form name="SearchForm" method="POST" action="">
                <tr>
                    <th height="22" colspan="2" align="center">玩家留存率查询</th>
                </tr>

                <tr>
                    <td align="right" class="forumRow">查询时间：</td>
                    <td width="85%" class="forumRow">
                        <input name="time" type="text" size="12" value="<?=$time?>" readonly onfocus="HS_setDate(this)">

                    </td>
                </tr>
                <tr>
                    <td align="right" class="forumRow"></td>
                    <td class="forumRow"><input type="submit" name="Submit2" class="bott01" value=" 确 定 ">
                    </td>
                </tr>
            </form>
        </table>


        <DIV >&nbsp;渠道次日留存率</DIV>
        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">
            <tr height="22">
                <th  height="22" align="center">渠道</th>
                <th  height="22" align="center">次日留存</th>
            </tr>
    
            <?php
            if(count($liucun)){
            foreach($liucun as $key=>$value){
                ?>
                <tr>
                <td nowrap class="forumRow" align="center"><?php echo $key; ?></td>
                <td nowrap class="forumRow" align="center"><?php echo $value; ?></td>
             </tr>
              <?php
            }}

            ?>

        </table>


    </body>

</html>