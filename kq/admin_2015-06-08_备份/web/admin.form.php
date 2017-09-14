<?php
//if($_POST){
//	$admin=new admin();
//	if($_POST['login_pass']) $_POST['login_pass']=md5($_POST['login_pass']);
//		else unset($_POST['login_pass']);
//	if($_POST['id']){
//		$admin->edit($_POST,$_POST['id']);
//		$altmsg='修改用戶成功';
//	}else{
//		$admin->add($_POST);
//		go(urlkill('altmsg').'&altmsg='.urlencode('添加用戶成功'));
//	}
//}
//!$userid && $userid=$_GET['id'];
//if($userid){
//	$admin=new admin();
//	$uinfo=$admin->getInfo($userid);
//}
$userid = $_SESSION['ADMIN_ID'];
?>

	<?if($userid){?><input type="hidden" name="id" value="<?=$userid?>"><?}?>
 
    <tr>
      <td class="N_title">账号：</td><td class="N_title">
          <input name="login_name" value="<?=$info['login_name']?>" class="N_input" readonly>
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
				$group->setLimit(0,1000);
				$group=$group->getArray();
				foreach($group as $g){
					$selected=($g['id']==$uinfo['gpid'])?'selected':'';
					echo '<option '.$selected.' value="'.$g['id'].'">'.$g['name'].'</option>';
				}
				?>
				</select>
      </td>
    </tr>
    <tr class="Ls2">
      <td class="N_title">所属部门：</td><td class="N_title">
          <select name="depId" disabled>
				<?
				$dep=new department();
				$dep->setLimit(0,100);
				$dep=$dep->getArray();
				foreach($dep as $g){
					$selected=($g['id']==$uinfo['depId'])?'selected':'';
					echo '<option '.$selected.' value="'.$g['id'].'">'.$g['name'].'</option>';
				}
				?>
				</select>
      </td>
    </tr>
    <tr class="Ls2">
      <td class="N_title">岗位：</td><td class="N_title">
          <select name="jobId" disabled>
				<?
				$job=new job();
				$job->setLimit(0,100);
				$job=$job->getArray();
				foreach($job as $g){
					$selected=($g['id']==$uinfo['jobId'])?'selected':'';
					echo '<option '.$selected.' value="'.$g['id'].'">'.$g['name'].'</option>';
				}
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
      <td class="N_title">真实姓名：</td><td class="N_title">
          <input name="real_name" value="<?=$uinfo['real_name']?>" class="N_input" readonly>
    </tr>
    <tr class="Ls2">
      <td class="N_title"></td>
      <td class="N_title"><input class="sub2" type="submit" value="提交表单"></td>
    </tr>
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