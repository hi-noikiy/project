<?php
if($_POST){
	$admin=new admin();
	if($_POST['login_pass']) $_POST['login_pass']=md5($_POST['login_pass']);
		else unset($_POST['login_pass']);
	if($_POST['id']){
		$admin->edit($_POST,$_POST['id'],'pass');
		$altmsg='修改用户成功';
	}
}
!$userid && $userid=$_SESSION['ADMIN_ID'];
if($userid){
	$admin=new admin();
	$uinfo=$admin->getInfo($userid,'','pass');
}

$sql="select hughTime from hugh_time_log where hughID='$userid'";
$query=mysql_query($sql);
$hugh_list=array();
while ($rs=mysql_fetch_assoc($query)){
	$hugh_list[]=$rs;
}
$hugh_time='';
if($hugh_list){
	foreach ($hugh_list as $val){
			$hugh_time+=$val['hughTime'];
	}
}
?>
<form method="post" onsubmit="return checkForm(this);">
	<?if($userid){?><input type="hidden" name="id" value="<?=$userid?>"><?}?>
 <h1 class="title"><span>修改用户资料</span></h1>
 <div class="pidding_5">
  <table cellspacing="0" cellpadding="0" class="Admin_L">
    <tr>
      <th class="T_title" scope="col" width="100">用户资料</th>
      <th class="T_title" scope="col">&nbsp;</th>
    </tr>
    <tr>
      <td class="N_title">账号：</td><td class="N_title">
          <input name="login_name" value="<?=$uinfo['login_name']?>" class="N_input" readonly>
      </td>
    </tr>
    <tr class="Ls2">
      <td class="N_title">密码：</td><td class="N_title">
        <input type="password" name="login_pass" value="" class="N_input">
      </td>
    </tr>
    <tr>
      <td class="N_title">确认密码：</td><td class="N_title">
        <input type="password" name="password" value="" class="N_input">
      </td>
    </tr>
    <tr class="Ls2">
      <td class="N_title">群组：</td><td class="N_title">
          <select name="gpid" disabled>
				<?
				$group=new group();
				//$group->setLimit(0,1000);
				$groupinfo=$group->getInfo($uinfo['gpid'],'','pass');
				//foreach($group as $g){
				//	$selected=($g['id']==$uinfo['gpid'])?'selected':'';
					echo '<option  value="'.$groupinfo['id'].' selected">'.$groupinfo['name'].'</option>';
				//}
				?>
				</select>
      </td>
    </tr>
    <tr class="Ls2">
      <td class="N_title">所属部门：</td><td class="N_title">
          <select name="depId" disabled>
				<?
				$dep=new department();
				//$dep->setLimit(0,100);
				$depinfo=$dep->getInfo($uinfo['depId'],'','pass');
				//foreach($dep as $g){
				//	$selected=($g['id']==$uinfo['depId'])?'selected':'';
					echo '<option  value="'.$depinfo['depId'].' selected">'.$depinfo['name'].'</option>';
				//}
				?>
				</select>
      </td>
    </tr>
    <tr class="Ls2">
      <td class="N_title">岗位：</td><td class="N_title">
          <select name="jobId" disabled>
				<?
				$job=new job();
				//$job->setLimit(0,100);
				$jobinfo=$job->getInfo($uinfo['jobId'],'','pass');
				//foreach($job as $g){
				//	$selected=($g['id']==$uinfo['jobId'])?'selected':'';
					echo '<option  value="'.$jobinfo['id'].' selected">'.$jobinfo['name'].'</option>';
				//}
				?>
				</select>
      </td>
    </tr>
    <tr class="Ls2">
      <td class="N_title">部门主管：</td><td class="N_title">
          <select name="depMax" disabled>
                                    <option value="0" <?php if($uinfo['depMax']=='0') echo "selected"; ?>>否</option>
                                    <option value="1" <?php if($uinfo['depMax']=='1') echo "selected"; ?>>是</option>
				</select>
      </td>
    </tr>
    <tr class="Ls2">
        <td class="N_title" width="130">查询所有审核：</td>
        <td class="N_title">
         <select name="seartag" disabled>
                                    <option value="0" <?php if($uinfo['seartag']=='0') echo "selected"; ?>>否</option>
                                    <option value="1" <?php if($uinfo['seartag']=='1') echo "selected"; ?>>是</option>
	 </select>
      </td>
    </tr>
    <tr class="Ls2">
      <td class="N_title">门禁卡号：</td><td class="N_title">
        <input name="card_id" value="<?=$uinfo['card_id']?>" class="N_input" readonly>
    </tr>
    <tr class="Ls2">
      <td class="N_title">真实姓名：</td><td class="N_title">
          <input name="real_name" value="<?=$uinfo['real_name']?>" class="N_input" readonly>
          </td>
    </tr>
    <tr class="Ls2">
      <td class="N_title">共可调休时间：</td><td class="N_title">
          <input name="real_name" size="12" value="<?=number_format($uinfo['totalOverTime']/60,1)?>小时" class="N_input" disabled>
       手工总调休时间:<?php echo number_format($hugh_time/60,1)?>小时
       </td>
    </tr>
    <tr class="Ls2">
      <td class="N_title">已申请调休时间：</td><td class="N_title">
          <input name="real_name" size="12" value="<?=number_format($uinfo['reserve']/60,1)?>小时" class="N_input" disabled>
          </td>
    </tr>
    <tr class="Ls2">
      <td class="N_title"></td>
      <td class="N_title"><input class="sub2" type="submit" value="提交表单"></td>
    </tr>
  </table>
  </div>
</form>
<script>
function checkForm(form){
	var msg='';
	if(form.login_name.value=='') msg+='请输入该用户的登录名\r\n';
	if(form.real_name.value=='') msg+='请输入该用户的真实姓名\r\n';
	<?if(!$userid){?>if(form.login_pass.value=='') msg+='请输入该用户的密码\r\n';<?}?>
	if(form.login_pass.value!=form.password.value) msg+='两次输入的密码需要一致\r\n';
	if(msg){
		alert(msg);
		return false;
	}else return true;
}
</script>