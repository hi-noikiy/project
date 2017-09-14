<?php
/**
* ==============================================
* Copyright (c) 2015 All rights reserved.
* ----------------------------------------------
* 调休特殊管理
* ==============================================
* @date: 2015-6-25
* @author: Administrator
* @return:
*/
global $webdb;

$workClass = new workday();
$year = date("Y");
$lastyear = $year-1;
$thisyear = $year;
$nextyear = $year+1;
$month = date("m");
if($_REQUEST['year'])
	$year = $_REQUEST['year'];
if($_REQUEST['month'])
	$month = $_REQUEST['month'];
$firstday = $year."-".$month."-01";
$totalday = date('t',strtotime($firstday." 00:00:00"));  //当月天数
$lastday = $year."-".$month."-".$totalday;
$workClass->setWhere(" workday>='$firstday' and workday<='$lastday' ");
$workClass->pageReNum = "31";

$list = $workClass->getArray('pass');
$dep=new department();
$depList=$dep->getArray('pass');

$admin = new admin();
$depId=$_REQUEST['depId'];
if($depId){
	$admin->wheres="depId='$depId'";
	$admin->pageReNum='200';
	$admin->orders='id desc';
	$userList=$admin->getArray('pass');
}

$uid=$_REQUEST['uid'];
$sql="select hughdate from _web_hugh_pass where uid='$uid'";
$hughPassList=$webdb->getList($sql);
$hughPassDayArr=array();
if(!empty($hughPassList)){
	foreach ($hughPassList as $v){
		$hughPassDayArr[]=$v['hughdate'];
	}
}
?>
<h1 class="title"><span>工作日管理</span></h1>
<div class="pidding_5">
    <div class="search">
		<form action="" method="post" onsubmit="return checkFrom();"> 
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
             <span>年份:</span>
             <select name="year">
                 <option value="<?=$lastyear?>" <?php echo $lastyear==$year?'selected':'' ?>><?=$lastyear?></option>
                 <option value="<?=$thisyear?>" <?php echo $thisyear==$year?'selected':'' ?>><?=$thisyear?></option>
                 <option value="<?=$nextyear?>" <?php echo $nextyear==$year?'selected':'' ?>><?=$nextyear?></option>
             </select>
             <span>月份:</span>
             <select name="month">
             	
                <?
                    for($i=1; $i<13; $i++){
                        if($i<10)
                        	$j = "0".$i;
                        else
                        	$j = $i;
                ?>
             	<option value="<?=$j?>" <?php echo $month==$j?'selected':'' ?>><?=$j?></option>
                <? }?>
             </select>
			<input type="submit" name="sub" value="查 询" class="sub2">
		</form>
	</div>
   	<?php if($list){?>
  		<table cellspacing="0" cellpadding="0" class="Admin_L">
		    <tr>
		      <th scope="col" class="N_title">星期一</th>
		      <th scope="col" class="N_title">星期二</th>
		      <th scope="col" class="N_title">星期三</th>
		      <th scope="col" class="N_title">星期四</th>
		      <th scope="col" class="N_title">星期五</th>
		      <th scope="col" class="N_title">星期六</th>
		      <th scope="col" class="N_title">星期日</th>
		    </tr>
			<?
    			$whf = date('N',strtotime($firstday." 00:00:00"));   //第一天是星期几
    			$whl = date('N',strtotime($lastday." 00:00:00"));   //最后一天是星期几
    			//echo $whl;
    			for($i=1;$i<$whf;$i++){
        			array_unshift($list,array('id'=>'0','workday'=>'0','tag'=>'0'));
    			}
    
    			for($i=1;$i<=7-$whl;$i++){
        			array_push($list,array('id'=>'0','workday'=>'0','tag'=>'0'));
    			}
    			for($i=0;$i<count($list);$i=$i+7){
			?>
	    		<tr class="Ls2">
	        		<td class="N_title">
			        <?
			            if($list[$i]['id']!="0" && $list[$i]['tag']!=0){
							$chs='';
							if(in_array($list[$i]['workday'], $hughPassDayArr))
			                	$chs = 'checked';
			                echo $list[$i]['workday']." <input type='checkbox' name='works' value='".$list[$i]['workday']."' $chs>";
			            } else
			            	echo "&nbsp;";
			        ?>
	        		</td>
			        <td class="N_title">
			        <?
			            if($list[$i+1]['id']!="0" && $list[$i+1]['tag']!=0){
							$chs1='';
			                if(in_array($list[$i+1]['workday'], $hughPassDayArr))
								$chs1 = 'checked';
			                echo $list[$i+1]['workday']." <input type='checkbox'  name='works' value='".$list[$i+1]['workday']."' $chs1>";
			            } else
			            	echo "&nbsp;";
			        ?>
			        </td>
			        <td class="N_title">
			        <?
			            if($list[$i+2]['id']!="0" && $list[$i+2]['tag']!=0){
							$chs2='';
			                if(in_array($list[$i+2]['workday'], $hughPassDayArr))
								$chs2 = 'checked';
			                echo $list[$i+2]['workday']." <input type='checkbox'  name='works' value='".$list[$i+2]['workday']."' $chs2>";
			            } else
			            	echo "&nbsp;";
			        ?>
			        </td>
			        <td class="N_title">
			        <?
			            if($list[$i+3]['id']!="0" && $list[$i+3]['tag']!=0){
							$chs3='';
			                if(in_array($list[$i+3]['workday'], $hughPassDayArr))
								$chs3 = 'checked';
			                echo $list[$i+3]['workday']." <input type='checkbox'  name='works' value='".$list[$i+3]['workday']."' $chs3>";
			            } else
			            	echo "&nbsp;";
			        ?>
			        </td>
			        <td class="N_title">
			        <?
			            if($list[$i+4]['id']!="0" && $list[$i+4]['tag']!=0){
							$chs4='';
			                if(in_array($list[$i+4]['workday'], $hughPassDayArr))
								$chs4 = 'checked';
			                echo $list[$i+4]['workday']." <input type='checkbox'  name='works' value='".$list[$i+4]['workday']."' $chs4>";
			            } else
			            	echo "&nbsp;";
			        ?>
			        </td>
			        <td class="N_title">
			        <?
			            if($list[$i+5]['id']!="0" && $list[$i+5]['tag']!=0){
							$chs5='';
			                if(in_array($list[$i+5]['workday'], $hughPassDayArr))
								$chs5 = 'checked';
			                echo $list[$i+5]['workday']." <input type='checkbox'  name='works' value='".$list[$i+5]['workday']."' $chs5>";
			            
						} else
			            	echo "&nbsp;";
			        ?>
			        </td>
			        <td class="N_title">
			        <?	
			            if($list[$i+6]['id']!="0" && $list[$i+6]['tag']!=0){
							$chs6='';
							if(in_array($list[$i+6]['workday'], $hughPassDayArr))
								$chs6 = 'checked';	
			                echo $list[$i+6]['workday']." <input type='checkbox' name='works' value='".$list[$i+6]['workday']."' $chs6>";
			            } else
			            	echo "&nbsp;";
			        ?>
			        </td>
	    		</tr>
    		<? }?>
  		</table>
  	<? } else echo "未生成数据";?>
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
	var $worksInput=$("input[name='works']");
	$worksInput.click(function(){
		var $this=$(this);
		var hughdate=$this.val();
		var uid=$uidSelect.val();
		$.ajax({
		   	url:'ajax/getHughPass.php',  
		    data:{hughdate:hughdate, uid:uid},    
		    type:'post',    
		    cache:false,    
		    dataType:'json',    
		    success:function(data) {
			   alert(data.msg); 
			   if(data.status=='-1'){
					$this.removeAttr("checked");
				}
				   
		    },    
		    error : function() {
		    	alert("异常！");    
		    }
		});  
		
		
	});
});

function checkFrom(){
	var $department=$("#departmentSelect");
	var $uidSelect=$("#uidSelect");
	if($department.val()==''){
		alert('请选择部门......');
		return false;
	}
	if($uidSelect.val()==''){
		alert('请选择姓名......');
		return false;
	}
}
</script>