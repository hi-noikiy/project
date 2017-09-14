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