<?php
/**
 * ==============================================
 * Copyright (c) 2015 All rights reserved.
 * ----------------------------------------------
 * 考勤统计  优化
 * ==============================================
 * @date: 2015-6-18
 * @author: luoxue
 * @return:
 */
global $webdb;
$from = $_POST['fromTime'];
$to = $_POST['toTime'];
$depId=$_POST['depId'];
$uid = $_POST['uid'];
if(!$_POST['fromTime']){
    $from = mktime(0, 0, 0,date("m")-1,'01',date("Y"));
    $from = date('Y-m-d',$from);
}
if(!$_POST['toTime']){
	$lastdate = date('t',$from);   //计算当月天数
    $to = mktime(0, 0, 0,date("m")-1,$lastdate,date("Y"));
    $to = date('Y-m-d',$to);
}
if($depId){
	$admin = new admin();
	$admin->pageReNum=100;
	$admin->fieldList="id,real_name";
    $admin->wheres="depId='$depId'";
    $admin->orders="id asc";
    $userList=$admin->getArray('pass');  
}
if($_POST){
    //找出有效上班日期
    $workClass = new workday();
    $workClass->setWhere(" workday>='$from' and workday<='$to' and tag='1'");
    $workClass->pageReNum = "1000";
    $timelist = $workClass->getArray('pass');
    $timestr = "";   
    foreach($timelist as $v){
        $timestr .= "'".$v['workday']."',";
    }
    $timestr = substr($timestr,0,-1);
    $n = count($timelist);   //上班天数
    
    
    $self = new admin();    //查看权限
    $sear = $self->getInfo($_SESSION['ADMIN_ID'],'seartag','pass');
    //找出员工列表
    $adminList = new admin();
    $adminList->wheres .=" and id!='99' and id!='145' and (depId!='11' or depMax='1' )";
    //总经理账号99
    if($_SESSION['ADMIN_ID']!='99' && !$sear)
        $adminList->wheres .=" and id='".$_SESSION['ADMIN_ID']."'";
    if($depId)
    	$adminList->wheres .=" and depId='$depId'";
    if($uid)
        $adminList->wheres .=" and id='$uid'";  
    $adminList->pageReNum = "500";
    $adminres = $adminList->getArray("pass");
   	
    
    
    foreach($adminres as $key=>$admin){	
        $res = 0;
        $reshugh = 0;
        $resout = 0;
        
        foreach($timelist as $vd){
            //计算有效上班时间
            $res = $webdb->getValue("select totaltime from _web_record where card_id='".$admin['card_id']."' and recorddate ='".$vd['workday']."'",'totaltime');  
            $admin['total'] += $res;
            //计算调休时间
            $reshugh = acLateTime('',$vd['workday'],$vd['workday'],$admin['id']);
            $admin['late'] += $reshugh;
            //计算公出时间
            $resout = $webdb->getValue("select sum(totalM) as outs from _web_outrecord  where  available='1' and manTag='2' and uid='".$admin['id']."' and fromTime>='".$vd['workday']."' and toTime<='".$vd['workday']."' ",'outs');
            $admin['outs'] += $resout;
            
            //计算年假时间
			$annual = $webdb->getValue("select sum(totalTime) as leaves from _web_leave  where  available='1' and manTag='2' and uid='".$admin['id']."' and fromTime>='".$vd['workday']."' and toTime<='".$vd['workday']."' and leaveType = '年假'",'leaves');
			$admin['annual'] += $annual;
            
			$tmp = 480 - $reshugh - $res - $resout - $annual*60;
            if($tmp>0)
            	$admin['dis'] += $tmp;
        }
        //计算总上班时间
        $resall = $webdb->getValue("select sum(totalall) as totalall from _web_record where card_id='".$admin['card_id']."' and recorddate>='$from' and recorddate<='$to' ",'totalall');
        $admin['totalall'] = $resall;
/*     //计算有效上班时间
        $res = $webdb->getValue("select sum(totaltime) as total from _web_record where card_id='".$admin['card_id']."' and recorddate in($timestr)",'total');
        $admin['total'] = $res;
        //计算调休时间
        //$reshugh = $webdb->getValue("select sum(latetime) as late from _web_hugh where  available='1' and addtag='1' and uid='".$admin['id']."' and fromTime in($timestr)",'late');
        $reshugh = acLateTime('',$from,$to,$admin['id']);
        $admin['late'] = $reshugh;
  * 
  */
        //加班时间
        $resover = $webdb->getValue("select sum(totalTime) as over from _web_overtime where  available='1' and addtag='1' and uid='".$admin['id']."' and fromTime>='$from' and toTime<='$to' ",'over');
        $admin['over'] = $resover;
     /*   //计算公出时间
        $resout = $webdb->getValue("select sum(totalTime) as outs from _web_outrecord  where  available='1' and manTag='2' and uid='".$admin['id']."' and fromTime>='$from' and toTime<='$to' ",'outs');
        $admin['outs'] = $resout;
      * 
      */
        //计算请假时间
        $resleave = $webdb->getValue("select sum(totalTime) as leaves from _web_leave  where  available='1' and manTag='2' and uid='".$admin['id']."' and fromTime>='$from' and toTime<='$to' and leaveType != '年假'",'leaves');
        $admin['leaves'] = $resleave;
		//计算年假时间
        /*$annual = $webdb->getValue("select sum(totalTime) as leaves from _web_leave  where  available='1' and manTag='2' and uid='".$admin['id']."' and fromTime>='$from' and toTime<='$to' and leaveType = '年假'",'leaves');
        $admin['annual'] = $annual;*/
        $adminres[$key] = $admin;
    }
}
?>
<h1 class="title"><span>考勤统计查询</span></h1>
<div class="pidding_5">
    <div class="search">
        <form action="" method="post">
            <span>时间：</span>
            <input type="text" name="fromTime" size="10" id="date_s" value="<?=$from?>" readonly>
            <span>到：</span>
        	<input type="text" name="toTime" size="10" id="date_e" value="<?=$to?>" readonly>
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
        	<? if($sear=='1'){?>
        		<a href='statExcel.php?fromTime=<?=$from?>&toTime=<?=$to?>&uid=<?=$uid?>'>导出excel</a>
        	<? }?>
		</form>
	</div>
<? if($_POST){?>
<table cellspacing="0" cellpadding="0" class="Admin_L">
    <tr>
      	<th scope="col" class="N_title">编号</th>
      	<th scope="col" class="N_title">姓名</th>
      	<th scope="col" class="N_title">总上班时间</th>
      	<th scope="col" class="N_title">需上班时间</th>
      	<th scope="col" class="N_title">正常上班时间</th>
      	<th scope="col" class="N_title">加班时间</th>
      	<th scope="col" class="N_title">调休时间</th>
      	<th scope="col" class="N_title">请假时间</th>
		<th scope="col" class="N_title">年假时间</th>
      	<th scope="col" class="N_title">公出时间</th>
      	<th scope="col" class="N_title">扣考勤</th>
      	<th scope="col" class="N_title">操作</th>
    </tr>
    <tr class="Ls2">
        <td class="N_title" colspan="12">时间段:<? echo $from."~".$to;?></td>
    </tr>
    <?
        $i=0;
        foreach($adminres as $val){
            $i++;
    ?>
    <tr class="Ls2">
        <td class="N_title"><?=$i?></td>
        <td class="N_title"><?=$val['real_name']?></td>
        <td class="N_title"><a href='index.php?type=web&do=list&cn=record&fromTime=<?=$from?>&toTime=<?=$to?>&card_id=<?=$val['card_id']?>' target="_blank"><?=floor($val['totalall']/60)."小时".($val['totalall']%60)."分钟"?></a></td>
        <td class="N_title"><?=$n*8?>小时</td>
        <td class="N_title"><?=floor($val['total']/60)."小时".($val['total']%60)."分钟"?></td>
        <td class="N_title"><?=$val['over']?$val['over'].'小时':'&nbsp;'?></td>
        <td class="N_title"><?=$val['late']>0?floor($val['late']/60)."小时".($val['late']%60)."分钟":'&nbsp;'?></td>
        <td class="N_title"><?=$val['leaves']?$val['leaves'].'小时':'&nbsp;'?></td>
		<td class="N_title"><?=$val['annual']?$val['annual'].'小时':'&nbsp;'?></td>
        <td class="N_title"><?=$val['outs']?floor($val['outs']/60)."小时".($val['outs']%60)."分钟":'&nbsp;'?></td>
        <td class="N_title">
        <?
            $left = $val['dis'];
            if($left<0)
            	echo "0小时0分钟";
            else
            	echo floor($left/60)."小时".($left%60)."分钟";
        ?>
        </td>
        <td class="N_title">
            <a href="detail.php?id=<?=$val['id']?>&from=<?=$from?>&to=<?=$to?>&left=<?=$left?>&total=<?=$val['total']?>&over=<?=$val['over']?>&late=<?=$val['late']?>&outs=<?=$val['outs']?>&leaves=<?=$val['leaves']?>&card_id=<?=$val['card_id']?>" target="_blank">详细查询</a>
        </td>
    </tr>
    <?}?>
</table>
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