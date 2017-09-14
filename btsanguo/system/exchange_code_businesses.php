<?
include("inc/CheckUser.php");
include("inc/config.php");

if($_REQUEST['action']=="add"){
    if($_POST['belong_name']){
        $belong_name = $_POST['belong_name'];
        $sql="insert into u_code_businesses(name)
        values('".mysql_escape_string($_POST['belong_name'])."')";
        mysql_query($sql);
    }
}
if($_REQUEST['action']=="del"){
    if($_REQUEST['id']){
        
        $sql="delete from u_code_businesses where id='".$_REQUEST['id']."' ";
        mysql_query($sql);
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
                    <th colspan="2" align="center">兑换码渠道新增</th>
                </tr>
                <tr>
                    <td align="right" class="forumRow">渠道：</td>
                    <td class="forumRow"><input name="belong_name" value="" /></td>
                </tr>

                <tr>
                    <td align="right" class="forumRow">&nbsp;</td>
                    <td class="forumRow"><input name="Add" class="bott01" type="submit" value=" 增 加 "  />
                    </td>
                </tr>
            </form>
        </table>
        <table width="100%" border="0" align="center" cellpadding="2" cellspacing="1" class="tableBorder">

            <tr height="22">
                <th width="148"  height="22" align="center">兑换码渠道</th>
                <th width="107">操作</th>
            </tr>
            <? for($i=0;$i<count($result_arr);$i++){
                ?>
            <tr height="22">

                <td><?  echo $result_arr[$i]['name']; ?></td>
                <td><a href="?action=del&id=<?  echo $result_arr[$i]['id']; ?>">删除</a></td>

            </tr>
            <?  } ?>
        </table>
    </body>

</html>