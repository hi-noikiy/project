<?php
include("inc/config.php");
include("inc/CheckUser.php");
include("inc/function.php");
include("inc/page.php");
include("../inc/config.php");
include("../inc/function.php");
include("inc/game_config.php");

if($_POST['action']=='sub'){
    $time = $_POST['time'];
    $login_name = $_POST['login_name'];

    if(file_exists(ROOT_PATH."log/".date("ym", strtotime($time))."/$login_name".date("ymd",strtotime($time)).".txt"))
    {
        $handle = @fopen(ROOT_PATH."log/".date("ym", strtotime($time))."/$login_name".date("ymd",strtotime($time)).".txt", "r");
        while (!@feof($handle)) {
            $buffer = @fgets($handle, 4096);
            if($buffer){
                echo   $buffer_alert = $buffer;
                echo "<br/>";
            }
        }
        fclose($handle);

    }else{
        echo "没有该日志记录";
    }

    exit;
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
            <form name="SearchForm" method="POST" target="_blank" action="">
                <input type="hidden" name="action" value="sub">
                <tr>
                    <th height="22" colspan="2" align="center">sdk日志查看</th>
                </tr>

                <tr>
                    <td align="right" class="forumRow">日期：</td>

                    <td width="85%" class="forumRow">
                        <input name="time" type="text" size="12" value="<?=$time?>" readonly onfocus="HS_setDate(this)">
                    </td>

                </tr>
                <tr>
                    <td align="right" class="forumRow">渠道日志：</td>

                    <td width="85%" class="forumRow">
                        <select name="login_name">
                            <option value="">请选择日志类型</option>

                            <option value="sogou_login_info_all_">搜狗登陆日志</option>
                            <option value="sogou_login_result_log_">搜狗渠道方登陆请求日志</option>
                            <option value="sogou_login_error_">搜狗登陆错误日志</option>
                            <option value="sogou_callback_all_">搜狗回调日志</option>
                            <option value="sogou_callback_error_">搜狗回调错误日志</option>

                            <option value="xunlei_login_info_all_">迅雷登陆日志</option>
                            <option value="xunlei_login_result_log_">迅雷渠道方登陆请求日志</option>
                            <option value="xunlei_login_error_">迅雷登陆错误日志</option>
                            <option value="xunlei_callback_all_">迅雷回调日志</option>
                            <option value="xunlei_callback_error_">迅雷回调错误日志</option>

                            <option value="xiaomi_login_all_log_">小米登陆日志</option>
                            <option value="xiaomi_login_result_log_">小米渠道方登陆请求日志</option>
                            <option value="xiaomi_callback_log_">小米回调日志</option>
                            <option value="xiaomi_check_result_log_">小米回调查询日志</option>
                            <option value="xiaomi_callback_error_">小米回调错误日志</option>

                            <option value="kaiwan_login_all_">快玩登陆日志</option>
                            <option value="kuaiwan_login_check_log_">快玩渠道方登陆请求日志</option>
                            <option value="kuaiwan_login_error_">快玩登陆异常日志</option>
                            <option value="kuaiwan_callback_log_">快玩回调日志</option>
                            <option value="kuaiwan_callback_error_">快玩回调错误日志</option>

                            <option value="yayawan_login_2_all_log_">丫丫玩_2_登陆日志</option>
                            <option value="yayawan_login_2_error_log_">丫丫玩_2_登陆异常日志</option>
                            <option value="yayawan_2_callback_log_">丫丫玩_2_回调日志</option>
                            <option value="yayawan_2_callback_error_">丫丫玩_2_回调错误日志</option>

                            <option value="kudong_login_all_log_">酷动登陆日志</option>
                            <option value="kudong_login_error_log_">酷动登陆异常日志</option>
                            <option value="kudong_callback_log_">酷动回调日志</option>
                            <option value="kudong_callback_error_">酷动错误日志</option>


                            <option value="kaiyong_login_info_all_">快用登陆日志</option>
                            <option value="kaiyong_login_error_">快用登陆异常日志</option>
                            <option value="kaiyong_callback_all_">快用回调日志</option>
                            <option value="kudong_callback_error_">快用回调错误日志</option>
                        </select>
                        (客户端主要查看登陆日志和回调日志,错误日志只有记录部分错误)
                    </td>

                </tr>
                <tr>
                    <td align="right" class="forumRow">&nbsp;</td>
                    <td class="forumRow"><input type="submit" name="Submit2" class="bott01" value=" 搜 索 ">时间、日志  不能为空</td>
                </tr>
            </form>
        </table>


    </body>
</html>