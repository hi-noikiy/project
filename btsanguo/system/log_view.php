<?
include("inc/config.php");
include("inc/CheckUser.php");
include("inc/function.php");
include("inc/page.php");
include("../inc/config.php");
include("../inc/function.php");
include("inc/game_config.php");
 $ip = getIP_front();

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
<title>日志查看</title>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<link href="CSS/Style.css" rel="stylesheet" type="text/css">
        <script language="javascript" src="JS/calendar.js"></script>
        <script language="javascript" src="JS/jquery-1.3.2.min.js"></script>
        <script src="JS/FormValid.js" type="text/javascript"></script>
        <script src="JS/common.js" type="text/javascript"></script>
</head>
<body class="main">

<table width="100%" border="0" align="center" cellpadding="3" cellspacing="1" class="tableBorder">
  <form name="from1" id="from1"  method="POST" action="" >
  <input type="hidden" name="action" id="action">
    <tr>
      <th height="22" colspan="3" align="center">日志查询</th>
    </tr>
    <tr>
      <td align="right" class="forumRow">日志：</td>
      <td width="85%" class="forumRow">
                        <select name="login_name"  valid="required" errmsg="日志不能为空!" >
                            <option value='' >选择日志</option>
                            <option value='payByAdmin' >人工充值记录</option>
                            <option value='add_good_view_' >玩家添加物品</option>
                            <option value='add_good_buchang_view_' >补偿物品</option>
                            <option value='customer_command_view_' >客服命令</option>
                            <option value='new_account_pp_log_' >pp助手新注册</option>
                            <option value='new_account_360_log_' >360新注册</option>
                            <option value='new_account_zhen_37wan_log_' >真的37玩新注册</option>
                            <option value='new_account_37wan_log_' >17173玩新注册</option>
                            <option value='new_account_5gwan_log_' >5g玩新注册</option>
                            <option value='new_account_5gwan_channel_log_' >5g玩渠道新注册</option>
                            <option value='new_account_8849_log_' >8849新注册</option>
                            <option value='new_account_91_log_' >91新注册</option>
                           <option value='new_account_anzhi_log_' >安智新注册</option>
                           <option value='new_account_appchina_log_' >appchina新注册</option>
                           <option value='new_account_chanyou_log_' >禅游新注册</option>
                           <option value='new_account_dianjin_log_' >点金新注册（里面是点金的新注册）</option>
                           <option value='new_account_dl3_log_' >当乐新注册</option>
                           <option value='new_account_gfan_log_' >机锋新注册</option>
                           <option value='new_account_huawei_log_' >华为新注册</option>
                           <option value='new_account_itools_log_' >itools新注册</option>
                           <option value='new_account_lenovo_log_' >联想新注册</option>
                           <option value='new_account_oppo_log_' >oppo新注册</option>
                           <option value='new_account_pptv_log_' >pptv新注册</option>
                           <option value='new_account_uc_log_' >uc新注册</option>
                           <option value='uxin_login_result_log_' >有信新注册</option>
                           <option value='new_account_wanpu_log_' >万普新注册</option>
                           <option value='new_account_xiaomi_log_' >小米新注册</option>
                           
                        </select>
            </td>
    </tr>
  <tr>
                    <td align="right" class="forumRow">查询时间：</td>
                    <td width="85%" class="forumRow">
                        <input name="time" type="text" size="12" value="<?=$time?>"  valid="required" errmsg="查询时间不能为空!" readonly onfocus="HS_setDate(this)">

                    </td>
     </tr>
    <tr>
      <td align="right" class="forumRow"></td>
      <td class="forumRow"> <input type="button"  onclick=" return submitCommonForm(document.getElementById('from1'), 'sub')" value="提交"/></td>
      <td class="forumRow">&nbsp;</td>
    </tr>
    </form>
</table>

</body>

</html>
