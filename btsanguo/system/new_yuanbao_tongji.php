<?php
include("inc/config.php");
include("inc/CheckUser.php");
include("inc/function.php");
include("inc/page.php");
include("../inc/config.php");
include("../inc/function.php");
include("inc/game_config.php");

$str_time = $_POST['str_time'];
$end_time = $_POST['end_time'];

if($_POST['action']=='select'&&$str_time&&$end_time){
    $str_time_int = strtotime($str_time);
    $end_time_int = strtotime($end_time)+3600*24;
    if((($end_time_int-$str_time_int)/(3600*24)>31)||$str_time_int>=$end_time_int){
        exit('<script language="javascript">alert("时间选择异常");</script>');
    }
    $day = ($end_time_int-$str_time_int)/(3600*24);
    $result = array();
    for($i=0;$i<$day;$i++){
        $result_1 = text_num("customer_command_view_",$str_time_int+3600*24*$i);
        if(is_array($result_1)){
            $result = array_merge_recursive($result,$result_1);
        }
    }
   // print_r($result);
}

function text_num($login_name,$time){

    if(file_exists(ROOT_PATH."log/".date("ym", $time)."/$login_name".date("ymd",$time).".txt"))
    {
        $handle = @fopen(ROOT_PATH."log/".date("ym", $time)."/$login_name".date("ymd",$time).".txt", "r");

        $title_pattern = "/,yuanbao\=(\d+),/is";
        while (!@feof($handle)) {
            $buffer = @fgets($handle, 4096);
            $buffer_matches = array();
            preg_match($title_pattern,$buffer,$buffer_matches);
            if($buffer_matches[1]){
                $result['yuanbao'][] = $buffer_matches[1];
                $result['log'][] = $buffer;
            }
        }
        fclose($handle);
        return  $result;
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
                    <th height="22" colspan="2" align="center">元宝发放统计</th>
                </tr>

                <tr>
                    <td align="right" class="forumRow">开始日期：</td>

                    <td width="85%" class="forumRow">
                        <input name="str_time" type="text" size="12" value="<?=$str_time?>" readonly onfocus="HS_setDate(this)">
                    </td>

                </tr>
                <tr>
                    <td align="right" class="forumRow">结束日期：</td>

                    <td width="85%" class="forumRow">
                        <input name="end_time" type="text" size="12" value="<?=$end_time?>" readonly onfocus="HS_setDate(this)">
                    </td>

                </tr>

                <tr>
                    <td align="right" class="forumRow">&nbsp;</td>
                    <td class="forumRow"><input type="submit" name="Submit2" class="bott01" value=" 搜 索 ">时间不能为空,另外时间跨度不能超过一个月</td>
                </tr>
            </form>
        </table>
        <DIV style="FONT-SIZE: 2px">&nbsp;</DIV>
        <?php for($i=0;$i<count($result['yuanbao']);$i++){ $sum_yuanbao = $sum_yuanbao + $result['yuanbao'][$i];   };echo  "元宝总计：".$sum_yuanbao."<br/><br/>"; ?>

        <?php for($i=0;$i<count($result['log']);$i++){echo $result['log'][$i]."<br/>";} ?>


    </body>
</html>