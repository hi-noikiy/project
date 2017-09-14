<?php
include("inc/config.php");
include("inc/CheckUser.php");
include("inc/function.php");
include("inc/page.php");
include("../inc/config.php");
include("../inc/function.php");
include("inc/game_config.php");

if($_POST['action']=='select'){
    $time = $_POST['time'];
    $pp_num = text_num("new_account_pp_log_",$time);
    $num_360 = text_num("new_account_360_log_",$time);
    $num_37wan = text_num("new_account_zhen_37wan_log_",$time);
    $num_17173 = text_num("new_account_37wan_log_",$time);
    $num_5gwan = text_num("new_account_5gwan_log_",$time);
    $num_5gwan_channel = text_num("new_account_5gwan_channel_log_",$time);
    $num_8849 = text_num("new_account_8849_log_",$time);
    $num_91 = text_num("new_account_91_log_",$time);
    $num_anzhi = text_num("new_account_anzhi_log_",$time);
    $num_appchina = text_num("new_account_appchina_log_",$time);
    $num_chanyou = text_num("new_account_chanyou_log_",$time);
    $num_dianjin = text_num("new_account_dianjin_log_",$time);
    $num_dl = text_num("new_account_dl3_log_",$time);
    $num_gfan = text_num("new_account_gfan_log_",$time);
    $num_huawei = text_num("new_account_huawei_log_",$time);
    $num_itools = text_num("new_account_itools_log_",$time);
    $num_lenovo = text_num("new_account_lenovo_log_",$time);
    $num_oppo = text_num("new_account_oppo_log_",$time);
    $num_pptv = text_num("new_account_pptv_log_",$time);
    $num_uc = text_num("new_account_uc_log_",$time);
    $num_youxin = text_num("new_account_youxin_log_",$time);
    $num_wanpu = text_num("new_account_wanpu_log_",$time);
    $num_xiaomi = text_num("new_account_xiaomi_log_",$time);
    $num_baidu = text_num("new_account_baidu_log_",$time);
    $num_cooguo = text_num("new_account_cooguo_log_",$time);
    $num_vivo = text_num("new_account_vivo_log_",$time);
    $num_3g = text_num("new_account_3g_log_",$time);
    $num_keno = text_num("new_account_keno_log_",$time);
    $num_uucun = text_num("new_account_uucun_37wan_log_",$time);
    $num_kudong = text_num("new_account_kudong_log_",$time);
    $num_yayawan = text_num("new_account_yayawan_log_",$time);
    $num_chongchong = text_num("new_account_chongchong_log_",$time);

}

function text_num($login_name,$time){

    if(file_exists(ROOT_PATH."log/".date("ym", strtotime($time))."/$login_name".date("ymd",strtotime($time)).".txt"))
    {
        $handle = @fopen(ROOT_PATH."log/".date("ym", strtotime($time))."/$login_name".date("ymd",strtotime($time)).".txt", "r");
        $i=0;
        while (!@feof($handle)) {
            $buffer = @fgets($handle, 4096);
            $i++;
        }
        fclose($handle);
        return  $i;
    }else{
        return 0 ;
    }

}



?>


<html>
    <head>
        <title>list</title>
        <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
        <link href="CSS/Style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="JS/DateTime/DateDialog.js"></script>
        <script language="javascript" src="JS/ActionFrom.js"></script>

        <script language="javascript" src="JS/jquery-1.3.2.min.js"></script>

        <script language="javascript" src="JS/calendar.js"></script>

    </head>
    <body class="main">

        <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
            <form name="SearchForm" method="POST" action="">
                <input type="hidden" name="action" value="select">
                <tr>
                    <th height="22" colspan="2" align="center">玩家新注册</th>
                </tr>

                <tr>
                    <td align="right" class="forumRow">日期：</td>

                    <td width="85%" class="forumRow">
                        <input name="time" type="text" size="12" value="<?=$time?>" readonly onfocus="HS_setDate(this)">
                    </td>

                </tr>
                <tr>
                    <td align="right" class="forumRow">&nbsp;</td>
                    <td class="forumRow"><input type="submit" name="Submit2" class="bott01" value=" 搜 索 ">时间不能为空</td>
                </tr>
            </form>
        </table>
        <DIV style="FONT-SIZE: 2px">&nbsp;</DIV>

        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">

            <tr height="22">
                <th width="96" height="22" align="center">渠道</th>
                <th width="96" height="22" align="center">人数</th>
            </tr>


            <tr bgcolor="#ECECED">
                <td width="96" height="22" align="center">pp助手</td>
                <td width="96" height="22" align="center"><?php echo $pp_num;?></td>
            </tr>
            <tr bgcolor="#ECECED">
                <td width="96" height="22" align="center">360</td>
                <td width="96" height="22" align="center"><?php echo $num_360;?></td>
            </tr>

            <tr bgcolor="#ECECED">
                <td width="96" height="22" align="center">37wan</td>
                <td width="96" height="22" align="center"><?php echo $num_37wan;?></td>
            </tr>
            <tr bgcolor="#ECECED">
                <td width="96" height="22" align="center">17173</td>
                <td width="96" height="22" align="center"><?php echo $num_17173;?></td>
            </tr>
            <tr bgcolor="#ECECED">
                <td width="96" height="22" align="center">5gwan</td>
                <td width="96" height="22" align="center"><?php echo $num_5gwan;?></td>
            </tr>
            <tr bgcolor="#ECECED">
                <td width="96" height="22" align="center">5gwan渠道</td>
                <td width="96" height="22" align="center"><?php echo $num_5gwan_channel;?></td>
            </tr>
            <tr bgcolor="#ECECED">
                <td width="96" height="22" align="center">8849</td>
                <td width="96" height="22" align="center"><?php echo $num_8849;?></td>
            </tr>
            <tr bgcolor="#ECECED">
                <td width="96" height="22" align="center">91</td>
                <td width="96" height="22" align="center"><?php echo $num_91;?></td>
            </tr>            <tr bgcolor="#ECECED">
                <td width="96" height="22" align="center">安智</td>
                <td width="96" height="22" align="center"><?php echo $num_anzhi;?></td>
            </tr>
            <tr bgcolor="#ECECED">
                <td width="96" height="22" align="center">应用汇</td>
                <td width="96" height="22" align="center"><?php echo $num_appchina;?></td>
            </tr>
            <tr bgcolor="#ECECED">
                <td width="96" height="22" align="center">禅游</td>
                <td width="96" height="22" align="center"><?php echo $num_chanyou;?></td>
            </tr>
            <tr bgcolor="#ECECED">
                <td width="96" height="22" align="center">点金</td>
                <td width="96" height="22" align="center"><?php echo $num_dianjin;?></td>
            </tr>
            <tr bgcolor="#ECECED">
                <td width="96" height="22" align="center">当乐</td>
                <td width="96" height="22" align="center"><?php echo $num_dl;?></td>
            </tr>
            <tr bgcolor="#ECECED">
                <td width="96" height="22" align="center">机锋</td>
                <td width="96" height="22" align="center"><?php echo $num_gfan;?></td>
            </tr>
            <tr bgcolor="#ECECED">
                <td width="96" height="22" align="center">华为</td>
                <td width="96" height="22" align="center"><?php echo $num_huawei;?></td>
            </tr>
            <tr bgcolor="#ECECED">
                <td width="96" height="22" align="center">itools</td>
                <td width="96" height="22" align="center"><?php echo $num_itools;?></td>
            </tr>            <tr bgcolor="#ECECED">
                <td width="96" height="22" align="center">联想</td>
                <td width="96" height="22" align="center"><?php echo $num_lenovo;?></td>
            </tr>
            <tr bgcolor="#ECECED">
                <td width="96" height="22" align="center">oppo</td>
                <td width="96" height="22" align="center"><?php echo $num_oppo;?></td>
            </tr>

            <tr bgcolor="#ECECED">
                <td width="96" height="22" align="center">pptv</td>
                <td width="96" height="22" align="center"><?php echo $num_pptv;?></td>
            </tr>

            <tr bgcolor="#ECECED">
                <td width="96" height="22" align="center">uc</td>
                <td width="96" height="22" align="center"><?php echo $num_uc;?></td>
            </tr>
            <tr bgcolor="#ECECED">
                <td width="96" height="22" align="center">有信</td>
                <td width="96" height="22" align="center"><?php echo $num_youxin;?></td>
            </tr>
            <tr bgcolor="#ECECED">
                <td width="96" height="22" align="center">万普</td>
                <td width="96" height="22" align="center"><?php echo $num_wanpu;?></td>
            </tr>
            <tr bgcolor="#ECECED">
                <td width="96" height="22" align="center">小米</td>
                <td width="96" height="22" align="center"><?php echo $num_xiaomi;?></td>
            </tr>
            <tr bgcolor="#ECECED">
                <td width="96" height="22" align="center">百度</td>
                <td width="96" height="22" align="center"><?php echo $num_baidu;?></td>
            </tr>
            
             <tr bgcolor="#ECECED">
                <td width="96" height="22" align="center">谷果</td>
                <td width="96" height="22" align="center"><?php echo $num_cooguo;?></td>
            </tr>
            <tr bgcolor="#ECECED">
                <td width="96" height="22" align="center">vivo</td>
                <td width="96" height="22" align="center"><?php echo $num_vivo;?></td>
            </tr>
            <tr bgcolor="#ECECED">
                <td width="96" height="22" align="center">3g门户</td>
                <td width="96" height="22" align="center"><?php echo $num_3g;?></td>
            </tr>
            <tr bgcolor="#ECECED">
                <td width="96" height="22" align="center">博雅科诺</td>
                <td width="96" height="22" align="center"><?php echo $num_keno;?></td>
            </tr>
            <tr bgcolor="#ECECED">
                <td width="96" height="22" align="center">悠悠村</td>
                <td width="96" height="22" align="center"><?php echo $num_uucun;?></td>
            </tr>
            <tr bgcolor="#ECECED">
                <td width="96" height="22" align="center">酷动</td>
                <td width="96" height="22" align="center"><?php echo $num_kudong;?></td>
            </tr>
            <tr bgcolor="#ECECED">
                <td width="96" height="22" align="center">丫丫玩</td>
                <td width="96" height="22" align="center"><?php echo $num_yayawan;?></td>
            </tr>
            <tr bgcolor="#ECECED">
                <td width="96" height="22" align="center">乐非凡</td>
                <td width="96" height="22" align="center"><?php echo $num_chongchong;?></td>
            </tr>


        </table>

    </body>
</html>