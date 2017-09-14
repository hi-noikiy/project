<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 审查结果查询  优化
* ==============================================
* @date: 2015-6-11
* @author: Administrator
* @return:
*/
$className=$_REQUEST['cn'];
$from=$_REQUEST['fromTime'];
$to = $_REQUEST['toTime'];
$deptag = $_REQUEST['depTag'];
$pertag = $_REQUEST['perTag'];
$mantag = $_REQUEST['manTag'];
$depId=$_REQUEST['depId'];
$uid = $_REQUEST['uid'];

if(!$from)
    $from = date('Y-m')."-01";
if(!$to)
    $to = date('Y-m-d');
$dep=new department();
$admin = new admin();
$userinfo = $admin->getInfo($_SESSION['ADMIN_ID'],'','pass');
$sear = intval($userinfo['seartag']);
if($_SESSION['role']==2) {
	$sear = 1;
	if (!$depId)
		$depId = $userinfo['depId'];
	$dep->setWhere('id='.intval($userinfo['depId']));	
}

$depList=$dep->getArray('pass');

if($className){
    $class=new $className();
    if($_SESSION['ADMIN_ID']!='99' && !$sear)
    	//总经理账号99
        $class->wheres .=" and uid='".$_SESSION['ADMIN_ID']."'";
    if($from){
        if($className=='sign')
        	$class->wheres .=" and addDate>='$from'";
        elseif($className=='oddtime')
       		$class->wheres .=" and supdate>='$from'";
        else
        	$class->wheres .=" and fromtime>='$from'";
    }
    if($to){
        if($className=='sign')
        	$class->wheres .=" and addDate<='$to'";
        elseif($className=='oddtime')
        	$class->wheres .=" and supdate<='$to'";
        else
        	$class->wheres .=" and toTime<='$to'";
    }
    if($depId){
    	$class->wheres .=" and depId='$depId'";
    	$admin->wheres="depId='$depId'";
    	$userList=$admin->getList();
    }
    if($uid)
        $class->wheres .=" and uid = '$uid'";
    if(isset($deptag) && $deptag!='')
        $class->wheres .=" and depTag='$deptag'";
    if(isset($pertag) && $pertag!='')
        $class->wheres .=" and perTag='$pertag'";
    if(isset($mantag) && $mantag!='')
        $class->wheres .=" and manTag='$mantag'";
    $class->p=$_REQUEST['p'];

    $list=$class->getList();
    
    $pageCtrl=$class->getPageInfoHTML('0',"search.php?type=web&cn=$className&fromTime=$from&toTime=$to&depTag=$deptag&perTag=$pertag&manTag=$mantag&depId=$depId&uid=$uid&p=");
}
?>
<h1 class="title"><span>审核结果查询</span></h1>

<div class="pidding_5">
    <div class="search">
    	<form action="" method="post">
        	<span>类型:</span>
            <select name="cn">
            	<option value="">请选择</option>
            	<option value="overtime" <?php echo $className=='overtime'?'selected':'' ?>>加班</option>
                <option value="hugh" <?php echo $className=='hugh'?'selected':'' ?>>调休</option>
                <option value="leave" <?php echo $className=='leave'?'selected':'' ?>>请假</option>
                <option value="sign" <?php echo $className=='sign'?'selected':'' ?>>签呈</option>
                <option value="outrecord" <?php echo $className=='outrecord'?'selected':'' ?>>公出</option>
                <option value="oddtime" <?php echo $className=='oddtime'?'selected':'' ?>>异常单</option>
            </select>
            <span>时间：</span>
            <input type="text" name="fromTime" size="10" id="date_s" value="<?=$from?>"  readonly >
            <span>到：</span>
            <input type="text" name="toTime" size="10" id="date_e" value="<?=$to?>" readonly >
			<span>部门审核:</span>
        	<select name="depTag">
            	<option value="">全部</option>
              	<option value="0" <?php echo $deptag=='0'?'selected':'' ?>>未审核</option>
              	<option value="1" <?php echo $deptag=='1'?'selected':'' ?>>不通过</option>
              	<option value="2" <?php echo $deptag=='2'?'selected':'' ?>>通过</option>
          	</select>
        	<span>人事审核:</span>
        	<select name="perTag">
            	<option value="">全部</option>
              	<option value="0" <?php echo $pertag=='0'?'selected':'' ?>>未审核</option>
              	<option value="1" <?php echo $pertag=='1'?'selected':'' ?>>不通过</option>
              	<option value="2" <?php echo $pertag=='2'?'selected':'' ?>>通过</option>
          	</select>
        	<span>总经理审核:</span>
        	<select name="manTag">
            	<option value="">全部</option>
              	<option value="0" <?php echo $mantag=='0'?'selected':'' ?>>未审核</option>
              	<option value="1" <?php echo $mantag=='1'?'selected':'' ?>>不通过</option>
              	<option value="2" <?php echo $mantag=='2'?'selected':'' ?>>通过</option>
          	</select>
        	<? if($sear=='1'){?>
        		<span>部门:</span>
			    <select name="depId" id="departmentSelect">
					<option value=''>请选择...</option>
			        <? foreach ($depList as $v){?>
						<option value='<?=$v['id'] ?>' <? if($depId==$v['id']){ echo 'selected';} ?>><?=$v['name']?></option>
					<? }?>
				</select>
				<span>姓名:</span> 
			    <select name="uid" id="uidSelect">
			        <option value=''>请选择...</option>
			       	<? foreach ($userList as $v){ ?>
			        	<option value='<?=$v['id']?>' <? if($uid==$v['id']){ echo 'selected';} ?>><?=$v['real_name'] ?></option>
			        <? } ?>
				</select>
        	<? }?>
        	<input type="submit" name="sub" value="查 询" class="sub2">
		</form>
	</div>
<? if($className){ ?>
	<? include($_GET['type'].'/'.$className.'.list.php');?>
  	<div class="news-viewpage"><?=$pageCtrl?></div>
<? }?>
</div>
<script type="text/javascript">
$(function(){
	var $department=$("#departmentSelect");
	var $uidSelect=$("#uidSelect");
	$department.change(function(){
		var depId=$(this).val();
		$.ajax( { 
		    url:'ajax/getUser.php',  
		    data:{depId:depId},    
		    type:'post',    
		    cache:false,    
		    dataType:'json',    
		    success:function(data) {
			    var str="<option value=''>请选择...</option>";
			    for(var i=0; i<data.length; i++){
					str+="<option value='"+data[i].id+"'>"+data[i].real_name+"</option>";
				}
			    $uidSelect.html(str);
		     },    
		     error : function() {
		          alert("异常！");    
		     }    
		});  
	});
});
</script>