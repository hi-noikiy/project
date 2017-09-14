<?
include("inc/CheckUser.php");
include("inc/config.php");

$sql = "select * from u_code_businesses";
$result=mysql_query($sql);
while($rs=mysql_fetch_array($result)){
    $result_arr[] = $rs;
}

if($_REQUEST['action']=='select'){
    $StarTime = $_REQUEST['StarTime'];
    $EndsTime = $_REQUEST['EndsTime'];
    $belong = $_REQUEST['belong'];
    $code_id = trim($_REQUEST['code_id']);
    if($code_id){
        $sql = "select * from u_code_exchange where code_id='$code_id'";
        $code_rs=mysql_fetch_array(mysql_query($sql));
    }
    $sql = " select count(*) count_,used from u_code_exchange where 1=1   ";
    if($StarTime){
        $StarTime_str = date("ymdhis",strtotime($StarTime));
        $sql  .= " and time_stamp>'$StarTime_str' ";
    }
    if($EndsTime){
        $EndsTime_str = date("ymdhis",strtotime($EndsTime));
        $sql  .= " and time_stamp>'$EndsTime_str' ";
    }
    if($belong){
        $sql  .= " and belong='$belong' ";
    }
    $sql .= " group by used ";
    $result_count=mysql_query($sql);
    while($rs=mysql_fetch_array($result_count)){
        if($rs['used']=='0'){
           $wei_count = $rs['count_'];
        }else{
          $yi_count = $rs['count_'];
        }
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
        <script language="javascript" src="JS/calendar.js"></script>

    </head>
    <body class="main">

        <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
            <form name="SearchForm" method="POST" action="?action=select">
                <tr>
                    <th height="22" colspan="2" align="center">兑换码管理</th>
                </tr>
                <tr>
                    <td width="15%" align="right" class="forumRow">兑换码生成时间：</td>
                    <td width="85%" class="forumRow">
                        <input name="StarTime" type="text" size="12" value="<?=$StarTime?>"  readonly onfocus="HS_setDate(this)">
                        ～
                        <input name="EndsTime" type="text" size="12" value="<?=$EndsTime?>"  readonly onfocus="HS_setDate(this)">
                    </td>
                </tr>
                <tr>
                    <td align="right" class="forumRow">兑换码：</td>
                    <td width="85%" class="forumRow">
                        <input name="code_id" value="<?php  echo $code_id; ?>" />  有兑换码情况下，单独查兑换码的使用情况
                    </td>
                </tr>
                <tr>
                    <td align="right" class="forumRow">渠道：</td>
                    <td width="85%" class="forumRow">
                        <select name="belong">
                            <option value="">选择渠道</option>
                            <? for($i=0;$i<count($result_arr);$i++){?>
                            <option value="<?  echo $result_arr[$i]['id']; ?>" <?php if($belong==$result_arr[$i]['id']){ echo "selected";} ?>   ><?  echo $result_arr[$i]['name']; ?></option>
                            <?  } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right" class="forumRow">&nbsp;</td>
                    <td class="forumRow"><input type="submit" name="Submit2" class="bott01" value=" 搜 索 ">
                    <a href="exchange_code_add.php">兑换码分类</a>  <a href="exchange_code_businesses.php">兑换码渠道</a></td>
                </tr>
            </form>
        </table>
        <DIV style="FONT-SIZE: 2px">&nbsp;</DIV>
        <?php  if($code_id){  ?>
        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">

            <tr height="22">
                <th  height="22" align="center">兑换码</th>
                <th height="22" align="center">从属渠道</th>
                <th  height="22" align="center">生成时间</th>
                <th  height="22" align="center">使用时间</th>
            </tr>

            <tr>
                <td  align="center"><?php echo $code_rs['code_id']; ?></td>
                <td  align="center"><?php
                    for($i=0;$i<count($result_arr);$i++){
                        if($result_arr[$i]['id']==$code_rs['belong']){
                            echo $result_arr[$i]['name'];
                        }
                    }
                    ?></td>
                <td  align="center"><?php  echo $code_rs['time_stamp']; ?></td>
                <td  align="center"><?php if($code_rs['used_time_stamp']){echo $code_rs['used_time_stamp']; }else{ echo "不存在或者未使用"; } ; ?></td>
            </tr>




        </table>
        <?php  } ?>

        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">

            <tr height="22">
                <th  height="22" align="center">使用个数</th>
                <th height="22" align="center">未使用个数</th>
            </tr>

            <tr>
                <td  align="center"><?php echo $yi_count; ?></td>
                <td  align="center"><?php echo $wei_count;  ?></td>
            </tr>

        </table>


    </body>
</html>