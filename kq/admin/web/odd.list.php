<?php
/**
 * ==============================================
 * Copyright (c) 2015 All rights reserved.
 * ----------------------------------------------
 * 打卡异常查询  优化
 * ==============================================
 * @date: 2015-6-11
 * @author: luoxue
 * @return:
 */
$admin = new admin();
$sear = $admin->getInfo($_SESSION['ADMIN_ID'],'','pass');
	
$from = $_REQUEST['fromTime'];
$to = $_REQUEST['toTime'];
$depId=$_REQUEST['depId'];
$uid=$_REQUEST['uid'];
$record = new record();
$p=$_REQUEST['p'];
if(!$p)
	$p='1';
if($sear['seartag']!='1')
	$record->setWhere("card_id='".$sear['card_id']."'");

if($uid){
	$card_id=$admin->getInfo($uid, 'card_id', 'pass');
	$record->setWhere("card_id='".$card_id."'");
}

if(!$from)
    $from = date('Y-m')."-01";
if(!$to)
    $to = date('Y-m-d');
if($depId){
	$user=new admin();

	$user->wheres="depId='$depId'";
	
	$usercardIdArr=array();
	foreach ($user->getList() as $k=>$v){
		$usercardIdArr[]=$v['card_id'];
	}
	$cardIdStr=implode(',', $usercardIdArr);
	$record->setWhere("card_id in ($cardIdStr)");
	$admin->wheres="depId='$depId'";
	$userList=$admin->getList();
}

$record->setWhere("recorddate>='$from' and recorddate<='$to' and card_id!='4632924'"); //迎宾卡:4632924
$record->pageReNum = "100000";
$list = $record->getList();
$ary = array();
foreach($list as $val){
    if(date('N',strtotime($val['recorddate']." 00:00:00"))<6){
       $time1 = strtotime($val['recorddate']." 09:00:00");
       $time2 = strtotime($val['recorddate']." 12:00:00");
       $time3 = strtotime($val['recorddate']." 13:30:00");
       $time4 = strtotime($val['recorddate']." 18:30:00");
       
       $val['addtime'] = preg_replace('/\[\d{2}:\d{2}:\d{2}\s\[进门2\]\]/','',$val['addtime']);//去除去门2的数据
       preg_match_all('/(\d{2}:\d{2}:\d{2})/',$val['addtime'],$out);

       foreach($out[0] as $v){
           if(strtotime($val['recorddate'].$v)>$time1&&strtotime($val['recorddate'].$v)<$time2 || strtotime($val['recorddate'].$v)>$time3&&strtotime($val['recorddate'].$v)<$time4)
               $ary[] = $val['card_id']."#".$val['recorddate']." ".$v;
       }
    }
}

$pn = '15';
$pageCtrl=getPageInfoHTMLForRecord($ary,$p,'',$pn);
?>
<div class="search">
	<span>打卡异常查询</span>
	<form action="odd.php" method="get">
		<span>时间:</span>
		<input name="fromTime" type="text" size="10" id="date_s" value="<?=$from?>" readonly>
		<span>到:</span>
		<input name="toTime" id="date_e" type="text" size="10" value="<?=$to?>" readonly>
        <? if($sear['seartag']=='1'){ ?>
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
		<? } ?>
		<input name="type" type="hidden" value="<?=$_GET['type']?>" size="20">
		<input name="do" type="hidden" value="list" size="20">
  		<input name="cn" type="hidden" value="<?=$className?>" size="20">
  		<input name="sear" type="hidden" value="sear"/>
  		<input type="submit" value="搜索" class="sub2">
	</form>
</div>
<table cellspacing="0" cellpadding="0" class="Admin_L">
    <tr>
        <th scope="col" class="T_title">编号</th>
        <th scope="col" class="T_title">姓名</th>
      	<th scope="col" class="T_title">打卡时间</th>
    </tr>
    <?
    	//计算第一条
    	$first = $pn*($p-1);
    	$f =0;
      	for($i=0;$i<$pn;$i++){
      		$f=$first+$i;
          	if($f<=count($ary)-1){
            	$vs = $ary[$f];
            	$vv = split('#',$vs);
    ?>
    <tr class="Ls2">
        <td class="N_title"><?=($f+1)?></td>
        <td class="N_title">
        <?
            $ad = new admin();
            $ad->setWhere("card_id='".$vv[0]."'");
            $name = $ad->getArray('pass');
            echo $name[0]['real_name'];
        ?>
        </td>
        <td class="N_title"><?=$vv[1]?></td>
    </tr>
    <? }} ?>
</table>
<div class="news-viewpage"><?=$pageCtrl?></div>
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