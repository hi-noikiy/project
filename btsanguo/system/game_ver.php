<?
include("inc/CheckUser.php");
include("inc/config.php");
include("inc/function.php");
include("inc/game_config.php");

$Action=$_REQUEST["Action"];

if ($Action=="Edit"){
//	$VersionNO=CheckStr($_REQUEST["VersionNO"]);
//	$sql="Update client_ver Set VersionNO='$VersionNO' Where ClassID=1";
//	mysql_query($sql);
//	header("Location: game_ver.php");
    $game_id = $_REQUEST["game_id"];
    $VersionNO = $_REQUEST["VersionNO"];
    $sql_1="select * from client_ver where game_id=".intval($_REQUEST["game_id"]);
    $conn=mysql_query($sql_1);
    $rs=mysql_fetch_array($conn);
    if($rs){
       	$sql="Update client_ver Set VersionNO='$VersionNO' Where game_id=".intval($game_id);
    }else{
       	$sql="INSERT INTO client_ver(VersionNO,game_id) VALUES ('$VersionNO', '$game_id');";
    }
      mysql_query($sql);
  	header("Location: game_ver.php");
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<script language="javascript" src="JS/jquery-1.3.2.min.js"></script>
<title>edit</title>
<script language = "JavaScript">
function CheckForm(){
  if (document.myform.VersionNO.value==""){
    alert("版本号不能为空！");
	document.myform.VersionNO.focus();
	return false;
  }
}
</script>
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
</head>
<body class="main">
<?
//默认游戏为天问
$SqlMain="select * from client_ver where game_id=1";
$conn=mysql_query($SqlMain);
$rs=mysql_fetch_array($conn);
?>
<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form method="POST" name="myform" onSubmit="return CheckForm();" action="game_ver.php">
    <tr> 
      <th height="22" colspan="2" align="center">版本编辑</th>
    </tr>
   <tr>
      <td align="right" class="forumRow">游戏分类：</td>
      <td class="forumRow">
      <select name='game_id' onchange="change_game(this.value)">
      <?php
      if($game_arr){
	      foreach ($game_arr as $key=>$val){
	      	echo "<option value='$key' ".(($key==$rs['game_id'])?"selected":"")." >".$val['name']."</option>";
	      }
      }
      ?>
      </select>
      </td>
    </tr>
    <tr> 
      <td width="30%" height="16" align="right" class="forumRow">当前版本：
      </td>
      <td width="70%" class="forumRow"><input name="VersionNO" id="VersionNO" type="text" value="<?=$rs[VersionNO]?>" size="30" maxlength="255"></td>
    </tr>
    <tr> 
      <td align="right" class="forumRow">&nbsp; </td>
      <td class="forumRow">
		<input name="Action" type="hidden" value="Edit">
		<input name="Save" type="submit" class="bott" value=" 保 存 ">
		<input name="Cancel" type="button" class="bott" value=" 取 消 " onClick="javascript:history.back();"></td>
    </tr>
  </form>
</table>
</body>
<script type="text/javascript">
   function change_game(game_id){
       $.post("ajax/game.php", { action: "game_no", game_id: game_id },
  function(data){
         $("#VersionNO").val(data);
    });
   }
</script>
</html>
<? mysql_close();?>