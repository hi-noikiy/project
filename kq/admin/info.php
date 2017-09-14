<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 修改
* ==============================================
* @date: 2015-5-25
* @author: Administrator
* @return:
*/
$className = $_GET['cn'];
$classStr = $GLOBALS['_type'][$className];
$class = new $className();
if ($_POST) {
	if ($_POST ['id']) {
		$class->edit ( $_POST, $_POST ['id'] );
		$altmsg = '修改' . $classStr . '成功';
		if ($_REQUEST ["issee"]) {
			$signList = getListByCN('sign', $_SESSION ['role'], $_SESSION ['ADMIN_ID'], '1');
			if ($signList)
				go("index.php?type=web&do=info&cn=sign&id=" . $signList [0] ['id'] . "&issee=1");
			$oddtimeList = getListByCN ('oddtime', $_SESSION ['role'], $_SESSION ['ADMIN_ID'], '1');
			if ($oddtimeList)
				go("index.php?type=web&do=info&cn=oddtime&id=" . $oddtimeList [0] ['id'] . "&issee=1");
			$overtimeList = getListByCN('overtime', $_SESSION ['role'], $_SESSION ['ADMIN_ID'], '1');
			if ($overtimeList)
				go("index.php?type=web&do=info&cn=overtime&id=" . $overtimeList [0] ['id'] . "&issee=1" );
			$hughList = getListByCN('hugh', $_SESSION ['role'], $_SESSION ['ADMIN_ID'], '1');
			if ($hughList)
				go("index.php?type=web&do=info&cn=hugh&id=" . $hughList [0] ['id'] . "&issee=1");
			$leaveList = getListByCN('leave', $_SESSION ['role'], $_SESSION ['ADMIN_ID'], '1' );
			if ($leaveList)
				go("index.php?type=web&do=info&cn=leave&id=" . $leaveList [0] ['id'] . "&issee=1");
			$outList = getListByCN('outrecord', $_SESSION ['role'], $_SESSION ['ADMIN_ID'], '1');
			if ($outList)
				go ("index.php?type=web&do=info&cn=outrecord&id=" . $outList [0] ['id'] . "&issee=1");
			
			go ('unread.php'); // 所有单子审批完，跳回到未处理页面
		}
	} else {
		//请假报备一键生成
		if($className=='leave_filing' && $_POST['isKey']=='1'){		
			$hour_s=$_POST['hour_s'];
			$minute_s=$_POST['minute_s'];
			$hour_e=$_POST['hour_e'];
			$minute_e=$_POST['minute_e'];
			//一天的情况
			if($_POST['fromTime']==$_POST['toTime']){
				for ($i=0; $i<2; $i++){
					if($i==0){
						$_POST['hour_e']='12';
						$_POST['minute_e']='00';
					} else {
						$_POST['hour_s']='13';
						$_POST['minute_s']='30';
						$_POST['hour_e']=$hour_e;
						$_POST['minute_e']=$minute_e;
					}
					$second=strtotime($_POST['fromTime'].$_POST['hour_e'].$_POST['minute_e'])-strtotime($_POST['fromTime'].$_POST['hour_s'].$_POST['minute_s']);					
					$_POST['totalTime']=$second/3600;
					$class->add($_POST);
				}
				go (urlkill_new('altmsg') . '&altmsg=' . urlencode ('新增' . $classStr . '成功'));
			}else{ //d多天的情况
				$fromTime=$_POST['fromTime'];
				$toTime=$_POST['toTime'];
				$workDay=new workday();
				$workDay->wheres=" workday between '$fromTime' and '$toTime' and tag='1'";
				$filingDay=$workDay->getArray('pass');	
				
				$dayNum=count($filingDay)-1;
				foreach ($filingDay as $k=>$v){
					$data['depId']=$_POST['depId'];
					$data['uid']=$_POST['uid'];
					$data['addDate']=$_POST['addDate'];
					$data['fromTime']=$v['workday'];
					$data['toTime']=$v['workday'];
					$data['reason']=$_POST['reason'];
					
					$data2['depId']=$_POST['depId'];
					$data2['uid']=$_POST['uid'];
					$data2['addDate']=$_POST['addDate'];
					$data2['fromTime']=$v['workday'];
					$data2['toTime']=$v['workday'];
					$data2['reason']=$_POST['reason'];
					
					if($k==0){
						if($hour_s<12){					
							$data['hour_s']=$hour_s;
							$data['minute_s']=$minute_s;
							$data['hour_e']='12';
							$data['minute_e']='00';	
							$second=strtotime($data['fromTime'].$data['hour_e'].$data['minute_e'])-strtotime($data['fromTime'].$data['hour_s'].$data['minute_s']);
							$data['totalTime']=$second/3600;
							$class->add($data);
							
							$data2['hour_s']='13';
							$data2['minute_s']='30';
							$data2['hour_e']='18';
							$data2['minute_e']='30';
							$second=strtotime($data2['fromTime'].$data2['hour_e'].$data2['minute_e'])-strtotime($data2['fromTime'].$data2['hour_s'].$data2['minute_s']);
							$data2['totalTime']=$second/3600;
							$class->add($data2);
						} else {				
							$data['hour_s']=$hour_s;
							$data['minute_s']=$minute_s;
							$data['hour_e']='18';
							$data['minute_e']='30';
							$second=strtotime($data['fromTime'].$data['hour_e'].$data['minute_e'])-strtotime($data['fromTime'].$data['hour_s'].$data['minute_s']);
							$data['totalTime']=$second/3600;
							$class->add($data);
						}
						
					}
					if($k>0 && $k<$dayNum){
						
						$data['hour_s']='09';
						$data['minute_s']='00';
						$data['hour_e']='12';
						$data['minute_e']='00';
						$second=strtotime($data['fromTime'].$data['hour_e'].$data['minute_e'])-strtotime($data['fromTime'].$data['hour_s'].$data['minute_s']);
						$data['totalTime']=$second/3600;
						$class->add($data);
						
						$data2['hour_s']='13';
						$data2['minute_s']='30';
						$data2['hour_e']='18';
						$data2['minute_e']='30';
						$second=strtotime($data2['fromTime'].$data2['hour_e'].$data2['minute_e'])-strtotime($data2['fromTime'].$data2['hour_s'].$data2['minute_s']);
						$data2['totalTime']=$second/3600;
						$class->add($data2);
					}
					
					if($k==$dayNum){
						if($hour_e<=12){
							
							$data['hour_s']='09';
							$data['minute_s']='00';
							$data['hour_e']=$hour_e;
							$data['minute_e']=$minute_e;
							
							$second=strtotime($data['fromTime'].$data['hour_e'].$data['minute_e'])-strtotime($data['fromTime'].$data['hour_s'].$data['minute_s']);
							$data['totalTime']=$second/3600;
							$class->add($data);
						}else{	
							
							$data['hour_s']='09';
							$data['minute_s']='00';
							$data['hour_e']='12';
							$data['minute_e']='00';
							$second=strtotime($data['fromTime'].$data['hour_e'].$data['minute_e'])-strtotime($data['fromTime'].$data['hour_s'].$data['minute_s']);
							$data['totalTime']=$second/3600;
							$class->add($data);
							
							$data2['hour_s']='13';
							$data2['minute_s']='30';
							$data2['hour_e']=$hour_e;
							$data2['minute_e']=$minute_e;
							$second=strtotime($data2['fromTime'].$data2['hour_e'].$data2['minute_e'])-strtotime($data2['fromTime'].$data2['hour_s'].$data2['minute_s']);
							$data2['totalTime']=$second/3600;
							$class->add($data2);
						}
					}
				}
				go (urlkill_new('altmsg') . '&altmsg=' . urlencode ('新增' . $classStr . '成功'));	
			}
		}
		$class->add ($_POST);
		go (urlkill_new('altmsg') . '&altmsg=' . urlencode ('新增' . $classStr . '成功'));
	}
}
if ($_GET ['id'] || $className == 'admin') {
	if ($className == 'admin')
		$info = $class->getInfo ($_SESSION ['ADMIN_ID']);
	else
		$info = $class->getInfo ($_GET ['id']);
} else {
	if (!permission::check ($class->tableName, "a_tag")) {
		echo "<script>alert('对不起你没有该操作权限');</script>";
		exit ();
	}
}
// $ar = array('leave','hugh','sign','overtime'); //需要特殊显示的类
// 1普通员工不显示 2已经作废的表单不显示 提交按钮
$show = true;
/*
 * $ar 位于admin/common.inc.php
 * $personnelId 位于 conf/db.conf.php
 */
$ar=$GLOBALS['ar'];
$personnelId=$GLOBALS['personnelId'];
if ((in_array($className, $ar) && $_GET['id'] && $_SESSION['role'] == '3' && $_SESSION['ADMIN_ID'] != $personnelId) || (in_array ($className, $ar) && isset($info['available']) && $info['available'] != '1')) {
	$show = false;
}
?>
<form method="post" <?php if($_GET['seetag']!='1'){?> onsubmit="return checkForm('<?php echo C_DATE ?>','<?php echo $className ?>');"<?php } ?> enctype="multipart/form-data">
	<?if($_GET['id']){?>
	<input type="hidden" name="id" value="<?=$_GET['id']?>">
	<?}?>
 	<h1 class="title">
		<span><?=$classStr?>资料</span>
	</h1>
	<div class="pidding_5">
		<table cellspacing="0" cellpadding="0" class="Admin_L">
			<tr>
				<th class="T_title" scope="col" width="150"><?=$classStr?>资料</th>
				<th class="T_title" scope="col" colspan="7">&nbsp;</th>
			</tr>
			<?include($_GET['type'].'/'.$className.'.form.php')?>
    		<?if($show){?>
    		<tr class="Ls2">
				<td class="N_title">&nbsp;</td>
				<td class="N_title" colspan="7"><input class="sub2" type="submit" value="确 定"></td>
			</tr>
    		<?}?>
  		</table>
	</div>
</form>
<?if($info){?>
<script>
editFun(<?=jsonEncode($info)?>);
</script>
<?}?>