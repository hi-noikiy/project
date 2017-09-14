<?
include("inc/CheckUser.php");
include("inc/config.php");

if($_GET['action']=="add"){
    if($_REQUEST['content']&&$_REQUEST['belong']){
        $arr = explode("\n", $_REQUEST['content']);
        for($i=0;$i<count($arr);$i++){
            $arr[$i] = trim($arr[$i]);
            $sql = " update u_code_exchange set belong ='".$_REQUEST['belong']."' where code_id ='".$arr[$i]."' ";
            mysql_query($sql);
        }
        echo "<script>alert(\"分配完成\");</script>";
    }
}
$sql = "select * from u_code_businesses";
$result=mysql_query($sql);
while($rs=mysql_fetch_array($result)){
    $result_arr[] = $rs;
}

?>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=gb2312">
        <title>新增</title>
        <link href="CSS/Style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="JS/jquery-1.3.2.min.js">
        </script>

    </head>
    <body class="main">
        <table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
            <form name="form1" method="POST"  enctype="multipart/form-data" action="?action=add">
                <tr>
                    <th colspan="2" align="center">兑换码分类</th>
                </tr>
                <tr>
                    <td align="right" class="forumRow">渠道：</td>
                    <td class="forumRow">
                        <select name="belong">
                            <? for($i=0;$i<count($result_arr);$i++){
                                ?>
                            <option value="<?  echo $result_arr[$i]['id']; ?>"><?  echo $result_arr[$i]['name']; ?></option>
                            <?  } ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td align="right" class="forumRow">兑换码：</td>
                    <td class="forumRow"><textarea name="content" cols="60"  rows="30"></textarea></td>
                </tr>

                <tr>
                    <td align="right" class="forumRow">&nbsp;</td>
                    <td class="forumRow"><input name="Add" class="bott01" type="submit" value=" 增 加 "  />
                    </td>
                </tr>
            </form>
        </table>
    </body>

</html>