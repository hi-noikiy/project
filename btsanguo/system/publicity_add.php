<?
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/web_config.php");

if($_GET['action']=="add"){
    if($_POST['content']==''){
        echo "<script language=\"javascript\">
       alert(\"新增公告不能为空\");
      </script>";
    }else{
        $status = file_get_contents(WAP_URL.'interface/dl/publicity.php?content='.$_POST['content']);

        if($status==0){
            $sql="insert into publicity(content,add_time,status)
        values('".mysql_escape_string($_POST['content'])."',".time().",$status)";
            mysql_query($sql);
            echo "<script language=\"javascript\">
          alert(\"新增公告提交成功\");
          </script>";
        }else{
           echo "<script language=\"javascript\">
           alert(\"新增公告提交失败\");
          </script>";
        }

    }

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
            <form name="form1" method="POST"  enctype="multipart/form-data" action="publicity_add.php?action=add">
                <tr>
                    <th colspan="2" align="center">当乐新增公告</th>
                </tr>
                <tr>
                    <td align="right" class="forumRow">公告：</td>
                    <td class="forumRow"><textarea name="content" cols="60"  rows="6"></textarea></td>
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